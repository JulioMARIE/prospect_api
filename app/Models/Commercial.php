<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commercial extends Model
{
    protected $fillable = ['utilisateur_id'];

    public function prospections(){
        return $this->hasMany(Prospection::class);
    }

    public function quotas(){
        return $this->hasMany(Quota::class);
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }
}
