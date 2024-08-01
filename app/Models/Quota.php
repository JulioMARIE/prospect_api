<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Prospection;

class Quota extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['nombre_fait', 'statut'];

    public function getNombreFaitAttribute()
    {
        return Prospection::where('commercial_id', $this->commercial_id)
            ->whereBetween('date_heure', [$this->date_debut . ' 00:00:00', $this->date_fin . ' 23:59:59'])
            ->count();
    }

    public function getStatutAttribute()
    {
        return $this->nombre_fait >= $this->nombre_fixe ? 1 : 0;
    }

    public function commercial(){
        return $this->belongsTo(Commercial::class);
    }
}
