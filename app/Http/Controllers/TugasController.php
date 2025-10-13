<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\Application;
use Carbon\Carbon;

use Illuminate\Http\Request;

class TugasController extends Controller
{
    // List semua tugas
    public function index()
    {
        $app = Application::all(); 
        $title = 'Data Tugas';
        $tugas = Tugas::latest()->get();
        return view('admin.datatugas.index', compact('tugas','app','title'));
    }

    // Form buat tugas
    public function create()
    {
        return view('tugas.create');
    }

    // Simpan tugas baru
 public function store(Request $request)
{
    $request->validate([
        'judul' => 'required|string|max:255',
        'deskripsi' => 'required|string',
        'file' => 'nullable|file|mimes:pdf,docx,txt,zip|max:2048',
        // 'deadline' dan 'publish_at' dilewatkan dari validasi date
    ]);

    $data = $request->only(['judul', 'deskripsi', 'deadline', 'publish_at']);

    // Konversi datetime-local ke format MySQL
    if ($request->filled('deadline')) {
        $data['deadline'] = Carbon::parse($request->deadline)->format('Y-m-d H:i:s');
    }

    if ($request->filled('publish_at')) {
        $data['publish_at'] = Carbon::parse($request->publish_at)->format('Y-m-d H:i:s');
    }

    // File
    if ($request->hasFile('file')) {
        $data['file'] = $request->file('file')->store('tugas', 'public');
    }
    Tugas::create($data);

    return redirect()->route('admin.tugas.index')->with('success', 'Tugas berhasil dibuat');
}

    // Detail tugas



    // Form edit tugas
    public function edit(Tugas $tuga)
    {
        return view('tugas.edit', compact('tuga'));
    }

    // Update tugas
    public function update(Request $request, Tugas $tuga)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,docx,txt,zip|max:2048',
            'deadline' => 'nullable|date',
            'publish_at' => 'nullable|date',
        ]);


        $data = $request->only(['judul', 'deskripsi', 'deadline', 'publish_at']);

        if ($request->hasFile('file')) {
            $data['file'] = $request->file('file')->store('tugas', 'public');
        }
        if ($request->filled('deadline')) {
            $data['deadline'] = \Carbon\Carbon::parse($request->deadline)->format('Y-m-d H:i:s');
        }

        if ($request->filled('publish_at')) {
            $data['publish_at'] = \Carbon\Carbon::parse($request->publish_at)->format('Y-m-d H:i:s');
        }
        
        $tuga->update($data);

        return redirect()->route('admin.tugas.index')->with('success', 'Tugas berhasil diperbarui');
    }

    // Hapus tugas
    public function destroy(Tugas $tuga)
    {
        $tuga->delete();
        return redirect()->route('admin.tugas.index')->with('success', 'Tugas berhasil dihapus');
    }

    //siswa
    public function indexSiswa()
    {
    $app = Application::all(); 
        $title = 'Tugas Siswa';
        $tugas = Tugas::where(function ($q) {
            $q->whereNull('publish_at')
            ->orWhere('publish_at', '<=', now());
        })
        ->orderBy('deadline', 'asc')
        ->get();

    return view('users.tugas.index', compact('tugas','app','title'));
    }

public function showSiswa(Tugas $tuga)
{
        $userId = auth()->id();
        $app = Application::all(); 
        $title = 'Tugas Siswa';

            $pengumpulan = $tuga->pengumpulan()
                         ->where('id_siswa', $userId)
                         ->first();

    return view('users.tugas.view', compact('tuga','app','title', 'pengumpulan'));
}

}
