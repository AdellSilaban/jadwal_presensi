<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;

class volunteer extends Authenticatable
{
    
    protected $table = 'volunteer';
    protected $guard = 'volunteer';
    protected $primaryKey = 'vol_id';

    protected $fillable = [
        'vol_id',
        'nama',
        'nim',
        'fakultas',
        'jurusan',
        'email',
        'periode',
        'divisi_id',
        'password',
    ];

    public function divisi()
{
    return $this->belongsTo(divisi::class, 'divisi_id');
}

}
