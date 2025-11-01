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

      <div class="card-body">
        <div class="table-responsive text-nowrap">
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
                <td>{!! Str::limit(strip_tags($step->deskripsi), 60, '...') !!}</td>
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
                <td colspan="6" class="text-center">Data tidak ditemukan!</td>
              </tr>
              @endforelse
            </tbody>
          </table> 
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="formModalAdminSteps" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="{{ route('steps.store', $datapertemuan->id) }}" method="post">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-primary fw-bold">
            Tambah Langkah Pertemuan
          </h5>
          <button type="button" class="btn p-0" data-bs-dismiss="modal">
            <i class="bx bx-x-circle text-danger fs-4"></i>
          </button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Judul Langkah</label>
            <input type="text" name="judul" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea id="deskripsi_step" name="deskripsi" class="form-control" rows="5"></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Materi (opsional)</label>
            <select name="id_materis" class="form-select">
              <option value="">-- Pilih Materi --</option>
              @foreach($materis as $materi)
              <option value="{{ $materi->id }}">{{ $materi->title }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Quiz (opsional)</label>
            <select name="id_quiz" class="form-select">
              <option value="">-- Pilih Quiz --</option>
              @foreach($quizzes as $quiz)
              <option value="{{ $quiz->id }}">{{ $quiz->title }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Tugas (opsional)</label>
            <select name="id_tugas" class="form-select">
            <option value="">-- Pilih Tugas --</option>
            @foreach($tugas as $t)
              <option value="{{ $t->id_tugas }}">{{ $t->judul }}</option>
            @endforeach
          </select>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Tambah</button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- Modal Edit --}}
@foreach($datapertemuan->steps as $step)
<div class="modal fade" id="formModalEditStep{{ $step->id }}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="{{ route('steps.update', [$datapertemuan->id, $step->id]) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-warning fw-bold">Edit Langkah Pertemuan</h5>
          <button type="button" class="btn p-0" data-bs-dismiss="modal">
            <i class="bx bx-x-circle text-danger fs-4"></i>
          </button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Judul Langkah</label>
            <input type="text" name="judul" value="{{ $step->judul }}" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea id="deskripsi_edit_{{ $step->id }}" name="deskripsi" class="form-control" rows="5">{!! $step->deskripsi !!}</textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Materi (opsional)</label>
            <select name="id_materis" class="form-select">
              <option value="">-- Pilih Materi --</option>
              @foreach($materis as $materi)
              <option value="{{ $materi->id }}" {{ $step->id_materis == $materi->id ? 'selected' : '' }}>
                {{ $materi->title }}
              </option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Quiz (opsional)</label>
            <select name="id_quiz" class="form-select">
              <option value="">-- Pilih Quiz --</option>
              @foreach($quizzes as $quiz)
              <option value="{{ $quiz->id }}" {{ $step->id_quiz == $quiz->id ? 'selected' : '' }}>
                {{ $quiz->title }}
              </option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
  <label class="form-label">Tugas (opsional)</label>
  <select name="id_tugas" class="form-select">
    <option value="">-- Pilih Tugas --</option>
    @foreach($tugas as $t)
      <option value="{{ $t->id_tugas }}" {{ $step->id_tugas == $t->id_tugas ? 'selected' : '' }}>
        {{ $t->judul }}
      </option>
    @endforeach
  </select>
</div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-warning">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endforeach

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/trumbowyg@2.29.0/dist/trumbowyg.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/trumbowyg@2.29.0/dist/plugins/upload/trumbowyg.upload.min.js"></script>

<script>
function initTrumbowyg(selector) {
    $(selector).trumbowyg({
        btns: [
            ['viewHTML'],
            ['undo', 'redo'],
            ['formatting'],
            ['strong', 'em', 'del'],
            ['link'],
            ['insertImage'],
            ['upload'], // tombol upload gambar
            ['justifyLeft', 'justifyCenter', 'justifyRight'],
            ['unorderedList', 'orderedList'],
            ['horizontalRule'],
            ['removeformat']
        ],
        plugins: {
            upload: {
                serverPath: '{{ route("upload.image") }}',
                fileFieldName: 'file',
                urlPropertyName: 'file',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }
        },
        autogrow: true
    });
}

$(document).ready(function() {
    // Inisialisasi editor untuk tambah langkah
    initTrumbowyg('#deskripsi_step');

    // Inisialisasi editor untuk semua edit langkah
    @foreach($datapertemuan->steps as $step)
        initTrumbowyg('#deskripsi_edit_{{ $step->id }}');
    @endforeach
});
</script>
@endsection
