<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use App\Models\Petani;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;



class PetaniController extends Controller
{
    //
    

    public function index() {
        $petani = Petani::all();
    return view ('poktan.petani', compact('petani'));
    }

    public function profile() {
        $petani= Auth::guard('petani')->user();
        return view('petani.profile', compact('petani'));
    }

    public function update(Request $request, $nik)
    {
        $request->validate([
            'nik' => 'required',
            'nama' => 'required',
            'wa' => 'required',
            'password' => 'nullable|min:6',
            'kk' => 'nullable|file|mimes:jpg,png,jpeg|max:2048',
            'ktp' => 'nullable|file|mimes:jpg,png,jpeg|max:2048'
        ]);

        $petani = Petani::findOrFail($nik);

        // Update foto KK jika ada file baru
        if ($request->hasFile('kk')) {
            $kkPath = 'assets/doc/kk/' . $petani->kk;
            if ($petani->kk && Storage::disk('public')->exists($kkPath)) {
                Storage::disk('public')->delete($kkPath); // Hapus file lama
            }
            $kk = $request->file('kk');
            $kkName = $nik . '_kk_' . date('YmdH') . '.' . $kk->getClientOriginalExtension();
            $kk->storeAs('assets/doc/kk', $kkName, 'public');        
            $petani->kk = $kkName;
        }

        // Update foto KTP jika ada file baru
        if ($request->hasFile('ktp')) {
            $ktpPath = 'assets/doc/ktp/' . $petani->ktp;
            if ($petani->ktp && Storage::disk('public')->exists($ktpPath)) {
                Storage::disk('public')->delete($ktpPath); // Hapus file lama
            }
            $ktp = $request->file('ktp');
            $ktpName = $nik . '_ktp_' . date('YmdH') . '.' . $ktp->getClientOriginalExtension();
            $ktp->storeAs('assets/doc/ktp', $ktpName, 'public');
            $petani->ktp = $ktpName;
        }

        // Update data lainnya
        $petani->nama = $request->nama;
        $petani->wa = $request->wa;
        if ($request->filled('password')) {
            $petani->password = bcrypt($request->password);
        }
        $petani->update();

        return redirect()->route('petani.profile')->with('success', 'Data petani berhasil diperbarui!');
    }

    public function ktpUpdate(Request $request, $nik, $id_pengajuan)
    {
        $request->validate([
            'ktp' => 'required|file|mimes:jpg,png,jpeg|max:2048'
        ]);

        $petani = Petani::findOrFail($nik);

        if ($request->hasFile('ktp')) {
            $ktpPath = 'assets/doc/ktp/' . $petani->ktp;
            if ($petani->ktp && Storage::disk('public')->exists($ktpPath)) {
                Storage::disk('public')->delete($ktpPath); // Hapus file lama
            }
            $ktp = $request->file('ktp');
            $ktpName = $nik . '_ktp_' . date('YmdH') . '.' . $ktp->getClientOriginalExtension();
            $ktp->storeAs('assets/doc/ktp', $ktpName, 'public');
            $petani->ktp = $ktpName;
        }
        $petani->update();

        $pengajuan = Pengajuan::findOrFail($id_pengajuan);
            $pengajuan->status_validasi=0;
            $pengajuan->update();

        return redirect()->route('pengajuan.index')->with('success', 'Data petani berhasil diperbarui!');
        // return back();
    }

    public function kkUpdate(Request $request, $nik, $id_pengajuan)
    {
        $request->validate([
            'kk' => 'required|file|mimes:jpg,png,jpeg|max:2048'
        ]);

        $petani = Petani::findOrFail($nik);

        if ($request->hasFile('kk')) {
            $kkPath = 'assets/doc/kk/' . $petani->kk;
            if ($petani->kk && Storage::disk('public')->exists($kkPath)) {
                Storage::disk('public')->delete($kkPath); // Hapus file lama
            }
            $kk = $request->file('kk');
            $kkName = $nik . '_kk_' . date('YmdH') . '.' . $kk->getClientOriginalExtension();
            $kk->storeAs('assets/doc/kk', $kkName, 'public');        
            $petani->kk = $kkName;
        }

        $petani->update();
        $pengajuan = Pengajuan::findOrFail($id_pengajuan);
            $pengajuan->status_validasi=0;
            $pengajuan->update();

        return redirect()->route('pengajuan.index')->with('success', 'Data petani berhasil diperbarui!');
    }


    public function destroy(Request $request, $nik)
    {
        $petani = Petani::findOrFail($nik);

        $kkPath = 'assets/doc/kk/' . $petani->kk;
        if ($petani->kk && Storage::disk('public')->exists($kkPath)) {
            Storage::disk('public')->delete($kkPath);
        }
        
         $ktpPath = 'assets/doc/ktp/' . $petani->ktp;
        if ($petani->ktp && Storage::disk('public')->exists($ktpPath)) {
            Storage::disk('public')->delete($ktpPath); // Hapus file lama
        }

        $petani->delete();

        return redirect()->route('petani.show')->with('success', 'Data petani berhasil dihapus!');



    }


    
}
