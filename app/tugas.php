<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;

class tugas extends Model
{
    
    protected $table = 'tugas';
    protected $guard = 'tugas';
    protected $primaryKey = 'tugas_id';

    protected $fillable = [
        'tugas_id',
        'desk_tgs',
        'deadline',
        'status',
    ];

    public function divisi()
{
    return $this->belongsTo(divisi::class, 'divisi_id');
}
    public function volunteer()
{
    return $this->belongsTo(volunteer::class, 'vol_id');
}
}
