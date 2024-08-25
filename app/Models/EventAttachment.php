<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventAttachment extends Model
{
    use HasFactory;

    const FILE_CATEGORY_ATTACHMENT = 'event-attachments';
    const MODEL_ALIAS = 'event_attachments';

    protected $fillable = ['event_id', 'name', 'description', 'status', 'order'];

    public function uploads()
    {
        return $this
        ->belongsToMany(SystemUpload::class, 'upload_relations', 'relation_id', 'system_upload_id')
        ->wherePivot('alias_model_relation', self::MODEL_ALIAS)
        ->withPivot('alias_category');
    }
}
