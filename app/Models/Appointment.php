<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'doctor_id',
        'date',
        'start_time',
        'end_time',
        'max_patients'



    ];

    public function doctor(){
        return $this->belongsTo(Doctor::class,'doctor_id');
    }
    public function reservations(){
        return $this->hasMany(Reservation::class,'appointment_id');
    }

    // public function notifications()
    // {
    //   return $this->hasMany(Notifications::class, 'appoinment_id');
    // }

}
