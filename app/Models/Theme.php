<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use HasFactory;

    const ACTIVE = 1;
    const INATIVE = 0;
    const DEFAULT_THEME = 'demo';
}
