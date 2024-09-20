<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Reservation extends Model
{
    use HasFactory,Notifiable,SoftDeletes;
    protected $table = 'reservations';
    protected $fillable = ['doctor_id', 'user_id', 'appointment_id','status'];
    public function doctor(){
        return $this->belongsTo(Doctor::class,'doctor_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function appointment(){
        return $this->belongsTo(Appointment::class,'appointment_id');
    }
}
