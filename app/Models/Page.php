<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    const HOME_PAGE = 1;
    protected $table = 'pages';
    protected $fillable = ['event_id', 'name', 'layout', 'route', 'homepage', 'data'];

    public function templates()
    {
        return $this->belongsToMany(Template::class, 'template_pages', 'page_id', 'template_id');
    }
}
