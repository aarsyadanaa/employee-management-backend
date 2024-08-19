<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class unitKerja extends Model
{
    use HasFactory;
    protected $table = 'unit_kerja';
    protected $fillable = ['pegawai_id','nama_unit_kerja', 'tempat_tugas'];
    public function pegawai(){
        return $this->belongsTo(Pegawai::class);
    }
}

