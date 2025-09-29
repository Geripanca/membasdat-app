@extends('layouts.main.index')
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
<div class="card mb-4">
<div class="container py-3">
  <h4 class="mb-3">{{ $title }}</h4>

  <div class="d-flex overflow-auto gap-3 pb-2">
    @forelse($meetings as $meeting)
      <div class="shadow-none border p-2 flex-shrink-0" style="min-width: 280px; max-width: 320px;">
        <div class="card-body">
          <h5 class="card-title text-primary">{{ $meeting->judul }}</h5>
          <p class="card-text text-muted">
            {{ Str::limit($meeting->deskripsi, 100) }}
          </p>
          <a href="{{ route('users.pertemuan.show', $meeting->id) }}" class="btn btn-sm btn-outline-primary">
            Detail
          </a>
        </div>
      </div>
    @empty
      <p class="text-muted">Belum ada pertemuan tersedia.</p>
    @endforelse
  </div>
</div>
</div>
@endsection