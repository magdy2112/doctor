<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $fillable = [
        'doctor_id',
        'date',
        'start_time',
        'end_time',



    ];

    public function doctor(){
        return $this->belongsTo(Doctor::class,'doctor_id');
    }
    public function reservations(){
        return $this->hasMany(Reservation::class,'appointment_id');
    }



}
