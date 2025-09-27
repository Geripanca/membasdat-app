<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\Application;

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
            'deadline' => 'nullable|date',
            'publish_at' => 'nullable|date',
        ]);

        $data = $request->only(['judul', 'deskripsi', 'deadline', 'publish_at']);

        if ($request->hasFile('file')) {
            $data['file'] = $request->file('file')->store('tugas', 'public');
        }

        Tugas::create($data);

        return redirect()->route('tugas.index')->with('success', 'Tugas berhasil dibuat');
    }

    // Detail tugas
    public function show(Tugas $tuga)
    {
        return view('tugas.show', compact('tuga'));
    }

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

        $tuga->update($data);

        return redirect()->route('tugas.index')->with('success', 'Tugas berhasil diperbarui');
    }

    // Hapus tugas
    public function destroy(Tugas $tuga)
    {
        $tuga->delete();
        return redirect()->route('tugas.index')->with('success', 'Tugas berhasil dihapus');
    }
}
