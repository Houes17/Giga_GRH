<?php

namespace App\Models;

use App\Models\Site;
use App\Models\Ville;
use App\Models\Salaire;
use App\Models\Dotation;
use App\Models\Presence;
use App\Models\Departement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Employe extends Model
{
    use HasFactory;

    const EN_ACTIVITE = "EN ACTIVITE";
    const NON_ACTIVITE = "NON ACTIVITE";

    const CDD = "CDD";
    const STAGE = "STAGE";
    const ESSAI = "ESSAI";
    const CDI = "CDI";

    protected $casts = [
        'document' => 'array',
    ];

    protected $fillable =[
        'matricule',
        'nomprenoms',
        'telephone',
        'cin',
        'statut',
        'date engagement',
        'lieu naissance',
        'date naissance',
        'situation',
        'photo',
        //'salaire',
        'adresse',
        'document',
        'genre',
        'departement_id',
        'ville_id',
        'contrat',
        'datefinengagement',
        'categorie'
    ];
    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class);
    }
  
    public function presence()
    {
        return $this->hasMany(Presence::class);
    }
    
    public function salaires()
    {
        return $this->hasMany(Salaire::class);
    }

    public function sites(): BelongsToMany
    {
        return $this->belongsToMany(Site::class);
    }

    public function dotations(): BelongsToMany
    {
        return $this->belongsToMany(Dotation::class, 'employe_dotation');
    }
    
}
