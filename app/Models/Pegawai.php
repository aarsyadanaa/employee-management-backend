<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Pegawai extends Model
{
    use HasFactory;
    protected $table = 'pegawai';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nip', 'nama', 'tempat_lahir', 'tgl_lahir', 'jenis_kelamin', 'alamat', 'agama', 'no_hp', 'npwp', 'foto',
    ];
    protected function foto(): Attribute
    {
        return Attribute::make(
            get: fn ($foto) => url('/storage/posts/' . $foto),
        );
    }

    public function golongan()
    {
        return $this->hasMany(Golongan::class);
    }

    public function jabatan()
    {
        return $this->hasMany(Jabatan::class);
    }

    public function unitKerja()
    {
        return $this->hasMany(UnitKerja::class);
    }
}
