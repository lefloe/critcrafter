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
        'ko_bonus',
        'bonus_sep',
        'bonus_lep',
        'bonus_ini',
        'bonus_re',
        'race',
        'wesen',
        'racial_traits',
        'ko',
        'st',
        'ag',
        'ge',
        'we',
        'in',
        'mu',
        'ch',
        'ko_sum',
        'st_sum',
        'ag_sum',
        'ge_sum',
        'we_sum',
        'in_sum',
        'mu_sum',
        'ch_sum',
        'skill_weapon',
        'skill_aspect',
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
        'classability1',
        'classability2',
        'classability3',
        'handwerkskenntnisse',
        'lore',
    ];
    protected $casts = [
        'racial_traits' => 'array',
        'handwerkskenntnisse' => 'array',
        'classability1' => 'array',
        'classability2' => 'array',
        'classability3' => 'array',
        'skill_weapon' => 'array',
        'skill_aspect' => 'array',
        'lore' => 'array',
        'nw_gattung' => 'array',
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
