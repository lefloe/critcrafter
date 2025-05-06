<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentAssignment extends Model
{
    protected $fillable = [
        'equipment_id',
        'equipped'
    ];

    public function character()
    {
        return $this->belongsTo(Character::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
