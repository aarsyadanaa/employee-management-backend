<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;
    protected $table = 'jabatan';
    protected $fillable = ['pegawai_id','nama_jabatan', 'eselon'];

    public function pegawai(){
        return $this->belongsTo(Pegawai::class);
    }
}

