<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Equipment;
use App\Models\EquipmentAssignment;


class Character extends Model
{
    protected $table = 'characters';

    protected $fillable = [
        'name',
        'description',
        'system_id',
        'leiteigenschaft1',
        'leiteigenschaft2',
        'achretype',
        'main_stat_value',
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
        'experience-level',
        'klassenfertigkeiten',
        'handwerkskenntnisse',
        'lore',
        'portrait'
    ];
    protected $casts = [
        'rassenmerkmale' => 'array', 
        'handwerkskenntnisse' => 'array', 
        'klassenfertigkeiten' => 'array', 
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
        'nw_damage_type' => 'array'
    ];


    public function system(): BelongsTo
    {
        return $this->belongsTo(System::class);
    }



    public function equipmentAssignments()
    {
        return $this->hasMany(EquipmentAssignment::class);
    }

    public function equippedItems()
    {
        return $this->equipmentAssignments->filter(fn($a) => $a->equipped)->map->equipment;
    }

    public function equippedArmor()
    {
        return $this->equippedItems()->firstWhere('item_type', 'Rüstung');
    }
}
