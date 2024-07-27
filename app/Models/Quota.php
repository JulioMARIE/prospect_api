<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quota extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function commercial(){
        return $this->belongsTo(Commercial::class);
    }
}
