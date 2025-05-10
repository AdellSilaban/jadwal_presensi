<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubDivisi extends Model
{
    protected $table = 'sub_divisi';
    protected $primaryKey = 'sub_divisi_id';

    protected $fillable = [
        'divisi_id',
        'nama_subdivisi',
    ];

    // Relasi kebalikannya ke divisi
    public function divisi()
    {
        return $this->belongsTo(divisi::class, 'divisi_id');
    }

    // Relasi satu ke banyak dengan volunteer
    public function volunteer()
    {
        return $this->hasMany(volunteer::class, 'sub_divisi_id');  // Relasi ke 'sub_divisi_id' di tabel 'volunteers'
    }
}
