<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuthorBook;
use App\Models\Book;
use App\Models\Publisher;
use App\Models\PublisherBook;
use App\Services\BookService;
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
        protected UploadFileService $uploadFileService,
        protected AuthorBook $authorBookRepository,
        protected BookService $bookService
    )
    {
        $this->middleware(['can:books']);
    }

    public function index(Request $request) {
        if($request->filter) {
            $filters['filter'] = $request->filter;
            $filters['selected_filter'] = $request->selected_filter;

            $data['books'] = $this->repository->search($filters);
            $data['filters'] = $request->except('_token');
        } else {
            $data['books'] = $this->repository->paginate(8);
        }

        $data['books_'] = true;
        $data['title']  = 'Livros';
        $data['toptitle'] =  $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'], 'active' => true];

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
        $data['action'] = route('book.store');

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

        $data['books_'] = true;
        $data['title']  = 'Editar livro - ' . $book->name;
        $data['toptitle'] =  $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('books'), 'title' => 'Livros'];
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

        $data['books_'] = true;
        $data['title']  = 'Livro - ' . $book->name;
        $data['toptitle'] =  $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('books'), 'title' => 'Livros'];
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
        $request->validate([
            'image' => 'file|mimes:jpg,jpeg,png|max:5048',
            'name' => 'required|string',
            'description' => 'string|nullable',
            'author' => 'string|nullable',
            'subject' => 'string|nullable',
            'isbn' => 'string|nullable',
            'link' => 'string|nullable',
            'publisher_id' => 'integer|exists:publishers,id',
            'price' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
            'presential_price' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
            'presential_discount' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
            'virtual_discount' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
            'status' => 'required|integer'
        ]);

        $data = $request->all();
        if($request->file('image')) {
            $data['file'] = $request->file('image');
        }

        try {
            $this->bookService->store($data);
            return redirect()->route('books')->with('success', 'Livro criado com sucesso');
        } catch (Exception $e) {
            return redirect()->route('books')->with('warning',  $e->getMessage());
        }
    }

    public function update(Request $request, $id) {
        $book = $this->repository->find($id);
        if (!$book) {
            return redirect()->back()->with('error', 'Editora não localizada');
        }

        $request->validate([
            'image' => 'file|mimes:jpg,jpeg,png|max:5048',
            'name' => 'required|string',
            'description' => 'string|nullable',
            'author' => 'string|nullable',
            'subject' => 'string|nullable',
            'isbn' => 'string|nullable',
            'link' => 'string|nullable',
            'publisher_id' => 'integer|exists:publishers,id',
            'price' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
            'presential_discount' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
            'virtual_discount' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
            'status' => 'required|integer'
        ]);

        $data = $request->all();
        if($request->file('image')) {
            $data['file'] = $request->file('image');
        }
    
        DB::beginTransaction();
        try {
            $this->bookService->update($data, $book);
            return redirect()->route('books')->with('success', 'Livro editado com sucesso');
        } catch (Exception $e) {
            return redirect()->route('books')->with('warning',  $e->getMessage());
        }
    }

    public function delete($id) {
        try {
            $this->bookService->delete($id);
            return redirect()->route('books')->with('success', 'Livro removido com sucesso');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('books')->with('warning',  $e->getMessage());
        }
    }
}
