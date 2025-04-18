<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    protected $table = 'systems';

    protected $fillable = [
        'name',
    ];

    public function characters()
    {
        return $this->hasMany(Character::class);
    }
}
