<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotulensiPhoto extends Model
{
    use HasFactory;

    protected $fillable = ['notulensi_id', 'photo_path', 'file_name', 'file_size', 'file_extension'];

    public function notulensi()
    {
        return $this->belongsTo(Notulensi::class);
    }
}
