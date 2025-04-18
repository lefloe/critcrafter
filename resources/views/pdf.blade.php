<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>Charakterbogen – {{ $character->name }}</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10pt;
            margin: 0;
            padding: 0;
        }
        .container {
            margin: 0 auto;
            width: 100%;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-top: 20px;
            margin-right: 20px;
            padding-bottom: 5px;
        }
        .header h1 {
            font-size: 18pt;
            margin: 0;
        }
        .section {
            margin-bottom: 10px;
        }
        .section h2 {
            font-size: 14pt;
            margin-bottom: 5px;
            padding-bottom: 3px;
        }
        .table {
            border-collapse: collapse;
            margin-bottom: 10px;
            margin-right: 10px;
        }
        .table th, .table td {
            border: 1px solid #888;
            padding: 4px;
            text-align: left;
            font-size: 8pt;
            word-wrap: break-word;
            word-break: break-word;
            white-space: normal;
        }
        .small {
            font-size: 8pt;
        }
        /* Beispielweise Spaltenbreiten anpassen */
        .col-attr { width: 15%; }
        .col-value { width: 15%; }

        .page-break {
        page-break-after: always;
        }   
        .dark-half-abs {
            position: absolute;
            top: 148.5mm;  /* genau ab der Mitte */
            left: 0;
            width: 100%;
            height: 148.5mm;
            background-color: #333;
            color: #fff;
            padding: 0px;
            /* box-sizing: border-box; */
            z-index: -1;   /* damit es hinter dem Text liegt */
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Kopfzeile mit Portrait & Namen -->
        <div class="header">
            <table style="width: 100%; margin-bottom: 20px;">
                <tr>
                    <td style="width: 30%;">
                        @if($character->portrait)
                            <img src="{{ public_path('storage/' . $character->portrait) }}" alt="Portrait" style="max-width: 200px; max-height: 300px">
                        @endif                
                    </td>
                    <td style="width: 70%; vertical-align: top; padding-left: 15px;">
                        <h2 style="margin: 0;">{{ $character->name }}</h2>
                        <p><strong>Beschreibung:</strong> {{ $character->description }}</p>
                        <p><strong>Rasse:</strong> {{ $character->race }}</br>
                        <strong>Wesen:</strong> {{ $character->wesen ?? '—' }}</br>
                        <strong>Leiteigenschaften:</strong> {{ $character->leiteigenschaft1 }} / {{ $character->leiteigenschaft2 }}</p>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Abschnitt: körperl. Eigenschaften -->
        <div class="section" style="position: relative; z-index: 1;">
            <table class="table" style="width: 100%; margin-bottom: 20px;">
                <tr>
                    <td style="width: 30%;">
                        <table style="width: 100%;">
                            <tr>
                                <td>{{ $character->leps }}</td>
                                <th>Lebenspunkte (LeP)</th>
                            </tr>
                            <tr>
                                <td>{{ $character->tragkraft }}</td>
                                <th>Tragkraft</th>
                            </tr>
                            <tr>
                                <td>{{ $character->geschwindigkeit }}</td>
                                <th>Geschwindigkeit</th>
                            </tr>
                            <tr>
                                <td>{{ $character->handwerksbonus }}</td>
                                <th>Handwerksbonus</th>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 70%; vertical-align: top; padding-left: 15px;">
                        <table style="width: 100%; border-collapse: collapse;">
                            
                            <tr>
                                <th>KO</th>
                                <th>ST</th>
                                <th>AG</th>
                                <th>GE</th>
                            </tr>
                            <tr>
                                <td> {{ $character->ko }}</td>
                                <td> {{ $character->st }}</td>
                                <td> {{ $character->ag }}</td>
                                <td> {{ $character->ge }}</td>
                            </tr>
                        </td>
                    </table>
                </tr>
            </table>
            <h2>Leib</h2>
        </div>

        <!-- Abschnitt: Abgeleitete Werte -->
        <div class="section" style="position: relative; z-index: 1;">
            <h2>Abgeleitete Werte</h2>
            <table class="table">
                <tr>
                    <th>Lebenspunkte (LeP)</th>
                    <th>Tragkraft</th>
                    <th>Geschwindigkeit</th>
                    <th>Handwerksbonus</th>
                    <th>Kontrollbonus</th>
                    <th>Initiative</th>
                    <th>Verteidigung</th>
                    <th>Seelenpunkte</th>
                </tr>
                <tr>
                    <td>{{ $character->leps }}</td>
                    <td>{{ $character->tragkraft }}</td>
                    <td>{{ $character->geschwindigkeit }}</td>
                    <td>{{ $character->handwerksbonus }}</td>
                    <td>{{ $character->kontrollwiderstand }}</td>
                    <td>{{ $character->initiative }}</td>
                    <td>{{ $character->verteidigung }}</td>
                    <td>{{ $character->seelenpunkte }}</td>
                </tr>
            </table>
        </div>

    <!-- </div> evtl in zwei Container unterteilen?
        
    <div class="container"> -->

    <!-- Abschnitt: Fertigkeiten, Überlieferungen, etc. -->
        <div class="dark-half-abs"></div>
                <!-- Abschnitt: körperl. Eigenschaften -->
                <div class="section" style="position: relative; z-index: 1;">
            <h2>Seele</h2>
            <table class="table">
                <tr>
                    <th class="col-attr">WE</th>
                    <th class="col-attr">IN</th>
                    <th class="col-attr">MU</th>
                    <th class="col-attr">CH</th>
                </tr>
                <tr>
                    <td class="col-value">{{ $character->we }}</td>
                    <td class="col-value">{{ $character->in }}</td>
                    <td class="col-value">{{ $character->mu }}</td>
                    <td class="col-value">{{ $character->ch }}</td>
                </tr>
            </table>
        </div>
        <div class="section" style="position: relative; z-index: 1;">
            <h2>Fertigkeiten & Überlieferungen</h2>
            <table class="table">
                <tr>
                    <th>Klassenfertigkeiten</th>
                    <td>
                    @foreach($character->klassenfertigkeiten as $fertigkeit)
                        <li>{{ $fertigkeit }}</li>
                    @endforeach

                    </td>
                </tr>
                <tr>
                    <th>Handwerkskenntnisse</th>
                    <td>
                        @foreach($character->handwerkskenntnisse as $handwerk)
                            <li>{{ $handwerk }}</li>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th>Überlieferungen</th>
                    <td>{{ $character->lore }}</td>
                </tr>
            </table>
        </div>

        <!-- Optional: Weitere Sektionen, z. B. Equipment -->
        <div class="page-break"></div>
        <div class="section">
            <h2>Equipment</h2>
            @if ($character->equipment && $character->equipment->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Art</th>
                            <th>Qualität</th>
                            <th>Weitere Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($character->equipment as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->type }}</td>
                                <td>{{ $item->qualität }}</td>
                                <td>
                                    @if($item->hwp)
                                        HwP: {{ $item->hwp }}
                                    @endif
                                    {{-- Füge weitere Felder hinzu, je nach Equipment-Art --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>Kein Equipment zugeordnet.</p>
            @endif
        </div>
    </div>
</body>
</html>
