<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventBenchMapGallery extends Model
{
    use HasFactory;

    const FILE_CATEGORY_BENCHMAP = 'event-benchmap-gallery';
    const MODEL_ALIAS = 'event_benchmap_gallery';

    protected $fillable = ['event_id', 'name', 'description', 'link', 'status', 'order'];

    public function uploads()
    {
        return $this
        ->belongsToMany(SystemUpload::class, 'upload_relations', 'relation_id', 'system_upload_id')
        ->wherePivot('alias_model_relation', self::MODEL_ALIAS)
        ->withPivot('alias_category');
    }
}
