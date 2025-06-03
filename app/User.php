<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

     protected $table = 'users'; // Pastikan tabelnya sesuai dengan nama tabel di database
    protected $guard = 'users';
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'divisi_id',
        'nama',
        'jabatan',
         'email',
        'password',
        'status',
        'remember_token',
    ];

    protected $attributes = [
        'status' => 'Aktif',
    ];
    

    public function divisi()
    {
        return $this->belongsTo(divisi::class, 'divisi_id');
    }


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
