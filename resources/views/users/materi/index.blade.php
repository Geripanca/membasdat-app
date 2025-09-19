@extends('layouts.main.index')
@section('container')

<div class="row">
  <h1 class="fs-5 mt-2">Bacaan</h1>
  <div class="scrolling-wrapper d-flex flex-row flex-nowrap overflow-auto">
    @foreach($materis as $item)
      @if($item->category === 'file' && $item->file)
        <div class="card m-2" style="min-width: 200px;">
          <div class="card-body">
            <h5 class="card-title">{{ $item->title }}</h5>
            <a href="{{ asset('storage/' . $item->file) }}" target="_blank" class="btn btn-primary btn-sm">
              Lihat / Download
            </a>
          </div>
        </div>
      @endif
    @endforeach
  </div>
</div>

<div class="row mt-4">
  <h1 class="fs-5 mt-3">Video</h1>
  <div class="scrolling-wrapper d-flex flex-row flex-nowrap overflow-auto">
    @foreach($materis as $item)
      @if($item->category === 'video' && $item->video)
        <div class="card m-2" style="min-width: 250px;">
          <div class="ratio ratio-16x9">
            <iframe src="{{ $item->video }}" frameborder="0" allowfullscreen></iframe>
          </div>
          <div class="card-body">
            <h6 class="card-title">{{ $item->title }}</h6>
            <a href="{{ $item->video }}" target="_blank" class="btn btn-sm btn-primary">Kunjungi Link</a>
          </div>
        </div>
      @endif
    @endforeach
  </div>
</div>

@endsection

@section('script')
<script src="{{ asset('assets/js/materi/index.js') }}"></script>
@endsection
