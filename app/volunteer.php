<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable;

class volunteer extends Authenticatable
{
    use Notifiable;

    protected $table = 'volunteer'; // Pastikan tabelnya sesuai dengan nama tabel di database
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
        'no_rek_vlt',
        'divisi_id',
        'password',
        'sub_divisi_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relasi ke divisi
    public function divisi()
    {
        return $this->belongsTo(divisi::class, 'divisi_id');
    }

    // Relasi ke jadwal dengan tabel penghubung
    public function jadwals() {
        return $this->belongsToMany(jadwal::class, 'jadwal_volunteer', 'vol_id', 'jadwal_id');
    }

    // Relasi ke tugas dengan tabel penghubung
    public function tugas() {
        return $this->belongsToMany(tugas::class, 'tugas_volunteer', 'vol_id', 'tugas_id')
                    ->withPivot('status', 'peran', 'status_validasi', 'revisi_catatan')
                    ->withTimestamps();
    }
    

    // Relasi ke User berdasarkan email
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

  // Model Volunteer
public function subDivisi()
{
    return $this->belongsTo(SubDivisi::class, 'sub_divisi_id');
}

public function sertif()
{
    return $this->hasMany(sertif::class, 'vol_id', 'vol_id');
}

    

    // Mendapatkan identifikasi untuk autentikasi
    public function getAuthIdentifierName()
    {
        return 'email';
    }

    // Mendapatkan password untuk autentikasi
    public function getAuthPassword()
    {
        return $this->password;
    }
}
