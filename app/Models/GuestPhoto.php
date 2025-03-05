<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestPhoto extends Model
{
    protected $fillable = ['guest_id', 'photo_path', 'file_name', 'file_size', 'file_extension'];
}
