<?php

namespace App\Http\Controllers;
use App\Models\Pegawai;
use App\Models\Jabatan;
use App\Models\Golongan;
use App\Models\unitKerja;
use App\Post;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    // get all pegawai
    //GET /api/pegawai
    public function index()
    {
        // return Pegawai::with('jabatan', 'golongan', 'unitKerja')->get();
        return Golongan::with('pegawai.jabatan', 'pegawai.golongan', 'pegawai.unitKerja')->get();
    }
    //POST /api/pegawai
    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'nip' => 'required|unique:pegawai|max:20',
            'nama' => 'required|max:100',
            'tempat_lahir' => 'nullable|max:50',
            'tgl_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'nullable|max:255',
            'agama' => 'nullable|max:50',
            'no_hp' => 'nullable|max:20',
            'npwp' => 'nullable|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'golongan' => 'nullable|max:50',
            'keterangan' => 'nullable|max:50',
            'nama_unit_kerja' => 'nullable|max:200',
            'tempat_tugas' => 'nullable|max:100',
            'nama_jabatan' => 'nullable|max:50',
            'eselon' => 'nullable|max:50',
        ]);
        if($validatedData->fails()){
            return response()->json($validatedData->errors());
        }
        $validatedData = $validatedData->validated();
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filePath = $file->storeAs('public/posts', $file->hashName());
            $validatedData['foto'] = $filePath;
        }
        
        // Buat pegawai
        $pegawai = Pegawai::create($validatedData);
        $pegawaiId = $pegawai->id;

        // Golongan
        $subsetDataGolongan = [
            'golongan' => $validatedData['golongan'],
            'keterangan' => $validatedData['keterangan'],
        ];
        $validatedDataGolongan = array_merge($subsetDataGolongan, ['pegawai_id' => $pegawaiId]);
        $pegawai = Golongan::create($validatedDataGolongan);
        
        // Unit Kerja
        $subsetDataUnitKerja = [
            'nama_unit_kerja' => $validatedData['nama_unit_kerja'],
            'tempat_tugas' => $validatedData['tempat_tugas'],
        ];
        $validatedDataUnitKerja = array_merge($subsetDataUnitKerja, ['pegawai_id' => $pegawaiId]);
        $pegawai = unitKerja::create($validatedDataUnitKerja);
        
        // Jabatan
        $subsetDataJabatan = [
            'nama_jabatan' => $validatedData['nama_jabatan'],
            'eselon' => $validatedData['eselon'],
        ];
        $validatedDataJabatan = array_merge($subsetDataJabatan, ['pegawai_id' => $pegawaiId]);
        $pegawai = Jabatan::create($validatedDataJabatan);
        
        return response()->json($pegawai, 204);        
    }

    //GET api/pegawai/{id}
    public function show(string $id)
    {
        $pegawai = Pegawai::with(['golongan', 'jabatan', 'unitKerja'])->findOrFail($id);
        return response()->json($pegawai);
    }

    //PUT api/pegawai/{id}
    public function update(Request $request, string $id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $validatedData = $request->validate([
            'nip' => 'required|max:20|unique:pegawai,nip,' . $pegawai->id_pegawai,
            'nama' => 'required|max:100',
            'tempat_lahir' => 'nullable|max:50',
            'tgl_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'nullable|max:255',
            'agama' => 'nullable|max:50',
            'no_hp' => 'nullable|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'npwp' => 'nullable|max:20',
            'id_golongan' => 'required|exists:golongan,id_golongan',
            'id_jabatan' => 'required|exists:jabatan,id_jabatan',
            'id_unit_kerja' => 'required|exists:unit_kerja,id_unit_kerja',
        ]);
        if ($request->hasFile('foto')) {
            if ($pegawai->foto) {
                Storage::disk('public')->delete($pegawai->foto);
            }
            $validatedData['foto'] = $request->file('foto')->store('photos', 'public');
        }
        $pegawai->update($validatedData);
        return response()->json($pegawai);
    }

    //DELETE api/pegawai/{id}
    public function destroy(string $id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->delete();
        return response()->json(null, 204);
    }
}
