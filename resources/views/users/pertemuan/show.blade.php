@extends('layouts.main.index')

@section('style')
<style>
.shadow-step {
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: 0.3s;
}

.shadow-step:hover {
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.scrolling-wrapper {
    overflow-x: auto;
    white-space: nowrap;
    padding-bottom: 1rem;
}

.scrolling-wrapper .card {
    display: inline-block;
}

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
</style>
@endsection

@section('container')
@php use Illuminate\Support\Str; @endphp

<div class="container py-3">
    <h4 class="mb-3">{{ $meeting->judul }}</h4>
    <p class="text-muted">{{ $meeting->deskripsi }}</p>

    <hr>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('users.pertemuan.index') }}" class="text-primary">Pertemuan</a>
            </li>
            <li class="breadcrumb-item active">{{ $meeting->judul }}</li>
        </ol>
    </nav>

    <h5 class="mt-4">Langkah Pertemuan</h5>

    <div class="accordion" id="accordionSteps">
        @forelse($meeting->steps as $index => $step)
            <div class="accordion-item mb-3 shadow-step">
                <h2 class="accordion-header" id="heading{{ $step->id }}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $step->id }}" aria-expanded="false"
                            aria-controls="collapse{{ $step->id }}">
                             {{ $step->judul }}
                    </button>
                </h2>

<div id="collapse{{ $step->id }}" class="accordion-collapse collapse"
     aria-labelledby="heading{{ $step->id }}">
    <div class="accordion-body">
        <div>{!! $step->deskripsi !!}</div>

                        {{-- Materi Step --}}
                        @if($step->materi)
                            <div class="scrolling-wrapper d-flex flex-row flex-nowrap">

                                {{-- PDF --}}
                                @if($step->materi->category === 'file' && $step->materi->file)
                                    <div class="card m-2 shadow-sm border-0" style="min-width: 220px; max-width: 250px;">
                                        <div class="card-body d-flex flex-column justify-content-between">
                                            <div class="text-center mb-2">
                                                <i class="bi bi-file-earmark-text text-primary" style="font-size: 3rem;"></i>
                                            </div>
                                            <h6 class="card-title text-truncate text-center" title="{{ $step->materi->title }}">
                                                {{ $step->materi->title }}
                                            </h6>
                                            <p class="text-muted text-center small mb-3">📄 Bacaan</p>
                                            <a href="{{ asset('storage/' . $step->materi->file) }}" target="_blank"
                                               class="btn btn-outline-primary btn-sm w-100">Download</a>
                                        </div>
                                    </div>

                                {{-- YouTube Video --}}
                                @elseif($step->materi->category === 'video' && $step->materi->video)
                                    @php
                                        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $step->materi->video, $matches);
                                        $videoId = $matches[1] ?? null;
                                    @endphp
                                    @if($videoId)
                                        <div class="card m-2" style="min-width: 250px;">
                                            <img src="https://img.youtube.com/vi/{{ $videoId }}/hqdefault.jpg"
                                                 class="card-img-top"
                                                 alt="Thumbnail {{ $step->materi->title }}">
                                            <div class="card-body">
                                                <h6 class="card-title">{{ $step->materi->title }}</h6>
                                                <a href="https://www.youtube.com/watch?v={{ $videoId }}" target="_blank"
                                                   class="btn btn-sm btn-danger">▶ Tonton</a>
                                            </div>
                                        </div>
                                    @endif
                                @endif

                            </div>
                        @endif
                        
                        {{-- ===================== --}}
                        {{-- Bagian Tugas Step --}}
                        {{-- ===================== --}}
                        @if($step->tugas)
                                <div class="mt-3">
                                    {{-- Tombol menuju halaman tugas --}}
                                    <a href="{{ route('siswa.tugas.view', $step->tugas->id_tugas) }}"
                                        class="d-block p-3 mt-2 rounded bg-light text-decoration-none text-primary fw-semibold shadow-sm"
                                        style="transition: 0.2s;">
                                       📘 Buka Tugas
                                    </a>
                                </div>
                            </div>
                        @endif
                        {{-- Jika step memiliki quiz --}}
{{-- Quiz Step --}}
@if($step->quiz)
    @php
        $quizSlug = $step->quiz->slug ?? null;
    @endphp

    @if($quizSlug)
        <div class="mt-3">
            <a href="{{ route('users.quiz.show', $quizSlug) }}" 
               class="d-block p-3 mt-2 rounded bg-light text-decoration-none text-primary fw-semibold shadow-sm"
               style="transition: 0.2s;">
               🧩 {{ $step->quiz->title }}
            </a>
        </div>
    @endif
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
