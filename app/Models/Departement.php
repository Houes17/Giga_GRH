<?php

namespace App\Models;

use App\Models\Employe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Departement extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom'
    ];
    public function employe():  HasMany
    {
        return $this->hasMany(Employe::class);
    }
}
