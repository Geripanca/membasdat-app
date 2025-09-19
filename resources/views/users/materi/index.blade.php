@extends('layouts.main.index')
@section('container')

{{-- Section Bacaan --}}
<div class="row">
  <h1 class="fs-5 mt-2">Bacaan</h1>
  <div class="scrolling-wrapper d-flex flex-row flex-nowrap overflow-auto">
    @foreach($materis as $item)
      @if($item->category === 'file' && $item->file)
        <div class="card m-2 shadow-sm border-0" style="min-width: 220px; max-width: 250px;">
          <div class="card-body d-flex flex-column justify-content-between">

            {{-- Ikon dokumen --}}
            <div class="text-center mb-2">
              <i class="bi bi-file-earmark-text text-primary" style="font-size: 3rem;"></i>
            </div>

            {{-- Judul --}}
            <h6 class="card-title text-truncate text-center" title="{{ $item->title }}">
              {{ $item->title }}
            </h6>

            {{-- Label kategori --}}
            <p class="text-muted text-center small mb-3">📄 Bacaan</p>

            {{-- Tombol Download --}}
            <a href="{{ asset('storage/' . $item->file) }}" target="_blank" 
               class="btn btn-outline-primary btn-sm w-100">
              Download
            </a>
          </div>
        </div>
      @endif
    @endforeach
  </div>
</div>

{{-- Section Video --}}
<div class="row mt-4">
  <h1 class="fs-5 mt-3">Video</h1>
  <div class="scrolling-wrapper d-flex flex-row flex-nowrap overflow-auto">
    @foreach($materis as $item)
      @if($item->category === 'video' && $item->video)

        @php
          // ambil video ID dari link youtube
          preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $item->video, $matches);
          $videoId = $matches[1] ?? null;
        @endphp

        @if($videoId)
          <div class="card m-2" style="min-width: 250px;">
            <img src="https://img.youtube.com/vi/{{ $videoId }}/hqdefault.jpg"
                 class="card-img-top"
                 alt="Thumbnail {{ $item->title }}">
            <div class="card-body">
              <h6 class="card-title">{{ $item->title }}</h6>
              <a href="https://www.youtube.com/watch?v={{ $videoId }}" 
                 target="_blank" 
                 class="btn btn-sm btn-danger">
                 ▶ Tonton
              </a>
            </div>
          </div>
        @endif

      @endif
    @endforeach
  </div>
</div>

@endsection

@section('script')
<script src="{{ asset('assets/js/materi/index.js') }}"></script>
@endsection
