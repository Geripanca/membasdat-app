<?php

namespace App\Http\Controllers;

use App\Models\StepMeeting;
use App\Models\Meeting;
use App\Models\Materi;
use App\Models\Quiz;
use Illuminate\Http\Request;

class StepMeetingController extends Controller
{
    // Form tambah langkah ke pertemuan tertentu
    public function create(Meeting $meeting)
    {
        $materis = Materi::all();
        $quizzes = Quiz::all();
        return view('admin.steps.create', compact('meeting', 'materis', 'quizzes'));
    }

    // Simpan langkah
    public function store(Request $request, Meeting $meeting)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'id_materis' => 'nullable|exists:materis,id',
            'id_quiz' => 'nullable|exists:quizzes,id',
        ]);

        $meeting->steps()->create([
        'judul'     => $request->judul,
        'deskripsi' => strip_tags($request->deskripsi), // 🔑 buang semua tag HTML
        'id_materis'=> $request->id_materis,
        'id_quiz'   => $request->id_quiz,
        ]);

        return redirect()->route('datapertemuan.show', $meeting->id)->with('success', 'Langkah berhasil ditambahkan.');
    }

    // Edit langkah
    public function edit(Meeting $meeting, StepMeeting $step)
    {
        $materis = Materi::all();
        $quizzes = Quiz::all();
        return view('admin.steps.edit', compact('meeting', 'step', 'materis', 'quizzes'));
    }

    // Update langkah
    public function update(Request $request, Meeting $meeting, StepMeeting $step)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'id_materis' => 'nullable|exists:materis,id',
            'id_quiz' => 'nullable|exists:quizzes,id',
        ]);

    $step->update([
        'judul'     => $request->judul,
        'deskripsi' => strip_tags($request->deskripsi), // 🔑 buang semua tag HTML
        'id_materis'=> $request->id_materis,
        'id_quiz'   => $request->id_quiz,
    ]);
        return redirect()->route('datapertemuan.show', $meeting->id)->with('success', 'Langkah berhasil diperbarui.');
    }

    // Hapus langkah
    public function destroy(Meeting $meeting, StepMeeting $step)
    {
        $step->delete();
        return redirect()->route('datapertemuan.show', $meeting->id)->with('success', 'Langkah berhasil dihapus.');
    }
}
