<?php

namespace App;

use Illuminate\Database\Eloquent\Model;



class jadwal extends Model
{
    protected $table = 'jadwal';
    protected $primaryKey = 'jadwal_id';

    protected $fillable = [
        'divisi_id',
        'tgl_jadwal',
        'agenda',
    ];

    protected $casts = [
        'tgl_jadwal' => 'date',
    ];
    

    public function volunteers() {
        return $this->belongsToMany(volunteer::class, 'jadwal_volunteer', 'jadwal_id', 'vol_id');
    }

    public function divisi()
    {
        return $this->belongsTo(divisi::class, 'divisi_id');
    }  
}

