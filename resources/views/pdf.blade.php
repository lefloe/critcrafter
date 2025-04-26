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
            /* margin-top: 10px; */
        }
        .section h2 {
            font-size: 14pt;
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
        .table td.highlight {
            background-color: white;
        }

        .test {
            margin-bottom: 10px;
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

        <div class="section" > <!-- Abschnitt: körperl. Eigenschaften -->
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
            <div class="section" ><!-- Seele und geist. Eigenschaften -->
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
            <div class="section" > <!--  geist. Basiswerte und Basistalente -->
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
            <div class="section" > <!-- Fertigkeiten & Überlieferungen -->
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
        </div>

        <!-- Seite 2: Equipment -->
        <div class="page-break"></div>

        <!-- equipped -->
        <div class="section" style="margin-top:10pt;">  <!-- Rüstung & Schmuck -->
            
            <Table class="table" style="width:95%;">
                <tr>
                    <th>Rüstung</th>
                    <th>Talisman</th>
                    <th>Gesamtrüstung</th>
                    <th>Schmuck</th>
                </tr>
                <td>
                    @foreach($character->equipmentAssignments as $assignment)
                        @php $item = $assignment->equipment; @endphp
                        @if($item->item_type === 'Rüstung' && $assignment->equipped)
                            <li>Name: {{ $item->name }}</li>
                            <li>QS: {{ $item->quality }}</li>
                            <li>HwP: {{ $item->hwp }}</li>
                            <li>RS Schnitt: {{ $item->rs_schnitt }}</li>
                            <li>RS Stumpf: {{ $item->rs_stumpf }}</li>
                            <li>RS Stich: {{ $item->rs_stich }}</li>
                            <li>RS Elementar: {{ $item->rs_elementar }}</li>
                            <li>passive verteidigung: {{ $item->passive_verteidigung }}</li>
                            <li>Traglast: {{ $item->traglast }}</li>
                            <li>Erweiterungen: {{ implode(', ', $item->rs_erweiterungen ?? []) }}</li>
                            @if(!empty($item->enchantment))
                                    <li style="margin-bottom: 10px;">Verzauberung: {{ $item->enchantment }} ({{ $item->enchantment_qs }})</li>
                                @else
                                    <li style="margin-bottom: 10px;">Verzauberung: keine Verzauberung</li>
                                @endif
                        @endif
                    @endforeach
                </td>
                <td>
                    @foreach($character->equipmentAssignments as $assignment)
                        @php $item = $assignment->equipment; @endphp
                        @if($item->item_type === 'Talisman' && $assignment->equipped)
                            <li>Name: {{ $item->name }}</li>
                            <li>QS: {{ $item->quality }}</li>
                            <li>HwP: {{ $item->hwp }}</li>
                            <li>RS Arcan: {{ $item->rs_arcan }}</li>
                            <li>RS Chaos: {{ $item->rs_chaos }}</li>
                            <li>RS Spirituell: {{ $item->rs_spirit }}</li>
                            <li>Kontrollwiderstand: {{ $item->kontrollwiderstand }}</li>
                            <li>Traglast: {{ $item->traglast }}</li>
                            <li>Erweiterungen: {{ implode(', ', $item->ts_erweiterungen ?? []) }}</li>
                            @if(!empty($item->enchantment))
                                <li style="margin-bottom: 10px;">Verzauberung: {{ $item->enchantment }} ({{ $item->enchantment_qs }})</li>
                            @else
                                <li style="margin-bottom: 10px;">Verzauberung: keine Verzauberung</li>
                            @endif   
                        @endif
                    @endforeach
                </td>
                <td>
                    <li>tbd: Gesamtrüstung Berechnung </li>
                </td>
                <td>
                @foreach($character->equipmentAssignments as $assignment)
                    @php $item = $assignment->equipment; @endphp
                    @if($item->item_type === 'Schmuckstück' && $assignment->equipped)
                        <li>Name: {{ $item->name }}</li>
                        <li>QS: {{ $item->quality }}</li>
                        @if(!empty($item->enchantment))
                            <li style="margin-bottom: 10px;">Verzauberung: {{ $item->enchantment }} ({{ $item->enchantment_qs }})</li>
                        @else
                            <li style="margin-bottom: 10px;">Verzauberung: keine Verzauberung</li>
                        @endif                    
                    @endif
                @endforeach
                </td>
            </table>
        </div>
        <div class="section" style="margin-top:10pt;">  <!-- Waffen & Schild -->
            <p>tbd: Haupthand und Nebenhand equipped</p>
            <Table class="table" style="width:95%;">
                <tr>
                    <th>Haupthand</th>
                    <th>Nebenhand</th>
                    <th>Schild</th>
                    <th>Natürliche Waffe</th>
                </tr>
                <td>
                    @foreach($character->equipmentAssignments as $assignment)
                        @php $item = $assignment->equipment; @endphp
                        @if($item->item_type === 'Waffe' && $assignment->equipped)
                            <li>Name: {{ $item->name }}</li>
                            <li>QS: {{ $item->quality }}</li>
                            <li>HwP: {{ $item->hwp }}</li>
                            <li>Waffengattung: {{ $item->waffengattung }}</li>
                            <li>Angriffswert: {{ $item->angriffswert }}</li>
                            <li>Schadensarten: {{ implode(', ', $item->damage_type ?? []) }}</li>
                            <li>Trefferwürfel: {{ $item->trefferwuerfel }}</li>
                            <li>Traglast: {{ $item->traglast }}</li>
                            <li>Erweiterungen: {{ implode(', ', $item->wp_erweiterungen ?? []) }}</li>
                            @if(!empty($item->enchantment))
                                <li style="margin-bottom: 10px;">Verzauberung: {{ $item->enchantment }} ({{ $item->enchantment_qs }})</li>
                            @else
                                <li style="margin-bottom: 10px;">Verzauberung: keine Verzauberung</li>
                            @endif   
                        @endif
                    @endforeach
                </td>
                <td>
                    @foreach($character->equipmentAssignments as $assignment)
                        @php $item = $assignment->equipment; @endphp
                        @if($item->item_type === 'Waffe' && $assignment->equipped)
                            <li>Name: {{ $item->name }}</li>
                            <li>QS: {{ $item->quality }}</li>
                            <li>HwP: {{ $item->hwp }}</li>
                            <li>Waffengattung: {{ $item->waffengattung }}</li>
                            <li>Angriffswert: {{ $item->angriffswert }}</li>
                            <li>Schadensarten: {{ implode(', ', $item->damage_type ?? []) }}</li>
                            <li>Trefferwürfel: {{ $item->trefferwuerfel }}</li>
                            <li>Traglast: {{ $item->traglast }}</li>
                            <li>Erweiterungen: {{ implode(', ', $item->wp_erweiterungen ?? []) }}</li>
                            @if(!empty($item->enchantment))
                               <li style="margin-bottom: 10px;">Verzauberung: {{ $item->enchantment }} ({{ $item->enchantment_qs }})</li>
                            @else
                                <li style="margin-bottom: 10px;">Verzauberung: keine Verzauberung</li>
                            @endif   
                        @endif
                    @endforeach
                </td>
                <td>
                    @foreach($character->equipmentAssignments as $assignment)
                        @php $item = $assignment->equipment; @endphp
                        @if($item->item_type === 'Schild' && $assignment->equipped)
                            <li>Name: {{ $item->name }}</li>
                            <li>QS: {{ $item->quality }}</li>
                            <li>HwP: {{ $item->hwp }}</li>
                            <li>RS Schnitt: {{ $item->rs_schnitt }}</li>
                            <li>RS Stumpf: {{ $item->rs_stumpf }}</li>
                            <li>RS Stich: {{ $item->rs_stich }}</li>
                            <li>Traglast: {{ $item->traglast }}</li>
                            <li>Erweiterungen: {{ implode(', ', $item->wp_erweiterungen ?? []) }}</li>
                            @if(!empty($item->enchantment))
                                <li style="margin-bottom: 10px;">Verzauberung: {{ $item->enchantment }} ({{ $item->enchantment_qs }})</li>
                            @else
                                <li style="margin-bottom: 10px;">Verzauberung: keine Verzauberung</li>
                            @endif   
                        @endif
                    @endforeach
                </td>
                <td>
                    <li>tbd: Natürliche Waffe</li>
                </td>
            </table>
        </div>
            
        <!-- Abschnitt: dunkle Hälfte -->
        <div class="dark-area">
            <div class="section" style="margin-top:10pt;">  <!-- Abschnitt: aktuelle LeP, SeP &Ressourcen -->
                <h2>Aktuell</h2>
                <table class="table" style="width: 45%;">
                        <tr>
                            <th>LeP</th>
                            <td>
                            {{ $character->leps }}
                            </td>
                            <td class="highlight"> Aktuelle Lebenspunkte</td>
                        </tr>
                        <tr>
                            <th>SeP</th>
                            <td>    
                            <{{ $character->seelenpunkte }}
                            </td>
                            <td class="highlight"> Aktuelle Seelenpunkte</td>
                        </tr>
                </table>
                <table class="table" style="width: 45%;">
                        <tr>
                            <th>Ressource</th>
                            <td>
                            {{ $character->main_stat_value }}
                            </td>
                            <td class="highlight"> Aktuelle Ressourcen</td>
                        </tr>
                </table>
            </div>
            <div class="section" style="margin-top:10pt;">  <!-- Abschnitt: Fertigkeiten -->
                <h2>Fertigkeiten</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>KO</th>
                            <th>ST</th>
                            <th>AG</th>
                            <th>GE</th>
                            <th>WE</th>
                            <th>IN</th>
                            <th>MU</th>
                            <th>CH</th>
                        </tr>
                    </thead>
                    <tbody>
                        <td>
                            @if(!empty($character->skill_ko))
                                @foreach($character->skill_ko as $skill)
                                <li>{{ $skill }}</li>
                                @endforeach
                            @endif
                        </td>
                        <td>    
                            @if(!empty($character->skill_st))
                                @foreach($character->skill_st as $skill)
                                    <li>{{ $skill }}</li>
                                @endforeach
                            @endif
                        </td>
                        <td>
                            @if(!empty($character->skill_ag))
                                @foreach($character->skill_ag as $skill)
                                    <li>{{ $skill }}</li>
                                @endforeach
                            @endif
                        </td>
                        <td>
                            @if(!empty($character->skill_ge))
                                @foreach($character->skill_ge as $skill)
                                    <li>{{ $skill }}</li>
                                @endforeach
                            @endif
                        </td>
                        <td>
                            @if(!empty($character->skill_we))
                                @foreach($character->skill_we as $skill)
                                    <li>{{ $skill }}</li>
                                @endforeach
                            @endif
                        </td>
                        <td>
                            @if(!empty($character->skill_in))
                                @foreach($character->skill_in as $skill)
                                    <li>{{ $skill }}</li>
                                @endforeach
                            @endif
                        </td>
                        <td>
                            @if(!empty($character->skill_mu))
                                @foreach($character->skill_mu as $skill)
                                    <li>{{ $skill }}</li>
                                @endforeach
                            @endif
                        </td>
                        <td>
                            @if(!empty($character->skill_ch))
                                @foreach($character->skill_ch as $skill)
                                    <li>{{ $skill }}</li>
                                @endforeach
                            @endif
                        </td>
                    </tbody>
                </table>
            </div>
            <div class="section" style="margin-top:10pt;"> <!-- Abschnitt: Equipment -->

                <h2>Equipment</h2>           
                @if ($character->equipmentAssignments->isNotEmpty())
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Art</th>
                                <th>Qualität</th>
                                <th>HwP</th>
                                <th>Ausgerüstet</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($character->equipmentAssignments as $assignment)
                                @php $item = $assignment->equipment; @endphp
                                
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->item_type }}</td>
                                    <td>{{ $item->quality }}</td>
                                    <td>
                                        @if($item->hwp)
                                            HwP: {{ $item->hwp }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($assignment->equipped)
                                            <b>x</b>
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
    </div>
</body>
</html>
