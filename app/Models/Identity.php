<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Identity extends Model
{
    public function guests()
    {
        return $this->hasMany(Guest::class, 'identity_id', 'id');
    }
}
