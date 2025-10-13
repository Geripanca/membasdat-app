@extends('layouts.main.index')
@section('container')
@section('style')
<style>
  @media screen and (max-width: 1320px) {
    ::-webkit-scrollbar {
      display: none;
    }

    .navbreadcrumb {
      font-size: 14px;
    }
  }

  @media screen and (min-width: 1320px) {
    .navbreadcrumb {
      font-size: 14px;
    }
  }

  @media screen and (max-width: 576px) {
    .navbreadcrumb {
      font-size: small;
    }
  }

  .answer {
    word-wrap: break-word;
    white-space: normal;
    display: block;
  }
  .nav-btn {
  transition: all 0.2s ease-in-out;
}

.nav-btn.active {
  background-color: #ffc107 !important; /* 🟨 kuning (aktif) */
  border-color: #ffc107 !important;
  color: white;
}

.nav-btn.answered {
  background-color: #198754 !important; /* 🟩 hijau (sudah dijawab) */
  border-color: #198754 !important;
  color: white;
}

.nav-btn:not(.active):not(.answered):hover {
  background-color: #0d6efd !important;
  color: white;
}

</style>
@endsection
<div class="card">
  <div class="card-body">
   <div class="d-flex justify-content-between align-items-center mb-2">
  <nav aria-label="breadcrumb" class="navbreadcrumb">
    <ol class="breadcrumb mb-0">
      <li class="breadcrumb-item">
        <a href="/quiz" class="text-primary">Quiz</a>
      </li>
      <li class="breadcrumb-item active">{{ $titleQuiz }}</li>
    </ol>
  </nav>

  <!-- Kumpulan ikon di kanan -->
  <div class="d-flex align-items-center gap-2">
    <!-- Tombol Petunjuk -->
    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="modal" data-bs-target="#petunjukQuiz">
      <i class="bx bx-bulb text-primary mb-3 iconPetunjukQuiz" 
         data-bs-toggle="tooltip" 
         data-popup="tooltip-custom" 
         data-bs-placement="auto" 
         title="Petunjuk Quiz" 
         style="font-size: 20px;"></i>
    </button>

    <!-- Tombol Navigasi -->
    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="modal" data-bs-target="#navigasiQuiz">
      <i class="bx bx-grid-alt text-primary mb-3" 
         data-bs-toggle="tooltip" 
         data-popup="tooltip-custom" 
         data-bs-placement="auto" 
         title="Navigasi Soal" 
         style="font-size: 20px;"></i>
    </button>
  </div>
</div>

    <div class="flash-message" data-flash-message="@if(session()->has('messages')) {{ session('messages') }} @endif"></div>
    <form action="/quiz" method="post" id="quizForm">
      @csrf
      <input type="hidden" name="quizCode" value="{{ encrypt($codeQuiz) }}">
      <input type="hidden" name="bubblesmart" value="{{ encrypt($bubblesmart) }}">
@foreach ($quizzes as $index => $quiz) {{-- Hapus shuffle() --}}
<div class="question-item" data-index="{{ $index }}" style="display: {{ $index === 0 ? 'block' : 'none' }};">
  <div class="d-flex">
    <div>{{ $index + 1 }}.</div>
    <p style="font-size: 1rem; margin-left: 10px;">{{ $quiz->question }}</p>
  </div>
  <ol type="A" style="margin-top: -10px; margin-left: 10px; font-size: 1rem;">
    @foreach($quiz->answer as $answer) {{-- Hapus shuffle() --}}
    <div class="d-flex">
      <li>
        <input type="radio" id="{{ $answer->id }}" name="answer[{{ $quiz->id }}]" value="{{ encrypt($answer->id) }}" class="form-check-input" hidden>
        <label for="{{ $answer->id }}" class="form-check-label mb-1 answer cursor-pointer text-capitalize">{{ $answer->answer }}</label>
      </li>
    </div>
    @endforeach
  </ol>
</div>
@endforeach


  <div class="d-flex justify-content-between mt-3">
  <button type="button" class="btn btn-secondary prev-question" disabled>Sebelumnya</button>
  <button type="button" class="btn btn-primary next-question">Berikutnya</button>
</div>
      <button type="submit" class="btn btn-primary mt-2 mb-2 buttonSumbitQuiz"><i class='bx bx-task fs-5' style="margin-bottom: 3px;"></i>&nbsp;Selesai</button>
    </form>

    @if($quizzes->isEmpty())
    <div class="d-flex justify-content-center align-items-center" style="height: 50vh;">
      <span style="font-size: medium;"><i class='bx bx-info-circle fs-5' style="margin-bottom: 2px;"></i>&nbsp;Belum ada pertanyaan disini!</span>
    </div>
    @endif
  </div>
</div>


<!-- Modal Petunjuk -->
<div class="modal fade" id="petunjukQuiz" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header d-flex justify-content-between">
        <h5 class="modal-title text-primary fw-bold">Petunjuk Quiz&nbsp;<i class='bx bx-bulb fs-5' style="margin-bottom: 3px;"></i></h5>
        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-dismiss="modal"><i class="bx bx-x-circle text-danger fs-4" data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="auto" title="Tutup"></i></button>
      </div>
      <div class="modal-body" style="margin-top: -10px;">
        <div class="row mb-2">
          <div class="col-sm">
            <div class="mb-1"><strong style="font-weight: normal;">1. Klik pada jawaban untuk menjawab soal.</strong></div>
            <div class="mb-1"><strong style="font-weight: normal;">2. Minimal mengerjakan satu soal.</strong></div>
            <div class="mb-1"><strong style="font-weight: normal;">3. Tidak ada batas waktu saat mengerjakan.</strong></div>
            <div><strong style="font-weight: normal;">4. Klik tombol <b>Selesai</b> jika anda sudah selesai mengerjakan.</strong></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Confirm -->
<div class="modal fade" id="submitQuiz" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header d-flex justify-content-between">
        <h5 class="modal-title text-primary fw-bold">Konfirmasi&nbsp;<i class='bx bx-check-shield fs-5' style="margin-bottom: 3px;"></i></h5>
        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-dismiss="modal"><i class="bx bx-x-circle text-danger fs-4" data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="auto" title="Tutup"></i></button>
      </div>
      <div class="modal-body" style="margin-top: -10px;">
        <div class="col-sm fs-6">Jika Anda sudah yakin dengan jawaban Anda, tekan <strong>'Kirim'</strong> untuk mengirim jawaban Anda.</div>
      </div>
      <div class="modal-footer" style="margin-top: -5px;">
        <button type="button" class="btn btn-outline-danger cancelConfirmQuiz" data-bs-dismiss="modal"><i class='bx bx-share fs-6' style="margin-bottom: 3px;"></i>&nbsp;Batal</button>
        <button type="button" class="btn btn-primary confirmQuiz"><i class='bx bx-paper-plane fs-6' style="margin-bottom: 3px;"></i>&nbsp;Kirim</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal Navigasi Soal -->
<div class="modal fade" id="navigasiQuiz" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header d-flex justify-content-between">
        <h5 class="modal-title text-primary fw-bold">Navigasi Soal&nbsp;<i class='bx bx-grid-alt fs-5' style="margin-bottom: 3px;"></i></h5>
        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-dismiss="modal">
          <i class="bx bx-x-circle text-danger fs-4" data-bs-toggle="tooltip" title="Tutup"></i>
        </button>
      </div>
      <div class="modal-body">
<div class="text-center" 
     style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px;">
  @foreach ($quizzes as $index => $quiz)
    <button 
      type="button"
      class="btn btn-outline-secondary nav-btn" 
      data-index="{{ $index }}"
      style="height: 45px; border-radius: 10px; font-weight: 600;">
      {{ $index + 1 }}
    </button>
  @endforeach
</div>
      </div>
      <div class="modal-footer justify-content-center">
        <small class="text-muted">
          🟩 Sudah dijawab &nbsp;&nbsp; 🟨 Soal aktif &nbsp;&nbsp; ⚪ Belum dijawab
        </small>
      </div>
    </div>
  </div>
</div>


@section('script')
<script src="{{ asset('assets/js/quiz/start.js') }}"></script>
<script src="{{ asset('assets/js/quiz/navigate.js') }}"></script>

@endsection
@endsection