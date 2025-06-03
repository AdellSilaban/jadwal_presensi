<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class desk_div extends Model
{
      protected $table = 'desk_div';
    protected $primaryKey = 'deskripsi_id';

    protected $fillable = [
        'divisi_id',
        'deskripsi',
    ];

    // Relasi satu ke banyak dengan volunteer
    public function divisi()
{
    return $this->belongsTo(divisi::class, 'divisi_id');
}

}
