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
<h3>{{ $tuga->judul }}</h3>
<p>{{ $tuga->deskripsi }}</p>
<p><strong>Deadline:</strong> {{ $tuga->deadline ? $tuga->deadline->format('d M Y H:i') : '-' }}</p>

<hr>
<h4>Siswa yang sudah mengumpulkan</h4>
@if($pengumpulans->isEmpty())
    <p>Belum ada siswa yang mengumpulkan.</p>
@else
<table class="table table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>File</th>
            <th>Keterangan</th>
            <th>Status</th>
            <th>Nilai</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pengumpulans as $index => $pengumpulan)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $pengumpulan->siswa->name }}</td>
            <td>
                @if($pengumpulan->file)
                    <a href="{{ asset('storage/'.$pengumpulan->file) }}" target="_blank">Lihat</a>
                @else
                    -
                @endif
            </td>
            <td>{{ $pengumpulan->keterangan ?? '-' }}</td>
            <td>{{ ucfirst($pengumpulan->status) }}</td>
            <td>{{ $pengumpulan->nilai ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

<hr>
<h4>Siswa yang belum mengumpulkan</h4>
@if($siswaBelum->isEmpty())
    <p>Semua siswa sudah mengumpulkan.</p>
@else
<ul>
    @foreach($siswaBelum as $s)
        <li>{{ $s->name }} ({{ $s->email }})</li>
    @endforeach
</ul>
@endif
@endsection
