<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use App\Models\PublisherBook;
use App\Services\UploadFileService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PublisherController extends Controller
{
    public function __construct(
        protected UploadFileService $uploadFileService,
        protected Publisher $repository,
        protected PublisherBook $publisherBook
        )
    {
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

    public function edit($id) {
        if (!$id) {
            return redirect()->back();
        }

        $publisher = $this->repository->find($id);
        if (!$publisher) {
            return redirect()->back();
        }

        $data['publisher_'] = true;
        $data['title']  = 'Editar dado Editora - ' . $publisher->name;
        $data['toptitle'] =  $data['title'];
        $data['breadcrumb'][] = ['route' => route('painel'), 'title' => 'Dashboard'];
        $data['breadcrumb'][] = ['route' => route('publishers'), 'title' => 'Editoras'];
        $data['breadcrumb'][] = ['route' => '#', 'title' => $data['title'], 'active' => true];
        $data['logo'] = $publisher->uploads()->wherePivot('alias_category', $this->repository::FILE_CATEGORY_LOGO)->first();
        $data['price_list'] = $publisher->uploads()->wherePivot('alias_category', $this->repository::FILE_CATEGORY_PUBLISHER_BOOK_PRICE_LIST)->first();
        $data['publisher'] = $publisher;
        $data['action']         = route('publisher.update', $publisher->id);

        return view('admin.publishers.edit', $data);
    }

    public function store(Request $request) {
        DB::beginTransaction();
        try {
            $request->validate([
                'logo' => 'required|file|mimes:jpg,jpeg,png|max:2048',
                'price_list' =>  'file|mimes:pdf|max:2048',
                'name' => 'required|string|unique:publishers,name',
                'status' => 'required|integer',
                'description' => 'required|string'
            ]);

            $address = (object) [
                'address' => @$request->address,
                'zip_code' => @$request->zip_code,
                'state' => @$request->state,
                'district' => @$request->district,
                'city' => @$request->city,
                'number' => @$request->number
            ];

            $social = (object)[
                'facebook' => @$request->facebook,
                'instagram' => @$request->instagram,
                'youtube' => @$request->youtube
            ];

            $publisher = $this->repository->create([
                'name' => $request->name,
                'status' => $request->status,
                'description' => $request->description,
                'site' => @$request->site,
                'email' => @$request->email,
                'data' => json_encode((object)[
                    'social' => $social,
                    'address' => $address
                ])
            ]);

            $storedFileLogo = $this->uploadFileService->upload(
                $request->file('logo'),
                'publishers/publisher-' . $publisher->id . '/' . $this->repository::FILE_CATEGORY_LOGO
            );

            $upload = $this->uploadFileService->store($storedFileLogo);

            $this->uploadFileService->storeUploadRelation([
                'system_upload_id' => $upload->id,
                'relation_id' => $publisher->id,
                'alias_model_relation' => $this->repository::MODEL_ALIAS,
                'alias_category' => $this->repository::FILE_CATEGORY_LOGO
            ]);

            if ($request->file('price_list')) {
                $storedFilePriceList = $this->uploadFileService->upload(
                    $request->file('price_list'),
                    'publishers/publisher-' . $publisher->id . '/' . $this->repository::FILE_CATEGORY_PUBLISHER_BOOK_PRICE_LIST
                );
                $upload = $this->uploadFileService->store($storedFilePriceList);

                $this->uploadFileService->storeUploadRelation([
                    'system_upload_id' => $upload->id,
                    'relation_id' => $publisher->id,
                    'alias_model_relation' => $this->repository::MODEL_ALIAS,
                    'alias_category' => $this->repository::FILE_CATEGORY_PUBLISHER_BOOK_PRICE_LIST
                ]);
            }

            DB::commit();
            return redirect()->route('publishers')->with('success', 'Editora criada com sucesso');
        } catch (Exception $e) {
            DB::rollback();
            if (isset($storedFileLogo)) {
                $this->uploadFileService->deleteFile($storedFileLogo['server_file']);
            }

            if (isset($storedFilePriceList)) {
                $this->uploadFileService->deleteFile($storedFilePriceList['server_file']);
            }
            return redirect()->route('publishers')->with('warning',  $e->getMessage());
        }
    }

    public function update(Request $request, $id) {
        $publisher = $this->repository->find($id);
        if (!$publisher) {
            return redirect()->back()->with('error', 'Editora não localizada');
        }

        $request->validate([
            'logo' => 'file|mimes:jpg,jpeg,png|max:2048',
            'price_list' => 'file|mimes:pdf|max:2048',
            'name' => 'required|string|unique:publishers,name,' . $id,
            'description' => 'required|string',
            'status' => 'required|integer'
        ]);

        DB::beginTransaction();
        try {
            DB::commit();
            if ($request->file('logo')) {
                $logo = $publisher->uploads()->wherePivot('alias_category', $this->repository::FILE_CATEGORY_LOGO)->first();
                if($logo) {
                    $this->uploadFileService->deleteFile('', $logo);
                }

                $storedFileLogo = $this->uploadFileService->upload(
                    $request->file('logo'),
                    'publishers/publisher-' . $publisher->id . '/' . $this->repository::FILE_CATEGORY_LOGO
                );
                $upload = $this->uploadFileService->store($storedFileLogo);

                $this->uploadFileService->storeUploadRelation([
                    'system_upload_id' => $upload->id,
                    'relation_id' => $publisher->id,
                    'alias_model_relation' => $this->repository::MODEL_ALIAS,
                    'alias_category' => $this->repository::FILE_CATEGORY_LOGO
                ]);
            }
    
            if ($request->file('price_list')) {
                $price_list = $publisher->uploads()->wherePivot('alias_category', $this->repository::FILE_CATEGORY_PUBLISHER_BOOK_PRICE_LIST)->first();
                if ($price_list) {
                    $this->uploadFileService->deleteFile('', $price_list);
                }

                $storedFilePriceList = $this->uploadFileService->upload(
                    $request->file('price_list'),
                    'publishers/publisher-' . $publisher->id . '/' . $this->repository::FILE_CATEGORY_PUBLISHER_BOOK_PRICE_LIST
                );
                $upload = $this->uploadFileService->store($storedFilePriceList);

                $this->uploadFileService->storeUploadRelation([
                    'system_upload_id' => $upload->id,
                    'relation_id' => $publisher->id,
                    'alias_model_relation' => $this->repository::MODEL_ALIAS,
                    'alias_category' => $this->repository::FILE_CATEGORY_PUBLISHER_BOOK_PRICE_LIST
                ]);
            }

            $address = (object) [
                'address' => @$request->address,
                'zip_code' => @$request->zip_code,
                'state' => @$request->state,
                'district' => @$request->district,
                'city' => @$request->city,
                'number' => @$request->number
            ];

            $social = (object)[
                'facebook' => @$request->facebook,
                'instagram' => @$request->instagram,
                'youtube' => @$request->youtube
            ];

            $this->repository->where('id', $publisher->id)->update([
                'name' => $request->name,
                'status' => $request->status,
                'description' => $request->description,
                'site' => @$request->site,
                'email' => @$request->email,
                'data' => json_encode((object)[
                    'social' => $social,
                    'address' => $address
                ])
            ]);
            DB::commit();
            return redirect()->route('publishers')->with('success', 'Editora editada com sucesso');
        } catch (Exception $e) {
            DB::rollback();
            if (isset($storedFileLogo)) {
                $this->uploadFileService->deleteFile($storedFileLogo['server_file']);
            }

            if (isset($storedFilePriceList)) {
                $this->uploadFileService->deleteFile($storedFilePriceList['server_file']);
            }
            return redirect()->route('publishers')->with('warning',  $e->getMessage());
        }
    }

    public function delete($id) {
        DB::beginTransaction();
        try {
            $publisher = $this->repository->find($id);
            if (!$publisher) {
                return redirect()->back()->with('error', 'Editora não localizada');
            }

            $files = $publisher->uploads;

            if (!empty($files)) {
                foreach($files as $file) {
                    $this->uploadFileService->deleteFile(null, $file, true);
                }
            }

            $this->publisherBook->where('publisher_id', $publisher->id)->delete();
            $publisher->delete();
            DB::commit();
            return redirect()->route('publishers')->with('success', 'Editora removido com sucesso');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('publishers')->with('warning',  $e->getMessage());
        }
    }
}
