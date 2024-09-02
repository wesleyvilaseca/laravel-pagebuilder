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

    protected $fillable = ['name', 'description', 'site', 'email', 'data', 'url', 'status'];

    public function search($filter = null)
    {
        $results = $this->where([
            ['name', 'LIKE', "%{$filter}%"],
        ])
        ->orWhere([
                ['description', 'LIKE', "%{$filter}%"],
                ['site', 'LIKE', "%{$filter}%"],
                ['email', 'LIKE', "%{$filter}%"]
                ])
        ->paginate(8);

        return $results;
    }

    public function uploads()
    {
        return $this->belongsToMany(SystemUpload::class, 'upload_relations', 'relation_id', 'system_upload_id')->wherePivot('alias_model_relation', self::MODEL_ALIAS);
    }

    public function books()
    {
        return $this->belongsToMany(Book::class, 'publisher_books', 'publisher_id', 'book_id');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_publishers', 'publisher_id', 'event_id');
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
