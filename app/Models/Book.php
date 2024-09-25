<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'author', 'subject', 'isbn', 'description',  'price', 'price_discount', 'link', 'url', 'status'];

    const MODEL_ALIAS = 'book';
    const FILE_CATEGORY_BOOK_SINGLE_IMAGE = 'book-image';
    const FILE_CATEGORY_BOOK_IMAGE_GALLERY = 'book-gallery';

    // public function authors()
    // {
    //     return $this->belongsToMany(Author::class, 'author_books', 'book_id', 'author_id');
    // }

    public function uploads()
    {
        return $this->belongsToMany(SystemUpload::class, 'upload_relations', 'relation_id', 'system_upload_id')->wherePivot('alias_model_relation', self::MODEL_ALIAS);
    }

    public function authorsAvailable($filter = null)
    {
        $authors = Author::whereNotIn('authors.id', function ($query) {
            $query->select('author_books.author_id');
            $query->from('author_books');
            $query->whereRaw("author_books.book_id={$this->id}");
        })
            ->where(function ($queryFilter) use ($filter) {
                if ($filter)
                    $queryFilter->where('authors.name', 'LIKE', "%{$filter}%");
            })->get();

        return $authors;
    }

    public function publishers()
    {
        return $this->belongsToMany(Publisher::class, 'publisher_books', 'book_id', 'publisher_id');
    }

    public function search(array $filters) {
        $query = Book::query();

        if (isset($filters['selected_filter']) && isset($filters['filter'])) {
            if ($filters['selected_filter'] === 'publisher_name') {
                $query->whereHas('publishers', function ($query) use ($filters) {
                    $query->where('name', 'like', '%' . $filters['filter'] . '%');
                });
            } else {
                $query->where($filters['selected_filter'], 'like', '%' . $filters['filter'] . '%');
            }
        }

        $query->orderBy('name', 'asc');

        return $query->paginate(8);

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
