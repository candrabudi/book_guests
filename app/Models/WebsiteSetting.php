<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    protected $fillable = [
        'website_name',
        'website_logo',
        'website_favicon',
        'website_copyright',
    ];
}
