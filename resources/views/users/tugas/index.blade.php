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
  <h3><strong>Daftar Tugas</strong></h3>
  <p><i>Daftar tugas siswa</i><p>

  <hr>
  <div class="card p-5">
  <div class="d-flex overflow-auto" style="gap: 1rem;">
    @foreach($tugas as $task)
      <div class="card border-4" style="min-width: 250px;">
        <div class="card-body">
          <h5 class="card-title">{{ $task->judul }}</h5>
          <p class="card-text text-truncate" style="max-width: 200px;">{{ $task->deskripsi }}</p>
          <p class="small text-muted mb-1">Deadline: {{ $task->deadline ? $task->deadline->format('d M Y H:i') : '-' }}</p>
          <a href="{{ route('siswa.tugas.view', $task->id_tugas) }}" class="btn btn-primary btn-sm">Lihat Detail</a>
        </div>
      </div>
    @endforeach
  </div>
   </div> 
</div>
@endsection
