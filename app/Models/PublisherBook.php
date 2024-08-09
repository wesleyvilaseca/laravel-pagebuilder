<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublisherBook extends Model
{
    use HasFactory;

    protected $table = 'publisher_books';

    protected $fillable = ['publisher_id', 'book_id'];

}
