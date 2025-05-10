<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class presensi extends Model
{
    protected $table = 'presensi';
    protected $guard = 'presensi';
    protected $primaryKey = 'presensi_id';

    protected $fillable = [
        'jadwal_id',
        'vol_id',
        'check_in',
        'desk_tgs',
        'check_out',
        'total_jam',
        'status',
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];
    

    public function jadwal()
{
    return $this->belongsTo(jadwal::class, 'jadwal_id');
}
    public function volunteer()
{
    return $this->belongsTo(volunteer::class, 'vol_id');
}
}
