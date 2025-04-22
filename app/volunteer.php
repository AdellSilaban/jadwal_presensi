<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable; // Pastikan baris ini ada
use Illuminate\Notifications\Notifiable;

class volunteer extends Authenticatable // PERUBAHAN DI SINI
{
    use Notifiable;

    protected $table = 'volunteer';
    protected $guard = 'volunteer';
    protected $primaryKey = 'vol_id';
    public $timestamps = true;

    protected $fillable = [
        'nama',
        'nim',
        'fakultas',
        'jurusan',
        'email',
        'mulai_aktif',
        'akhir_aktif',
        'divisi_id',
        'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function divisi()
    {
        return $this->belongsTo(divisi::class, 'divisi_id');
    }

    public function jadwals() {
        return $this->belongsToMany(jadwal::class, 'jadwal_volunteer', 'vol_id', 'jadwal_id');
    }

    public function tugas() {
        return $this->belongsToMany(Tugas::class, 'tugas_volunteer', 'vol_id', 'tugas_id')
                    ->withPivot('status', 'peran')
                    ->withTimestamps();
    }
     

    public function user()
    {
        return $this->belongsTo(user::class, 'email', 'email');
    }

    public function getAuthIdentifierName()
    {
        return 'email';
    }

    public function getAuthPassword()
    {
        return $this->password;
    }
}