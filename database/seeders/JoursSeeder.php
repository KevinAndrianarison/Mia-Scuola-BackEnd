<?php

namespace Database\Seeders;

use App\Models\Jour;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JoursSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi',  'Samedi', 'Dimanche'];
        foreach ($jours as $jour) {
            Jour::create(['nom' => $jour]);
        }
    }
}
