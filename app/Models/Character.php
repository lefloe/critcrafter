<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'leps',
        'tragkraft',
        'geschwindigkeit',
        'handwerksbonus',
        'kontrollwiderstand',
        'initiative',
        'verteidigung',
        'seelenpunkte',
        'experience-level',
        'lore'
    ];
    protected $casts = [
        'rassenmerkmale' => 'array', // Konvertiert die Spalte in ein Array beim Abrufen und in JSON beim Speichern
    ];

    public function system(): BelongsTo
    {
        return $this->belongsTo(System::class);
    }
}
