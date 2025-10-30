<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class CharacterEquipment extends Model
{
    protected $table = 'character_equipment';
    protected $fillable = ['character_id', 'equipment_id', 'slot'];

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }
}
