<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Character;
use App\Models\Equipment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        Character::create([
            'name' => 'Testcharakter',
            'description' => 'Lorem ipsum dolor Aenean sit amet turpis a sapien faucibus dapibus. Vestibulum et lorem ut nulla mattis bibendum. Integer in congue sem. Curabitur egestas justo id malesuada gravida. Vivamus eget felis erat. Phasellus pretium blandit eros, in imperdiet justo bibendum in. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Maecenas vestibulum convallis lorem, vitae pretium sapien ullamcorper ac. Aliquam erat volutpat. Cras id augue id diam ullamcorper imperdiet. Nam at fringilla magna, et finibus ex. Sed sit amet nulla sed enim cursus porta. Vivamus a pulvinar sapien. Etiam pretium magna sit amet arcu imperdiet, sit amet porta neque tincidunt. Mauris bibendum fermentum elit.',
            'system_id' => 1, // sicherstellen, dass System mit ID 1 existiert
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
            'experience-level' => 1,
            'klassenfertigkeiten' => ['Schwertkampf', 'Magiekunde'],
            'handwerkskenntnisse' => ['Schmieden'],
            'lore' => 'Aspektwesen',
        ]);

        // Beispiel-Waffe
        Equipment::create([
            'name' => 'Kriegsaxt der Glut',
            'description' => 'Eine schwere, geschmiedete Axt mit glühender Schneide.',
            'quality' => 'episch',
            'item_type' => 'Waffe',
            'hwp' => 12,
            'waffengattung' => 'Nahkampfwaffe',
            'angriffswert' => 7,
            'damage_type' => ['stumpf', 'schnitt'],
            'trefferwuerfel' => 6,
            'traglast' => 3,
            'erweiterungen' => ['der Präzision', 'des Gemetzels'],
        ]);

        // Beispiel-Rüstung
        Equipment::create([
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
            'verzauberungen' => 'des Schutzes',
            'rs_erweiterungen' => 'verstärkt',
        ]);

        // Beispiel-Talisman
        Equipment::create([
            'name' => 'Talisman der Klarheit',
            'description' => 'Ein schimmernder Kristall, der geistige Klarheit spendet.',
            'quality' => 'legendär',
            'item_type' => 'Talisman',
            'hwp' => 8,
            'kontrollwiderstand' => 5,
            'rs_arcan' => 2,
            'rs_chaos' => 1,
            'rs_spirit' => 3,
            'verzauberungen' => 'der klarheit',
            'ts_erweiterungen' => 'der konzentration',
        ]);

        // Beispiel-Schild
        Equipment::create([
            'name' => 'Schild der Vorhut',
            'description' => 'Ein massiver Schild mit Gravuren eines Löwen.',
            'quality' => 'ungewöhnlich',
            'item_type' => 'Schild',
            'hwp' => 6,
            'schild_verteidigung' => 5,
            'rs_arcan' => 1,
            'rs_chaos' => 1,
            'rs_spirit' => 2,
            'verzauberungen' => 'des Schutzes',
            'ts_erweiterungen' => 'der ruhe',
        ]);
        \Database\Factories\EquipmentFactory::new()->count(10)->create();
    }
}
