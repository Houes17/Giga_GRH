<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salaire extends Model
{
    use HasFactory;

    const Non_Payé = "Non Payé";
    const Payé = "Payé";

    protected $fillable = [
        'employe_id',
        'salaire total',
        'etat',
        'mois',
        'année',
        'nbre_heures_travail',
        'nbre_heure_repos',
        'nbre_absence'
    ];
    public function employe()
    {
        return $this->belongsTo(Employe::class);
    }

}
