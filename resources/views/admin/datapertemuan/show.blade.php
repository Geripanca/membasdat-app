@extends('layouts.main.index')

@section('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trumbowyg@2.29.0/dist/ui/trumbowyg.min.css">
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
<nav aria-label="breadcrumb" class="navbreadcrumb ">
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="{{ route('datapertemuan.index') }}" class="text-primary">Data Pertemuan</a>
    </li>
    <li class="breadcrumb-item active">{{ $datapertemuan->judul }}</li>
  </ol>
</nav>
<!-- add dan search -->
<div class="row">
  <div class="col-md-12 col-lg-12 order-2 mb-4">
    <div class="card h-100">
       <div class="card-header d-flex align-items-center justify-content-between mb-2">
<div class="justify-content-start">
  <button type="button" 
          class="btn btn-xs btn-dark fw-bold p-2 buttonAddSteps"
          data-bs-toggle="modal" 
          data-bs-target="#formModalAdminSteps">
    <i class='bx bx-joystick fs-6'></i>&nbsp;TAMBAH LANGKAH
  </button>
</div>
    <div class="justify-content-end">
      <form action="">
        <div class="input-group">
          <input type="search" class="form-control" name="q" id="search"
            style="border: 1px solid #d9dee3;" placeholder="Cari Data Langkah..." autocomplete="off" />
        </div>
      </form>
    </div>
  </div>

  <!-- list pertemuan -->
  <div class="card-body">
    <div class="table-responsive text-nowrap" style="border-radius: 3px;">
      <table class="table table-striped">
        <thead class="table-dark">
          <tr>
            <th class="text-white">No</th>
            <th class="text-white">Judul</th>
            <th class="text-white">Deskripsi</th>
            <th class="text-white">Materi</th>
            <th class="text-white">Quiz</th>
            <th class="text-white text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
      @forelse($datapertemuan->steps as $index => $step)
            <tr>
              <td>{{ $index + 1 }}</td>
              <td>{{ Str::limit($step->judul, 40, '...') }}</td>
              <td>{{ Str::limit($step->deskripsi, 50, '...') }}</td>
              <td>{{ $step->materi?->title ?? '-' }}</td>
              <td>{{ $step->quiz?->title ?? '-' }}</td>
              <td class="text-center">
<button type="button" class="btn btn-sm btn-warning"
        data-bs-toggle="modal"
        data-bs-target="#formModalEditStep{{ $step->id }}">
    <i class="bx bx-edit"></i>
</button>

                <form action="{{ route('steps.destroy', [$datapertemuan->id, $step->id]) }}" 
                      method="POST" style="display:inline;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger" 
                          onclick="return confirm('Yakin hapus langkah ini?')">
                    <i class="bx bx-trash"></i>
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
</div>
<div class="flash-message" data-add-steps="@if(session()->has('success')) {{ session('success') }} @endif" ></div>
{{-- modal create --}}
<div class="modal fade" id="formModalAdminSteps" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="{{ route('steps.store', $datapertemuan->id) }}" method="post" class="modalStepMeeting">
      @csrf
      <div class="modal-content">
        <div class="modal-header d-flex justify-content-between">
          <h5 class="modal-title text-primary fw-bold">
            Tambah Langkah Pertemuan&nbsp;<i class='bx bx-list-plus fs-5' style="margin-bottom: 1px;"></i>
          </h5>
          <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-dismiss="modal">
            <i class="bx bx-x-circle text-danger fs-4" title="Tutup"></i>
          </button>
        </div>

        <div class="modal-body">
          <!-- Judul Step -->
          <div class="row">
            <div class="col mb-3">
              <label for="judul_step" class="form-label required-label">Judul Langkah</label>
              <input type="text" id="judul_step" name="judul" value="{{ old('judul') }}"
                class="form-control @error('judul') is-invalid @enderror"
                placeholder="Masukkan judul langkah" autocomplete="off" required>
              @error('judul')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <!-- Deskripsi -->
        <div class="row">
            <div class="col mb-3">
                <label for="deskripsi_step" class="form-label required-label">Deskripsi</label>
                <textarea id="deskripsi_step" name="deskripsi" rows="3"
                class="form-control @error('deskripsi') is-invalid @enderror"
                placeholder="Masukkan deskripsi langkah" autocomplete="off" required>{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

          <!-- Pilih Materi -->
          <div class="row">
            <div class="col mb-3">
              <label for="id_materis" class="form-label">Materi (opsional)</label>
              <select id="id_materis" name="id_materis" class="form-select">
                <option value="">-- Pilih Materi --</option>
                @foreach($materis as $materi)
                  <option value="{{ $materi->id }}">{{ $materi->title }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <!-- Pilih Quiz -->
          <div class="row">
            <div class="col mb-3">
              <label for="id_quiz" class="form-label">Quiz (opsional)</label>
              <select id="id_quiz" name="id_quiz" class="form-select">
                <option value="">-- Pilih Quiz --</option>
                @foreach($quizzes as $quiz)
                  <option value="{{ $quiz->id }}">{{ $quiz->title }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
            <i class='bx bx-share fs-6' style="margin-bottom: 3px;"></i>&nbsp;Batal
          </button>
          <button type="submit" class="btn btn-primary">
            <i class='bx bx-paper-plane fs-6' style="margin-bottom: 3px;"></i>&nbsp;Tambah Step
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
{{-- modal edit --}}
{{-- modal edit --}}
@foreach($datapertemuan->steps as $step)
<div class="modal fade" id="formModalEditStep{{ $step->id }}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="{{ route('steps.update', [$datapertemuan->id, $step->id]) }}" method="POST" class="modalEditStepMeeting">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header d-flex justify-content-between">
          <h5 class="modal-title text-warning fw-bold">
            Edit Langkah Pertemuan&nbsp;<i class='bx bx-edit fs-5' style="margin-bottom: 1px;"></i>
          </h5>
          <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-dismiss="modal">
            <i class="bx bx-x-circle text-danger fs-4" title="Tutup"></i>
          </button>
        </div>

        <div class="modal-body">
          <!-- Judul Step -->
          <div class="row">
            <div class="col mb-3">
              <label for="judul_step_{{ $step->id }}" class="form-label required-label">Judul Langkah</label>
              <input type="text" id="judul_step_{{ $step->id }}" name="judul"
                value="{{ old('judul', $step->judul) }}"
                class="form-control @error('judul') is-invalid @enderror"
                placeholder="Masukkan judul langkah" autocomplete="off" required>
              @error('judul')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <!-- Deskripsi -->
          <div class="row">
            <div class="col mb-3">
              <label for="deskripsi_step_{{ $step->id }}" class="form-label required-label">Deskripsi</label>
              <textarea id="deskripsi_step_{{ $step->id }}" name="deskripsi" rows="3"
                class="form-control @error('deskripsi') is-invalid @enderror"
                placeholder="Masukkan deskripsi langkah" autocomplete="off" required>{{ old('deskripsi', $step->deskripsi) }}</textarea>
              @error('deskripsi')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <!-- Pilih Materi -->
          <div class="row">
            <div class="col mb-3">
              <label for="id_materis_{{ $step->id }}" class="form-label">Materi (opsional)</label>
              <select id="id_materis_{{ $step->id }}" name="id_materis" class="form-select">
                <option value="">-- Pilih Materi --</option>
                @foreach($materis as $materi)
                  <option value="{{ $materi->id }}" {{ $step->id_materis == $materi->id ? 'selected' : '' }}>
                    {{ $materi->title }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>

          <!-- Pilih Quiz -->
          <div class="row">
            <div class="col mb-3">
              <label for="id_quiz_{{ $step->id }}" class="form-label">Quiz (opsional)</label>
              <select id="id_quiz_{{ $step->id }}" name="id_quiz" class="form-select">
                <option value="">-- Pilih Quiz --</option>
                @foreach($quizzes as $quiz)
                  <option value="{{ $quiz->id }}" {{ $step->id_quiz == $quiz->id ? 'selected' : '' }}>
                    {{ $quiz->title }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
            <i class='bx bx-share fs-6'></i>&nbsp;Batal
          </button>
          <button type="submit" class="btn btn-warning">
            <i class='bx bx-save fs-6'></i>&nbsp;Update Step
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
@endforeach

@section('script')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/trumbowyg@2.29.0/dist/trumbowyg.min.js"></script>
<script>
$('#deskripsi_step').trumbowyg();
</script>
<script src="{{ asset('assets/js/datapertemuan/show.js') }}"></script>
@endsection
@endsection