<?php

namespace App\Models;

use App\Models\Presence;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Faction extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'nbre_heure'];

    public function presences(){
        return $this->hasMany(Presence::class);
    }
}
