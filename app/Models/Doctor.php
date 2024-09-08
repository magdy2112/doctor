<?php

namespace App\Models;

// use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Doctor extends Authenticatable
{
    use HasFactory,HasApiTokens,Notifiable;

    protected $guard = 'doctor';
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
        'phone',
        'address',
        'age',
        'experience',
        'price',
        'description',
        'gender',
       'status',
       'qualification_id',
       'specialization_id',
       'city_id'

    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
       /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function specialization (){
        return $this->belongsTo(Specialization::class,'specialization');
    }
    public function appointments(){
        return $this->hasMany(Appointment::class,'doctor_id');
    }
    public function reservations(){
        return $this->hasMany(Reservation::class,'doctor_id');

    }
    public function cities(){
        return $this->belongsTo(City::class,'city_id');
    }
    public function qualification(){
        return $this->belongsTo(Qualification::class,'qualification_id');
    }
    public function specializations(){
        return $this->belongsTo(Specialization::class,'specialization_id');
    }

}






