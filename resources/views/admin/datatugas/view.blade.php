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
                <!-- Form beri nilai -->
                <form action="" method="post" class="d-flex">
                    @csrf
                    <input type="number" name="nilai" class="form-control form-control-sm me-2"
                           min="0" max="100" value="{{ $s->nilai }}">
                    <button class="btn btn-sm btn-success">Simpan</button>
                </form>
                @else
                    -
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>@endsection
