<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamificationStats extends Model
{
    protected $fillable = ['level', 'current_xp', 'next_level_xp'];
}
