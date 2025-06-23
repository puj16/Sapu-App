<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LahanController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\PenyaluranController;
use App\Http\Controllers\PetaniController;
use App\Http\Controllers\PupukController;
use App\Http\Controllers\PushNotificationController;
use App\Http\Controllers\RdkkController;
use App\Http\Controllers\StokController;
use App\Models\Stok;

Route::get('/log', function () {
    return view('auth/log');
});

Route::get('/dashboard-poktan', function () {
    return view('poktan/dashboard');
});

Route::get('/berkas', function () {
    return view('petani/berkasajuan');
});


Route::get('/', [DashboardController::class, 'landing'])->name('petani.landing');

Route::get('registrasi', [AuthController::class, 'registrasi'])->name('registrasi');
Route::post('registrasi_petugas', [AuthController::class, 'registrasi_petugas'])->name('registrasi_petugas');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('loginPetani', [AuthController::class, 'loginPetani'])->name('loginPetani');
Route::post('login_post', [AuthController::class, 'login_post'])->name('login_post');
Route::post('/logoutPetani', [AuthController::class, 'logoutPetani'])->name('petani.logout');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');


Route::post('save-push-notification-sub', [PushNotificationController::class, 'saveSubscription']);
Route::post('send-push-notification', [PushNotificationController::class, 'sendNotification']);


Route::group(['middleware'=>'petugas_poktan'], function(){
    Route::get('poktan/dashboard',[DashboardController::class,'dashboard']);
    Route::put('/petugasPoktan/{id}', [AuthController::class, 'update'])->name('petugasPoktan.update');
    Route::get('/data-petani', [PetaniController::class, 'index'])->name('petani.show');;
    Route::post('registrasi_post', [AuthController::class, 'registrasi_post'])->name('registrasi_post');
    Route::delete('/petaniDestroy/{nik}', [PetaniController::class, 'destroy'])->name('petani.destroy');
    Route::get('/pupuk-index', [PupukController::class, 'index'])->name('pupuk.index');
    Route::post('/pupuk-store', [PupukController::class, 'store'])->name('pupuk.store');
    Route::put('/pupuk/{id}', [PupukController::class, 'update'])->name('pupuk.update');
    Route::delete('/pupukDestroy/{id}', [PupukController::class, 'destroy'])->name('pupuk.destroy');

    Route::get('/pengajuan-show', [PengajuanController::class, 'show'])->name('pengajuan.show');
    Route::put('/pengajuan-validasi/{id_pengajuan}', [PengajuanController::class, 'validasi'])->name('pengajuan.validasi');
    Route::post('/pengajuan-cekberkas/{id_pengajuan}', [PengajuanController::class, 'cekBerkas'])->name('pengajuan.cek-berkas');
    Route::get('/pengajuan-report', [PengajuanController::class, 'report'])->name('pengajuan.report');
  
    Route::get('/rdkk-index', [RdkkController::class, 'index'])->name('rdkk.index');
    Route::get('/rdkk-add', [RdkkController::class, 'add'])->name('rdkk.add');
    Route::post('/rdkk-store', [RdkkController::class, 'store'])->name('rdkk.store');
    Route::put('/rdkk/{nik}', [RdkkController::class, 'updatepernik'])->name('rdkk.update');
    Route::put('/rdkk-update1', [RdkkController::class, 'update'])->name('rdkk.update1');
    Route::delete('/rdkkDestroy', [RdkkController::class, 'destroy'])->name('rdkk.destroy');
    Route::get('/rdkk-report', [RdkkController::class, 'report'])->name('rdkk.report');
    Route::get('/rdkk-export', [RdkkController::class, 'export'])->name('rdkk.export');
    // Route::get('/cari-rdkk', [PenyaluranController::class, 'cari']);
    Route::get('/penyaluran-index', [PenyaluranController::class, 'index'])->name('penyaluran.index');
    Route::get('/penyaluran-stok', [StokController::class, 'index'])->name('penyaluran.stok');    
    Route::post('/stok-store', [StokController::class, 'store'])->name('stok.store');
    Route::put('/stok-sync', [StokController::class, 'sync'])->name('stok.sync');
    Route::delete('/stok-destroy', [StokController::class, 'destroy'])->name('stok.destroy');
    Route::get('/penyaluran-lap', [PenyaluranController::class, 'laporan'])->name('penyaluran.lap');
    Route::post('/penyaluran-store', [PenyaluranController::class, 'store'])->name('penyaluran.store');
    Route::get('/penyalurandetail', [PenyaluranController::class, 'detailpenyaluran'])->name('detailpenyaluran');
    Route::get('/penyaluran-konfirmasi', [PenyaluranController::class, 'konfirmasi'])->name('penyaluran.konfirmasi');
    Route::get('/penyaluran-berhasil', [PenyaluranController::class, 'berhasil'])->name('penyaluran.berhasil');
    Route::get('/penyaluran-gagal', [PenyaluranController::class, 'gagal'])->name('penyaluran.gagal');
    Route::get('/penyaluran-report', [PenyaluranController::class, 'report'])->name('penyaluran.report');
    Route::get('/penyaluran-reports', [PenyaluranController::class, 'reports'])->name('penyaluran.reports');
    Route::get('/penyaluran-info', [PenyaluranController::class, 'info'])->name('penyaluran.info');
});
Route::group(['middleware'=>'petugas_ppl'], function(){
    Route::get('ppl/dashboard',[DashboardController::class,'dashboard'])->name('dashboard.ppl');
    Route::put('/petugasPPL/{id}', [AuthController::class, 'update'])->name('petugasPPL.update');

    Route::get('/show-pengajuan', [PengajuanController::class, 'show'])->name('show.pengajuan');
    Route::post('/cekberkas-pengajuan/{id_pengajuan}', [PengajuanController::class, 'cekBerkas'])->name('cek-berkas.pengajuan');
    Route::get('/report-pengajuan', [PengajuanController::class, 'report'])->name('report.pengajuan');

    Route::get('/index-rdkk', [RdkkController::class, 'index'])->name('index.rdkk');
    Route::get('/report-rdkk', [RdkkController::class, 'report'])->name('report.rdkk');
    Route::get('/export-rdkk', [RdkkController::class, 'export'])->name('export.rdkk');

    Route::get('/index-penyaluran', [PenyaluranController::class, 'index'])->name('index.penyaluran');
    Route::get('/detailpenyaluran', [PenyaluranController::class, 'detailpenyaluran'])->name('detail.penyaluran');
    Route::get('/report-penyaluran', [PenyaluranController::class, 'report'])->name('report.penyaluran');
    Route::get('/reports-penyaluran', [PenyaluranController::class, 'reports'])->name('reports.penyaluran');
    Route::get('/info-penyaluran', [PenyaluranController::class, 'info'])->name('info.penyaluran');
    Route::get('/lap-penyaluran', [PenyaluranController::class, 'laporan'])->name('lap.penyaluran');

});

Route::middleware(['petani'])->group(function () {
    Route::get('/dashboard',[DashboardController::class,'dashboardpetani'])->name('dashboard');
    Route::get('/petani-profile', [PetaniController::class, 'profile'])->name('petani.profile');
    Route::put('/petani/{nik}', [PetaniController::class, 'update'])->name('petani.update');
    Route::put('/ktp/{nik}/{id_pengajuan}', [PetaniController::class, 'ktpUpdate'])->name('ktp.update');
    Route::put('/kk/{nik}/{id_pengajuan}', [PetaniController::class, 'kkUpdate'])->name('kk.update');
    Route::get('/lahan', [LahanController::class, 'index'])->name('lahan.show');
    Route::post('/lahan-store', [LahanController::class, 'store'])->name('lahan.store');
    Route::put('/lahan/{NOP}', [LahanController::class, 'update'])->name('lahan.update');
    Route::put('/sppt/{NOP}/{id_pengajuan}', [LahanController::class, 'spptUpdate'])->name('sppt.update');
    Route::delete('/lahanDestroy/{NOP}', [LahanController::class, 'destroy'])->name('lahan.destroy');
    Route::get('/pengajuan-index', [PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::post('/pengajuan-store', [PengajuanController::class, 'store'])->name('pengajuan.store');
    Route::put('/pengajuan/{id_pengajuan}', [PengajuanController::class, 'update'])->name('pengajuan.update');
    Route::delete('/pengajuanDestroy/{id_pengajuan}', [PengajuanController::class, 'destroy'])->name('pengajuan.destroy');
    Route::post('/pengajuan-berkas/{id_pengajuan}', [PengajuanController::class, 'detailBerkas'])->name('pengajuan.berkas');
    Route::get('/penyaluran-show', [PenyaluranController::class, 'show'])->name('penyaluran.show');
    Route::get('/penyaluran-detail', [PenyaluranController::class, 'detail'])->name('penyaluran.detail');
    Route::post('/penyaluran/{id}/bayar-online', [PenyaluranController::class, 'bayaronline'])->name('penyaluran.bayaronline');
    Route::get('/penyaluran-bayar/sukses/{id}', [PenyaluranController::class, 'bayarsukses'])->name('penyaluran.bayarsukses');
});


