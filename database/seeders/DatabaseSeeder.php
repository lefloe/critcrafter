<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Character;
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
    }
}
