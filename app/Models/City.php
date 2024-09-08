<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $fillable = ['city'];
    public function doctors(){
        return $this->hasMany(Doctor::class,'doctor_id');
    }
    public function users(){
        return $this->hasMany(User::class,'user_id');
    }
}
