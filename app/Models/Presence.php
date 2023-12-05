<?php

namespace App\Models;

use App\Models\Site;
use App\Models\Faction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Presence extends Model
{
    use HasFactory;

    const Présent = "Présent(e)";
    const Absent = "Absent(e)";
    const Repos = "Repos";


    protected $fillable = [
        'employe_id',
        'date',
        'observation',
        'effectuer par',
        'site_id', 
        'presence',
        'faction_id',
        'salairepresence'
    ];
    public function employe()
    {
        return $this->belongsTo(Employe::class);
    }
    public function site()
    {
        return $this->belongsTo(Site::class);
    }
    public function faction(){
        return $this->belongsTo(Faction::class);
    }

}
