<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sertif extends Model
{
    protected $table = 'sertif';
    protected $primaryKey = 'sertif_id';

    protected $fillable = [
        'vol_id',
        'periode_ke',
        'file_sertifikat',
    ];

    public function volunteer()
    {
        return $this->belongsTo(volunteer::class, 'vol_id', 'vol_id');
    }
}
