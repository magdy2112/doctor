<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['city'];
    public function doctors(){
        return $this->hasMany(Doctor::class,'city_id');
    }
    public function users(){
        return $this->hasMany(User::class,'user_id');
    }
}
