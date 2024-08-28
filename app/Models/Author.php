<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = ['first_name', 'last_name', 'description', 'data', 'status'];

    public function books()
    {
        return $this->belongsToMany(Book::class, 'author_books', 'author_id', 'book_id');
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
