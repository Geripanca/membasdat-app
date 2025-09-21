<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Application;
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

    public function show(Meeting $dataperteman)
    {
        $dataperteman->load('steps'); // langkah-langkah RBL
        return view('admin.datapertemuan.show', compact('dataperteman'));
    }

    public function edit(Meeting $dataperteman)
    {
        return view('admin.datapertemuan.edit', compact('dataperteman'));
    }

    public function update(Request $request, Meeting $dataperteman)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $dataperteman->update($request->only(['judul','deskripsi']));

        return redirect()->route('datapertemuan.index')->with('success','Pertemuan berhasil diperbarui!');
    }

    public function destroy(Meeting $dataperteman)
    {
        $dataperteman->delete();
        return redirect()->route('datapertemuan.index')->with('success','Pertemuan berhasil dihapus!');
    }
}
