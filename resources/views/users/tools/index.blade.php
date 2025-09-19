@extends('layouts.main.index')

@section('container')
<div class="row">
  <div class="col-12">
    <div class="card shadow">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">ERD Editor</h5>
        <button class="btn btn-sm btn-primary" onclick="toggleFullscreen()">Fullscreen</button>
      </div>
      <div class="card-body" style="height: 80vh;">
        <iframe id="diagram" src="https://erd-editor.io/" 
                style="width: 100%; height: 100%; border: none; border-radius: 8px;">
        </iframe>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
  function toggleFullscreen() {
    const iframe = document.getElementById("diagram");
    if (iframe.requestFullscreen) {
      iframe.requestFullscreen();
    } else if (iframe.mozRequestFullScreen) { // Firefox
      iframe.mozRequestFullScreen();
    } else if (iframe.webkitRequestFullscreen) { // Chrome, Safari, Opera
      iframe.webkitRequestFullscreen();
    } else if (iframe.msRequestFullscreen) { // IE/Edge
      iframe.msRequestFullscreen();
    }
  }
</script>
@endsection
