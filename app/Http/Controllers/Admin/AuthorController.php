<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\AuthorBook;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthorController extends Controller
{
    public function __construct(
        protected Author $repository,
        protected AuthorBook $authorBookRepository
    )
    {
        // $this->middleware(['can:authors']);
        //descotinua mas mantido o script para caso mudem de ideia
        abort(404);
    }

    public function index() {
        $data['authors_'] = true;
        $data['title']  = 'Autores';
        $data['toptitle'] =  $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'], 'active' => true];

        $data['authors'] = $this->repository->all();

        return view('admin.authors.index', $data);
    }

    public function create() {
        $data['authors_'] = true;
        $data['title']  = 'Criar Autor';
        $data['toptitle'] =  $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('authors'), 'title' => 'Editoras'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'], 'active' => true];
        $data['action']         = route('author.store');

        return view('admin.authors.create', $data);
    }

    public function edit($id) {
        if (!$id) {
            return redirect()->back();
        }

        $author = $this->repository->find($id);
        if (!$author) {
            return redirect()->back();
        }

        $data['authors_'] = true;
        $data['title']  = 'Editar dado Author - ' . $author->name;
        $data['toptitle'] =  $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('authors'), 'title' => 'Editoras'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'], 'active' => true];
        $data['author'] = $author;
        $data['action'] = route('author.update', $author->id);

        return view('admin.authors.edit', $data);
    }

    public function store(Request $request) {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|integer'
        ]);

        DB::beginTransaction();
        try {
            $this->repository->create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'description' => $request->description,
                'status' => $request->status
            ]);

            DB::commit();
            return redirect()->route('authors')->with('success', 'Autor criado com sucesso');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('authors')->with('warning',  $e->getMessage());
        }
    }

    public function update(Request $request, $id) {
        $author = $this->repository->find($id);
        if (!$author) {
            return redirect()->back()->with('error', 'Autor não localizado');
        }

        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|integer'
        ]);

        DB::beginTransaction();
        try {
            DB::commit();

            $this->repository->where('id', $author->id)->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'description' => $request->description,
                'status' => $request->status
            ]);

            DB::commit();
            return redirect()->route('authors')->with('success', 'Author editado com sucesso');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('authors')->with('warning',  $e->getMessage());
        }
    }

    public function delete($id) {
        DB::beginTransaction();
        try {
            $author = $this->repository->find($id);
            if (!$author) {
                return redirect()->back()->with('error', 'Autor não localizado');
            }

            $this->authorBookRepository->where('author_id', $author->id)->delete();
            $author->delete();
            DB::commit();
            return redirect()->route('authors')->with('success', 'Editora removido com sucesso');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('authors')->with('warning',  $e->getMessage());
        }
    }
}
