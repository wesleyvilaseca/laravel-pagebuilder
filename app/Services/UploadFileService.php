<?

namespace App\Services;

use App\Models\SystemUpload;
use App\Models\Upload;
use App\Models\UploadRelation;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadFileService {

    public function __construct(
        protected SystemUpload $uploadRepository,
        protected UploadRelation $uploadRelationRepository)
    {
    }
    
    public function upload(UploadedFile $file, string $directory = 'uploads', string $disk = 'public'): array
    {
        try {
            $publicId = sha1(uniqid(rand(), true));

            $filename = $publicId . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
    
            $server_file = $file->storeAs($directory, $filename, $disk);
    
            return [
                'server_file' => $server_file,
                'mime_type' => $file->getMimeType(),
                'public_id' => $publicId,
                'original_file' => $file->getClientOriginalName()
            ];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * store on data base
     */
    public function store(array $data) : object {
        if (!$data['server_file']) {
            throw new Exception('server file path is required.');
        }

        if (!$data['mime_type']) {
            throw new Exception('mime_type is required.');
        }

        if (!$data['public_id']) {
            throw new Exception('public_id is required.');
        }

        if (!$data['original_file']) {
            throw new Exception('original_file is required.');
        }

        return $this->uploadRepository->create($data);
    }

    public function storeUploadRelation(array $data) {
        if (!$data['system_upload_id']) {
            throw new Exception('system_upload_id is required.');
        }

        if (!$data['relation_id']) {
            throw new Exception('relation_id is required.');
        }

        if (!$data['alias_model_relation']) {
            throw new Exception('relation_id is required.');
        }

        return $this->uploadRelationRepository->create($data);
    }

    public function deleteFile(string $path = null, SystemUpload $file = null, bool $deletePublisherDirectory = false) {
        DB::beginTransaction();
        try {
            if ($file) {
                $path = $file->server_file;
                $this->uploadRelationRepository->where('system_upload_id', $file->id)->delete();
                $this->uploadRepository->where('id', $file->id)->delete();
            }

            if(!$file && !$path) {
                throw new Exception('server_file is required.');
            }

            $dir = dirname($path);

            Storage::disk('public')->delete($path);
    
            if (count(Storage::disk('public')->files($dir)) === 0&&
                count(Storage::disk('public')->directories($dir)) === 0) {
                Storage::disk('public')->deleteDirectory($dir);

                $parentDir = dirname($dir);
        
                if($deletePublisherDirectory) {
                    if (count(Storage::disk('public')->files($parentDir)) === 0){
                        Storage::disk('public')->deleteDirectory($parentDir);
                    }
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception($e->getMessage());
        }
        
    }
}