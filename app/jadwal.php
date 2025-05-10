<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;




class jadwal extends Model
{
    protected $table = 'jadwal';
    protected $primaryKey = 'jadwal_id';

    protected $fillable = [
        'divisi_id',
        'tgl_jadwal',
        'agenda',
        'jam_buka',
        'jam_tutup',
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

    public function getIsTodayAttribute()
    {
        return $this->tgl_jadwal->toDateString() === Carbon::now()->toDateString();
    }

    

}

