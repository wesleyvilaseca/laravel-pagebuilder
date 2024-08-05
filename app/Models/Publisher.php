<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    use HasFactory;

    const MODEL_ALIAS = 'publisher';
    const FILE_CATEGORY_LOGO = 'publisher-logo';
    const FILE_CATEGORY_PUBLISHER_BOOK_PRICE_LIST = 'publisher-price-list';

    protected $fillable = ['name', 'description', 'site', 'email', 'data', 'status'];

    public function uploads()
    {
        return $this->belongsToMany(SystemUpload::class, 'upload_relations', 'relation_id', 'system_upload_id');
    }

}
