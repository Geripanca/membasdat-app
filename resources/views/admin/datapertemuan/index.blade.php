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
  <button type="button" 
          class="btn btn-xs btn-dark fw-bold p-2 buttonAddMeeting"
          data-bs-toggle="modal" 
          data-bs-target="#formModalAdminMeeting">
    <i class='bx bx-joystick fs-6'></i>&nbsp;TAMBAH PERTEMUAN
  </button>
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
<button type="button"
    class="btn btn-icon btn-warning btn-sm buttonEditMeeting"
    data-bs-toggle="tooltip"
    data-popup="tooltip-custom"
    data-bs-placement="auto"
    title="Edit Pertemuan"
    data-id="{{ $meeting->id }}"
    data-judul="{{ $meeting->judul }}"
    data-deskripsi="{{ $meeting->deskripsi }}">
    <span class="tf-icons bx bx-edit" style="font-size: 15px;"></span>
</button>


<button type="button"
        class="btn btn-icon btn-danger btn-sm btnDeleteMeeting"
        data-id="{{ $meeting->id }}"
        data-judul="{{ $meeting->judul }}"
        data-url="{{ route('datapertemuan.destroy', $meeting->id) }}"
        title="Hapus Pertemuan">
    <span class="tf-icons bx bx-trash" style="font-size: 15px;"></span>
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

<div class="flash-message" data-add-meeting="@if(session()->has('success')) {{ session('success') }} @endif" ></div>
<!-- Modal Tambah Pertemuan -->
<div class="modal fade" id="formModalAdminMeeting" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="{{ route('datapertemuan.store') }}" method="post" class="modalAdminMeeting">
      @csrf
      <div class="modal-content">
        <div class="modal-header d-flex justify-content-between">
          <h5 class="modal-title text-primary fw-bold">
            Tambah Pertemuan&nbsp;<i class='bx bx-calendar-plus fs-5' style="margin-bottom: 1px;"></i>
          </h5>
          <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-dismiss="modal">
            <i class="bx bx-x-circle text-danger fs-4" title="Tutup"></i>
          </button>
        </div>

        <div class="modal-body">
          <!-- Judul Pertemuan -->
          <div class="row">
            <div class="col mb-3">
              <label for="judul" class="form-label required-label">Judul Pertemuan</label>
              <input type="text" id="judul" name="judul" value="{{ old('judul') }}"
                class="form-control @error('judul') is-invalid @enderror"
                placeholder="Masukkan judul pertemuan" autocomplete="off" required>
              @error('judul')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <!-- Deskripsi -->
          <div class="row">
            <div class="col mb-3">
              <label for="deskripsi" class="form-label required-label">Deskripsi</label>
              <textarea id="deskripsi" name="deskripsi" rows="4"
                class="form-control @error('deskripsi') is-invalid @enderror"
                placeholder="Masukkan deskripsi pertemuan" autocomplete="off" required>{{ old('deskripsi') }}</textarea>
              @error('deskripsi')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
            <i class='bx bx-share fs-6' style="margin-bottom: 3px;"></i>&nbsp;Batal
          </button>
          <button type="submit" class="btn btn-primary">
            <i class='bx bx-paper-plane fs-6' style="margin-bottom: 3px;"></i>&nbsp;Tambah
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- Modal Edit Meeting -->
<div class="modal fade" id="formEditModalMeeting" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
<form action="{{ route('datapertemuan.update', ':id') }}" method="POST" id="formEditMeeting">
      @csrf
      @method('PUT')
      <input type="hidden" name="id_meeting" class="idMeeting">

      <div class="modal-content">
        <div class="modal-header d-flex justify-content-between">
          <h5 class="modal-title text-primary fw-bold">Edit Pertemuan&nbsp;<i class='bx bx-edit-alt fs-5'></i></h5>
          <button type="button" class="btn p-0" data-bs-dismiss="modal">
            <i class="bx bx-x-circle text-danger fs-4" title="Tutup"></i>
          </button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="judulEdit" class="form-label required-label">Judul Meeting</label>
            <input type="text" id="judulEdit" name="judul" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="deskripsiEdit" class="form-label">Deskripsi</label>
            <textarea id="deskripsiEdit" name="deskripsi" rows="4" class="form-control"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
            <i class='bx bx-share'></i>&nbsp;Batal
          </button>
          <button type="submit" class="btn btn-primary">
            <i class='bx bx-save'></i>&nbsp;Update
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- Modal Delete Meeting -->
<div class="modal fade" id="deleteMeetingConfirm" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" id="formDeleteMeeting">
      @csrf
      @method('DELETE')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Konfirmasi Hapus</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p class="deleteMeetingMessage"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Ya, Hapus!</button>
        </div>
      </div>
    </form>
  </div>
</div>



@section('script')
<script src="{{ asset('assets/js/datapertemuan/index.js') }}"></script>
@endsection
@endsection