<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanionAssign extends Model
{
    public function companion()
    {
        return $this->hasOne(Companion::class, 'id', 'companion_id');
    }
}
