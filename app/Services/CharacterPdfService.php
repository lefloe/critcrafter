<?php

namespace App\Services;

use App\Models\Character;
use App\Models\Equipment;
use mikehaertl\pdftk\Pdf;

class CharacterPdfService
{
    private const LEITEIGENSCHAFT_MAP = [
        'KO' => 'Konstitution',
        'ST' => 'Stärke',
        'AG' => 'Agilität',
        'GE' => 'Geschick',
        'WE' => 'Weisheit',
        'IN' => 'Instinkt',
        'MU' => 'Mut',
        'CH' => 'Charisma',
    ];

    public function fill(Character $character): string
    {
        $character->load('equipment');

        $fields = array_merge(
            $this->basicFields($character),
            $this->eigenschaftenFields($character),
            $this->combatFields($character),
            $this->naturalWeaponFields($character),
            $this->skillFields($character),
            $this->racialTraitFields($character),
            $this->equipmentFields($character),
        );

        $templatePath = resource_path('pdf/Character_Sheet.pdf');
        $outputPath = tempnam(sys_get_temp_dir(), 'char_pdf_').'.pdf';

        $pdf = new Pdf($templatePath);
        $pdf->fillForm($fields)->saveAs($outputPath);

        if ($pdf->getError()) {
            throw new \RuntimeException('PDF generation failed: '.$pdf->getError());
        }

        return $outputPath;
    }

    private function basicFields(Character $character): array
    {
        $wesen = match ($character->wesen) {
            'Biest' => 'Biest/Geist',
            'Dämon' => 'Dämon/Spekter',
            default => $character->wesen ?? '',
        };

        return [
            'Name' => $character->name ?? '',
            'Rasse' => $character->race ?? '',
            'Erfahrungsgrad' => $character->xp ?? '',
            'PLE' => self::LEITEIGENSCHAFT_MAP[$character->leiteigenschaft1 ?? ''] ?? '',
            'SLE' => self::LEITEIGENSCHAFT_MAP[$character->leiteigenschaft2 ?? ''] ?? '',
            'Archetyp' => $character->archetype ?? '',
            'Ressource' => $character->main_stat_value ?? '',
            'Seele' => $wesen,
        ];
    }

    private function eigenschaftenFields(Character $character): array
    {
        return [
            'Konstitution' => $character->ko_sum ?? '',
            'Stärke' => $character->st_sum ?? '',
            'Agilität' => $character->ag_sum ?? '',
            'Geschick' => $character->ge_sum ?? '',
            'Weisheit' => $character->we_sum ?? '',
            'Instinkt' => $character->in_sum ?? '',
            'Mut' => $character->mu_sum ?? '',
            'Charisma' => $character->ch_sum ?? '',
            'Ausdauer' => $character->ausdauer_sum ?? '',
            'BFZäh' => $character->zähigkeit_sum ?? '',
            'Kraftakt' => $character->kraftakt_sum ?? '',
            'Körperbeherrschung' => $character->körperbeherrschung_sum ?? '',
            'Fingerfertigkeit' => $character->fingerfertigkeit_sum ?? '',
            'Konzentration' => $character->konzentration_sum ?? '',
            'Wahrnehmung' => $character->wahrnehmung_sum ?? '',
            'Willenskraft' => $character->willenskraft_sum ?? '',
            'Kommunikation' => $character->kommunikation_sum ?? '',
        ];
    }

    private function combatFields(Character $character): array
    {
        return [
            'Lebenspunkte' => $character->leps ?? '',
            'Seelenpunkte' => $character->seelenpunkte ?? '',
            'Tragkraft' => $character->tragkraft ?? '',
            'Geschwindigkeit' => $character->gs_leib ?? '',
            'INI' => $character->initiative ?? '',
            'Initiativebonus' => $character->bonus_ini ?? '',
            'VW' => $character->verteidigung ?? '',
            'Verteidigungsbonus' => $character->bonus_re ?? '',
            'Handwerksbonus' => $character->handwerksbonus ?? '',
            'Kontrollbonus' => $character->ko_bonus ?? '',
            'KTRLWD' => $character->kontrollwiderstand ?? '',
        ];
    }

    private function naturalWeaponFields(Character $character): array
    {
        $gattung = implode(', ', (array) ($character->nw_gattung ?? []));
        $damageTypes = (array) ($character->nw_damage_type ?? []);

        return [
            'NWQS' => $character->nw_quality ?? '',
            'NWGATT' => $gattung,
            'NWAW' => $character->nw_aw ?? '',
            'NWVW' => $character->nw_vw ?? '',
            'NWTW' => $character->nw_tw ?? '',
            'NWLE1' => $damageTypes[0] ?? '',
            'AW' => $character->nw_aw ?? '',
        ];
    }

    private function skillFields(Character $character): array
    {
        $fields = [];

        // Klassenfertigkeiten: flatten classability1/2/3 into Klasse1-6
        $classAbilities = array_merge(
            (array) ($character->classability1 ?? []),
            (array) ($character->classability2 ?? []),
            (array) ($character->classability3 ?? []),
        );
        foreach (range(1, 6) as $i) {
            $fields["Klasse{$i}"] = $classAbilities[$i - 1] ?? '';
        }

        // Fertigkeiten: skill_weapon + skill_aspect → FE01-FE12
        $skills = array_merge(
            (array) ($character->skill_weapon ?? []),
            (array) ($character->skill_aspect ?? []),
        );
        foreach (range(1, 12) as $i) {
            $fields["FE{$i}"] = $skills[$i - 1] ?? '';
        }

        // Überlieferungen: lore → UEL1-7
        $lore = (array) ($character->lore ?? []);
        foreach (range(1, 7) as $i) {
            $fields["UEL{$i}"] = $lore[$i - 1] ?? '';
        }

        // Handwerkskenntnisse: just append to lore overflow if needed
        // (no dedicated PDF field found; skip for now)

        return $fields;
    }

    private function racialTraitFields(Character $character): array
    {
        $fields = [];
        $traits = (array) ($character->racial_traits ?? []);
        foreach (range(1, 7) as $i) {
            $fields["RS{$i}"] = $traits[$i - 1] ?? '';
        }

        return $fields;
    }

    private function equipmentFields(Character $character): array
    {
        $fields = [];

        // Group equipment by slot
        $bySlot = [];
        foreach ($character->equipment as $item) {
            $slot = $item->pivot->slot ?? 'not_equipped';
            $bySlot[$slot][] = $item;
        }

        // Haupthand weapon (right_arm)
        $mainWeapon = collect($bySlot['right_arm'] ?? [])->first();
        $fields = array_merge($fields, $this->weaponFields($mainWeapon, 'HH'));

        // Nebenhand weapon (left_arm, only if it's a weapon not a shield)
        $offHandItems = $bySlot['left_arm'] ?? [];
        $offWeapon = collect($offHandItems)->first(fn ($e) => $e->item_type === 'Waffe');
        $fields = array_merge($fields, $this->weaponFields($offWeapon, 'NH'));

        // Rüstung (armor slot)
        $armor = collect($bySlot['armor'] ?? [])->first();
        $fields = array_merge($fields, $this->armorFields($armor));

        // Schild (left_arm shield)
        $shield = collect($offHandItems)->first(fn ($e) => $e->item_type === 'Schild');
        $fields = array_merge($fields, $this->shieldFields($shield));

        // Talisman (talisman_1)
        $talisman = collect($bySlot['talisman_1'] ?? [])->first();
        $fields = array_merge($fields, $this->talismanFields($talisman));

        // EQUIP flags
        $fields['EQUIPRS'] = $armor ? 'Ja' : '';
        $fields['EQUIPSCHILD'] = $shield ? 'Ja' : '';
        $fields['EQUIPTAL'] = $talisman ? 'Ja' : '';

        // Enchantments: collect VZ/QS pairs from all equipped items
        $enchantments = [];
        foreach ($character->equipment as $item) {
            $slot = $item->pivot->slot ?? 'not_equipped';
            if ($slot === 'not_equipped') {
                continue;
            }
            foreach ((array) ($item->enchantment ?? []) as $ench) {
                $enchantments[] = ['name' => $ench, 'qs' => $item->enchantment_qs ?? ''];
            }
        }
        foreach (range(1, 5) as $i) {
            $fields["VZ{$i}"] = $enchantments[$i - 1]['name'] ?? '';
            $fields["VZ{$i}QS"] = $enchantments[$i - 1]['qs'] ?? '';
        }

        return $fields;
    }

    private function weaponFields(?Equipment $weapon, string $prefix): array
    {
        if (! $weapon) {
            return array_fill_keys(
                ["{$prefix}QS", "{$prefix}GATT", "{$prefix}AW", "{$prefix}VW", "{$prefix}TW",
                    "{$prefix}TL", "{$prefix}HwP", "{$prefix}LE1", "{$prefix}LE2",
                    "{$prefix}HE1", "{$prefix}HE2", "{$prefix}HE3", "{$prefix}HE4", "{$prefix}HE5", "{$prefix}HE6"],
                ''
            );
        }

        $extensions = (array) ($weapon->wp_erweiterungen ?? []);

        return [
            "{$prefix}QS" => $weapon->quality ?? '',
            "{$prefix}GATT" => implode(', ', (array) ($weapon->waffenführung ?? [])),
            "{$prefix}AW" => $weapon->attackvalue ?? '',
            "{$prefix}VW" => $weapon->wp_vw ?? '',
            "{$prefix}TW" => $weapon->tw ?? '',
            "{$prefix}TL" => $weapon->traglast ?? '',
            "{$prefix}HwP" => $weapon->hwp ?? '',
            "{$prefix}LE1" => implode(', ', (array) ($weapon->damage_type ?? [])),
            "{$prefix}LE2" => $weapon->name ?? '',
            "{$prefix}HE1" => $extensions[0] ?? '',
            "{$prefix}HE2" => $extensions[1] ?? '',
            "{$prefix}HE3" => $extensions[2] ?? '',
            "{$prefix}HE4" => $extensions[3] ?? '',
            "{$prefix}HE5" => $extensions[4] ?? '',
            "{$prefix}HE6" => $extensions[5] ?? '',
        ];
    }

    private function armorFields(?Equipment $armor): array
    {
        if (! $armor) {
            return array_fill_keys(
                ['RSQS', 'RSHwP', 'RSpVW', 'RSSchnitt', 'RSStumpf', 'RSStich',
                    'RSElementar', 'RSArkan', 'RSChaos', 'RSSpirituell',
                    'RSHE1', 'RSHE2', 'RSHE3', 'RSHE4', 'RSHE5', 'RSHE6'],
                ''
            );
        }

        $extensions = (array) ($armor->rs_erweiterungen ?? []);

        return [
            'RSQS' => $armor->quality ?? '',
            'RSHwP' => $armor->hwp ?? '',
            'RSpVW' => $armor->passive_verteidigung ?? '',
            'RSSchnitt' => $armor->armor_schnitt ?? '',
            'RSStumpf' => $armor->armor_stumpf ?? '',
            'RSStich' => $armor->armor_stich ?? '',
            'RSElementar' => $armor->armor_elementar ?? '',
            'RSArkan' => $armor->armor_arcan ?? '',
            'RSChaos' => $armor->armor_chaos ?? '',
            'RSSpirituell' => $armor->armor_spirit ?? '',
            'RSHE1' => $extensions[0] ?? '',
            'RSHE2' => $extensions[1] ?? '',
            'RSHE3' => $extensions[2] ?? '',
            'RSHE4' => $extensions[3] ?? '',
            'RSHE5' => $extensions[4] ?? '',
            'RSHE6' => $extensions[5] ?? '',
        ];
    }

    private function shieldFields(?Equipment $shield): array
    {
        if (! $shield) {
            return array_fill_keys(
                ['SCH', 'SchildVW', 'SchildHwP', 'SchildTL', 'SchildTaP',
                    'SchildSchnitt', 'SchildStich', 'SchildStumpf',
                    'SHHE1', 'SHHE2', 'SHHE3', 'SHHE4', 'SHHE5', 'SHHE6'],
                ''
            );
        }

        $extensions = (array) ($shield->sd_erweiterungen ?? []);

        return [
            'SCH' => $shield->schild_verteidigung ?? '',
            'SchildVW' => $shield->schild_verteidigung ?? '',
            'SchildHwP' => $shield->hwp ?? '',
            'SchildTL' => $shield->traglast ?? '',
            'SchildTaP' => $shield->offensivschild ?? '',
            'SchildSchnitt' => $shield->shield_schnitt ?? '',
            'SchildStich' => $shield->shield_stich ?? '',
            'SchildStumpf' => $shield->shield_stumpf ?? '',
            'SHHE1' => $extensions[0] ?? '',
            'SHHE2' => $extensions[1] ?? '',
            'SHHE3' => $extensions[2] ?? '',
            'SHHE4' => $extensions[3] ?? '',
            'SHHE5' => $extensions[4] ?? '',
            'SHHE6' => $extensions[5] ?? '',
        ];
    }

    private function talismanFields(?Equipment $talisman): array
    {
        if (! $talisman) {
            return array_fill_keys(
                ['TLKW', 'TLRS', 'TLHwP', 'TLTL', 'TAHE1', 'TAHE2', 'TAHE3', 'TAHE4', 'TAHE5', 'TAHE6'],
                ''
            );
        }

        $extensions = (array) ($talisman->ts_erweiterungen ?? []);
        $charmTotal = ($talisman->charm_arcan ?? 0) + ($talisman->charm_chaos ?? 0) + ($talisman->charm_spirit ?? 0);

        return [
            'TLKW' => $charmTotal ?: '',
            'TLRS' => $talisman->charm_arcan ?? '',
            'TLHwP' => $talisman->hwp ?? '',
            'TLTL' => $talisman->traglast ?? '',
            'TAHE1' => $extensions[0] ?? '',
            'TAHE2' => $extensions[1] ?? '',
            'TAHE3' => $extensions[2] ?? '',
            'TAHE4' => $extensions[3] ?? '',
            'TAHE5' => $extensions[4] ?? '',
            'TAHE6' => $extensions[5] ?? '',
        ];
    }
}
