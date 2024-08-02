<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    const PRINCIPAL_EVENT = 1;
    const NOT_PRINCIPAL_ENVENT = 0;

    const EVENT_ACTIVE = 1;
    const EVENT_INACTIVE = 0;

    protected $fillable = ['name', 'description', 'url', 'status', 'theme_id', 'principal'];

}
