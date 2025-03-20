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
        'race',
        'experience-level',
    ];

    public function system(): BelongsTo
    {
        return $this->belongsTo(System::class);
    }
}
