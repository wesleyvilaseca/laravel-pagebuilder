<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadRelation extends Model
{
    use HasFactory;

    protected $fillable = ['system_upload_id', 'relation_id', 'alias_model_relation', 'alias_category'];

}
