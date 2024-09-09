<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qualification extends Model
{
    use HasFactory;
    protected $table = 'qualifications';
    protected $fillable = [ 'qualification'];
    public function doctors(){
        return $this->hasMany(Doctor::class,'qualification');
    }
}
