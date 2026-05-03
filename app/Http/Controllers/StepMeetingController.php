<?php

namespace App\Http\Controllers;

use App\Models\StepMeeting;
use App\Models\Meeting;
use App\Models\Materi;
use App\Models\Quiz;
use App\Models\Tugas; 
use Illuminate\Http\Request;

class StepMeetingController extends Controller
{
    // Form tambah langkah ke pertemuan tertentu
    public function create(Meeting $meeting)
    {
        $materis = Materi::all();
        $quizzes = Quiz::all();
        $tugas = Tugas::all();
        return view('admin.steps.create', compact('meeting', 'materis', 'quizzes','tugas'));
    }

    // Simpan langkah
    public function store(Request $request, Meeting $meeting)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'materi_id' => 'nullable|array',
            'materi_id.*' => 'nullable|exists:materis,id',

            'id_quiz' => 'nullable|exists:quizzes,id',
            'id_tugas' => 'nullable|exists:tugas,id_tugas',

        ]);
    $step = $meeting->steps()->create([
        'judul' => $request->judul,
        'deskripsi' => $request->deskripsi,
        'id_quiz' => $request->id_quiz,
        'id_tugas'=> $request->id_tugas,
    ]);

    // Attach materi (pivot)
if (!empty(array_filter($request->materi_id ?? []))) {
    $step->materis()->attach(array_filter($request->materi_id));
}


        return redirect()->route('datapertemuan.show', $meeting->id)->with('success', 'Langkah berhasil ditambahkan.');
    }

    // Edit langkah
    public function edit(Meeting $meeting, StepMeeting $step)
    {
        $materis = Materi::all();
        $quizzes = Quiz::all();
        $tugas = Tugas::all();
        return view('admin.steps.edit', compact('meeting', 'step', 'materis', 'quizzes','tugas'));
    }

    // Update langkah
public function update(Request $request, Meeting $meeting, StepMeeting $step)
{
    $request->validate([
        'judul' => 'required|string|max:255',
        'deskripsi' => 'required|string',

        // FIX: samakan dengan store (array)
        'materi_id' => 'nullable|array',
        'materi_id.*' => 'exists:materis,id',

        'id_quiz' => 'nullable|exists:quizzes,id',
        'id_tugas' => 'nullable|exists:tugas,id_tugas',
    ]);

    // Update data utama step
    $step->update([
        'judul' => $request->judul,
        'deskripsi' => $request->deskripsi,
        'id_quiz' => $request->id_quiz,
        'id_tugas' => $request->id_tugas,
    ]);

    if ($request->has('materi_ids')) {
    $filtered = array_filter($request->materi_ids);

    if (!empty($filtered)) {
        $step->materis()->sync($filtered);
    } else {
        $step->materis()->detach(); 
    }
}
    
    return redirect()
        ->route('datapertemuan.show', $meeting->id)
        ->with('success', 'Langkah berhasil diperbarui.');
        
}
    // Hapus langkah
    public function destroy(Meeting $meeting, StepMeeting $step)
    {
        $step->delete();
        return redirect()->route('datapertemuan.show', $meeting->id)->with('success', 'Langkah berhasil dihapus.');
    }
}
