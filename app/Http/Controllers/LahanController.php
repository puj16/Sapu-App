<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LahanController extends Controller
{
    //
    public function index() {
        $nik= Auth::guard('petani')->user()->nik;
        $lahan = Lahan::where('add_by', $nik)->get();
        return view ('petani.lahan', compact('lahan'));
    }

    public function store(Request $request)
    {
        // Replace commas with dots to ensure decimal values are correctly processed
        $request->merge([
            'luas' => str_replace(',', '.', $request->luas)
        ]);

        $request->validate([
            'nop' => 'required|string|max:18|unique:lahan,NOP',
            'nik' => 'required|string|max:16',
            'luas' => 'required|numeric|min:0.001',
            'status' => 'required|in:Milik,Sewa,Garapan,Bagi Hasil',
            'sppt' => 'nullable|image|mimes:jpg,jpeg,png|max:2048' // Optional, only if uploaded
        ]);

        // Process file upload if exists
        $spptName = null;
        if ($request->hasFile('sppt')) {
            $sppt = $request->file('sppt');
            $spptName = $request->nop . '_sppt_' . date('YmdH') . '.' . $sppt->getClientOriginalExtension();
            $sppt->storeAs('assets/doc/sppt', $spptName, 'public');        
        }

        // Save data to database
        Lahan::create([
            'NOP' => $request->nop,
            'NIK' => $request->nik,
            'Luas' => $request->luas,
            'status' => $request->status,
            'Foto_SPPT' => $spptName,
            'add_by' => Auth::guard('petani')->user()->nik,
        ]);

        // Redirect with success message
        return redirect()->route('lahan.show')->with('success', 'Data lahan berhasil ditambahkan.');
    }


        public function update(Request $request, $NOP)
        {
            $request->merge([
                'luas' => str_replace(',', '.', $request->luas)
            ]);
            $request->validate([
                'nop' => 'required|string|max:50',
                'nik' => 'required|string|max:16',
                'luas' => 'required|numeric|min:0.001',
                'status' => 'required|in:Milik,Sewa,Garapan,Bagi Hasil',
                'sppt' => 'nullable|image|mimes:jpg,jpeg,png|max:2048' // Opsional, hanya jika diunggah
            ]);

            $lahan = Lahan::findOrFail($NOP);

            if ($request->hasFile('sppt')) {
                $spptPath = 'assets/doc/sppt/' . $lahan->Foto_SPPT;
                if ($lahan->Foto_SPPT && Storage::disk('public')->exists($spptPath)) {
                    Storage::disk('public')->delete($spptPath); // Hapus file lama
                }
                $sppt = $request->file('sppt');
                $spptName = $request->nop . '_sppt_' . date('YmdH') . '.' . $sppt->getClientOriginalExtension();
                $sppt->storeAs('assets/doc/sppt', $spptName, 'public');        
                $lahan->Foto_SPPT = $spptName;
            }

                $lahan->NOP = $request->nop;
                $lahan->NIK = $request->nik;
                $lahan->Luas = $request->luas;
                $lahan->status = $request->status;
                $lahan->add_by= Auth::guard('petani')->user()->nik;

                $lahan->update();

                return redirect()->route('lahan.show')->with('success', 'Data lahan berhasil diperbarui!');
        

 
        }


        public function spptUpdate(Request $request, $NOP, $id_pengajuan)
        {
            $request->validate([
                'sppt' => 'required|image|mimes:jpg,jpeg,png|max:2048' // Opsional, hanya jika diunggah
            ]);

            $lahan = Lahan::findOrFail($NOP);
            

            if ($request->hasFile('sppt')) {
                $spptPath = 'assets/doc/sppt/' . $lahan->Foto_SPPT;
                if ($lahan->Foto_SPPT && Storage::disk('public')->exists($spptPath)) {
                    Storage::disk('public')->delete($spptPath); // Hapus file lama
                }
                $sppt = $request->file('sppt');
                $spptName = $NOP . '_sppt_' . date('YmdH') . '.' . $sppt->getClientOriginalExtension();
                $sppt->storeAs('assets/doc/sppt', $spptName, 'public');        
                $lahan->Foto_SPPT = $spptName;
            }
            $lahan->update();
            $pengajuan = Pengajuan::findOrFail($id_pengajuan);
            $pengajuan->status_validasi=0;
            $pengajuan->update();

            return redirect()->route('pengajuan.index')->with('success', 'Data petani berhasil diperbarui!');

        }

        public function destroy(Request $request, $NOP)
        {
            $lahan = Lahan::findOrFail($NOP);
    
            $spptPath = 'assets/doc/sppt/'. $lahan->Foto_SPPT;
            if ($lahan->Foto_SPPT && Storage::disk('public')->exists($spptPath)) {
                Storage::disk('public')->delete($spptPath); // Hapus file lama
            }else {
                return redirect('/lahan')->with('error', 'SPPT lahan tidak ditemukan!!');
            }
    
            $lahan->delete();
    
            return redirect()->route('lahan.show')->with('success', 'Data lahan berhasil dihapus!');
            // return $spptPath;
        }    
}


