<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Qualification extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'qualifications';
    protected $fillable = [ 'qualification'];
    public function doctors(){
        return $this->hasMany(Doctor::class,'qualification');
    }
}
