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
        'traglast',
        'passive_verteidigung',
        'schild_verteidigung',
        'rs_schnitt',
        'rs_stumpf',
        'rs_stich',
        'rs_elementar',
        'rs_arcan',
        'rs_chaos',
        'rs_spirit',
        'enchantment',
        'enchantment_qs',
        'kontrollwiderstand',
        'rs_arcan',
        'rs_chaos',
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
