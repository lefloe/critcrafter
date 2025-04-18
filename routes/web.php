<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CharacterPdfController;


Route::get('/characters/{id}/print', [CharacterPdfController::class, 'printCharacter'])
    ->name('character.print');
