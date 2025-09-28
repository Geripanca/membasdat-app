<?php

namespace App\Http\Controllers;

use App\Models\PengumpulanTugas;
use App\Models\Tugas;
use App\Models\User;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengumpulanTugasController extends Controller
{
    public function show(Tugas $tuga)
{
           $app = Application::all(); 
        $title = 'Edit Tugas';
    // Semua pengumpulan siswa
        $pengumpulans = PengumpulanTugas::with('siswa')
                        ->where('id_tugas', $tuga->id_tugas)
                        ->get();

    // Semua siswa
// Semua siswa
        $siswa = \App\Models\User::where('is_admin', 0)->get();
       $pengumpulans = PengumpulanTugas::with('siswa')
        ->where('id_tugas', $tuga->id_tugas)
        ->get();


// ID siswa yang sudah mengumpulkan
$idsSudah = $pengumpulans->pluck('id_siswa');

// Siswa yang belum mengumpulkan
    $siswaBelum = User::where('is_admin', 0)
                       ->whereNotIn('id', $idsSudah)
                       ->get();

    return view('admin.datatugas.view', compact('tuga', 'pengumpulans', 'siswaBelum','app','title'));
}
    // List pengumpulan per tugas
public function index(Tugas $tuga)
{
    // Ambil semua pengumpulan tugas yang terkait dengan $tuga, sekaligus memuat data siswa
    $pengumpulans = $tuga->pengumpulan()->with('siswa')->get();

    // Kirim ke view admin untuk menampilkan detail tugas
    return view('admin.tugas.show', compact('tuga', 'pengumpulans'));
}


    // Form pengumpulan (untuk siswa)
    public function create(Tugas $tuga)
    {
        return view('pengumpulan.create', compact('tuga'));
    }
    public function edit(Tugas $tuga)
{
        $app = Application::all(); 
        $title = 'Edit Tugas';
    $userId = auth()->id();
    $pengumpulan = $tuga->pengumpulan()->where('id_siswa', $userId)->firstOrFail();
    return view('users.tugas.edit', compact('tuga', 'pengumpulan','app','title'));
}

// Update pengumpulan
public function update(Request $request, Tugas $tuga)
{
    $userId = auth()->id();
    $pengumpulan = $tuga->pengumpulan()->where('id_siswa', $userId)->firstOrFail();

    $request->validate([
        'file' => 'nullable|file|mimes:pdf,docx,txt,zip|max:2048',
        'keterangan' => 'nullable|string',
    ]);

    $data = [
        'keterangan' => $request->keterangan,
        'status' => now()->greaterThan($tuga->deadline) ? 'terlambat' : 'dikumpulkan',
        'submit_at' => now(),
    ];

    if ($request->hasFile('file')) {
        $data['file'] = $request->file('file')->store('pengumpulan', 'public');
    }

    $pengumpulan->update($data);

    return redirect()->route('siswa.tugas.view', $tuga->id_tugas)
                     ->with('success', 'Pengumpulan tugas berhasil diperbarui');
}

    // Simpan pengumpulan
public function store(Request $request, Tugas $tuga)
{
    $userId = auth()->id();

    // Cek apakah siswa sudah submit
    if ($tuga->pengumpulan()->where('id_siswa', $userId)->exists()) {
        return redirect()->route('siswa.tugas.view', $tuga->id_tugas)
                         ->with('error', 'Anda sudah mengumpulkan tugas ini.');
    }

    $request->validate([
        'file' => 'nullable|file|mimes:pdf,docx,txt,zip|max:2048',
        'keterangan' => 'nullable|string',
    ]);

    $data = [
        'id_tugas' => $tuga->id_tugas,
        'id_siswa' => $userId,
        'keterangan' => $request->keterangan,
        'status' => now()->greaterThan($tuga->deadline) ? 'terlambat' : 'dikumpulkan',
        'submit_at' => now(),
    ];

    if ($request->hasFile('file')) {
        $data['file'] = $request->file('file')->store('pengumpulan', 'public');
    }

    PengumpulanTugas::create($data);

    return redirect()->route('siswa.tugas.view', $tuga->id_tugas)
                     ->with('success', 'Tugas berhasil dikumpulkan');
}


    // Nilai pengumpulan (untuk guru/admin)
    public function nilai(Request $request, PengumpulanTugas $pengumpulan)
    {
        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
        ]);

        $pengumpulan->update([
            'nilai' => $request->nilai,
            'status' => 'dinilai',
        ]);

        return back()->with('success', 'Nilai berhasil diberikan');
    }

    // Hapus pengumpulan (opsional, untuk admin)
    public function destroy(PengumpulanTugas $pengumpulan)
    {
        $pengumpulan->delete();
        return back()->with('success', 'Pengumpulan tugas berhasil dihapus');
    }

    //siswa
    // List pengumpulan milik siswa
public function mySubmissions()
{
    $pengumpulans = PengumpulanTugas::with('tugas')
        ->where('id_siswa', Auth::id())
        ->orderBy('submit_at', 'desc')
        ->get();

    return view('users.pengumpulan.index', compact('pengumpulans'));
}

}
