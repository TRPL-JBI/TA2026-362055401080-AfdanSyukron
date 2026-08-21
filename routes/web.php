<?php

use App\Http\Controllers\AlatController;
use App\Http\Controllers\DetailPengajuanController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\OrmawaController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Models\Jurusan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
})->name('/');






Route::post('actionlogin', [LoginController::class, 'actionlogin'])->name('actionlogin');


// Route::get('/mahasiswa', function () {
//     return view('mahasiswa.mahasiswa');
// })->name('/mahasiswa');
Route::middleware(['auth'])->group(function () {
    // ----------------------------------------------------
    // Routes untuk Semua Role Terautentikasi
    // ----------------------------------------------------
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/dashboard2', function () {
        $user = auth()->user();
        $role = strtolower($user->role->role ?? '');

        // Mahasiswa hanya melihat pengajuan milik sendiri
        if ($role == 'mahasiswa') {
            $totalPengajuan = App\Models\Pengajuan::where('user_id', $user->id)->count();
        } else {
            $totalPengajuan = App\Models\Pengajuan::count();
        }

        $totalUser = App\Models\User::count();
        $totalMahasiswa = App\Models\Mahasiswa::count();
        $totalAlat = App\Models\Alat::sum('stok');
        $recentSubmissions = App\Models\Pengajuan::with(['mahasiswa', 'details.alat'])
            ->whereNotIn('status', ['finished', 'decline'])
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('admin.dashboard2', compact('totalPengajuan', 'totalUser', 'totalMahasiswa', 'totalAlat', 'recentSubmissions'));
    })->name('dashboard2');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('actionlogout', [LoginController::class, 'actionlogout'])->name('actionlogout');

    // Katalog Alat & Status Pengajuan (Dapat dilihat oleh semua role)
    Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan');
    Route::get('/pengajuan/list', [PengajuanController::class, 'list'])->name('pengajuan.list');
    Route::get('/pengajuan/history', [PengajuanController::class, 'history'])->name('pengajuan.history');
    Route::get('/pengajuan/show/{id}', [PengajuanController::class, 'show'])->name('pengajuan.show');

    // Ajax route get prodi by jurusan
    Route::get('getProdi/{id}', function ($id) {
        $prodi = App\Models\Prodi::where('jurusan_id', $id)->get();
        return response()->json($prodi);
    });

    // Mahasiswa self-edit profile or Staff Admin edit mahasiswa
    Route::middleware(['role:mahasiswa,staff admin,staff humas,admin'])->group(function () {
        Route::get('/mahasiswa/edit/{id}', [MahasiswaController::class, 'edit'])->name('mahasiswa.edit');
        Route::post('/mahasiswa/update/{id}', [MahasiswaController::class, 'update'])->name('mahasiswa.update');
    });

    // ----------------------------------------------------
    // Routes Khusus Mahasiswa
    // ----------------------------------------------------
    Route::middleware(['role:mahasiswa'])->group(function () {
        Route::get('/pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create');
        Route::post('/pengajuan/store', [PengajuanController::class, 'store'])->name('pengajuan.store');
        Route::get('/pengajuan/edit/{id}', [PengajuanController::class, 'edit'])->name('pengajuan.edit');
        Route::post('/pengajuan/update/{id}', [PengajuanController::class, 'update'])->name('pengajuan.update');
        Route::get('/pengajuan/delete/{id}', [PengajuanController::class, 'delete'])->name('pengajuan.delete');
        Route::post('/tambahAlat', [DetailPengajuanController::class, 'tambahAlat'])->name('detailpengajuan.tambahAlat');
        Route::get('/detailpengajuan/delete/{id}', [DetailPengajuanController::class, 'delete'])->name('detailpengajuan.delete');
    });

    // ----------------------------------------------------
    // Routes Khusus Staff Admin & Kepala Humas
    // (Verifikasi, Penolakan, Pengembalian, Laporan Bulanan)
    // ----------------------------------------------------
    Route::middleware(['role:staff admin,staff humas,admin,kepala humas'])->group(function () {
        Route::get('/pengajuan/verif/{id}', [PengajuanController::class, 'verif'])->name('pengajuan.verif');
        Route::get('/pengajuan/decline/{id}', [PengajuanController::class, 'decline'])->name('pengajuan.decline');
        Route::get('/pengajuan/finish/{id}', [PengajuanController::class, 'finish'])->name('pengajuan.finish');
        Route::get('/pengajuan/report-bulanan', [PengajuanController::class, 'reportBulanan'])->name('pengajuan.report');
    });

    // ----------------------------------------------------
    // Routes Khusus Staff Admin & Staff Humas (Kelola Data Master)
    // ----------------------------------------------------
    Route::middleware(['role:staff admin,staff humas,admin'])->group(function () {
        // Data Mahasiswa CRUD
        Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa');
        Route::get('/mahasiswa/create', [MahasiswaController::class, 'create'])->name('mahasiswa.create');
        Route::post('/mahasiswa/store', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
        Route::get('/mahasiswa/delete/{id}', [MahasiswaController::class, 'delete'])->name('mahasiswa.delete');
        Route::post('/mahasiswa/import', [MahasiswaController::class, 'import'])->name('mahasiswa.import');

        // Data Master Jurusan
        Route::get('/jurusan', [JurusanController::class, 'index'])->name('jurusan');
        Route::get('/jurusan/create', [JurusanController::class, 'create'])->name('jurusan.create');
        Route::post('/jurusan/store', [JurusanController::class, 'store'])->name('jurusan.store');
        Route::get('/jurusan/edit/{id}', [JurusanController::class, 'edit'])->name('jurusan.edit');
        Route::post('/jurusan/update/{id}', [JurusanController::class, 'update'])->name('jurusan.update');
        Route::get('/jurusan/delete/{id}', [JurusanController::class, 'delete'])->name('jurusan.delete');

        // Data Master Prodi
        Route::get('/prodi', [ProdiController::class, 'index'])->name('prodi');
        Route::get('/prodi/create', [ProdiController::class, 'create'])->name('prodi.create');
        Route::post('/prodi/store', [ProdiController::class, 'store'])->name('prodi.store');
        Route::get('/prodi/edit/{id}', [ProdiController::class, 'edit'])->name('prodi.edit');
        Route::post('/prodi/update/{id}', [ProdiController::class, 'update'])->name('prodi.update');
        Route::get('/prodi/delete/{id}', [ProdiController::class, 'delete'])->name('prodi.delete');

        // Data Master Ormawa
        Route::get('/ormawa', [OrmawaController::class, 'index'])->name('ormawa');
        Route::get('/ormawa/create', [OrmawaController::class, 'create'])->name('ormawa.create');
        Route::post('/ormawa/store', [OrmawaController::class, 'store'])->name('ormawa.store');
        Route::get('/ormawa/edit/{id}', [OrmawaController::class, 'edit'])->name('ormawa.edit');
        Route::post('/ormawa/update/{id}', [OrmawaController::class, 'update'])->name('ormawa.update');
        Route::get('/ormawa/delete/{id}', [OrmawaController::class, 'delete'])->name('ormawa.delete');

        // Data Master Alat (CRUD & Maintenance)
        Route::get('/alat', [AlatController::class, 'index'])->name('alat');
        Route::get('/alat/create', [AlatController::class, 'create'])->name('alat.create');
        Route::post('/alat/store', [AlatController::class, 'store'])->name('alat.store');
        Route::get('/alat/edit/{id}', [AlatController::class, 'edit'])->name('alat.edit');
        Route::post('/alat/update/{id}', [AlatController::class, 'update'])->name('alat.update');
        Route::get('/alat/delete/{id}', [AlatController::class, 'delete'])->name('alat.delete');
        Route::get('/alat/maintenance/{id}', [AlatController::class, 'maintenance'])->name('alat.maintenance');

        // Data Master Role
        Route::get('/role', [RoleController::class, 'index'])->name('role');
        Route::get('/role/create', [RoleController::class, 'create'])->name('role.create');
        Route::post('/role/store', [RoleController::class, 'store'])->name('role.store');
        Route::get('/role/edit/{id}', [RoleController::class, 'edit'])->name('role.edit');
        Route::post('/role/update/{id}', [RoleController::class, 'update'])->name('role.update');
        Route::get('/role/delete/{id}', [RoleController::class, 'delete'])->name('role.delete');

        // Data Master User
        Route::get('/user', [UserController::class, 'index'])->name('user');
        Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
        Route::post('/user/update/{id}', [UserController::class, 'update'])->name('user.update');
        Route::get('/user/delete/{id}', [UserController::class, 'delete'])->name('user.delete');
    });
});
