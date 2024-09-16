<?php

namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    use HasFactory,HasApiTokens,Notifiable;
    // public $timestamps = true;
    protected $table = 'password_reset_tokens';
    protected $fillable = ['email', 'token','updated_at'];
}
