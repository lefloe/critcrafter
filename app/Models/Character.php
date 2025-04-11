<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Equipment;


class Character extends Model
{
    protected $table = 'characters';

    protected $fillable = [
        'name',
        'description',
        'system_id',
        'leiteigenschaft1',
        'leiteigenschaft2',
        'race',
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
        'experience-level',
        'klassenfertigkeiten',
        'handwerkskenntnisse',
        'lore',
        // 'equipment_id',
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
    ];


    public function system(): BelongsTo
    {
        return $this->belongsTo(System::class);
    }

    public function equipment()
    {
        return $this->hasMany(equipment::class);
    }
}
