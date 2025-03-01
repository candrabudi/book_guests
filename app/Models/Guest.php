<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    public function notulensi()
    {
        return $this->hasOne(Notulensi::class, 'guest_id', 'id');
    }

    public function identity()
    {
        return $this->hasOne(Identity::class, 'id', 'identity_id');
    }

    public function institution()
    {
        return $this->hasOne(Institution::class, 'id', 'institution_id');
    }
}
