<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'theme_id', 'status', 'url'];

    public function pages()
    {
        return $this->belongsToMany(Page::class, 'template_pages', 'template_id', 'page_id');
    }
}
