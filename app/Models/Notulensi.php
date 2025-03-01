<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notulensi extends Model
{
    use HasFactory;

    protected $fillable = ['guest_id', 'title', 'notulensi', 'appointment'];

    public function photos()
    {
        return $this->hasMany(NotulensiPhoto::class);
    }
}
