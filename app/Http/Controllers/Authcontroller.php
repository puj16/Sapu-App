<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Petani;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function login() {
        return view('auth.log');
    }

    public function registrasi() {
        return view('auth.registrasi');
    }

    public function registrasi_petugas(Request $request) {
        // dd($request->all());
        $user = request()->validate([
            'email'=>'required|unique:users',
            'password'=>'required',
            'username'=>'required',
            'foto'=>'required'
        ]);

        $fotoName = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = $request->username.  '.' . $foto->getClientOriginalExtension();
            $foto->storeAs('assets/doc/profile', $fotoName, 'public');        
        }


        $user = new User();
        $user->email = trim($request->email);
        $user->username = trim($request->username);
        $user->password = Hash::make($request->password);
        $user->role = "0";
        $user->foto = $fotoName;
        $user->remember_token = Str::random(50);
        $user->save();

        return redirect('/login')->with('success', 'Register Succesfully');
        // return $fotoName;
    }


    public function registrasi_post(Request $request) {
        // Validasi input dengan pesan error khusus
        $validatedData = $request->validate([
            'nik' => 'required|unique:petani,nik',
            'nama' => 'required|string|max:255',
            'wa' => 'required|string|max:20',
            // 'password' => 'required|string|min:6',
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.unique' => 'NIK sudah terdaftar. Silakan gunakan NIK lain.',
            'nama.required' => 'Nama wajib diisi.',
            'nama.string' => 'Nama harus berupa teks.',
            'nama.max' => 'Nama tidak boleh lebih dari 255 karakter.',
            'wa.required' => 'Nomor WhatsApp wajib diisi.',
            'wa.string' => 'Nomor WhatsApp harus berupa teks.',
            'wa.max' => 'Nomor WhatsApp tidak boleh lebih dari 20 karakter.',
            'password.required' => 'Password wajib diisi.',
            // 'password.string' => 'Password harus berupa teks.',
            // 'password.min' => 'Password harus minimal 6 karakter.'
        ]);
    
        // Simpan ke database
        $petani = new Petani;
        $petani->nik = trim($validatedData['nik']);
        $petani->nama = trim($validatedData['nama']);
        $petani->wa = trim($validatedData['wa']);
        // $petani->password = Hash::make($validatedData['password']);
        $petani->password = Hash::make('123456');
        $petani->kk = "Belum terisi";
        $petani->ktp = "Belum terisi";
        $petani->remember_token = Str::random(50);
        $petani->save();
    
        return redirect()->back()->with('success', 'Registrasi berhasil!');
    }

    public function loginPetani(Request $request){
        $credentials = $request->validate([
            'nik' => 'required|string',
            'password' => 'required|string',
        ]);
        if (Auth::guard('petani')->attempt($credentials)) {
            return redirect()->intended('/dashboard');  }
        return back()->with(['error' => 'NIK atau password salah.'])->withInput();    
    }

    public function login_post(Request $request) {
        if(Auth::attempt([
            'username' => $request->username,
            'password' => $request->password]
        ,true)){
            if(Auth::User()->role == '0'){
                return redirect()->intended('poktan/dashboard');
            }elseif(Auth::User()->role=='1'){
                return redirect()->intended('ppl/dashboard'); 
            }else{
                return back()->with(['error' => 'Bukan Petugas! Coba login sebagai petani.'])->withInput();    }
        }else{
            return redirect()->back()->with(['error' => 'Bukan Petugas! Coba login sebagai petani.'])->withInput();;
        };
    }


    public function logoutPetani(Request $request)
    {
        Auth::guard('petani')->logout(); // Logout dari guard 'petani'
        
        $request->session()->invalidate(); // Hapus session
        $request->session()->regenerateToken(); // Regenerasi token CSRF

        return redirect()->route('petani.landing'); // Redirect ke halaman utama atau login
    }

    public function logout(){
        Auth::logout();
        return redirect(url('/'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'email'=>'required',
            'username'=>'required',
        ]);

        $user = User::findOrFail($id);
        if ($request->hasFile('foto')) {
            $fotoPath = 'assets/doc/profile/' . $user->foto;
            if ($user->foto && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath); // Hapus file lama
            }
            $foto = $request->file('foto');
            $fotoName = $request->username. '_poktan_' . date('YmdH') .  '.' . $foto->getClientOriginalExtension();
            $foto->storeAs('assets/doc/profile', $fotoName, 'public');                
            $user->foto = $fotoName;
        }

        $user->username = $request->username;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->update();

        if(Auth::User()->role == '0'){
            return redirect()->intended('poktan/dashboard')->with('success', 'Data petugas berhasil diperbarui!');;
        }else{
            // echo "gg"; die();
            return redirect()->intended('ppl/dashboard')->with('success', 'Data petugas berhasil diperbarui!');; 
        }
    
    }
}
