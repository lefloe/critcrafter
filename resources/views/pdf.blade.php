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
            margin-right: 20px;
        }
        .header {
            text-align: center;
            margin-top: 10px;
            margin-right: 20px;
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
        .img {
            margin: 8px;
            border-radius: 8px;
        }
        .table {
            border-collapse: collapse;
            margin-bottom: 10px;
            margin-right: 10px;
        }
        .table th, .table td, .table li {
            border: 1px solid #888;
            padding: 4px;
            text-align: left;
            font-size: 7pt;
            word-wrap: break-word;
            word-break: break-word;
            white-space: normal;
            list-style:none;
            vertical-align: top;
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
        .dark-area {
            position: absolute;
            top: 148.5mm;  /* genau ab der Mitte */
            left: 0;
            width: 100%;
            height: 148.5mm;
            background-color: #333;
            color: #fff;
            padding: 10px;
            color: white;
            box-sizing: border-box;
            z-index: -1;   /* damit es hinter dem Text liegt */
        }
        .dark-area h1,
        .dark-area h2,
        .dark-area h3,
        .dark-area p,
        .dark-area span,
        .dark-area li,
        .dark-area td,
        .dark-area th {
            color: #fff !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Kopfzeile mit Portrait & Namen -->
        <div class="header">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 25%; vertical-align: top">
                        @if($character->portrait)
                            <img class="img" src="{{ public_path('storage/' . $character->portrait) }}" alt="Portrait" style="max-width: 200px; max-height: 250px;">
                        @endif                
                    </td>
                    <td style="width: 75%; vertical-align: top; padding-left: 15px;">
                        <h2 style="margin: 0;">{{ $character->name }}</h2>
                        <p><strong>Beschreibung:</strong></p>
                            <p style="font-size: {{ strlen($character->description) > 600 ? '8pt' : '9pt' }};">
                                {{ $character->description }}
                            </p>
                            <!-- {{ $character->description }} -->
                        </p>
                        <p><strong>Rasse:</strong> {{ $character->race }}</br>
                        <strong>Wesen:</strong> {{ $character->wesen ?? '—' }}</br>
                        <strong>Leiteigenschaften:</strong> {{ $character->leiteigenschaft1 }} / {{ $character->leiteigenschaft2 }}</p>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Abschnitt: körperl. Eigenschaften -->
        <div class="section" >
            <table class="table" style="width: 95%;">
                <tr>
                    <td style="width: 25%;">
                        <table class="table" style="width: 100%;">
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
                    <td style="width: 75%; vertical-align: top;">
                        <table class="table" style="width: 100%; border-collapse: collapse;">
                            
                            <tr>
                                <td>{{ $character->ko }}</td><th style="font-size: 7pt;">Zähigkeit</th>
                                <td>{{ $character->st }}</td><th style="font-size: 7pt;">Kraftakt</th>
                                <td>{{ $character->ag }}</td><th style="font-size: 7pt;">Körperbeherrschung</th>
                                <td>{{ $character->ge }}</td><th style="font-size: 7pt;">Fingerfertigkeit</th>
                            </tr>
                            <tr>
                                <td>{{ $character->ko }}</td><td> Zäher Hund</td>
                                <td>{{ $character->st }}</td><td> Wurfarm</td>
                                <td>{{ $character->ag }}</td><td> Lösen</td>
                                <td>{{ $character->ge }}</td><td> Löschen Abstreifen</td>
                            </tr>
                            <tr>
                                <td> {{ $character->ko }}</td><td> Standhalten</td>
                                <td> {{ $character->st }}</td><td> Halten Stoßen Zerren</td>
                                <td> {{ $character->ag }}</td><td> Leichtfüßig</td>
                                <td> {{ $character->ge }}</td><td> Schnell anwenden</td>
                            </tr>
                            <tr>
                                <td> {{ $character->ko }}</td><td> Second Wind</td>
                                <td> {{ $character->st }}</td><td> Schleppen</td>
                                <td> {{ $character->ag }}</td><td> Abrollen</td>
                                <td> {{ $character->ge }}</td><td> Schnellziehen</td>
                            </tr>
                            <tr>
                                <td> {{ $character->ko }}</td><td> Eisern</td>
                                <td> {{ $character->st }}</td><td> Dampwalze</td>
                                <td> {{ $character->ag }}</td><td> Ausweichen</td>
                                <td> {{ $character->ge }}</td><td> Schnell herstellen</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>        
        <div class="section" ><!-- Leib und co -->
            <table class="table" style="width: 95%;">
                <tr>
                    <td style="width: 30%; vertical-align: bottom;">
                        <h2>Leib</h2>
                    </td>
                    <td style="width: 70%; vertical-align: top;">
                        <table class="table" style="width: 50%; border-collapse: collapse; table-layout: fixed">
                            
                            <tr>
                                <th style="font-size: 10pt;">KO</th>
                                <th style="font-size: 10pt;">ST</th>
                                <th style="font-size: 10pt;">AG</th>
                                <th style="font-size: 10pt;">GE</th>
                            </tr>
                            <tr>
                                <td style="font-size: 12pt;"> {{ $character->ko }}</td>
                                <td style="font-size: 12pt;"> {{ $character->st }}</td>
                                <td style="font-size: 12pt;"> {{ $character->ag }}</td>
                                <td style="font-size: 12pt;"> {{ $character->ge }}</td>
                            </tr>                            

                        </table>
                    </td>
                </tr>
            </table>
        </div>

    <!-- Abschnitt: dark half -->
        <div class="dark-area" padding>
        <div class="section" ><!-- Leib und co -->
            <table class="table" style="width: 95%;">
                <tr>
                    <td style="width: 30%; vertical-align: top;">
                        <h2>Seele</h2>
                    </td>
                    <td style="width: 70%; vertical-align: top;">
                        <table class="table" style="width: 50%; border-collapse: collapse; table-layout: fixed">
                            
                            <tr>
                                <th style="font-size: 10pt;">WE</th>
                                <th style="font-size: 10pt;">IN</th>
                                <th style="font-size: 10pt;">MU</th>
                                <th style="font-size: 10pt;">CH</th>
                            </tr>
                            <tr>
                                <td style="font-size: 12pt;"> {{ $character->we }}</td>
                                <td style="font-size: 12pt;"> {{ $character->in }}</td>
                                <td style="font-size: 12pt;"> {{ $character->mu }}</td>
                                <td style="font-size: 12pt;"> {{ $character->ch }}</td>
                            </tr>                            

                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <div class="section" >
            <table class="table" style="width: 95%;">
                <tr>
                    <td style="width: 25%;">
                        <table class="table" style="width: 100%;">
                            <tr>
                                <td>{{ $character->kontrollwiderstand }}</td>
                                <th>Kontrollwiderstand (LeP)</th>
                            </tr>
                            <tr>
                                <td>{{ $character->initiative }}</td>
                                <th>Initiative (Ini)</th>
                            </tr>
                            <tr>
                                <td>{{ $character->verteidigung }}</td>
                                <th>Verteidigung</th>
                            </tr>
                            <tr>
                                <td>{{ $character->seelenpunkte }}</td>
                                <th>Seelenpunkte (SeP)</th>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 75%; vertical-align: top;">
                        <table class="table" style="width: 100%; border-collapse: collapse;">
                            
                            <tr>
                                <td>{{ $character->we }}</td><th>Konzentration</th>
                                <td>{{ $character->in }}</td><th>Wahrnehmung</th>
                                <td>{{ $character->mu }}</td><th>Willenskraft</th>
                                <td>{{ $character->ch }}</td><th>Kommunikation</th>
                            </tr>
                            <tr>
                                <td>{{ $character->we }}</td><td> Ausspähen</td>
                                <td>{{ $character->in }}</td><td> Observieren</td>
                                <td>{{ $character->mu }}</td><td> Verbinden</td>
                                <td>{{ $character->ch }}</td><td> Provozieren</td>
                            </tr>
                            <tr>
                                <td> {{ $character->we }}</td><td> Talisman wechseln</td>
                                <td> {{ $character->in }}</td><td> Gefahreninstinkt</td>
                                <td> {{ $character->mu }}</td><td> Riskieren</td>
                                <td> {{ $character->ch }}</td><td> Planen</td>
                            </tr>
                            <tr>
                                <td> {{ $character->we }}</td><td> Fokussierter Wille</td>
                                <td> {{ $character->in }}</td><td> Zur rechten Zeit</td>
                                <td> {{ $character->mu }}</td><td> Überwinden</td>
                                <td> {{ $character->ch }}</td><td> Motivieren</td>
                            </tr>
                            <tr>
                                <td> {{ $character->we }}</td><td> Fokussieren</td>
                                <td> {{ $character->in }}</td><td> Wittern</td>
                                <td> {{ $character->mu }}</td><td> Gestählter Geist</td>
                                <td> {{ $character->ch }}</td><td> Hundeblick</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>  

        <div class="section" style="position: relative; z-index: 1;">
            <h2>Fertigkeiten & Überlieferungen</h2>
            <table class="table" style="width:95%;">
                <tr>
                    <th>Klassenfertigkeiten</th>
                    <th>Handwerkskenntnisse</th>
                    <th>Überlieferungen</th>
                </tr>
                <tr>
                    <td>
                    @foreach($character->klassenfertigkeiten as $fertigkeit)
                        <li>{{ $fertigkeit }}</li>
                    @endforeach
                    </td>
                    <td>
                        @foreach($character->handwerkskenntnisse as $handwerk)
                            <li>{{ $handwerk }}</li>
                        @endforeach
                    </td>
                    <td>
                    @foreach($character->handwerkskenntnisse as $handwerk)
                            <li>{{ $character->lore }}</li>
                        @endforeach
                    </td>
                </tr>
            </table>
        </div>

        <!-- Seite 2: Equipment -->
        <div class="page-break"></div>
        <div class="section">
            <h2>Equipment</h2>
            @if ($character->Equipment()->exists())
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
                        @foreach($character->Equipment as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->item_type }}</td>
                                <td>{{ $item->quality }}</td>
                                <td>
                                    @if($item->hwp)
                                        HwP: {{ $item->hwp }}
                                    @endif
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
