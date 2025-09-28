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

<div class="row">
  <div class="col-md-12 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between mb-2">
        <div>
          <button type="button" 
                  class="btn btn-xs btn-dark fw-bold p-2 buttonAddMeeting"
                  data-bs-toggle="modal" 
                  data-bs-target="#formModalAdminTugas">
            <i class='bx bx-joystick fs-6'></i>&nbsp;TAMBAH TUGAS
          </button>
        </div>

        <div>
          <form action="">
            <div class="input-group">
              <input type="search" class="form-control" name="q" id="search"
                style="border: 1px solid #d9dee3;" placeholder="Cari Data Tugas..." autocomplete="off" />
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
                <th class="text-white">Judul Tugas</th>
                <th class="text-white">Deskripsi</th>
                <th class="text-white">File</th>
                <th class="text-white">Deadline</th>
                <th class="text-white">Publish</th>
                <th class="text-white text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($tugas as $index => $task)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ Str::limit($task->judul, 50) }}</td>
                  <td>{{ Str::limit($task->deskripsi, 50) }}</td>
                  <td>
                    @if($task->file)
                      <a href="{{ asset('storage/'.$task->file) }}" target="_blank">Lihat File</a>
                    @else
                      -
                    @endif
                  </td>
<td>{{ $task->deadline ? $task->deadline->format('d M Y H:i') : '-' }}</td>
<td>{{ $task->publish_at ? $task->publish_at->format('d M Y H:i') : '-' }}</td>

                  <td class="text-center">
<button type="button" 
        class="btn btn-sm btn-warning" 
        title="Edit Tugas"
        data-bs-toggle="modal" 
        data-bs-target="#formModalEditTugas{{ $task->id }}">
  <i class="bx bx-edit"></i>
</button>

                    <form action="{{ route('admin.tugas.destroy', $task->id_tugas) }}" method="POST" style="display:inline;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger"
                              onclick="return confirm('Yakin ingin menghapus tugas ini?')">
                        <i class="bx bx-trash"></i>
                      </button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center">Belum ada tugas.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="flash-message" data-add-meeting="@if(session()->has('success')) {{ session('success') }} @endif" ></div>
<!-- Modal Tambah Tugas -->
<div class="modal fade" id="formModalAdminTugas" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="{{ route('admin.tugas.store') }}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="modal-content">
        <div class="modal-header d-flex justify-content-between">
          <h5 class="modal-title text-primary fw-bold">
            Tambah Tugas&nbsp;<i class='bx bx-file-plus fs-5' style="margin-bottom: 1px;"></i>
          </h5>
          <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-dismiss="modal">
            <i class="bx bx-x-circle text-danger fs-4" title="Tutup"></i>
          </button>
        </div>

        <div class="modal-body">
          <!-- Judul Tugas -->
          <div class="mb-3">
            <label for="judul" class="form-label required-label">Judul Tugas</label>
            <input type="text" id="judul" name="judul" value="{{ old('judul') }}"
              class="form-control @error('judul') is-invalid @enderror"
              placeholder="Masukkan judul tugas" required>
            @error('judul')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Deskripsi -->
          <div class="mb-3">
            <label for="deskripsi" class="form-label required-label">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="4"
              class="form-control @error('deskripsi') is-invalid @enderror"
              placeholder="Masukkan deskripsi tugas" required>{{ old('deskripsi') }}</textarea>
            @error('deskripsi')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- File Tugas -->
          <div class="mb-3">
            <label for="file" class="form-label">File (opsional)</label>
            <input type="file" id="file" name="file"
              class="form-control @error('file') is-invalid @enderror" accept=".pdf,.docx,.txt,.zip">
            @error('file')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Deadline -->
          <div class="mb-3">
            <label for="deadline" class="form-label">Deadline (opsional)</label>
<input type="datetime-local" 
       id="deadline" 
       name="deadline"
       class="form-control @error('deadline') is-invalid @enderror" 
       value="{{ $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('Y-m-d\TH:i') : '' }}">
            @error('deadline')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Publish At -->
          <div class="mb-3">
            <label for="publish_at" class="form-label">Publish (opsional)</label>
<input type="datetime-local" 
       id="publish_at" 
       name="publish_at"
       class="form-control @error('publish_at') is-invalid @enderror" 
       value="{{ $task->publish_at ? \Carbon\Carbon::parse($task->publish_at)->format('Y-m-d\TH:i') : '' }}">
            @error('publish_at')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
            <i class='bx bx-share fs-6' style="margin-bottom: 3px;"></i>&nbsp;Batal
          </button>
          <button type="submit" class="btn btn-primary">
            <i class='bx bx-paper-plane fs-6' style="margin-bottom: 3px;"></i>&nbsp;Tambah Tugas
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- Modal Edit Tugas -->
<div class="modal fade" id="formModalEditTugas{{ $task->id }}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="{{ route('admin.tugas.update', $task) }}" method="post" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header d-flex justify-content-between">
          <h5 class="modal-title text-primary fw-bold">
            Edit Tugas&nbsp;<i class='bx bx-edit fs-5' style="margin-bottom: 1px;"></i>
          </h5>
          <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-dismiss="modal">
            <i class="bx bx-x-circle text-danger fs-4" title="Tutup"></i>
          </button>
        </div>

        <div class="modal-body">
          <!-- Judul Tugas -->
          <div class="mb-3">
            <label for="judul{{ $task->id }}" class="form-label required-label">Judul Tugas</label>
            <input type="text" id="judul{{ $task->id }}" name="judul"
              value="{{ old('judul', $task->judul) }}"
              class="form-control @error('judul') is-invalid @enderror"
              placeholder="Masukkan judul tugas" required>
            @error('judul')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Deskripsi -->
          <div class="mb-3">
            <label for="deskripsi{{ $task->id }}" class="form-label required-label">Deskripsi</label>
            <textarea id="deskripsi{{ $task->id }}" name="deskripsi" rows="4"
              class="form-control @error('deskripsi') is-invalid @enderror"
              placeholder="Masukkan deskripsi tugas" required>{{ old('deskripsi', $task->deskripsi) }}</textarea>
            @error('deskripsi')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- File Tugas -->
          <div class="mb-3">
            <label for="file{{ $task->id }}" class="form-label">File (opsional)</label>
            <input type="file" id="file{{ $task->id }}" name="file"
              class="form-control @error('file') is-invalid @enderror" accept=".pdf,.docx,.txt,.zip">
            @if ($task->file)
              <small class="text-muted">File saat ini: <a href="{{ asset('storage/'.$task->file) }}" target="_blank">Lihat</a></small>
            @endif
            @error('file')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Deadline -->
          <div class="mb-3">
            <label for="deadline{{ $task->id }}" class="form-label">Deadline (opsional)</label>
            <input type="datetime-local" 
                   id="deadline{{ $task->id }}" 
                   name="deadline"
                   class="form-control @error('deadline') is-invalid @enderror" 
                   value="{{ old('deadline', $task->deadline ? $task->deadline->format('Y-m-d\TH:i') : '') }}">
            @error('deadline')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Publish At -->
          <div class="mb-3">
            <label for="publish_at{{ $task->id }}" class="form-label">Publish (opsional)</label>
            <input type="datetime-local" 
                   id="publish_at{{ $task->id }}" 
                   name="publish_at"
                   class="form-control @error('publish_at') is-invalid @enderror" 
                   value="{{ old('publish_at', $task->publish_at ? $task->publish_at->format('Y-m-d\TH:i') : '') }}">
            @error('publish_at')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
            <i class='bx bx-share fs-6' style="margin-bottom: 3px;"></i>&nbsp;Batal
          </button>
          <button type="submit" class="btn btn-success">
            <i class='bx bx-save fs-6' style="margin-bottom: 3px;"></i>&nbsp;Update Tugas
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

@endsection
