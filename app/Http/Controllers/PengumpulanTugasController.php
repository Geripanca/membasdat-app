<?php

namespace App\Http\Controllers;

use App\Models\PengumpulanTugas;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengumpulanTugasController extends Controller
{
    // List pengumpulan per tugas
    public function index(Tugas $tuga)
    {
        $pengumpulans = $tuga->pengumpulan()->with('siswa')->get();
        return view('pengumpulan.index', compact('tuga', 'pengumpulans'));
    }

    // Form pengumpulan (untuk siswa)
    public function create(Tugas $tuga)
    {
        return view('pengumpulan.create', compact('tuga'));
    }

    // Simpan pengumpulan
    public function store(Request $request, Tugas $tuga)
    {
        $request->validate([
            'file' => 'nullable|file|mimes:pdf,docx,txt,zip|max:2048',
            'keterangan' => 'nullable|string',
        ]);

        $data = [
            'id_tugas' => $tuga->id_tugas,
            'id_siswa' => Auth::id(),
            'keterangan' => $request->keterangan,
            'status' => now()->greaterThan($tuga->deadline) ? 'terlambat' : 'dikumpulkan',
            'submit_at' => now(),
        ];

        if ($request->hasFile('file')) {
            $data['file'] = $request->file('file')->store('pengumpulan', 'public');
        }

        PengumpulanTugas::create($data);

        return redirect()->route('tugas.show', $tuga->id_tugas)->with('success', 'Tugas berhasil dikumpulkan');
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
}
