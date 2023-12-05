<?php

namespace Database\Seeders;

use App\Models\Departement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    const ADS = "Agent de sécurité";
    const AdsChefEquipe = "Chef agent de sécurité";
    const Contrôleur = "Contrôleur";
    const ROP = "Responsable des operations";
    const Assistante = "Assistante";

    public function run(): void
    {
        $departements = [
            self::ADS,
            self::AdsChefEquipe,
            self::Contrôleur,
            self::ROP,
            self::Assistante
        ];
        foreach ($departements as $key => $value) {
            Departement::firstOrCreate(['nom' => $value]);
        }
    }
}
