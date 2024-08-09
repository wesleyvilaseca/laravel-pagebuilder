<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Publisher;
use App\Models\PublisherBook;
use App\Services\UploadFileService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function __construct(
        protected Book $repository,
        protected Publisher $publisherRepository,
        protected PublisherBook $publisherBookRepository,
        protected UploadFileService $uploadFileService
    )
    { 
    }

    public function index() {
        $data['books_'] = true;
        $data['title']  = 'Livros';
        $data['toptitle'] =  $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'], 'active' => true];

        $data['books'] = $this->repository->all();
        return view('admin.books.index', $data);
    }

    public function create() {
        $data['books_'] = true;
        $data['title']  = 'Cadastrar livro';
        $data['toptitle'] =  $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('books'), 'title' => 'Livros'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'], 'active' => true];
        $data['publishers'] = $this->publisherRepository->all();
        $data['action']         = route('book.store');

        return view('admin.books.create', $data);
    }

    public function edit($id) {
        if (!$id) {
            return redirect()->back();
        }

        $book = $this->repository->find($id);
        if (!$book) {
            return redirect()->back();
        }

        $data['publisher_'] = true;
        $data['title']  = 'Editar livro - ' . $book->name;
        $data['toptitle'] =  $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('publishers'), 'title' => 'Editoras'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'], 'active' => true];
        $data['publishers'] = $this->publisherRepository->all();
        $data['image'] = $book->uploads()->wherePivot('alias_category', $this->repository::FILE_CATEGORY_BOOK_SINGLE_IMAGE)->first();
        $data['book'] = $book;
        $data['publisherBook'] = $book->publishers->first();
        $data['action'] = route('book.update', $book->id);

        return view('admin.books.edit', $data);
    }

    public function show($id) {
        if (!$id) {
            return redirect()->back();
        }

        $book = $this->repository->find($id);
        if (!$book) {
            return redirect()->back();
        }

        $data['publisher_'] = true;
        $data['title']  = 'Livro - ' . $book->name;
        $data['toptitle'] =  $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('publishers'), 'title' => 'Editoras'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'], 'active' => true];
        $data['publishers'] = $this->publisherRepository->all();
        $data['image'] = $book->uploads()->wherePivot('alias_category', $this->repository::FILE_CATEGORY_BOOK_SINGLE_IMAGE)->first();
        $data['book'] = $book;
        $data['action'] = route('book.delete', $book->id);
        $data['show'] = true;
        $data['publisherBook'] = $book->publishers->first();

        return view('admin.books.show', $data);
    }

    public function store(Request $request) {
        DB::beginTransaction();
        try {
            $request->validate([
                'image' => 'required|file|mimes:jpg,jpeg,png|max:2048',
                'name' => 'required|string',
                'status' => 'required|integer',
                'description' => 'string',
                'publisher_id' => 'integer|exists:publishers,id',
                'price' => 'regex:/^\d+(\.\d{1,2})?$/',
                'discount' => 'regex:/^\d+(\.\d{1,2})?$/'
            ]);

            $url = Str::slug($request->name);
            if ($request->publisher_id) {
                $hasBookName  = $this->repository->publishers()
                            ->where('publisher_id', $request->publisher_id)
                            ->whereHas('books', function ($query) use ($url) {
                                $query->where('url', $url);
                            })->first();

                if($hasBookName) {
                    return redirect()->back()->with('warning', 'A editora já possui um livro com o nome ' . $request->name);
                }
            }

            $book = $this->repository->create([
                'name' => $request->name,
                'status' => $request->status,
                'description' => $request->description,
                'price' => @$request->price,
                'discount' => @$request->discount,
                'url' => $url
            ]);


            if($request->publisher_id) {
                $this->publisherBookRepository->create(['book_id' => $book->id, 'publisher_id' => $request->publisher_id]);
            }

            if($request->file('image')) {
                $storedFileSigleImage = $this->uploadFileService->upload(
                    $request->file('image'),
                    'books/' .  $url . '/' . $this->repository::FILE_CATEGORY_BOOK_SINGLE_IMAGE
                );

                $upload = $this->uploadFileService->store($storedFileSigleImage);

                $this->uploadFileService->storeUploadRelation([
                    'system_upload_id' => $upload->id,
                    'relation_id' => $book->id,
                    'alias_model_relation' => $this->repository::MODEL_ALIAS,
                    'alias_category' => $this->repository::FILE_CATEGORY_BOOK_SINGLE_IMAGE
                ]);
            }

            DB::commit();
            return redirect()->route('books')->with('success', 'Editora criada com sucesso');
        } catch (Exception $e) {
            DB::rollback();
            if (isset($storedFileSigleImage)) {
                $this->uploadFileService->deleteFile($storedFileSigleImage['server_file']);
            }

            return redirect()->route('books')->with('warning',  $e->getMessage());
        }
    }

    public function update(Request $request, $id) {
        $book = $this->repository->find($id);
        if (!$book) {
            return redirect()->back()->with('error', 'Editora não localizada');
        }
    
        DB::beginTransaction();
        try {
            $request->validate([
                'image' => 'required|file|mimes:jpg,jpeg,png|max:2048',
                'name' => 'required|string',
                'status' => 'required|integer',
                'description' => 'string',
                'publisher_id' => 'integer',
                'price' => 'numeric|decimal:2',
                'discount' => 'numeric|decimal:2'
            ]);

            $url = Str::slug($request->name);
            if ($request->publisher_id && $request->name != $book->name) {
                $hasBookName = $this->repository-checkIfPublisherHasBook($request->publisher_id, $url);

                if($hasBookName) {
                    return redirect()->back()->with('warning', 'A editora já possui um livro com o nome ' . $request->name);
                }
            }

            $book = $this->repository->where('id', $book->id)->update([
                'name' => $request->name,
                'status' => $request->status,
                'description' => $request->description,
                'price' => @$request->price,
                'discount' => @$request->discount,
                'url' => $url
            ]);

            if ($request->file('image')) {
                $image = $book->uploads()->wherePivot('alias_category', $this->repository::FILE_CATEGORY_BOOK_SINGLE_IMAGE)->first();
                if ($image) {
                    $this->uploadFileService->deleteFile('', $image);
                }

                $storedFileSigleImage = $this->uploadFileService->upload(
                    $request->file('image'),
                    'publishers/' . $url . '/' . $this->repository::FILE_CATEGORY_BOOK_SINGLE_IMAGE
                );
                $upload = $this->uploadFileService->store($storedFileSigleImage);

                $this->uploadFileService->storeUploadRelation([
                    'system_upload_id' => $upload->id,
                    'relation_id' => $book->id,
                    'alias_model_relation' => $this->repository::MODEL_ALIAS,
                    'alias_category' => $this->repository::FILE_CATEGORY_BOOK_SINGLE_IMAGE
                ]);
            }

            DB::commit();
            return redirect()->route('books')->with('success', 'Livro editado com sucesso');
        } catch (Exception $e) {
            DB::rollback();
            if (isset($storedFileSigleImage)) {
                $this->uploadFileService->deleteFile($storedFileSigleImage['server_file']);
            }

            return redirect()->route('books')->with('warning',  $e->getMessage());
        }
    }

    public function delete($id) {
        DB::beginTransaction();
        try {
            $book = $this->repository->find($id);
            if (!$book) {
                return redirect()->back()->with('error', 'Livro não localizado');
            }

            $files = $book->uploads;

            if (!empty($files)) {
                foreach($files as $file) {
                    $this->uploadFileService->deleteFile(null, $file, true);
                }
            }

            $this->publisherBookRepository->where('book_id', $id)->delete();
            $book->delete();
            DB::commit();
            return redirect()->route('books')->with('success', 'Livro removido com sucesso');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('books')->with('warning',  $e->getMessage());
        }
    }
}
