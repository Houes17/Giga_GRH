<?php

namespace App\Models;

use App\Models\Site;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rapport extends Model
{
    use HasFactory;

    protected $fillable = ['totalemployé','totalsalaire','montantdotation','depensetotal','année','mois','site_id'];

    public function sites()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }
}
