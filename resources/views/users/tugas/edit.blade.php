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
<h3>{{ $tuga->judul }}</h3>

<form action="{{ route('siswa.tugas.kumpul.update', $tuga->id_tugas) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="file">File Tugas (opsional)</label>
        <input type="file" name="file" class="form-control">
        @if ($pengumpulan->file)
            <small class="text-muted">File saat ini: 
                <a href="{{ asset('storage/'.$pengumpulan->file) }}" target="_blank">Lihat</a>
            </small>
        @endif
    </div>

    <div class="mb-3">
        <label for="keterangan">Keterangan (opsional)</label>
        <textarea name="keterangan" class="form-control">{{ old('keterangan', $pengumpulan->keterangan) }}</textarea>
    </div>

    <button type="submit" class="btn btn-success">Update Jawaban</button>
</form>

@endsection
