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
                  data-bs-target="#formModalAdminMeeting">
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
                    <a href="{{ route('admin.tugas.edit', $task->id_tugas) }}" 
                       class="btn btn-sm btn-warning" title="Edit Tugas">
                      <i class="bx bx-edit"></i>
                    </a>
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
@endsection
