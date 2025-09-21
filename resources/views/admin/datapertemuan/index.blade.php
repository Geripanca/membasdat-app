@extends('layouts.main.index')

@section('style')
<style>
  ::-webkit-scrollbar { display: none; }

  .card-hover:hover {
    border-color: #5e72e4;
    box-shadow: 0 4px 12px rgba(0,0,0,.1);
    transition: .2s;
  }

  .truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
</style>
@endsection

@section('container')
  <!-- {{-- Flash Message --}}
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif -->
<div class="row">
  <div class="col-md-12 col-lg-12 order-2 mb-4">
    <div class="card h-100">
       <div class="card-header d-flex align-items-center justify-content-between mb-2">
    <div class="justify-content-start">
      <a href="{{ route('datapertemuan.create') }}" class="btn btn-xs btn-dark fw-bold p-2">
        <i class='bx bx-joystick fs-6'></i>&nbsp;TAMBAH PERTEMUAN
      </a>
    </div>
    <div class="justify-content-end">
      <form action="">
        <div class="input-group">
          <input type="search" class="form-control" name="q" id="search"
            style="border: 1px solid #d9dee3;" placeholder="Cari Data Pertemuan..." autocomplete="off" />
        </div>
      </form>
    </div>
  </div>

  {{-- List Pertemuan --}}
  <div class="card-body">
    <div class="table-responsive text-nowrap" style="border-radius: 3px;">
      <table class="table table-striped">
        <thead class="table-dark">
          <tr>
            <th class="text-white">No</th>
            <th class="text-white">Judul Pertemuan</th>
            <th class="text-white">Deskripsi</th>
            <th class="text-white text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @forelse($meetings as $index => $meeting)
          <tr>
            <td>{{ $meetings->firstItem() + $index }}</td>
            @if (preg_match("/[\x{0000}-\x{007F}]/u", $meeting->judul))
              <td>{{ Str::limit($meeting->judul, 40, '...') }}</td>
            @else
              <td style="font-size: 18px;">{{ Str::limit($meeting->judul, 31, '...') }}</td>
            @endif
            <td>{{ Str::limit($meeting->deskripsi, 50, '...')}}</td>
            <td class="text-center">
              <a href="{{ route('datapertemuan.show', $meeting->id) }}" 
                 class="btn btn-icon btn-primary btn-sm" data-bs-toggle="tooltip" title="Detail Pertemuan">
                <span class="tf-icons bx bx-show" style="font-size: 15px;"></span>
              </a>
              <a href="{{ route('datapertemuan.edit', $meeting->id) }}" 
                 class="btn btn-icon btn-warning btn-sm" data-bs-toggle="tooltip" title="Edit Pertemuan">
                <span class="tf-icons bx bx-edit" style="font-size: 15px;"></span>
              </a>
              <form action="{{ route('datapertemuan.destroy', $meeting->id) }}" method="POST" class="d-inline">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-icon btn-danger btn-sm" 
                        data-bs-toggle="tooltip" title="Hapus Pertemuan"
                        onclick="return confirm('Yakin hapus pertemuan ini?')">
                  <span class="tf-icons bx bx-trash" style="font-size: 14px;"></span>
                </button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="4" class="text-center">Data tidak ditemukan!</td>
          </tr>
          @endforelse
        </tbody>
      </table> 
    </div>
  </div>
</div>

    {{-- Pagination --}}
    @if($meetings->lastPage() > 1)
      <div class="d-flex justify-content-center mt-3">
        {{ $meetings->links('pagination::bootstrap-5') }}
      </div>
    @endif
  </div>
</div>
@endsection
