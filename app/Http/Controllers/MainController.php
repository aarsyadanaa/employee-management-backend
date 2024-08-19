<?php

namespace App\Http\Controllers;
use App\Models\Pegawai;
use App\Models\Jabatan;
use App\Models\Golongan;
use App\Models\unitKerja;
use App\Post;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

use Illuminate\Http\Request;

class MainController extends Controller
{
    // get all pegawai
    //GET /api/pegawai
    public function index()
    {
        // return Pegawai::with('jabatan', 'golongan', 'unitKerja')->get();
        return Pegawai::with('jabatan', 'golongan', 'unitKerja')->get();
    }
    //POST /api/pegawai
    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'nip' => 'required|unique:pegawai|max:20',
            'nama' => 'required|max:100',
            'tempat_lahir' => 'nullable|max:50',
            'tgl_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:l,p',
            'alamat' => 'nullable|max:255',
            'agama' => 'nullable|max:50',
            'no_hp' => 'nullable|max:20',
            'npwp' => 'nullable|max:20',
            'foto' => 'nullable|image|max:6048',
            'golongan' => 'nullable|max:50',
            'keterangan' => 'nullable|max:50',
            'unit_kerja' => 'nullable|max:200',
            'tempat_tugas' => 'nullable|max:100',
            'jabatan' => 'nullable|max:50',
            'eselon' => 'nullable|max:50',
        ]);
        if($validatedData->fails()){
            return response()->json($validatedData->errors());
        }
        $validatedData = $validatedData->validated();
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filePath = $file->storeAs('public/posts', $file->hashName());
            $validatedData['foto'] = $file->hashName();
        }
        
        // Buat pegawai
        $pegawai = Pegawai::create($validatedData);
        $pegawaiId = $pegawai->id;

        // Golongan
        if (!empty($validatedData['golongan'])) {
            Golongan::create([
                'pegawai_id' => $pegawaiId, 
                'golongan' => $validatedData['golongan'],
                'keterangan' => $validatedData['keterangan'],
            ]);
        }
        
        // Unit Kerja
        if (!empty($validatedData['unit_kerja'])) {
            unitKerja::create([
                'pegawai_id' => $pegawaiId, 
                'nama_unit_kerja' => $validatedData['unit_kerja'],
                'tempat_tugas' => $validatedData['tempat_tugas'],
            ]);
        }
        
        // Jabatan
        if (!empty($validatedData['jabatan'])) {
            Jabatan::create([
                'pegawai_id' => $pegawaiId, 
                'nama_jabatan' => $validatedData['jabatan'],
                'eselon' => $validatedData['eselon'],
            ]);
        }
        return response()->json([
            'message' => 'Pegawai created successfully',
            'data' => $pegawai
        ], 201);       
    }

    //GET api/pegawai/{id}
    public function show(string $id)
    {
        $pegawai = Pegawai::with(['golongan', 'jabatan', 'unitKerja'])->findOrFail($id);
        return response()->json($pegawai);
    }

    //PUT api/pegawai/{id}
    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $validatedData = Validator::make($request->all(), [
            'nip' => 'max:20|unique:pegawai,nip,',
            'nama' => 'max:100',
            'tempat_lahir' => 'nullable|max:50',
            'tgl_lahir' => 'nullable|date',
            'jenis_kelamin' => 'in:L,P',
            'alamat' => 'nullable|max:255',
            'agama' => 'nullable|max:50',
            'no_hp' => 'nullable|max:20',
            'npwp' => 'nullable|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // 'golongan' => 'nullable|max:50',
            // 'keterangan' => 'nullable|max:50',
            // 'nama_unit_kerja' => 'nullable|max:200',
            // 'tempat_tugas' => 'nullable|max:100',
            // 'nama_jabatan' => 'nullable|max:50',
            // 'eselon' => 'nullable|max:50',
        ]);
        if($validatedData->fails()){
            return response()->json($validatedData->errors());
        }
        $validatedData = $validatedData->validated();
        if ($request->hasFile('foto')) {
            if ($pegawai->foto) {
                Storage::disk('public')->delete($pegawai->foto);
            }
            $file = $request->file('foto');
            $filePath = $file->storeAs('public/posts');
            $validatedData['foto'] = $filePath;
        }
        $pegawai->update($validatedData);
        // Golongan
        $golongan = null;
        $keterangan_golongan = null;
        $golongan = $request->golongan;
        $keterangan_golongan = $request->keterangan;
        if($golongan || $keterangan_golongan !== null){
        $golongan = Golongan::where('pegawai_id', $id);
        $golongan = $request->golongan;
        $golongan = $request->keterangan;
        $golongan->save();
        }
        
        // // Unit Kerja
        // $subsetDataUnitKerja = [
        //     'nama_unit_kerja' => $validatedData['nama_unit_kerja'],
        //     'tempat_tugas' => $validatedData['tempat_tugas'],
        // ];
        // $validatedDataUnitKerja = array_merge($subsetDataUnitKerja, ['pegawai_id' => $pegawaiId]);
        // $unitKerja = unitKerja::findOrFail($id);
        // $unitKerja->update($validatedDataUnitKerja);
        
        // // Jabatan
        // $subsetDataJabatan = [
        //     'nama_jabatan' => $validatedData['nama_jabatan'],
        //     'eselon' => $validatedData['eselon'],
        // ];
        // $validatedDataJabatan = array_merge($subsetDataJabatan, ['pegawai_id' => $pegawaiId]);
        // $jabatan = Jabatan::findOrFail($id);
        // $jabatan->update($validatedDataJabatan);

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
