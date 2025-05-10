<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tugas_volunteer extends Model
{
    protected $table = 'tugas_volunteer';

    // Kolom yang dapat diisi
    protected $fillable = 
    ['status','peran', 'status_validasi', 'revisi_catatan'];

    // Matikan timestamps jika tabel pivot tidak memiliki kolom created_at dan updated_at
    public $timestamps = false;

    // Relasi ke model Task
    public function tugas()
    {
        return $this->belongsTo(tugas::class, 'tugas_id');
    }

    // Relasi ke model Volunteer
    public function volunteers()
    {
        return $this->belongsTo(volunteer::class, 'vol_id');
    }
}

