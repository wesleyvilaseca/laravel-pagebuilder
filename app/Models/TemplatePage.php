<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PHPageBuilder\Contracts\PageRepositoryContract;

class TemplatePage extends Model
{
    use HasFactory;

    protected $fillable = ['template_id', 'page_id'];
}
