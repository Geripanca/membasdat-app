@extends('layouts.main.index')
@section('container')
@section('style')
<style>
  .btn-label-primary {
    color: #fff;
    border-color: rgba(0, 0, 0, 0);
    background: #696cff;
  }

  .btn-label-primary:hover {
    color: #696cff;
    border-color: rgba(0, 0, 0, 0);
    background: rgba(105, 108, 255, 0.16) !important;
  }

  @media screen and (max-width: 575px) {
    .pagination-mobile {
      display: flex;
      justify-content: end;
    }
  }
</style>
@endsection
@section('container')

<div class="container mt-4">
<div class="card p-3">
  <h4><strong>{{ $tuga->judul }}</strong></h4>
  <p><i>{{ $tuga->deskripsi }}</i></p>
  <p class="text-muted">Deadline: {{ $tuga->deadline ? $tuga->deadline->format('d M Y H:i') : '-' }}</p>
</div>
  <hr>

@if($pengumpulan)
    <div class="alert alert-success">
        Anda sudah mengumpulkan tugas ini.
    </div>
    <div class="card p-3 mb-2">
        <p><strong>File dikumpulkan:</strong> 
       @if($pengumpulan->file)
           <a href="{{ asset('storage/'.$pengumpulan->file) }}" target="_blank">Lihat</a>
       @else
           Tidak ada file
       @endif
    </p>
    <p><strong>Keterangan:</strong> {{ $pengumpulan->keterangan }}</p>
    <p><strong>Status:</strong> {{ ucfirst($pengumpulan->status) }}</p>
    @if($pengumpulan->nilai)
        <p><strong>Nilai:</strong> {{ $pengumpulan->nilai }}</p>
    @endif
     </div>
    <a href="{{ route('siswa.tugas.kumpul.edit', $tuga->id_tugas) }}" class="btn btn-warning mb-3">
        Edit Jawaban
    </a>
@else
    <form action="{{ route('siswa.tugas.kumpul.store', $tuga->id_tugas) }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="file">File Tugas</label>
            <input type="file" name="file" class="form-control">
        </div>
        <div class="mb-3">
            <label for="keterangan">Keterangan (opsional)</label>
            <textarea name="keterangan" class="form-control"></textarea>
        </div>
   
        <button type="submit" class="btn btn-primary">Kirim Tugas</button>
    </form>
    
@endif

</div>
@endsection
