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
            'description' => 'Lorem ipsum dolor Aenean sit amet turpis a sapien faucibus dapibus. Vestibulum et lorem ut nulla mattis bibendum. Integer in congue sem. Curabitur egestas justo id malesuada gravida. Vivamus eget felis erat. Phasellus pretium blandit eros, in imperdiet justo bibendum in. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Maecenas vestibulum convallis lorem, vitae pretium sapien ullamcorper ac. Aliquam erat volutpat. Cras id augue id diam ullamcorper imperdiet. Nam at fringilla magna, et finibus ex. Sed sit amet nulla sed enim cursus porta. Vivamus a pulvinar sapien. Etiam pretium magna sit amet arcu imperdiet, sit amet porta neque tincidunt. Mauris bibendum fermentum elit.',
            'leiteigenschaft1' => 'KO',
            'leiteigenschaft2' => 'ST',
            'archetype' => 'Sappeur',
            'race' => 'Ainu',
            'wesen' => 'Biest',
            'rassenmerkmale' => ['Apex', 'Nachtsicht'],
            'ko' => 12,
            'st' => 10,
            'ag' => 9,
            'ge' => 11,
            'we' => 8,
            'in' => 10,
            'mu' => 9,
            'ch' => 10,
            'skill_ko' => ['Block', 'Entwaffnen'],
            'skill_st' => ['Plattenbrecher', 'Schädelbrecher'],
            'leps' => 24,
            'tragkraft' => 10,
            'geschwindigkeit' => 4.5,
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
            'klassenfertigkeiten' => ['Animist I', 'Barde I', 'Berserker I'],
            'handwerkskenntnisse' => ['Werkzeuge'],
            'lore' => 'Aspektwesen',
        ]);


        // Beispiel-Waffe
        Equipment::create([
            'user_id' => 1,
            'name' => 'Kriegsaxt der Glut',
            'description' => 'Eine schwere, geschmiedete Axt mit glühender Schneide.',
            'quality' => 'episch',
            'item_type' => 'Waffe',
            'hwp' => 12,
            'waffengattung' => 'Nahkampfwaffe',
            'attackvalue' => 7,
            'damage_type' => ['stumpf', 'schnitt'],
            'trefferwuerfel' => 6,
            'traglast' => 3,
            'wp_erweiterungen' => ['der Präzision', 'des Gemetzels'],
        ]);

        // Beispiel-Rüstung
        Equipment::create([
            'user_id' => 1,
            'name' => 'Verstärkte Knochenrüstung',
            'description' => 'Rüstung aus alchemistisch gehärtetem Knochen.',
            'quality' => 'selten',
            'item_type' => 'Rüstung',
            'hwp' => 9,
            'passive_verteidigung' => 4,
            'rs_schnitt' => 5,
            'rs_stumpf' => 3,
            'rs_stich' => 4,
            'rs_elementar' => 2,
            'traglast' => 4,
            'enchantment' => 'des Schutzes',
            'enchantment_qs' => 'einfach',
            'rs_erweiterungen' => ['Verstärkt'],
        ]);

        // Beispiel-Talisman
        Equipment::create([
            'user_id' => 1,
            'name' => 'Talisman der Klarheit',
            'description' => 'Ein schimmernder Kristall, der geistige Klarheit spendet.',
            'quality' => 'legendär',
            'item_type' => 'Talisman',
            'hwp' => 8,
            'kontrollwiderstand' => 5,
            'rs_arcan' => 2,
            'rs_chaos' => 1,
            'rs_spirit' => 3,
            'traglast' => 1,
            'enchantment' => 'des Eifers',
            'enchantment_qs' => 'episch',
            'ts_erweiterungen' => ['der konzentration'],
        ]);

        // Beispiel-Schild
        Equipment::create([
            'user_id' => 1,
            'name' => 'Schild der Vorhut',
            'description' => 'Ein massiver Schild mit Gravuren eines Löwen.',
            'quality' => 'ungewöhnlich',
            'item_type' => 'Schild',
            'hwp' => 6,
            'schild_verteidigung' => 5,
            'rs_schnitt' => 1,
            'rs_stumpf' => 1,
            'rs_stich' => 2,
            'enchantment' => 'des Schutzes',
            'enchantment_qs' => 'ungewöhnlich',
            'ts_erweiterungen' => ['der ruhe'],
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
