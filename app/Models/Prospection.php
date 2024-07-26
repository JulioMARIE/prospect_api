<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prospection extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function commercial(){
        return $this->belongsTo(Commercial::class);
    }

    public function societe(){
        return $this->belongsTo(Societe::class);
    }

    public function suivis(){
        return $this->hasMany(Suivi::class);
    }
}
