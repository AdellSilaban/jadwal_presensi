<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class divisi extends Model
{
    protected $table = 'divisi';
    protected $primaryKey = 'divisi_id';

    protected $fillable = [
        'divisi_id',
        'nama_divisi',
        'desk_divisi',
    ];

    // Relasi satu ke banyak dengan volunteer
    public function volunteers()
    {
        return $this->hasMany(volunteer::class, 'divisi_id');
    }

    // Relasi satu ke banyak dengan subDivisi
    public function subDivisi()
    {
        return $this->hasMany(SubDivisi::class, 'divisi_id');
    }
}
