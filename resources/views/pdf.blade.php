<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>Charakterbogen – {{ $character->name }}</title>
    <style>

        @font-face {
            font-family: 'PaliRegular';
            src: url({{ storage_path('fonts/PaliRegular.otf') }}) format("opentype");
            font-weight: normal;
            font-style: normal;
        }

        @page {
            margin: 0;
        }
        body {
            font-family: 'PaliRegular', DejaVu Sans, sans-serif;
            font-size: 10pt;
            margin: 0;
            padding: 0;
        }
        .container {
            margin: 0 auto;
            width: 100%;
            padding: 10px;
            margin-left: 5px;
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
            margin-bottom: 5px;
        }
        .section h2 {
            font-size: 14pt;
        }
        .img {
            margin: 8px;
            border-radius: 8px;
        }


        .table {
            border-collapse: separate;
            border-radius: 4px;
            border: 1px solid #888;
            border-spacing: 0;
            /*margin-bottom: 10px;*/
            /*margin-right: 10px;*/
        }
        .table th, .table td, .table tr, .table li {
            padding: 4px;
            border-radius: 4px;
            border: 1px solid #888;
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
            padding: 10px;
            color: white;
            box-sizing: border-box;
            padding-left: 15px ;
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
{{--
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
                        <table style="padding-bottom: 10px;">
                            <td style="padding-right: 50px;">
                                <strong>Rasse:</strong> {{ $character->race }}</br>
                                <strong>Wesen:</strong> {{ $character->wesen ?? '—' }}
                            </td>
                            <td>
                                <strong>Leiteigenschaften:</strong> {{ $character->leiteigenschaft1 }} / {{ $character->leiteigenschaft2 }}  ({{ $character->archetype }})</br>
                                <strong>Erfahrungsgrad:</strong> {{ $character->xp }}
                            </td>
                        </table>

                    </td>
                </tr>
            </table>
        </div>

        <div class="section" > <!-- Abschnitt: körperl. Eigenschaften -->
            <table class="table" style="width: 95%; border: none">
                <tr>
                    <td style="width: 25%; border: none;">
                        <table  style="width: 90%;">
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
                    <td style="width: 75%; vertical-align: top; border: none">
                        <table class="table" style="width: 100%; border: none; border-spacing: 1">
                            <tr style="border-spacing: 2;">
                                <td>{{ $character->ko }}</td><th>Zähigkeit</th>
                                <td style="width: 10px; border: none"></td>
                                <td>{{ $character->st }}</td><th>Kraftakt</th>
                                <td style="width: 10px; border: none"></td>
                                <td>{{ $character->ag }}</td><th>Körperbeherrschung</th>
                                <td style="width: 10px; border: none""></td>
                                <td>{{ $character->ge }}</td><th>Fingerfertigkeit</th>
                            </tr>
                            <tr>
                                <td>{{ $character->ko }}</td><td> Zäher Hund</td>
                                <td style="width: 10px; border: none""></td>
                                <td>{{ $character->st }}</td><td> Wurfarm</td>
                                <td style="width: 10px; border: none""></td>
                                <td>{{ $character->ag }}</td><td> Lösen</td>
                                <td style="width: 10px; border: none""></td>
                                <td>{{ $character->ge }}</td><td> Löschen Abstreifen</td>
                            </tr>
                            <tr>
                                <td> {{ $character->ko }}</td><td> Standhalten</td>
                                <td style="width: 10px; border: none""></td>
                                <td> {{ $character->st }}</td><td> Halten Stoßen Zerren</td>
                                <td style="width: 10px; border: none""></td>
                                <td> {{ $character->ag }}</td><td> Leichtfüßig</td>
                                <td style="width: 10px; border: none""></td>
                                <td> {{ $character->ge }}</td><td> Schnell anwenden</td>
                            </tr>
                            <tr>
                                <td> {{ $character->ko }}</td><td> Second Wind</td>
                                <td style="width: 10px; border: none""></td>
                                <td> {{ $character->st }}</td><td> Schleppen</td>
                                <td style="width: 10px; border: none""></td>
                                <td> {{ $character->ag }}</td><td> Abrollen</td>
                                <td style="width: 10px; border: none""></td>
                                <td> {{ $character->ge }}</td><td> Schnellziehen</td>
                            </tr>
                            <tr>
                                <td> {{ $character->ko }}</td><td> Eisern</td>
                                <td style="width: 10px; border: none""></td>
                                <td> {{ $character->st }}</td><td> Dampwalze</td>
                                <td style="width: 10px; border: none""></td>
                                <td> {{ $character->ag }}</td><td> Ausweichen</td>
                                <td style="width: 10px; border: none""></td>
                                <td> {{ $character->ge }}</td><td> Schnell herstellen</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <div class="section" ><!-- Leib und co -->
            <table class="table" style="width: 95%; border: none;">
                <tr>
                    <td style="width: 30%; vertical-align: bottom; border: none;">
                        <h2>Leib</h2>
                    </td>
                    <td style="width: 70%; vertical-align: top; border: none;">
                        <table class="table" style="width: 50%; table-layout: fixed; border: none; border-spacing: 2;">

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
        <div class="dark-area" >
            <div class="section" ><!-- Seele und geist. Eigenschaften -->
                <table class="table" style="width: 95%; border: none;">
                    <tr>
                        <td style="width: 30%; vertical-align: top; border: none;">
                            <h2>Seele</h2>
                        </td>
                        <td style="width: 70%; vertical-align: top; border: none;">
                            <table class="table" style="width: 50%;  border: none; border-spacing: 2;  table-layout: fixed; ">

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
                <table class="table" style="width: 95%; border: none;">
                    <tr>
                        <td style="width: 25%; border: none;">
                            <table class="table" style="width: 90%; border: none; border-spacing: 2;">
                                <tr>
                                    <td>{{ $character->kontrollwiderstand }}</td>
                                    <th>Kontrollwiderstand</th>
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
                        <td style="width: 75%; vertical-align: top; border: none;">
                            <table class="table" style="width: 100%; border: none; border-spacing: 1">

                                <tr>
                                    <td>{{ $character->we }}</td><th>Konzentration</th>
                                    <td style="width: 10px; border: none""></td>
                                    <td>{{ $character->in }}</td><th>Wahrnehmung</th>
                                    <td style="width: 10px; border: none""></td>
                                    <td>{{ $character->mu }}</td><th>Willenskraft</th>
                                    <td style="width: 10px; border: none""></td>
                                    <td>{{ $character->ch }}</td><th>Kommunikation</th>
                                </tr>
                                <tr>
                                    <td>{{ $character->we }}</td><td> Ausspähen</td>
                                    <td style="width: 10px; border: none""></td>
                                    <td>{{ $character->in }}</td><td> Observieren</td>
                                    <td style="width: 10px; border: none""></td>
                                    <td>{{ $character->mu }}</td><td> Verbinden</td>
                                    <td style="width: 10px; border: none""></td>
                                    <td>{{ $character->ch }}</td><td> Provozieren</td>
                                </tr>
                                <tr>
                                    <td> {{ $character->we }}</td><td> Talisman wechseln</td>
                                    <td style="width: 10px; border: none""></td>
                                    <td> {{ $character->in }}</td><td> Gefahreninstinkt</td>
                                    <td style="width: 10px; border: none""></td>
                                    <td> {{ $character->mu }}</td><td> Riskieren</td>
                                    <td style="width: 10px; border: none""></td>
                                    <td> {{ $character->ch }}</td><td> Planen</td>
                                </tr>
                                <tr>
                                    <td> {{ $character->we }}</td><td> Fokussierter Wille</td>
                                    <td style="width: 10px; border: none""></td>
                                    <td> {{ $character->in }}</td><td> Zur rechten Zeit</td>
                                    <td style="width: 10px; border: none""></td>
                                    <td> {{ $character->mu }}</td><td> Überwinden</td>
                                    <td style="width: 10px; border: none""></td>
                                    <td> {{ $character->ch }}</td><td> Motivieren</td>
                                </tr>
                                <tr>
                                    <td> {{ $character->we }}</td><td> Fokussieren</td>
                                    <td style="width: 10px; border: none""></td>
                                    <td> {{ $character->in }}</td><td> Wittern</td>
                                    <td style="width: 10px; border: none""></td>
                                    <td> {{ $character->mu }}</td><td> Gestählter Geist</td>
                                    <td style="width: 10px; border: none""></td>
                                    <td> {{ $character->ch }}</td><td> Hundeblick</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="section" > <!-- Fertigkeiten & Überlieferungen -->
                <h2>Fertigkeiten & Überlieferungen</h2>
                <table class="table" style="width:95%; border: none;border-spacing: 5;table-layout: fixed">
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
                        @foreach($character->lore as $lore)
                                <li>{{ $lore }}</li>
                            @endforeach
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Seite 2: Equipment -->
        <div class="page-break"></div>
--}}
        <!-- equipped -->
        <div class="section" style="width: 95%;">  <!-- Rüstung & Schmuck -->

            <table class="table" style="width:100%; border: none"> <!-- outer table -->
                <td style="width: 78%; border: none">
                    <table>
                        <td style="width: 45%; border-spacing: 3; border: none">
                            <table>
                                <th style="border: none" colspan="2">Rüstung</th>
                                @foreach($character->equipmentAssignments as $assignment)
                                    @php $item = $assignment->equipment; @endphp
                                    @if($item->item_type === 'Rüstung' && $assignment->equipped)
                                        <tr>
                                            <td colspan="2">Name: {{ $item->name }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><b>QS:</b> {{ $item->quality }}  <b>HwP:</b> {{ $item->hwp }}</td>
                                        </tr>
                                        <tr>
                                            <td>RS Schnitt: {{ $item->rs_schnitt }}</td>
                                            <td>RS Stumpf: {{ $item->rs_stumpf }}</td>
                                        </tr>
                                        <tr>
                                            <td>RS Stich: {{ $item->rs_stich }}</td>
                                            <td>RS Elementar: {{ $item->rs_elementar }}</td>
                                        </tr>
                                        <tr>
                                            <td>passive verteidigung: {{ $item->passive_verteidigung }}</td>
                                            <td>Traglast: {{ $item->traglast }}</td>
                                        </tr>
                                        <tr>
                                            <td>Erweiterungen: {{ implode(', ', $item->rs_erweiterungen ?? []) }}</td>
                                            @if(!empty($item->enchantment))
                                                <td style="margin-bottom: 10px;">Verzauberung: {{ $item->enchantment }} ({{ $item->enchantment_qs }})</td>
                                            @else
                                                <td style="margin-bottom: 10px;">Verzauberung: keine Verzauberung</td>
                                            @endif
                                        </tr>
                                    @endif
                                @endforeach
                            </table>
                        </td>
                        <td style="width: 45%;border: none;">
                            <table>
                                <th style="border: none" colspan="2">Talisman</th>

                                @foreach($character->equipmentAssignments as $assignment)
                                    @php $item = $assignment->equipment; @endphp
                                    @if($item->item_type === 'Talisman' && $assignment->equipped)
                                        <tr>
                                            <td colspan="2">Name: {{ $item->name }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><b>QS:</b> {{ $item->quality }}  <b>HwP:</b> {{ $item->hwp }}</td>
                                        </tr>
                                        <tr>
                                            <td>RS Arcan: {{ $item->rs_arcan }}</td>
                                            <td>RS Chaos: {{ $item->rs_chaos }}</td>
                                        </tr>
                                        <tr>
                                            <td>RS Spirituell: {{ $item->rs_spirit }}</td>
                                            <td>Kontrollwiderstand: {{ $item->kontrollwiderstand }}</td>
                                        </tr>
                                        <tr>
                                            <td>Traglast: {{ $item->traglast }}</td>
                                        </tr>
                                        <tr>
                                            <td>Erweiterungen: {{ implode(', ', $item->ts_erweiterungen ?? []) }}</td>
                                            @if(!empty($item->enchantment))
                                                <td style="margin-bottom: 10px;">Verzauberung: {{ $item->enchantment }} ({{ $item->enchantment_qs }})</td>
                                            @else
                                                <td style="margin-bottom: 10px;">Verzauberung: keine Verzauberung</td>
                                            @endif
                                        </tr>
                                    @endif


                                @endforeach
                            </table>
                        </td>
                    </table>
                    <table class="table" style="width: 100%; table-layout: fixed; padding-top: 5pt; border: none; padding-right: 5pt; border-spacing: 1">
                        <tr>
                            <th>Schnitt</th>
                            <th>Stumpf</th>
                            <th>Stich</th>
                            <th>Elementar</th>
                            <th>Arcan</th>
                            <th>Chaos</th>
                            <th>Spirituell</th>
                        </tr>
                        <tr>
                            @php
                                $armor = null;
                                $talisman = null;

                                foreach ($character->equipmentAssignments as $assignment) {
                                    if (! $assignment->equipped) continue;

                                    if ($assignment->equipment->item_type === 'Rüstung') {
                                        $armor = $assignment->equipment;
                                    }

                                    if ($assignment->equipment->item_type === 'Talisman') {
                                        $talisman = $assignment->equipment;
                                    }
                                }
                            @endphp
                            <td>{{ $armor?->rs_schnitt ?? 0 }}</td>
                            <td>{{ $armor?->rs_stumpf ?? 0 }}</td>
                            <td>{{ $armor?->rs_stich ?? 0 }}</td>
                            <td>{{ $armor?->rs_elementar ?? 0 }}</td>
                            <td>{{ $talisman?->rs_arcan ?? 0 }}</td>
                            <td>{{ $talisman?->rs_chaos ?? 0 }}</td>
                            <td>{{ $talisman?->rs_spirit ?? 0 }}</td>
                    </table>
                </td>
                    <table style="padding-top: 10pt">
                        <th style="border: none">Schmuck</th>

                        @foreach($character->equipmentAssignments as $assignment)
                            @php $item = $assignment->equipment; @endphp
                            @if($item->item_type === 'Schmuckstück' && $assignment->equipped)
                                <tr>
                                    <td>
                                        <b> {{ $item->name }} </b></br>
                                            QS:</b> {{ $item->quality }}</br>
                                        @if(!empty($item->enchantment))
                                            Verzauberung: {{ $item->enchantment }} ({{ $item->enchantment_qs }})
                                        @else
                                            Verzauberung: keine Verzauberung
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </table>
            </table>
        </div>
        <div class="section" style="">  <!-- Waffen & Schild -->
            <!-- <p>tbd: Haupthand und Nebenhand equipped</p> -->
            <Table class="table" style="width:95%; border: none;">
                <tr>
                    <th style="border: none;">Haupthand</th>
                    <th style="border: none;">Nebenhand</th>
                    <th style="border: none;">Schild</th>
                    <th style="border: none;">Natürliche Waffe</th>
                </tr>
                <td style="border: none;">
                    @foreach($character->equipmentAssignments as $assignment)
                        @php $item = $assignment->equipment; @endphp
                        @if($item->item_type === 'Waffe' && $assignment->equipped)
                            <li>Name: {{ $item->name }}</li>
                            <li><b>QS:</b> {{ $item->quality }}  <b>HwP:</b> {{ $item->hwp }}</li>
                            <li>Waffengattung: {{ $item->waffengattung }}</li>
                            <li>Angriffswert: {{ $item->angriffswert }}</li>
                            <li>Schadensarten: {{ implode(', ', $item->damage_type ?? []) }}</li>
                            <li>Trefferwürfel: {{ $item->trefferwuerfel }}</li>
                            <li>Traglast: {{ $item->traglast }}</li>
                            <li>Erweiterungen: {{ implode(', ', $item->wp_erweiterungen ?? []) }}</li>
                        @endif
                    @endforeach
                </td>
                <td style="border: none;">
                    @foreach($character->equipmentAssignments as $assignment)
                        @php $item = $assignment->equipment; @endphp
                        @if($item->item_type === 'Waffe' && $assignment->equipped)
                            <li>Name: {{ $item->name }}</li>
                            <li><b>QS:</b> {{ $item->quality }}  <b>HwP:</b> {{ $item->hwp }}</li>
                            <li>Waffengattung: {{ $item->waffengattung }}</li>
                            <li>Angriffswert: {{ $item->angriffswert }}</li>
                            <li>Schadensarten: {{ implode(', ', $item->damage_type ?? []) }}</li>
                            <li>Trefferwürfel: {{ $item->trefferwuerfel }}</li>
                            <li>Traglast: {{ $item->traglast }}</li>
                            <li>Erweiterungen: {{ implode(', ', $item->wp_erweiterungen ?? []) }}</li>
                        @endif
                    @endforeach
                </td>
                <td style="border: none;">
                    @foreach($character->equipmentAssignments as $assignment)
                        @php $item = $assignment->equipment; @endphp
                        @if($item->item_type === 'Schild' && $assignment->equipped)
                            <li>Name: {{ $item->name }}</li>
                            <li>QS: {{ $item->quality }} <b>HwP:</b> {{ $item->hwp }}</li>
                            <li>RS Schnitt: {{ $item->rs_schnitt }}</li>
                            <li>RS Stumpf: {{ $item->rs_stumpf }}</li>
                            <li>RS Stich: {{ $item->rs_stich }}</li>
                            <li>Traglast: {{ $item->traglast }}</li>
                            <li>Erweiterungen: {{ implode(', ', $item->wp_erweiterungen ?? []) }}</li>
                        @endif
                    @endforeach
                </td>
                <td style="border: none;">
                    <li><b>Waffengattung</b> {{ $character->nw_gattung }}</li>
                    <li><b>QS</b> {{ $character->nw_quality }}</li>
                    <li><b>Schadenarten</b> {{ implode(', ', $character->nw_damage_type) }}</li>
                    <li><b>AW</b> {{ $character->nw_aw }}</li>
                    <li><b>VW</b> {{ $character->nw_vw }}</li>
                    <li><b>TW</b> {{ $character->nw_tw }}</li>
                </td>
            </table>
        </div>

        <!-- Abschnitt: dunkle Hälfte -->
        <div class="dark-area">
            <div class="section" style="margin-top:10pt;">
            <!-- Abschnitt: aktuelle LeP, SeP &Ressourcen -->
                <h3>Aktuell</h3>
                <table class="table" style="width: 95%; border: none;">
                    <td style="border: none">
                        <table class="table" style="width: 95%; border: none; border-spacing: 2">
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
                    </td>
                    <td style="border: none">
                        <table class="table" style="width: 100%; border: none; border-spacing: 2">
                                <tr>
                                    <th>Ressource</th>
                                    <td>
                                    {{ $character->main_stat_value }}
                                    </td>
                                    <td class="highlight"> Aktuelle Ressourcen</td>
                                </tr>
                                <tr>
                                <td class="highlight" colspan="3"> Aktuelle Ressourcen</td>
                                </tr>
                        </table>
                    </td>
                </table>
            </div>
            <div class="section" style="margin-top:5pt;">
            <!-- Abschnitt: Fertigkeiten -->
                <h3>Fertigkeiten</h3>
                <table class="table" style="border-spacing: 2;border: none; width: 90%;">
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
            <div class="section" style="margin-top:5pt;"> <!-- Abschnitt: Equipment dark -->
                <h3>Equipment</h3>
                @if ($character->equipmentAssignments->isNotEmpty())
                    <table class="table" style="border: none; border-spacing: 1">
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
