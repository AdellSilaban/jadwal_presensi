<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;


class divisi extends Model
{
    
    protected $table = 'divisi';
    protected $primaryKey = 'divisi_id';

    protected $fillable = [
        'divisi_id',
        'nama_divisi',
        'desk_divisi',
    ];

    public function volunteer()
    {
        return $this->hasMany(volunteer::class, 'divisi_id');
    }
}
