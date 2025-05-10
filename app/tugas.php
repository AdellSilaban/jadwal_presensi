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
        'divisi_id',
        'desk_tgs',
        'deadline',
        'link_gdrive',
    ];

    public function divisi()
{
    return $this->belongsTo(divisi::class, 'divisi_id');
}

public function volunteers() {
    return $this->belongsToMany(volunteer::class, 'tugas_volunteer', 'tugas_id', 'vol_id')
                ->withPivot('status', 'peran', 'status_validasi', 'revisi_catatan')
                ->withTimestamps();
}


}
