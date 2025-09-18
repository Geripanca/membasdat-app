@extends('layouts.main.index')
@section('container')
@section('style')
<style>
  ::-webkit-scrollbar {
    display: none;
  }

  @media screen and (min-width: 1320px) {
    #search {
      width: 250px;
    }
  }

  .required-label::after {
    content: " *";
    color: red;
  }

  @media screen and (max-width: 575px) {
    .pagination-mobile {
      display: flex;
      justify-content: end;
    }
  }

  audio {
    height: 35px;
    outline: none;
  }

  audio::-webkit-media-controls-panel {
    background-color: #f1f1f1;
    color: #333;
    border-radius: 8px;
    padding: 0px;
  }

  audio::-webkit-media-controls-play-button,
  audio::-webkit-media-controls-pause-button {
    background-color: #696cff;
    color: #fff;
    border-radius: 50%;
    padding: 0px;
    border: none;
    cursor: pointer;
  }
</style>
@endsection
<div class="flash-message" data-add-materi="@if(session()->has('addMateriSuccess')) {{ session('addMateriSuccess') }} @endif" data-edit-materi="@if(session()->has('editMateriSuccess')) {{ session('editMateriSuccess') }} @endif" data-delete-materi="@if(session()->has('deleteMateriSuccess')) {{ session('deleteMateriSuccess') }} @endif"></div>
<div class="row">
  <div class="col-md-12 col-lg-12 order-2 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between" style="margin-bottom: -0.7rem;">
        <div class="justify-content-start">
          <button type="button" class="btn btn-xs btn-dark fw-bold p-2 buttonAddMateri" data-bs-toggle="modal" data-bs-target="#formModalAdminMateri">
            <i class='bx bx-book-content fs-6'></i>&nbsp;TAMBAH MATERI
          </button>
        </div>
        <div class="justify-content-end">
          <form action="/admin/data-materi/search">
            <div class="input-group">
              <input type="search" class="form-control" name="q" id="search" style="border: 1px solid #d9dee3;" placeholder="Cari Data Materi..." autocomplete="off" />
            </div>
          </form>
        </div>
      </div>
      <div class="card-body">
        <ul class="p-0 m-0">
          <div class="table-responsive text-nowrap" style="border-radius: 3px;">
            <table class="table table-striped">
              <thead class="table-dark">
                <tr>
                  <th class="text-white">No</th>
                  <th class="text-white text-center">Judul Materi</th>
                  <th class="text-white text-center">Kategori</th>
                  <th class="text-white text-center">File / Video</th>
                  <th class="text-white">Tanggal Pembuatan</th>
                  <th class="text-white">Tanggal Update</th>
                  <th class="text-white text-center">Aksi</th>
                </tr>
              </thead>

              <tbody class="table-border-bottom-0">
              @foreach($materis as $index => $materi)
                <tr>
                  <td>{{ $materis->firstItem() + $index }}</td>
                  <td class="text-capitalize text-center">{{ $materi->title }}</td>
                  <td class="text-center">
                  @if($materi->category == 'file')
                    <span class="badge bg-label-success fw-bold">File</span>
                  @else
                    <span class="badge bg-label-info fw-bold">Video</span>
                  @endif
                  </td>
                  <td class="text-center">
                  @if($materi->category == 'file' && $materi->file)
                    <a href="{{ asset('storage/' . $materi->file) }}" target="_blank" class="btn btn-sm btn-primary">
                    Download
                    </a>
                  @elseif($materi->category == 'video' && $materi->video)
                    <iframe width="200" height="113" src="{{ $materi->video }}" frameborder="0" allowfullscreen></iframe>
                  @else
                    <span class="text-muted">Tidak ada data</span>
                  @endif
                  </td>
                  <td>{{ $materi->created_at->locale('id')->isoFormat('D MMMM YYYY | H:mm') }}</td>
                  <td>{{ $materi->updated_at->locale('id')->isoFormat('D MMMM YYYY | H:mm') }}</td>
                  <td class="text-center">
                    <button type="button" class="btn btn-icon btn-primary btn-sm buttonEditMateri"
                    data-bs-toggle="tooltip"
                    title="Edit Materi"
                    data-code-materi="{{ encrypt($materi->id) }}"
                    data-title-materi="{{ $materi->title }}"
                    data-category-materi="{{ $materi->category }}">
                    <span class="tf-icons bx bx-edit" style="font-size: 15px;"></span>
                    </button>
                    <button type="button" class="btn btn-icon btn-danger btn-sm buttonDeleteMateri"
                    data-bs-toggle="tooltip"
                    title="Hapus Materi"
                    data-code-materi="{{ encrypt($materi->id) }}"
                    data-title-materi="{{ $materi->title }}">
                    <span class="tf-icons bx bx-trash" style="font-size: 14px;"></span>
                    </button>
                  </td>
                </tr>
              @endforeach
  @if($materis->isEmpty())
  <tr>
    <td colspan="100" class="text-center">Data tidak ditemukan!</td>
  </tr>
  @endif
</tbody>

            </table>
          </div>
        </ul>
        @if(!$materis->isEmpty())
        <div class="mt-3 pagination-mobile">{{ $materis->withQueryString()->onEachSide(1)->links() }}</div>
        @endif
      </div>
    </div>
  </div>
</div>

<div id="errorModalAddMateri" data-error-title="@error('title') {{ $message }} @enderror" data-error-image="@error('image') {{ $message }} @enderror" data-error-audio="@error('audio') {{ $message }} @enderror" data-error-category="@error('category') {{ $message }} @enderror"></div>
<!-- Modal Add Materi-->
<div class="modal fade" id="formModalAdminMateri" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="" method="post" class="modalAdminMateri" enctype="multipart/form-data">
      @csrf
      <div class="modal-content">
        <div class="modal-header d-flex justify-content-between">
          <h5 class="modal-title text-primary fw-bold">Tambah Materi Baru&nbsp;<i class='bx bx-book-content fs-5' style="margin-bottom: 1px;"></i></h5>
          <button type="button" class="btn p-0 dropdown-toggle hide-arrow cancelModalAddMateri" data-bs-dismiss="modal"><i class="bx bx-x-circle text-danger fs-4" data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="auto" title="Tutup"></i></button>
        </div>
        <div class="modal-body">
          <div class="row">
          <div class="col mb-3">
          <label for="title" class="form-label required-label">Judul Materi</label>
          <input type="text" id="title" name="title" value="{{ old('title') }}"
          class="form-control @error('title') is-invalid @enderror"
          placeholder="Masukkan Judul Materi" autocomplete="off" required>
          @error('title')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          </div>
        </div>
        <div class="row">
          <div class="col mb-3">
            <label for="file" class="form-label">Upload File</label>
            <input type="file" id="file" name="file" class="form-control @error('file') is-invalid @enderror">
            @error('file')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text">Maks 5MB. Format: PDF, DOC, DOCX, ODT, ODF</div>
          </div>
        </div>
        <div class="row">
          <div class="col mb-3">
            <label for="video" class="form-label">Link Video</label>
            <input type="url" id="video" name="video" value="{{ old('video') }}" class="form-control @error('video') is-invalid @enderror" placeholder="https://www.youtube.com/embed/...">
            @error('video')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text">Masukkan link embed YouTube</div>
          </div>
        </div>
          <div class="row">
            <div class="col">
            <label for="category" class="form-label required-label">Kategori</label>
            <select class="form-select @error('category') is-invalid @enderror" name="category" id="category" required>
              <option value="" disabled selected>Pilih Kategori</option>
              <option value="file" @if(old('category')=='file') selected @endif>File</option>
              <option value="video" @if(old('category')=='video') selected @endif>Video</option>
            </select>
            @error('category')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-danger cancelModalAddMateri" data-bs-dismiss="modal"><i class='bx bx-share fs-6' style="margin-bottom: 3px;"></i>&nbsp;Batal</button>
          <button type="submit" class="btn btn-primary"><i class='bx bx-paper-plane fs-6' style="margin-bottom: 3px;"></i>&nbsp;Tambah</button>
        </div>
      </div>
    </form>
  </div>
</div>

<div id="errorModalEditMateri" data-error-edit-title="@error('titleEdit') {{ $message }} @enderror" data-error-edit-image="@error('imageEdit') {{ $message }} @enderror" data-error-edit-audio="@error('audioEdit') {{ $message }} @enderror" data-error-edit-category="@error('categoryEdit') {{ $message }} @enderror"></div>
<!-- Modal Edit Materi-->
<div class="modal fade" id="formEditModalAdminMateri" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="/admin/data-materi/update" method="post" class="modalAdminMateri" enctype="multipart/form-data">
      @csrf
      <input type="hidden" class="codeMateri" value="{{ old('codeMateri') }}" name="codeMateri">
      <div class="modal-content">
        <div class="modal-header d-flex justify-content-between">
          <h5 class="modal-title text-primary fw-bold">Edit Materi&nbsp;<i class='bx bx-joystick fs-5' style="margin-bottom: 1px;"></i></h5>
          <button type="button" class="btn p-0 dropdown-toggle hide-arrow cancelModalEditMateri" data-bs-dismiss="modal"><i class="bx bx-x-circle text-danger fs-4" data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="auto" title="Tutup"></i></button>
        </div>
        <div class="modal-body">
          <div class="row">
  <div class="col mb-3">
    <label for="titleEdit" class="form-label required-label">Judul Materi</label>
    <input type="text" id="titleEdit" name="titleEdit" value="{{ old('titleEdit') }}"
      class="form-control @error('titleEdit') is-invalid @enderror"
      placeholder="Masukkan Judul Materi" autocomplete="off" required>
    @error('titleEdit')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>
</div>
          <div class="row">
  <div class="col mb-3">
    <label for="fileEdit" class="form-label">Upload File</label>
    <input type="file" id="fileEdit" name="fileEdit" class="form-control @error('fileEdit') is-invalid @enderror">
    @error('fileEdit')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>
</div>
<div class="row">
  <div class="col mb-3">
    <label for="videoEdit" class="form-label">Link Video</label>
    <input type="url" id="videoEdit" name="videoEdit" value="{{ old('videoEdit') }}" class="form-control @error('videoEdit') is-invalid @enderror">
    @error('videoEdit')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>
</div>
<div class="row">
  <div class="col">
    <label for="categoryEdit" class="form-label required-label">Kategori</label>
    <select class="form-select @error('categoryEdit') is-invalid @enderror" name="categoryEdit" id="categoryEdit" required>
      <option value="file" @if(old('categoryEdit')=='file') selected @endif>File</option>
      <option value="video" @if(old('categoryEdit')=='video') selected @endif>Video</option>
    </select>
    @error('categoryEdit')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>
</div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-danger cancelModalEditMateri" data-bs-dismiss="modal"><i class='bx bx-share fs-6' style="margin-bottom: 3px;"></i>&nbsp;Batal</button>
          <button type="submit" class="btn btn-primary"><i class='bx bx-save fs-6' style="margin-bottom: 3px;"></i>&nbsp;Update</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- modal delete Materi -->
<div class="modal fade" id="deleteMateriConfirm" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="/admin/data-materi/delete" method="post" id="formDeleteMateri">
      @csrf
      <input type="hidden" class="codeMateri" value="{{ old('codeMateri') }}" name="codeMateri">
      <div class="modal-content">
        <div class="modal-header d-flex justify-content-between">
          <h5 class="modal-title text-primary fw-bold">Konfirmasi&nbsp;<i class='bx bx-check-shield fs-5' style="margin-bottom: 3px;"></i></h5>
          <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-dismiss="modal"><i class="bx bx-x-circle text-danger fs-4" data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="auto" title="Tutup"></i></button>
        </div>
        <div class="modal-body" style="margin-top: -10px;">
          <div class="col-sm fs-6 materiMessagesDelete"></div>
        </div>
        <div class="modal-footer" style="margin-top: -5px;">
          <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal"><i class='bx bx-share fs-6' style="margin-bottom: 3px;"></i>&nbsp;Tidak</button>
          <button type="submit" class="btn btn-primary"><i class='bx bx-trash fs-6' style="margin-bottom: 3px;"></i>&nbsp;Ya, Hapus!</button>
        </div>
      </div>
    </form>
  </div>
</div>

@section('script')
<script src="{{ asset('assets/js/datamateri/index.js') }}"></script>
@endsection
@endsection