<?php
namespace App\Http\Controllers;
use App\Models\Character;
use Barryvdh\DomPDF\Facade\Pdf;  // oder use PDF; falls ein Alias in config/app.php existiert
use Illuminate\Http\Request;

class CharacterPdfController extends Controller
{
    /**
     * Erzeugt ein PDF für einen Charakter.
     */
    public function printCharacter($id)
    {
        // Lade den Charakter oder scheitere, falls nicht gefunden
        $character = Character::with('equipment')->findOrFail($id);        
        // Lade die View "pdf.character" und übergebe den Character
        $pdf = Pdf::loadView('pdf', compact('character'));
        
        //PDF direkt anzeigen
        return $pdf->stream('character-' . $id . '.pdf');
        
        // downlaod
        // return $pdf->download('character-' . $id . '.pdf');
    }
}