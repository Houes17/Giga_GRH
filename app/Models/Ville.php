<?php

namespace App\Models;

use App\Models\Site;
use App\Models\Employe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ville extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'region',   
    ];

    public function sites(){
        return  $this->hasMany(Site::class);
    }
    
}
