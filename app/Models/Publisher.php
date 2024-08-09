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

    public function books()
    {
        return $this->belongsToMany(Book::class, 'publisher_books', 'publisher_id', 'book_id');
    }

    /**
     * Define an accessor to cast the 'data' column to an object.
     *
     * @param  string  $value
     * @return mixed
     */
    public function getDataAttribute($value)
    {
        return json_decode($value);
    }
}
