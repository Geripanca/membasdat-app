<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Application;
use App\Models\StepMeeting;
use App\Models\Materi;
use App\Models\Quiz;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
public function index()
{
    $meetings = Meeting::with('steps')->orderBy('created_at', 'desc')->paginate(10);
    $app = Application::all(); 
    $title = 'Data Pertemuan';

    return view('admin.datapertemuan.index', compact('meetings', 'app', 'title'));
}

    public function create()
    {
        return view('admin.datapertemuan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        Meeting::create($request->only(['judul','deskripsi']));

        return redirect()->route('datapertemuan.index')->with('success','Pertemuan berhasil dibuat!');
    }

public function show(Meeting $datapertemuan)
{
    $app = Application::all(); 
    $title = 'Detail Pertemuan';
    $datapertemuan->load('steps'); 
    $materis = Materi::all();
    $quizzes = Quiz::all();

    return view('admin.datapertemuan.show', compact('datapertemuan', 'app', 'title', 'materis', 'quizzes'));
}

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $meeting = Meeting::findOrFail($id);
        $meeting->update([
            'judul'     => $request->judul,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('datapertemuan.index')
                         ->with('success', 'Meeting berhasil diperbarui!');
    }
public function destroy(Meeting $datapertemuan)
{
    $datapertemuan->delete();
    return redirect()->route('datapertemuan.index')->with('success','Pertemuan berhasil dihapus!');
}

}
