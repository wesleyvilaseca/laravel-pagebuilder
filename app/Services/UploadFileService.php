<?

namespace App\Services;

use App\Models\SystemUpload;
use App\Models\Upload;
use App\Models\UploadRelation;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadFileService {

    protected $uploadRepository;
    protected $uploadRelationRepository;

    public function __construct(SystemUpload $uploadRepository, UploadRelation $uploadRelationRepository)
    {
        $this->uploadRepository = $uploadRepository;
        $this->uploadRelationRepository = $uploadRelationRepository;
    }
    
    public function upload(UploadedFile $file, string $directory = 'uploads', string $disk = 'public'): array
    {
        try {
            $publicId = sha1(uniqid(rand(), true));

            $directory = $directory . '/' . $publicId;
            $filename = uniqid() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
    
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

    public function deleteFile($path) {
        Storage::disk('public')->delete($path);

        if (count(Storage::disk('public')->files($path)) === 0 &&
            count(Storage::disk('public')->directories($path)) === 0) {
            Storage::disk('public')->deleteDirectory($path);
        }
    }
}