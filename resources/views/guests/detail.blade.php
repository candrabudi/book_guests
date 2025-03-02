@extends('layouts.app')
@section('title', 'Detail Tamu')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Detail Tamu</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="{{ $guest->status == 'completed' || $guest->status == 'accepted' ? 'col-xxl-7 col-xl-7' : 'col-xxl-8 col-xl-7' }} ">
            <div class="card d-block">
                <div class="card-body">
                    <h3 class="mt-3">
                        {{ $guest->identity->full_name }}
                    </h3>
                    <span>{{ $guest->institution->institution_name }}</span>
                    <div class="row">
                        <div class="col-6">
                            <p class="mt-2 mb-1 text-muted fw-bold font-12 text-uppercase">Due Date</p>
                            <div class="d-flex">
                                <i class='uil uil-schedule font-18 text-success me-1'></i>
                                <div>
                                    <h5 class="mt-1 font-14">
                                        {{ \Carbon\Carbon::parse($guest->created_at)->format('Y/m/d H:i') }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-6">
                            <p class="mt-2 mb-1 text-muted fw-bold font-12 text-uppercase">Status Janji</p>
                            <div class="d-flex">
                                <i class='uil uil-check-circle font-18 text-primary me-1'></i>
                                <div>
                                    <h5 class="mt-1 font-14">
                                        {{ $guest->has_appointment == 'yes' ? 'Sudah Membuat Janji' : 'Belum Membuat Janji' }}
                                    </h5>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <p class="mt-2 mb-1 text-muted fw-bold font-12 text-uppercase">Status Tamu</p>
                            <div class="d-flex">
                                <i class='uil uil-number-square font-18 text-warning me-1'></i>
                                <div>
                                    @if ($guest->status == 'pending')
                                        <h5 class="mt-1 font-14"><span
                                                class="badge badge-warning-lighten font-14">Pending</span>
                                        </h5>
                                    @elseif ($guest->status == 'accepted')
                                        <h5 class="mt-1 font-14"><span
                                                class="badge badge-success-lighten font-14">Diterima</span>
                                        </h5>
                                    @elseif ($guest->status == 'completed')
                                        <h5 class="mt-1 font-14"><span
                                                class="badge badge-success-lighten font-14">Selesai</span>
                                        </h5>
                                    @elseif($guest->status == 'disposition')
                                        <h5 class="mt-1 font-14"><span
                                                class="badge badge-info-lighten font-14">Disposisi</span>
                                        </h5>
                                    @elseif ($guest->status == 'rejected')
                                        <h5 class="mt-1 font-14"><span
                                                class="badge badge-danger-lighten font-14">Disposisi</span>
                                        </h5>
                                    @elseif ($guest->status == 'reschedule')
                                        <h5 class="mt-1 font-14"><span
                                                class="badge badge-secondary-lighten font-14">Reschedule</span>
                                        </h5>
                                    @endif
                                </div>
                            </div>
                            @if ($guest->companion)
                                <p class="mt-2 mb-1 text-muted fw-bold font-12 text-uppercase">Pendamping</p>
                                <div class="d-flex">
                                    <i class='uil uil-number-square font-18 text-warning me-1'></i>
                                    <div>
                                        <h5 class="mt-1 font-14">
                                            {{ $guest->companion->companion_name }}
                                        </h5>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-6">
                            <p class="mt-2 mb-1 text-muted fw-bold font-12 text-uppercase">Nomor Antrian</p>
                            <div class="d-flex">
                                <i class='uil uil-number-square font-18 text-warning me-1'></i>
                                <div>
                                    <h5 class="mt-1 font-14">
                                        {{ $guest->queue_number ?? 'Tidak Ada Nomor Antrian' }}
                                    </h5>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <p class="mt-2 mb-1 text-muted fw-bold font-12 text-uppercase">Sudah Berapa Lama</p>
                            <div class="d-flex">
                                <i class='uil uil-clock font-18 text-info me-1'></i>
                                <div>
                                    <h5 class="mt-1 font-14">
                                        {{ \Carbon\Carbon::parse($guest->created_at)->diffForHumans() }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5 class="mt-3">Overview:</h5>
                    <p class="text-muted mb-4">
                        {{ $guest->purpose }}
                    </p>
                </div>
            </div>
        </div>


        @if ($guest->status == 'pending')
            <div class="col-xxl-4 col-xl-5">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Update Data</h5>
                        <form id="updateForm" action="{{ route('guests.update', $guest->id) }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="statusSelect" class="form-label">Status</label>
                                <select class="form-control" id="statusSelect" name="status">
                                    <option value="">Pilih Status</option>
                                    <option value="disposition">Disposition</option>
                                    <option value="accepted">Accepted</option>
                                    <option value="rejected">Rejected</option>
                                    <option value="reschedule">Reschedule</option>
                                </select>
                            </div>

                            <div class="mb-3" id="companionField" style="display: none;">
                                <label for="companionSelect" class="form-label">Pendamping</label>
                                <select class="form-control" id="companionSelect" name="companion_id">
                                    <option value="">Pilih Pendamping</option>
                                    @foreach ($companions as $cpn)
                                        <option value="{{ $cpn->id }}">{{ $cpn->companion_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-warning">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @elseif($guest->status == 'accepted' && !$guest->notulensi)
            <div class="col-xxl-5 col-xl-5">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Input Notulensi</h5>

                        <form id="notulensi-form" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="notulensi">Judul</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="notulensi">Notulensi</label>
                                <div id="snow-editor" style="height: 300px;"></div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="ada_janji">Ada Janji?</label>
                                <select name="ada_janji" id="ada_janji" class="form-control">
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </div>

                            <div class="form-group" id="file-input-container">
                                <label for="images[]">Choose Images</label>
                                <input type="file" class="form-control" name="images[]" multiple id="upload-img" />
                            </div>

                            <button type="button" class="btn btn-secondary mt-3" id="add-file-input">Add More
                                Files</button>

                            <button type="submit" class="btn btn-primary mt-3">Submit</button>
                        </form>

                    </div>
                </div>
            </div>
        @elseif($guest->status == 'completed' && $guest->notulensi)
            <div class="col-xxl-5 col-xl-5">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Detail Notulensi</h5>
                        <p><strong>Judul: </strong> {{ $guest->notulensi->title }}</p>
                        <p><strong>Ada Tidak Lanjut ?: </strong>
                            {!! $guest->notulensi->appointment == 1
                                ? '<span class="badge bg-success">Ya</span>'
                                : '<span class="badge bg-danger">Tidak</span>' !!}
                        </p>
                        <p><strong>Tanggal Dibuat: </strong> {{ $guest->notulensi->created_at->format('d M Y, H:i') }}</p>
                        <hr>
                        <h6 class="card-subtitle mb-2 text-muted">Notulensi:</h6>
                        <div class="notulensi-content">
                            {!! preg_replace(
                                [
                                    '/<div class="ql-tooltip[^>]*>.*?<\/div>/is',
                                    '/class="[^"]*?ql-[^"]*?"/i',
                                    '/contenteditable="true"/i',
                                    '/<div><a[^>]*><\/a><input[^>]*><a><\/a><a><\/a><\/div>/is',
                                ],
                                '',
                                $guest->notulensi->notulensi,
                            ) !!}
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Attachment</h5>
                        <div class="card mb-1 shadow-none border">
                            @foreach ($guest->notulensi->photos as $photo)
                                <div class="p-2">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="avatar-sm">
                                                <span class="avatar-title rounded">
                                                    {{ $photo->file_extension }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col ps-0">
                                            <a href="javascript:void(0);"
                                                class="text-muted fw-bold">{{ $photo->file_name }}</a>
                                        </div>
                                        <div class="col-auto">
                                            <a href="javascript:void(0);" class="btn btn-link btn-lg text-muted">
                                                <i class="ri-download-2-line"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        @else
            <div class="col-xxl-4 col-xl-5">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Mohon Maaf data ini sudah closed</h5>
                    </div>
                </div>
            </div>
        @endif
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.getElementById('statusSelect').addEventListener('change', function() {
                var companionField = document.getElementById('companionField');
                if (this.value === 'accepted' || this.value === 'disposition') {
                    companionField.style.display = 'block';
                } else {
                    companionField.style.display = 'none';
                }
            });

            document.getElementById('updateForm').addEventListener('submit', function(event) {
                var statusSelect = document.getElementById('statusSelect');
                var companionField = document.getElementById('companionField');
                var companionSelect = document.getElementById('companionSelect');

                if (statusSelect.value === "") {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Status harus dipilih!',
                    });
                } else if ((statusSelect.value === 'accepted' || statusSelect.value === 'disposition') &&
                    companionSelect.value === "") {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Pendamping harus dipilih!',
                    });
                }
            });
        </script>
        @push('scripts')
            @if (!$guest->notulensi)
                <script src="{{ asset('assets/vendor/quill/quill.js') }}"></script>
                <script src="{{ asset('assets/js/pages/demo.quilljs.js') }}"></script>
                <script>
                    document.getElementById('add-file-input').addEventListener('click', function() {
                        const container = document.getElementById('file-input-container');
                        const newInput = document.createElement('input');
                        newInput.type = 'file';
                        newInput.name = 'images[]';
                        newInput.classList.add('form-control', 'mt-2');
                        container.appendChild(newInput);
                    });

                    document.getElementById('notulensi-form').addEventListener('submit', function(event) {
                        event.preventDefault();

                        const form = document.getElementById('notulensi-form');
                        const formData = new FormData(form);

                        const editorContent = document.querySelector('#snow-editor').innerHTML;
                        formData.append('notulensi', editorContent);

                        fetch('/guests/notulensi/store/{{ $guest->id }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                                },
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Form submitted successfully!',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        form.reset();
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Failed',
                                        text: 'Failed to submit the form.',
                                        icon: 'error',
                                        confirmButtonText: 'Try Again'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    title: 'Error',
                                    text: 'An error occurred. Please try again.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            });

                    });
                </script>
            @endif
        @endpush
    </div>
@endsection
