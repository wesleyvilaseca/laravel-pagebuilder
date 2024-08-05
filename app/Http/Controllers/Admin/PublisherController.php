<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use App\Services\UploadFileService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PublisherController extends Controller
{
    protected $uploadFileService;
    protected $repository;

    public function __construct(
        UploadFileService $uploadFileService,
        Publisher $repository
        )
    {
       $this->uploadFileService = $uploadFileService;
       $this->repository = $repository;
    }

    public function index() {
        $data['publisher_'] = true;
        $data['title']  = 'Editoras';
        $data['toptitle'] =  $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'], 'active' => true];

        $data['publishers'] = $this->repository->all();

        return view('admin.publishers.index', $data);
    }

    public function create() {
        $data['publisher_'] = true;
        $data['title']  = 'Criar Editora';
        $data['toptitle'] =  $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('publishers'), 'title' => 'Editoras'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'], 'active' => true];
        $data['action']         = route('publisher.store');

        return view('admin.publishers.create', $data);
    }

    public function store(Request $request) {
        DB::beginTransaction();
        try {
            $request->validate([
                'logo' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
                'name' => 'required|string|unique:publishers,name',
                'status' => 'required|integer'
            ]);

            $publisher = $this->repository->create([
                'name' => $request->name,
                'status' => $request->status
            ]);

            $filePath = 'publishers/' .  Str::slug($publisher->name) . '/' . $this->repository::FILE_CATEGORY_LOGO;
            $storedFile = $this->uploadFileService->upload(
                $request->file('logo'),
                $filePath
            );

            $upload = $this->uploadFileService->store($storedFile);

            $this->uploadFileService->storeUploadRelation([
                'system_upload_id' => $upload->id,
                'relation_id' => $publisher->id,
                'alias_model_relation' => $this->repository::MODEL_ALIAS,
                'alias_category' => $this->repository::FILE_CATEGORY_LOGO
            ]);

            DB::commit();
            return redirect()->route('publishers')->with('success', 'Editora criada com sucesso');
        } catch (Exception $e) {
            DB::rollback();
            if ($storedFile) {
                $this->uploadFileService->deleteFile($storedFile['server_file']);
            }
            return redirect()->route('publishers')->with('warning',  $e->getMessage());
        }
    }
}
