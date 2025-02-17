<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;

class koordinator extends Authenticatable
{

    protected $table = 'koordinator';
    protected $guard = 'koordinator';
    protected $primaryKey = 'koor_id';

    protected $fillable = [
        'koor_id',
        'nama_koor',
        'email',
        'password',
    ];

    public function divisi()
{
    return $this->belongsTo(divisi::class, 'divisi_id');
}

}
