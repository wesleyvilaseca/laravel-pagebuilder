<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Request;

class AuthorBookController extends Controller
{
    public function __construct(
        protected Book $bookRepository,
        protected Author $authorRepository
    )
    {
        $this->middleware(['can:books']);
    }

    public function index(int $bookId)
    {
        $book = $this->bookRepository->find($bookId);
        if (!$book) {
            return redirect()->back();
        }
        
        $data['title'] = 'Autores do livro ' . $book->name;
        $data['toptitle'] = $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('books') , 'title' => 'Livros'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'] , 'active' => true];
        $data['books_'] = true;
        $data['book'] = $book;
        $data['authors'] = $book->authors;

        return view('admin.book-authors.index', $data);
    }

    public function available(int $bookId) {
        $book = $this->bookRepository->find($bookId);
        if (!$book) {
            return redirect()->back();
        }

        $data['title'] = 'Adicionar autores para o livro ' . $book->name;
        $data['toptitle'] = $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('books') , 'title' => 'Livros'];
        $data['breadcrumb'][] = ['route' => route('book.authors', $book->id) , 'title' => 'Autores do livro - ' . $book->name];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'] , 'active' => true];
        $data['books_'] = true;
        $data['book'] = $book;
        $data['authors'] = $book->authorsAvailable();

        return view('admin.book-authors.available', $data);
    }

    public function attachPublisherEvent(Request $request, $bookId)
    {
        $book = $this->bookRepository->find($bookId);
        if (!$book) {
            return redirect()->back()->with('error', 'Operação não autorizada');
        }

        if (!$request->authors || count($request->authors) == 0) 
            return redirect()->back()->with('warning', 'Precisa escolher pelo menos uma editora');
        
        $book->authors()->attach($request->authors);

        return redirect()->route('book.authors', $book->id);
    }

    public function detachEventPublisher($bookId, $authorId)
    {
        $book = $this->bookRepository->find($bookId);
        $author = $this->authorRepository->find($authorId);

        if (!$book || !$author) {
            return redirect()->back()->with('error', 'Operação não autorizada');
        }
            
        
        $book->authors()->detach($author);

        return redirect()->route('book.authors', $book->id);
    }
}
