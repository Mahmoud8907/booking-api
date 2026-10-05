<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = ['name', 'description', 'price', 'duration'];
    
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
