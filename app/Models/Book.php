<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'author', 'subject', 'isbn', 'description',  'price', 'presential_discount', 'virtual_discount', 'link', 'url', 'status'];

    const MODEL_ALIAS = 'book';
    const FILE_CATEGORY_BOOK_SINGLE_IMAGE = 'book-image';
    const FILE_CATEGORY_BOOK_IMAGE_GALLERY = 'book-gallery'; 

    public function uploads()
    {
        return $this->belongsToMany(SystemUpload::class, 'upload_relations', 'relation_id', 'system_upload_id');
    }

    public function publishers()
    {
        return $this->belongsToMany(Publisher::class, 'publisher_books', 'book_id', 'publisher_id');
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
