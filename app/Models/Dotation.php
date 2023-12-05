<?php

namespace App\Models;

use App\Models\Employe;
use App\Models\Departement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Dotation extends Model
{
    use HasFactory;

    protected $fillable = ['type_dotation','description','date', 'montant','month','year'];
    
    public function employes(): BelongsToMany
    {
        return $this->belongsToMany(Employe::class, 'employe_dotation');
    }
    public function departements()
    {
        return $this->employes;
    }
}
