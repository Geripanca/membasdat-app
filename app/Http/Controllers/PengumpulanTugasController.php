<?php

namespace App\Http\Controllers;

use App\Models\PengumpulanTugas;
use App\Models\Tugas;
use App\Models\User;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PengumpulanTugasController extends Controller
{
public function show(Tugas $tugas)
{
    $app = Application::all(); 
    $title = 'Detail Tugas'; 

    $siswaTugas = DB::table('users as u')
        ->leftJoin('pengumpulan_tugas as p', function($join) use ($tugas) {
            $join->on('u.id', '=', 'p.id_siswa')
                 ->where('p.id_tugas', '=', $tugas->id_tugas);
        })
        ->where('u.is_admin', 0)
        ->select(
            'u.id',
            'u.name',
            'u.email',
            'p.id_pengumpulan',
            'p.file',
            'p.keterangan',
            'p.status',
            'p.nilai',
            'p.submit_at'
        )
        ->get();



    return view('admin.datatugas.view', compact('tugas','siswaTugas','app','title'));
}

    public function edit(Tugas $tuga)
    {
        $app = Application::all(); 
        $title = 'Edit Tugas';
        $userId = auth()->id();
        $pengumpulan = $tuga->pengumpulan()->where('id_siswa', $userId)->firstOrFail();
        return view('users.tugas.edit', compact('tuga', 'pengumpulan','app','title'));
    }


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

    public function store(Request $request, Tugas $tuga)
    {
        $userId = auth()->id();
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

    
    public function destroy(PengumpulanTugas $pengumpulan)
    {
        $pengumpulan->delete();
        return back()->with('success', 'Pengumpulan tugas berhasil dihapus');
    }


// public function mySubmissions()
// {
//     $pengumpulans = PengumpulanTugas::with('tugas')
//         ->where('id_siswa', Auth::id())
//         ->orderBy('submit_at', 'desc')
//         ->get();

//     return view('users.pengumpulan.index', compact('pengumpulans'));
// }

}
