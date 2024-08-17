<?php

namespace App\Models;

use App\Services\UploadFileService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    const PRINCIPAL_EVENT = 1;
    const NOT_PRINCIPAL_ENVENT = 0;

    const EVENT_ACTIVE = 1;
    const EVENT_INACTIVE = 0;

    protected $fillable = ['name', 'description', 'url', 'status', 'theme_id', 'principal'];

    public function publishers()
    {
        return $this->belongsToMany(Publisher::class, 'event_publishers', 'event_id', 'publisher_id');
    }

    public function banners()
    {
        return $this->hasMany(EventBannerGallery::class, 'event_id');
    }

    public function banchMaps() {
        return $this->hasMany(EventBenchMapGallery::class, 'event_id');
    }

    /**
     * Cateroies not linked with this product
     */
    public function publishersAvailable($filter = null)
    {
        $publishers = Publisher::whereNotIn('publishers.id', function ($query) {
            $query->select('event_publishers.publisher_id');
            $query->from('event_publishers');
            $query->whereRaw("event_publishers.event_id={$this->id}");
        })
            ->where(function ($queryFilter) use ($filter) {
                if ($filter)
                    $queryFilter->where('publishers.name', 'LIKE', "%{$filter}%");
            })
            ->paginate();

        return $publishers;
    }

    public function delete() {
        $uploadFileService = new UploadFileService(new SystemUpload(), new UploadRelation());
        $this->banners->each(function ($banner) use ($uploadFileService) {
            if($banner->uploads[0]) {
                $uploadFileService->deleteFile('', $banner->uploads[0], true);
            }

            $banner->delete();
        });

        $this->banchMaps->each(function ($banner) use ($uploadFileService) {
            if($banner->uploads[0]) {
                $uploadFileService->deleteFile('', $banner->uploads[0], true);
            }

            $banner->delete();
        });
        parent::delete();
    }
}
