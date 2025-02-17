<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;

class kepala_lpkksk extends Authenticatable
{
    
    protected $table = 'kepala_lpkksk';
    protected $guard = 'kepala_lpkksk';
    protected $primaryKey = 'kpl_id';

    protected $fillable = [
        'kpl_id',
        'email',
        'password',
    ];
}
