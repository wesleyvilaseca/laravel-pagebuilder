<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Publisher;
use App\Models\PublisherBook;
use App\Services\BookService;
use App\Services\UploadFileService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PublisherBooksController extends Controller
{
    public function __construct(
        protected UploadFileService $uploadFileService,
        protected Publisher $publisherRepository,
        protected PublisherBook $publisherBook,
        protected BookService $bookService,
        protected Book $repository,
        protected Author $authorRepository
        )
    {
        $this->middleware(['can:publishers']);
    }

    public function index($publisherUrl) {
        $publisher = $this->publisherRepository->where('url', $publisherUrl)->first();
        if(!$publisher) {
            return abort(404);
        }

        $data['publisher_'] = true;
        $data['title']  = 'Livros editora ' . $publisher->name;
        $data['toptitle'] =  $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('publishers'), 'title' => 'Editoras'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'], 'active' => true];
        $data['books'] = $publisher->books()->get();
        $data['publisher'] = $publisher;

        return view('admin.publisher-books.index', $data);
    }

    public function create($publisherUrl) {
        $publisher = $this->publisherRepository->where('url', $publisherUrl)->first();
        if(!$publisher) {
            return abort(404);
        }

        $data['publisher_'] = true;
        $data['title']  = 'Cadastrar livro editora - ' . $publisher->name;
        $data['toptitle'] =  $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('publishers'), 'title' => 'Editoras'];
        $data['breadcrumb'][] = ['route' => route('publisher.books', $publisher->url), 'title' => 'Livros editora - ' . $publisher->name];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'], 'active' => true];
        $data['action'] = route('publisher.book.store', $publisher->url);

        return view('admin.publisher-books.create', $data);
    }

    public function edit($publisherUrl, $id) {
        if (!$id) {
            return abort(404);
        }

        $publisher = $this->publisherRepository->where('url', $publisherUrl)->first();
        if(!$publisher) {
            return abort(404);
        }

        if (!$book = $publisher->books->where('id', $id)->first()) {
            return abort(404);
        }

        $data['publisher_'] = true;
        $data['title']  = 'Editar livro - ' . $book->name;
        $data['toptitle'] =  $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('publishers'), 'title' => 'Editoras'];
        $data['breadcrumb'][] = ['route' => route('publisher.books', $publisher->url), 'title' => 'Livros editora - ' . $publisher->name];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'], 'active' => true];
        $data['image'] = $book->uploads()->wherePivot('alias_category', $this->repository::FILE_CATEGORY_BOOK_SINGLE_IMAGE)->first();
        $data['book'] = $book;
        $data['publisherBook'] = $book->publishers->first();
        $data['action'] = route('publisher.book.update', [$publisher->url, $book->id]);

        return view('admin.publisher-books.edit', $data);
    }

    public function show($publisherUrl, $id) {
        if (!$id) {
            return abort(404);
        }

        $publisher = $this->publisherRepository->where('url', $publisherUrl)->first();
        if(!$publisher) {
            return abort(404);
        }

        if (!$book = $publisher->books->where('id', $id)->first()) {
            return abort(404);
        }

        $data['publisher_'] = true;
        $data['title']  = 'Livro - ' . $book->name;
        $data['toptitle'] =  $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('publishers'), 'title' => 'Editoras'];
        $data['breadcrumb'][] = ['route' => route('publisher.books', $publisher->url), 'title' => 'Livros editora - ' . $publisher->name];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'], 'active' => true];
        $data['image'] = $book->uploads()->wherePivot('alias_category', $this->repository::FILE_CATEGORY_BOOK_SINGLE_IMAGE)->first();
        $data['book'] = $book;
        $data['action'] = route('publisher.book.delete', [$publisher->url, $book->id]);
        $data['show'] = true;

        return view('admin.publisher-books.show', $data);
    }

    public function store(Request $request, string $publisherUrl) {
        $request->validate([
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:5048',
            'name' => 'required|string',
            'description' => 'string|nullable',
            'author' => 'string|nullable',
            'subject' => 'string|nullable',
            'isbn' => 'string|nullable',
            'link' => 'string|nullable',
            'publisher_id' => 'integer|exists:publishers,url',
            'price' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
            'presential_price' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
            'price_discount' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
            'status' => 'required|integer'
        ]);

        $data = $request->all();
        if($request->file('image')) {
            $data['file'] = $request->file('image');
        }

        try {
            $data['publisher_id'] = $this->publisherRepository->where('url', $publisherUrl)->first()->id;
            $this->bookService->store($data);
            return redirect()->route('publisher.books', $publisherUrl)->with('success', 'Livro criado com sucesso');
        } catch (Exception $e) {
            return redirect()->route('publisher.books', $publisherUrl)->with('warning',  $e->getMessage());
        }
    }

    public function update(Request $request, $publisherUrl, $id) {
        $book = $this->repository->find($id);
        if (!$book) {
            return redirect()->back()->with('error', 'Editora não localizada');
        }

        $request->validate([
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:5048',
            'name' => 'required|string',
            'description' => 'string|nullable',
            'author' => 'string|nullable',
            'subject' => 'string|nullable',
            'isbn' => 'string|nullable',
            'link' => 'string|nullable',
            'publisher_id' => 'integer|exists:publishers,url',
            'price' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
            'price_discount' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
            'status' => 'required|integer'
        ]);

        $data = $request->all();
        if($request->file('image')) {
            $data['file'] = $request->file('image');
        }
    
        try {
            $data['publisher_id'] = $this->publisherRepository->where('url', $publisherUrl)->first()->id;
            $this->bookService->update($data, $book);
            return redirect()->route('publisher.books', $publisherUrl)->with('success', 'Livro editado com sucesso');
        } catch (Exception $e) {
            return redirect()->route('publisher.books', $publisherUrl)->with('warning',  $e->getMessage());
        }
    }

    public function delete($publisherUrl, $id) {
        $publisher = $this->publisherRepository->where('url', $publisherUrl)->first();
        if(!$publisher) {
            return abort(404);
        }

        if (!$publisher->books->where('id', $id)->first()) {
            return abort(404);
        }

        try {
            $this->bookService->delete($id);
            return redirect()->route('publisher.books', $publisherUrl)->with('success', 'Livro removido com sucesso');
        } catch (Exception $e) {
            return redirect()->route('publisher.books', $publisherUrl)->with('warning',  $e->getMessage());
        }
    }

    public function deleteBatch(Request $request, $publisherUrl) {
        $publisher = $this->publisherRepository->where('url', $publisherUrl)->first();
        if(!$publisher) {
            return abort(404);
        }

        if(empty($request->books)) {
            return abort(404);
        }

        try {
            foreach ($request->books as $bookId) {
                $this->bookService->delete($bookId);
            }
            return redirect()->route('publisher.books', $publisherUrl)->with('success', 'Livros removidos com sucesso');
        } catch (Exception $e) {
            return redirect()->route('publisher.books', $publisherUrl)->with('warning',  $e->getMessage());
        }
    }

    public function batchStore(Request $request, $publisherUrl) {
        $publisher = $this->publisherRepository->where('url', $publisherUrl)->first();
        if(!$publisher) {
            return abort(404);
        }

        $request->validate([
            'csv_book_file' => 'required|file|mimes:csv,txt|max:5048',
        ], [
            'csv_book_file.required' => 'O arquivo CSV é obrigatório.',
            'csv_book_file.mimes' => 'Apenas arquivos CSV são permitidos.',
            'csv_book_file.max' => 'O arquivo não pode ter mais de 5 MB.',
        ]);

        $getFileEconding = function ($filename) {
            $finfo = finfo_open(FILEINFO_MIME_ENCODING);
            $encoding = finfo_file($finfo, $filename);
            finfo_close($finfo);
        
            return $encoding;
        };

        $file = $request->file('csv_book_file');
        $filePath = $file->getRealPath();

        $encoding = $getFileEconding($filePath);
        if ($encoding != 'utf-8') {
            $content = file_get_contents($filePath); 
            $convertedContent = mb_convert_encoding($content, 'UTF-8', 'ISO-8859-1');
            file_put_contents($filePath, $convertedContent);

            if($getFileEconding($filePath) != 'utf-8') {
                return redirect()->back()->withErrors([
                    'incorrect_encode' => 'O arquivo CSV precisa estar em UTF-8',
                    'convert_fail' => 'O arquivo ' . $file->getClientOriginalName() . ' está encodado em ' . $encoding . ' e não foi possível passar para UTF-8' 
                ]);
            }
        }

        $fileHandle = fopen($filePath, 'r');
        if ($fileHandle === false) {
            return redirect()->back()->withErrors(['csv_book_file' => 'Não foi possível abrir o arquivo CSV.']);
        }

        $firstLine = fgets($fileHandle);
        if (strpos($firstLine, ';') === false) {
            fclose($fileHandle);
            return redirect()->back()->withErrors(['csv_book_file' => 'O arquivo não é um CSV separado por vírgulas.']);
        }

        fseek($fileHandle, 0);
        $csv_header = fgetcsv($fileHandle, 0, ';');
        $csv_header = array_map(function($column) {
            return trim($column);
        }, $csv_header);

        $qtdExpectedColumns = 8;
        if (sizeof($csv_header) > $qtdExpectedColumns) {
            return redirect()->back()->withErrors(['csv_book_file' => 'O arquivo CSV possuí mais de ' . $qtdExpectedColumns . ' colunas']);
        }

        $header = ['ISBN', 'TITULO', 'AUTOR', 'EDITORA', 'PRECO_CAPA', 'PRECO_DESCONTO', 'ASSUNTO', 'URL_LIVRO'];

        // if ($header !== $expectedHeaders) {
        //     return redirect()->back()->withErrors(['csv_book_file' => 'O arquivo CSV não tem as colunas corretas. Esperado: ISBN, TITULO, AUTOR, EDITORA, PREÇO CAPA, PREÇO DESCONTO, ASSUNTO, URL_LIVRO']);
        // }
        
        DB::beginTransaction();
        try {
            $rows = [];
            while (($row = fgetcsv($fileHandle, 0, ';', '"')) !== false) {
                $rows[] = $row;
            }
            fclose($fileHandle);

            $collection = collect($rows);
            $uniqueData = $collection->unique(function ($item) {
                return implode(';', $item);
            });

            $uniqueRows = $uniqueData->toArray();
            array_unshift($uniqueRows, $header);

            foreach ($uniqueRows as $row) {
                if ($row === $header) {
                    continue;
                }

                $book = array_combine($header, $row);

                $data = [];
                $data['isbn'] = $book['ISBN'];
                $data['name'] = $book['TITULO'];
                $data['author'] = $book['AUTOR'];
                $data['subject'] = $book['ASSUNTO'];
                $data['price'] = floatval(str_replace(',', '.', $book['PRECO_CAPA']));
                $data['price_discount'] = floatval(str_replace(',', '.', $book['PRECO_DESCONTO']));
                $data['link'] = $book['URL_LIVRO'];
                $data['publisher_id'] = $publisher->id;
                $data['description'] = '';
                $data['status'] = 1;

                if (!$data['isbn'] || !$data['name']) {
                    continue;
                }

                try {
                   $this->bookService->store($data);
                } catch (Exception $e) {
                    if (str_contains($e->getMessage(), 'A editora já possui um livro com o nome')) {
                        $book = $this->bookService->getBookByIsbnAndUrlAndPublisher($data['isbn'],  Str::slug($data['name']), $publisher->id);
                        if (!$book) {
                            continue;
                        }

                        $this->bookService->update($data, $book);
                        continue;
                    }

                    throw new Exception($e->getMessage());
                }

                // $author = $book['AUTOR'];
                // $author = explode(',', $author);
                // if(sizeof($author) > 1) {
                //     $author = ['first_name' => $author[1], 'last_name' => $author[0]];
                // } elseif (sizeof($author) == 1) {
                //     $author = ['first_name' => $author[0], 'last_name' => ''];
                // }
    
                // $newAuthor = $this->authorRepository
                // ->where([
                //     'first_name' => $author['first_name'],
                //     'last_name' => $author['last_name']
                //     ])
                //     ->first();
                    
                // if (!$newAuthor) {
                //     $newAuthor = $this->authorRepository->create($author);
                // }
    
                // $newBook->authors()->attach($newAuthor->id);
            }
            
           
            DB::commit();
            return redirect()->route('publisher.books', $publisherUrl)->with('success', 'Livros criados com sucesso');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('publisher.books', $publisherUrl)->with('warning',  $e->getMessage());
        }       

    }
}
