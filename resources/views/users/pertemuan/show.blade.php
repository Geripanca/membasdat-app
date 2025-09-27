@extends('layouts.main.index')
@section('style')
<style>
  .btn-label-primary {
    color: #fff;
    border-color: rgba(0, 0, 0, 0);
    background: #696cff;
  }

  .btn-label-primary:hover {
    color: #696cff;
    border-color: rgba(0, 0, 0, 0);
    background: rgba(105, 108, 255, 0.16) !important;
  }

  @media screen and (max-width: 575px) {
    .pagination-mobile {
      display: flex;
      justify-content: end;
    }
  }
</style>
@endsection
@section('container')
<div class="container py-3">
  <h4 class="mb-3">{{ $meeting->judul }}</h4>
  <p class="text-muted">{{ $meeting->deskripsi }}</p>

  <hr>

  <h5 class="mt-4">Langkah Pertemuan</h5>

  <div class="accordion" id="accordionSteps">
    @forelse($meeting->steps as $index => $step)
      <div class="accordion-item mb-3">
        <h2 class="accordion-header" id="heading{{ $step->id }}">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#collapse{{ $step->id }}" aria-expanded="false" aria-controls="collapse{{ $step->id }}">
            {{ $index + 1 }}. {{ $step->judul }}
          </button>
        </h2>
        <div id="collapse{{ $step->id }}" class="accordion-collapse collapse"
          aria-labelledby="heading{{ $step->id }}" data-bs-parent="#accordionSteps">
          <div class="accordion-body">
            <p>{{ $step->deskripsi }}</p>

            @if($step->materi)
              <p><i class="bx bx-book"></i> Materi: <strong>{{ $step->materi->title }}</strong></p>
            @endif

            @if($step->quiz)
              <p><i class="bx bx-question-mark"></i> Quiz: <strong>{{ $step->quiz->title }}</strong></p>
            @endif
          </div>
        </div>
      </div>
    @empty
      <p class="text-muted">Belum ada langkah dalam pertemuan ini.</p>
    @endforelse
  </div>
</div>
@endsection
