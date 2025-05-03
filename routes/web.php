<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CharacterPdfController;
use App\Http\Controllers\PdfTestController;


Route::get('/characters/{id}/print', [CharacterPdfController::class, 'printCharacter'])
    ->name('character.print');

Route::get('/fill-characters/{id}/print', [CharacterPdfController::class, 'fillForm'])
    ->name('fill-character.print');
