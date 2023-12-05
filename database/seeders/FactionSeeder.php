<?php

namespace Database\Seeders;

use App\Models\Faction;
use Illuminate\Database\Seeder;

class FactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    const JOUR  = 'Jour';
    const NUIT = 'Nuit';
    const JOUR_NUIT = 'Jour & Nuit';
    const ABSENCE = "Absence";
    const REPOS = "Repos";
    const REPOS_24 = "Repos-24";
    public function run()
    {
        $factions = [
            ['nom' => self::JOUR, 'nbre_heure' => 12],
            ['nom' => self::NUIT, 'nbre_heure' => 12],
            ['nom' => self::JOUR_NUIT, 'nbre_heure' => 24],
            ['nom' => self::ABSENCE, 'nbre_heure' => 0],
            ['nom' => self::REPOS, 'nbre_heure' => 12],
            ['nom' => self::REPOS_24, 'nbre_heure' => 24],
        ];

        foreach ($factions as $factionData) {
            Faction::firstOrCreate(['nom' => $factionData['nom']], $factionData);
        }
    }
}
