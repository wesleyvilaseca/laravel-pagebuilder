<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemUpload extends Model
{
    use HasFactory;

    protected $fillable = ['public_id', 'original_file', 'mime_type', 'server_file'];
}
