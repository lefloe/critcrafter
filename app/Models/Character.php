<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;



class Character extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'user_id',
        'description',
        'leiteigenschaft1',
        'leiteigenschaft2',
        'archetype',
        'main_stat_value',
        'ko_toggle',
        'race',
        'wesen',
        'rassenmerkmale',
        'ko',
        'st',
        'ag',
        'ge',
        'we',
        'in',
        'mu',
        'ch',
        'skill_ko',
        'skill_st',
        'skill_ag',
        'skill_ge',
        'skill_we',
        'skill_in',
        'skill_mu',
        'skill_ch',
        'leps',
        'tragkraft',
        'geschwindigkeit',
        'handwerksbonus',
        'kontrollwiderstand',
        'initiative',
        'verteidigung',
        'seelenpunkte',
        'nw_quality',
        'nw_gattung',
        'nw_damage_type',
        'nw_aw',
        'nw_vw',
        'nw_tw',
        'xp',
        'klassenfertigkeiten',
        'handwerkskenntnisse',
        'lore',
        'portrait'
    ];
    protected $casts = [
        'rassenmerkmale' => 'array',
        'handwerkskenntnisse' => 'array',
        'klassenfertigkeiten' => 'array',
        'klassenfertigkeiten2' => 'array',
        'klassenfertigkeiten3' => 'array',
        'skill_ko' => 'array',
        'skill_st' => 'array',
        'skill_ag' => 'array',
        'skill_ge' => 'array',
        'skill_we' => 'array',
        'skill_in' => 'array',
        'skill_mu' => 'array',
        'skill_ch' => 'array',
        'equipment' => 'array',
        'lore' => 'array',
        'nw_damage_type' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function equipment(): BelongsToMany
    {
        return $this->belongsToMany(Equipment::class, 'character_equipment')
            ->withPivot('slot')
            ->withTimestamps();
    }
    public function characterEquipment()
    {
        return $this->hasMany(CharacterEquipment::class);
    }
}
