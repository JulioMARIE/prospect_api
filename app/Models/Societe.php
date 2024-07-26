<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Societe extends Model
{
    // use HasFactory;

    protected $fillable = [
        'denomination',
        'raison_sociale',
        'IFU',
        'description_siege',
        'commune_id',
    ];

    public function prospections(){
        return $this->hasMany(Prospection::class);
    }

    public function commune(){
        return $this->belongsTo(Commune::class);
    }
}
