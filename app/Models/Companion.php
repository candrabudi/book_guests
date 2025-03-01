<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Companion extends Model
{
    protected $fillable = [
        'companion_name'
    ];

    public function guests()
    {
        return $this->hasMany(Guest::class, 'companion_id');
    }
}
