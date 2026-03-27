<?php

namespace Database\Seeders;

use App\Models\Character;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'user',
            'email' => 'user@example.com',
            'role' => 'user',
            'password' => bcrypt('password'),
        ]);
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        Character::create([
            'name' => 'Testcharakter',
            'user_id' => 1,
            'description' => '<h3>Hintergrund und Persönlichkeit</h3>
            <p><strong>Herkunft:</strong> (Woher stammt der Charakter? Wer waren seine Eltern?)<br>
            <strong>Motivation:</strong> (Was treibt den Charakter an? Welche Ziele verfolgt er?)<br>
            <strong>Charakterzüge:</strong> (Welche Stärken und Schwächen hat der Charakter?)<br>
            <strong>Einschneidendes Ereignis:</strong> (Welches Erlebnis hat ihn geprägt?)<br>
            </p>Lorem ipsum dolor Aenean sit amet turpis a sapien faucibus dapibus. Vestibulum et lorem ut nulla mattis bibendum. Integer in congue sem. Curabitur egestas justo id malesuada gravida. Vivamus eget felis erat. Phasellus pretium blandit eros, in imperdiet justo bibendum in. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Maecenas vestibulum convallis lorem, vitae pretium sapien ullamcorper ac. Aliquam erat volutpat. Cras id augue id diam ullamcorper imperdiet. Nam at fringilla magna, et finibus ex. Sed sit amet nulla sed enim cursus porta. Vivamus a pulvinar sapien. Etiam pretium magna sit amet arcu imperdiet, sit amet porta neque tincidunt. Mauris bibendum fermentum elit.',
            'leiteigenschaft1' => 'KO',
            'leiteigenschaft2' => 'MU',
            'archetype' => 'Sappeur',
            'race' => 'Ainu',
            'wesen' => 'Biest',
            'racial_traits' => ['Apex', 'Nachtsicht'],
            'ko' => 12,
            'st' => 10,
            'ag' => 9,
            'ge' => 11,
            'we' => 8,
            'in' => 10,
            'mu' => 9,
            'ch' => 10,
            'skill_weapon' => ['Block', 'Entwaffnen'],
            'skill_aspect' => ['Magniforma', 'Pandemalum'],
            'leps' => 24,
            'tragkraft' => 10,
            'gs_leib' => 5,
            'gs_seele' => 8,
            'handwerksbonus' => 1,
            'kontrollwiderstand' => 4,
            'initiative' => 5,
            'verteidigung' => 3,
            'seelenpunkte' => 20,
            'nw_gattung' => 'Nahkampfwaffe',
            'nw_quality'=> 'gewöhnlich',
            'nw_damage_type' => ['stumpf'],
            'nw_aw' => 4,
            'nw_vw'=> 0,
            'nw_tw'=> 1,
            'xp' => 7,
            'classability1' => ['Krüge zerdeppern'],
            'handwerkskenntnisse' => ['Werkzeuge'],
            'lore' => 'Aspektwesen',
        ]);


        // Beispiel-Waffe
        Equipment::create([
            'user_id' => 1,
            'name' => 'Gewöhnliches Kurzschwert',
            'description' => 'Ein gewöhnliches Kurzschwert, einfach aber effektiv.',
            'quality' => 'gewöhnlich',
            'item_type' => 'Waffe',
            'hwp' => 40,

            // Waffe-spezifische Felder
            'attackvalue' => 4, // QS 'gewöhnlich'
            'damage_type' => json_encode(['Schnitt', 'Stich']),
            'count_dice' => 1,
            'tw' => 'W6', // QS 'gewöhnlich'
            'waffenführung' => json_encode(['Einhändig']),
            'traglast' => 2,
            'wp_vw' => 4, // QS 'gewöhnlich'

            // Erweiterungen & Verzauberungen
            'wp_erweiterungen' => json_encode(['der einfachen Handhabung']),
            'enchantment' => json_encode(['der Künste']),
            'enchantment_qs' => 'einfach',
        ]);

        // Beispiel-Rüstung
        Equipment::create([
            'user_id' => 1,
            'name' => 'Lederrüstung',
            'description' => 'Leichte Lederrüstung für Reisende.',
            'quality' => 'einfach',
            'item_type' => 'Rüstung',
            'hwp' => 25,

            // Rüstung-spezifische Felder (RS)
            'pVW' => 2, // QS 'einfach'
            'armor_schnitt' => 2,
            'armor_stumpf' => 1,
            'armor_stich' => 1,
            'armor_elementar' => 0,

            // Erweiterungen & Verzauberungen
            'rs_erweiterungen' => json_encode(['passgenau']),
            'enchantment' => json_encode(['der Erleichterung']),
            'enchantment_qs' => 'schlecht',
        ]);

        // Beispiel-Talisman
        Equipment::create([
            'user_id' => 1,
            'name' => 'Talisman der Willenskraft',
            'description' => 'Ein kleiner Anhänger, der die Willenskraft stärkt.',
            'quality' => 'ungewöhnlich',
            'item_type' => 'Talisman',
            'hwp' => 50,

            // Talisman-spezifische Felder
            'kw' => 7, // QS 'ungewöhnlich'
            'charm_arcan' => 3,
            'charm_chaos' => 0,
            'charm_spirit' => 0,

            'ts_erweiterungen' => json_encode(['der willenskraft']),
            'enchantment' => json_encode(['der Hast']),
            'enchantment_qs' => 'einfach',
        ]);

        // Beispiel-Schild
        Equipment::create([
            'name' => 'Gewöhnlicher Rundschild',
            'description' => 'Ein einfacher, solider Rundschild.',
            'quality' => 'gewöhnlich',
            'item_type' => 'Schild',
            'hwp' => 35,

            // Schild-spezifische Felder
            'schild_verteidigung' => 4, // QS 'gewöhnlich'
            'shield_stumpf' => 1,
            'shield_stich' => 2,
            'shield_schnitt' => 2,
            'shield_elementar' => 0,
            'offensivschild' => 0,

            'sd_erweiterungen' => json_encode(['stabil', 'trommelschild']),
        ]);

        // Beispiel-Schmuckstück
        Equipment::create([
            'user_id' => 1,
            'name' => 'Ring der Macht',
            'description' => 'Ein massiver Ring mit Gravuren eines Zaubers.',
            'quality' => 'ungewöhnlich',
            'item_type' => 'Schmuckstück',
            'enchantment' => 'des Schutzes',
            'enchantment_qs' => 'ungewöhnlich',
        ]);
    }
}
