<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    use HasFactory;

    public function societes(){
        return $this->hasMany(Societe::class);
    }

    public function pays(){
        return $this->belongsTo(Pays::class);
    }
}
