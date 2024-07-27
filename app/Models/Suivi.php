<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suivi extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function prospection(){
        return $this->belongsTo(Prospection::class);
    }
}
