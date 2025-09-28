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
<h3>{{ $tugas->judul }}</h3>
<p>{{ $tugas->deskripsi }}</p>
<p><strong>Deadline:</strong> {{ $tugas->deadline ? $tugas->deadline->format('d M Y H:i') : '-' }}</p>

<hr>
<h4>Daftar Siswa</h4>
<div class="table-responsive">
<table class="table table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>File</th>
            <th>Keterangan</th>
            <th>Status</th>
            <th>Submit At</th>
            <th>Nilai</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($siswaTugas as $i => $s)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $s->name }}</td>
            <td>{{ $s->email }}</td>
            <td>
                @if($s->file)
                    <a href="{{ asset('storage/'.$s->file) }}" target="_blank">Lihat</a>
                @else
                    -
                @endif
            </td>
            <td>{{ $s->keterangan ?? '-' }}</td>
            <td>{{ $s->status ? ucfirst($s->status) : 'Belum Mengumpulkan' }}</td>
            <td>{{ $s->submit_at ?? '-' }}</td>
            <td>{{ $s->nilai ?? '-' }}</td>
            <td>
@if($s->id_pengumpulan)
<!-- Modal per siswa -->
    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#nilaiModal{{ $s->id_pengumpulan }}">
        Nilai
    </button>
<div class="modal fade" id="nilaiModal{{ $s->id_pengumpulan }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('pengumpulan.nilai.update', $s->id_pengumpulan) }}" method="post" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Beri Nilai: {{ $s->name }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="number" name="nilai" min="0" max="100" class="form-control" value="{{ $s->nilai }}">
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>


@endsection
