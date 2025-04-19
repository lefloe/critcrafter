<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Equipment;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Equipment>
 */
class EquipmentFactory extends Factory
{
    protected $model = Equipment::class;


    // @return array<string, mixed>

    public function definition(): array
    {
        $faker = $this->faker;

        $itemTypes = [
            'Paraphernalia',
            'Handelsware',
            'Nahrungsmittel',
            'Werkzeug',
            'Schmuckstück',
            'Anwendung',
            'Material',
            'sonstiges',
        ];

        $qualities = ['schlecht', 'einfach', 'gewöhnlich', 'ungewöhnlich', 'selten'];

        return [
            'name' => ucfirst($faker->words(2, true)),
            'description' => $faker->sentence(2),
            'quality' => $faker->randomElement($qualities),
            'item_type' => $faker->randomElement($itemTypes),
            'hwp' => $faker->numberBetween(1, 30),
            'traglast' => $faker->numberBetween(1, 9),
        ];
    }
}
