<?php

namespace App\Models;

use App\Models\User;
use App\Models\Ville;
use App\Models\Employe;
use App\Models\Rapport;
use App\Models\Presence;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'ville_id',
        'salaire'
    ];

    public function ville():  BelongsTo
    {
        return $this->belongsTo(Ville::class);
    }
    public function presence()
    {
        return $this->hasMany(Presence::class);
    }
    
    public function employes(): BelongsToMany
    {
        return $this->belongsToMany(Employe::class);
    }
    
    public function controleurs()
    {
        return $this->employes()->where('departement_id', 3);
    }

    public function ads()
    {
        return $this->employes()->where('departement_id', 1);
    }
    public function chefagent()
    {
        return $this->employes()->where('departement_id', 2);
    }
    public function rapports()
    {
        return $this->hasMany(Rapport::class);
    }
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
    
  
}
