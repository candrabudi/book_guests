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

    public function companion()
    {
        return $this->hasOne(Companion::class, 'id', 'companion_id');
    }

    public function guestPhoto()
    {
        return $this->hasOne(GuestPhoto::class, 'guest_id', 'id');
    }

    public function companionAssign()
    {
        return $this->hasMany(CompanionAssign::class, 'guest_id', 'id')
            ->with('companion');
    }
}
