<?php

namespace App\Http\Controllers;

use App\Models\Pupuk;
use Illuminate\Http\Request;

class PupukController extends Controller
{
    //
    public function index()  
    {
        $pupuk = Pupuk::all();
        return view('poktan.pupuk',compact('pupuk'));   
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pupuk' => 'required|string|max:255|unique:pupuk,nama_pupuk',
            'harga' => 'required|numeric|min:0',
        ]);

        $harga = str_replace('.', '', $request->harga); // Remove dots before saving

        Pupuk::create([
            'nama_pupuk' => $request->nama_pupuk,
            'harga' => $harga,
        ]);

        return redirect()->route('pupuk.index')->with('success', 'Pupuk berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pupuk' => 'required|string|max:255',
            'harga' => 'required|string',
        ]);

        $harga = str_replace('.', '', $request->harga); // Remove dots before saving

        $pupuk = Pupuk::findOrFail($id);
        $pupuk->update([
            'nama_pupuk' => $request->nama_pupuk,
            'harga' => $harga,
        ]);

        return redirect()->route('pupuk.index')->with('success', 'Pupuk berhasil diperbarui.');
    }


    public function destroy(Request $request, $nik)
    {
        $pupuk = Pupuk::findOrFail($nik);
        if(!$pupuk){
            return redirect()->route('pupuk.index')->with('error', 'Data pupuk tidak ditemukan!');
        }
        try {
           
        $pupuk->delete();
        return redirect()->route('pupuk.index')->with('success', 'Data pupuk berhasil dihapus!');

        } catch (\Throwable $th) {
            //throw $th;
        }



    }

}
