<?php
namespace App\Http\Controllers;
use App\Models\Character;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CharacterPdfController extends Controller
{
    /**
     * Erzeugt ein PDF für einen Charakter.
     */
    public function printCharacter($id)
    {
        // Lade den Charakter oder scheitere, falls nicht gefunden
        $character = Character::with('equipmentAssignments.equipment')->findOrFail($id);
        $basistalente = $this->getBasistalente($character);
        // Lade die View "pdf.character" und übergebe den Character
        $pdf = Pdf::loadView('pdf', compact('character', 'basistalente'));

        //PDF direkt anzeigen
        return $pdf->stream('character-' . $id . '.pdf');

        // downlaod
        // return $pdf->download('character-' . $id . '.pdf');
    }

    public function getBasistalente($character): array
    {
        $basistalente = [
            'KO' => [
                'wert' => $character->ko,
                'name' => 'Zähigkeit',
                'talente' => [
                    'Zäher Hund' => 14,
                    'Standhalten' => 15,
                    'Second Wind' => 16,
                    'Eisern' => 17,
                ],
            ],
        ];
        foreach ($basistalente as $eigenschaft => $data) {
            $basistalente[$eigenschaft]
            ['verfügbar'] = array_filter(
                $data['talente'],
                fn($schwelle) => $data['wert'] >= $schwelle
            );
        };
        return $basistalente;
    }



    public function fillForm($id)
    {
        // Daten, Schlüssel müssen exakt den Feldnamen im PDF entsprechen

        $character = Character::findOrFail($id);

        $fieldMapping = [
            'Name'  => 'name',
            'Geschwindigkeit' => 'geschwindigkeit',

            // PDF-Feldname => DB-Feldname
        ];

        $fields = [];

        foreach ($fieldMapping as $pdfField => $modelAttribute) {
            $fields[$pdfField] = $character->$modelAttribute;
        }



        $pdf = new \mikehaertl\pdftk\Pdf(
            storage_path('app/templates/Character-Sheet-LOSS-2_0.pdf'),
            [
            'command' => '/usr/bin/pdftk' // Pfad aus `which pdftk`
            ]
        );
        $result = $pdf
        ->fillForm($fields)
        ->saveAs(storage_path('app/output/ausgefüllt.pdf'));
        // dd($result);

        if ($result === false) {
            throw new \Exception('PDF-Befüllung fehlgeschlagen: ' . $pdf->getError());
        }

        return response()->file(storage_path('app/output/ausgefüllt.pdf'), [
            'Content-Type' => 'application/pdf',
        ]);

        //return response()->download(storage_path('app/output/ausgefüllt.pdf'));
    }


}
