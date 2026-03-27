<?php

use App\Models\Character;
use App\Services\CharacterPdfService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/player/characters/{character}/pdf', function (Character $character) {
    abort_unless(auth()->id() === $character->user_id, 403);

    $path = app(CharacterPdfService::class)->fill($character);
    $filename = str($character->name)->slug() . '-charakterbogen.pdf';

    return response()->file($path, [
        'Content-Type'        => 'application/pdf',
        'Content-Disposition' => 'inline; filename="' . $filename . '"',
    ])->deleteFileAfterSend();
})->middleware(['web', 'auth'])->name('character.pdf');
