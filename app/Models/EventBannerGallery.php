<?php

namespace App\Models;

use App\Services\UploadFileService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventBannerGallery extends Model
{
    use HasFactory;

    const FILE_CATEGORY_BANNER_DESKTOP = 'event-banner-gallery-desktop';
    const FILE_CATEGORY_BANNER_MOBILE = 'event-banner-gallery-mobile';
    const MODEL_ALIAS = 'event_banner_galleries';

    protected $fillable = ['event_id', 'name', 'description', 'link', 'status', 'order'];

    public function uploads()
    {
        return $this
            ->belongsToMany(SystemUpload::class, 'upload_relations', 'relation_id', 'system_upload_id')
            ->wherePivot('alias_model_relation', self::MODEL_ALIAS)
            ->withPivot('alias_category');
    }
}
