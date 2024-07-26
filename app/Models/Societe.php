<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Societe extends Model
{
    use HasFactory;

    public function prospections(){
        return $this->hasMany(Prospection::class);
    }

    public function commune(){
        return $this->belongsTo(Commune::class);
    }
}
