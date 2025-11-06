<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Materi;
use App\Models\Application;

class MateriController extends Controller
{
    public function index()
    {
        return view('admin.datamateri.index', [
            'app' => Application::all(),
            'materis' => Materi::latest()->paginate(10),
            'title' => 'Data Materi'
        ]);
    }

    // users show
    public function show()
    {
        return view('users.materi.index', [
            'app' => Application::all(),
            'materis' => Materi::all(),
            'title' => 'Materi'
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255|string',
            'category' => 'required|in:file,video,url',
            'file' => 'nullable|mimes:pdf,doc,docx,odt,odf|max:5120',
            'video' => 'nullable|url',
            'url' => 'nullable|url'
        ]);

        if ($request->file('file')) {
            $validatedData['file'] = $request->file('file')->store('materi_files');
        }

        Materi::create($validatedData);

        return back()->with('addMateriSuccess', 'Materi berhasil ditambah!');
    }


    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'titleEdit' => 'required|max:255|string',
            'categoryEdit' => 'required|in:file,video,url',
            'fileEdit' => 'nullable|mimes:pdf,doc,docx,odt,odf|max:5120',
            'videoEdit' => 'nullable|url',
            'urlEdit' => 'nullable|url'
        ]);

        $dataUpdate = [
            'title' => $validatedData['titleEdit'],
            'category' => $validatedData['categoryEdit'],
            'video' => $validatedData['videoEdit'] ?? null,
            'url' => $validatedData['urlEdit'] ?? null,
        ];

        if ($request->file('fileEdit')) {
            $dataUpdate['file'] = $request->file('fileEdit')->store('materi_files');
        }

        Materi::where('id', decrypt($request->codeMateri))->update($dataUpdate);

        return back()->with('editMateriSuccess', 'Materi berhasil diupdate!');
    }


    public function destroy(Request $request)
    {
        Materi::destroy(decrypt($request->codeMateri));
        return back()->with('deleteMateriSuccess', 'Materi berhasil dihapus!');
    }

    public function search(Request $request)
    {
        $keyword = $request->q;

        $materis = Materi::latest()
            ->searching($keyword)
            ->paginate(10);

        return view('admin.datamateri.index', [
            'app' => Application::all(),
            'title' => 'Data Materi',
            'materis' => $materis,
            'search' => $keyword
        ]);
    }

}
