<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;




class Equipment extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'description',
        'quality',
        'item_type',
        'hwp',
        'waffengattung',
        'attackvalue',
        'damage_type',
        'tw',
        'count_dice',
        'waffenführung',
        'waffengattung',
        'traglast',
        'passive_verteidigung',
        'wp_vw',
        'schild_verteidigung',
        'armor_schnitt',
        'armor_stumpf',
        'armor_stich',
        'armor_elementar',
        'armor_arcan',
        'armor_chaos',
        'armor_spirit',
        'shield_schnitt',
        'shield_stumpf',
        'shield_stich',
        'shield_elementar',
        'shield_arcan',
        'shield_chaos',
        'shield_spirit',
        'charm_arcan',
        'charm_chaos',
        'charm_spirit',
        'enchantment',
        'enchantment_qs',
        'kontrollwiderstand',
        'wp_erweiterungen',
        'rs_erweiterungen',
        'ts_erweiterungen',
        'sd_erweiterungen',
        'character_id',
        'equipped'
    ];
    protected $casts = [
        'damage_type' => 'array',
        'enchantment' => 'array',
        'wp_erweiterungen' => 'array',
        'rs_erweiterungen' => 'array',
        'ts_erweiterungen' => 'array',
        'sd_erweiterungen' => 'array',
        'waffenführung' => 'array',
    ];

    public function character(): BelongsToMany
    {
        return $this->belongsToMany(Character::class, 'character_equipment')
            ->withPivot('slot')
            ->withTimestamps();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeAvailable($query)
    {
        return $query->whereDoesntHave('character');
    }
}
