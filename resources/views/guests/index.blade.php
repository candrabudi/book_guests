@extends('layouts.app')
@section('title', 'Data Tamu')
@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <style>
        .queue-number-badge {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 16px;
            font-weight: bold;
        }
    </style>

    <div class="row">
        <div class="col-xxl-{{ Auth::user()->role == 'assistant' ? '12' : '8' }} ">
            <div class="page-title-box d-flex justify-content-between align-items-center">
                <h4 class="page-title">Data Tamu</h4>
                <div class="page-title-right">
                    <form id="searchGuestsForm" class="d-flex">
                        <div class="input-group">
                            <input type="text" id="searchQuery" class="form-control" placeholder="Search guests..."
                                aria-label="Search guests">
                            <button class="btn btn-secondary" type="submit">
                                <i class="uil uil-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-2">
                <h5 class="m-0 pb-2">
                    <a class="text-dark" data-bs-toggle="collapse" href="#todayTasks" role="button" aria-expanded="false"
                        aria-controls="todayTasks">
                        <i class="uil uil-angle-down font-18"></i>Pending <span
                            class="text-muted">({{ count($pendingGuests) }})</span>
                    </a>
                </h5>

                <div class="collapse show" id="todayTasks">
                    <div class="card mb-0">
                        <div class="card-body" id="pendingGuestsContainer">

                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-2">
                <h5 class="m-0 pb-2">
                    <a class="text-dark" data-bs-toggle="collapse" href="#todayTasks" role="button" aria-expanded="false"
                        aria-controls="todayTasks">
                        <i class="uil uil-angle-down font-18"></i>Diterima <span
                            class="text-muted">({{ count($acceptedGuests) }})</span>
                    </a>
                </h5>

                <div class="collapse show" id="todayTasks">
                    <div class="card mb-0">
                        <div class="card-body" id="acceptedGuestsContainer">

                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-2">
                <h5 class="m-0 pb-2">
                    <a class="text-dark" data-bs-toggle="collapse" href="#todayTasks" role="button" aria-expanded="false"
                        aria-controls="todayTasks">
                        <i class="uil uil-angle-down font-18"></i>Disposisi <span
                            class="text-muted">({{ count($dispositionGuests) }})</span>
                    </a>
                </h5>

                <div class="collapse show" id="todayTasks">
                    <div class="card mb-0">
                        <div class="card-body" id="dispositionGuestsContainer">

                        </div>
                    </div>
                </div>

            </div>
        </div>
        @if (Auth::user()->role != 'assistant')
            <div class="col-xxl-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Tambah Data Tamu</h4>
                    </div>
                    <div class="card-body">
                        <form id="guestForm">
                            @csrf
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="mb-3">
                                        <label for="queue_number" class="form-label">Nomor Antrian</label>
                                        <input type="number" id="queue_number" name="queue_number" class="form-control"
                                            value="{{ $queue }}" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="current_datetime" class="form-label">Tanggal dan Waktu</label>
                                        <input type="text" id="current_datetime" name="current_datetime"
                                            class="form-control" readonly>
                                    </div>

                                    <script>
                                        function updateDateTime() {
                                            const now = new Date();
                                            const day = String(now.getDate()).padStart(2, '0');
                                            const month = String(now.getMonth() + 1).padStart(2, '0');
                                            const year = now.getFullYear();
                                            const hours = String(now.getHours()).padStart(2, '0');
                                            const minutes = String(now.getMinutes()).padStart(2, '0');
                                            const formattedDateTime = `${day}-${month}-${year} ${hours}:${minutes}`;
                                            document.getElementById('current_datetime').value = formattedDateTime;
                                        }
                                        setInterval(updateDateTime, 1000);
                                        updateDateTime();
                                    </script>

                                    <div class="mb-3">
                                        <label for="nik" class="form-label">NIK</label>
                                        <input type="text" id="nik" name="nik" class="form-control"
                                            placeholder="Masukan NIK Pengunjung" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="full_name" class="form-label">Nama Lengkap</label>
                                        <input type="text" id="full_name" name="full_name" class="form-control"
                                            placeholder="Masukan nama lengkap" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone_number" class="form-label">Nomor Handphone</label>
                                        <input type="text" id="phone_number" name="phone_number" class="form-control"
                                            placeholder="Masukan nomor handphone" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="institution" class="form-label">Lembaga</label>
                                        <input type="text" id="institution" name="institution" class="form-control"
                                            placeholder="Masukan instansi" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="total_audience" class="form-label">Total Audience</label>
                                        <input type="text" id="total_audience" name="total_audience"
                                            class="form-control" placeholder="Masukan Total Audience" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="appointment" class="form-label">Sudah Buat Janji?</label>
                                        <select id="appointment" name="appointment" class="form-control" required>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="purpose" class="form-label">Purpose</label>
                                        <textarea id="purpose" name="purpose" class="form-control" rows="5" placeholder="Masukan detail kunjungan"
                                            required></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col text-end">
                                    <button type="button" id="submitGuestForm" class="btn btn-success">Tambah
                                        Tamu</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
            integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.getElementById('submitGuestForm').addEventListener('click', function() {
                const form = document.getElementById('guestForm');

                const requiredFields = ['nik', 'full_name', 'phone_number', 'institution', 'total_audience',
                    'appointment', 'purpose'
                ];
                let isValid = true;

                requiredFields.forEach(field => {
                    const input = document.getElementById(field);
                    if (!input.value.trim()) {
                        isValid = false;
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'All fields must be filled!',
                        });
                        return false;
                    }
                });

                if (!isValid) return;

                const formData = new FormData(form);

                axios.post('{{ route('guests.store') }}', formData)
                    .then(function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.data.message || 'Guest added successfully!',
                        }).then(() => {
                            window.location.reload();
                        });
                    })
                    .catch(function(error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.response.data.message || 'Failed to add guest. Please try again.',
                        });
                    });
            });
        </script>

        <script>
            function toTitleCase(str) {
                return str
                    .toLowerCase()
                    .replace(/\b\w/g, (char) => char.toUpperCase());
            }


            function loadPendingGuests(searchQuery = '') {
                $.ajax({
                    url: "{{ route('guests.pending') }}",
                    type: 'GET',
                    data: {
                        search: searchQuery
                    },
                    success: function(data) {
                        let htmlContent = '';
                        data.forEach(function(pg) {
                            const createdAt = new Date(pg.created_at);
                            const formattedDate = createdAt.getFullYear() + '/' +
                                ('0' + (createdAt.getMonth() + 1)).slice(-2) + '/' +
                                ('0' + createdAt.getDate()).slice(-2) + ' ' +
                                ('0' + createdAt.getHours()).slice(-2) + ':' +
                                ('0' + createdAt.getMinutes()).slice(-2);

                            htmlContent += `
                                <div class="row justify-content-between align-items-center py-3 border-bottom">
                                    <div class="col-md-6 d-flex align-items-center">
                                        <!-- Queue number -->
                                        <div class="queue-number-badge bg-primary text-white me-3 d-flex align-items-center justify-content-center">
                                            ${pg.queue_number}
                                        </div>
                                        <!-- Full name, NIK, institution, phone number -->
                                        <div>
                                            <h5 class="m-0">${toTitleCase(pg.full_name)} <small class="text-muted">[${pg.nik}]</small></h5>
                                            <span class="d-block">${pg.institution_name}</span>
                                            <span class="text-muted">[${pg.phone_number}]</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <!-- Appointment date, status badges, and action button -->
                                        <p class="mb-0">
                                            <i class="uil uil-schedule font-16 me-1"></i>
                                            ${formattedDate}
                                        </p>
                                        <span class="badge badge-warning-lighten p-1">Pending</span>
                                        ${pg.appointment == 'yes' 
                                            ? '<span class="badge badge-success-lighten p-1">Sudah Janji</span>'
                                            : '<span class="badge badge-danger-lighten p-1">Belum Janji</span>'}
                                        <a href="/guests/detail/${pg.id}" class="btn btn-sm btn-info ms-2">Detail</a>
                                    </div>
                                </div>
                            `;
                        });

                        $('#pendingGuestsContainer').html(htmlContent);
                    }
                });
            }


            function loadAcceptedGuests(searchQuery = '') {
                $.ajax({
                    url: "{{ route('guests.accepted') }}",
                    type: 'GET',
                    data: {
                        search: searchQuery
                    },
                    success: function(data) {
                        let htmlContent = '';
                        data.forEach(function(pg) {
                            const createdAt = new Date(pg.created_at);
                            const formattedDate = createdAt.getFullYear() + '/' +
                                ('0' + (createdAt.getMonth() + 1)).slice(-2) + '/' +
                                ('0' + createdAt.getDate()).slice(-2) + ' ' +
                                ('0' + createdAt.getHours()).slice(-2) + ':' +
                                ('0' + createdAt.getMinutes()).slice(-2);

                            htmlContent += `
                                <div class="row justify-content-between align-items-center py-3 border-bottom">
                                    <div class="col-md-6 d-flex align-items-center">
                                        <div class="queue-number-badge bg-primary text-white me-3 d-flex align-items-center justify-content-center">
                                            ${pg.queue_number}
                                        </div>
                                        <div>
                                            <h5 class="m-0">${toTitleCase(pg.full_name)} <small class="text-muted">[${pg.nik}]</small></h5>
                                            <span class="d-block">${pg.institution_name}</span>
                                            <span class="text-muted">[${pg.phone_number}]</span>
                                            <span class="d-block text-muted"><strong>Pendamping:</strong> ${pg.companion_name ? pg.companion_name : '-'}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <p class="mb-0">
                                            <i class="uil uil-schedule font-16 me-1"></i>
                                            ${formattedDate}
                                        </p>
                                        <span class="badge badge-success-lighten p-1">Diterima</span>
                                        ${pg.appointment == 'yes' 
                                            ? '<span class="badge badge-success-lighten p-1">Sudah Janji</span>'
                                            : '<span class="badge badge-danger-lighten p-1">Belum Janji</span>'}
                                        <a href="/guests/detail/${pg.id}" class="btn btn-sm btn-info ms-2">Detail</a>
                                    </div>
                                </div>
                            `;
                        });


                        $('#acceptedGuestsContainer').html(htmlContent);
                    }
                });
            }

            function loadDispositionGuests(searchQuery = '') {
                $.ajax({
                    url: "{{ route('guests.disposition') }}",
                    type: 'GET',
                    data: {
                        search: searchQuery
                    },
                    success: function(data) {
                        let htmlContent = '';
                        data.forEach(function(pg) {
                            const createdAt = new Date(pg.created_at);
                            const formattedDate = createdAt.getFullYear() + '/' +
                                ('0' + (createdAt.getMonth() + 1)).slice(-2) + '/' +
                                ('0' + createdAt.getDate()).slice(-2) + ' ' +
                                ('0' + createdAt.getHours()).slice(-2) + ':' +
                                ('0' + createdAt.getMinutes()).slice(-2);

                            htmlContent += `
                                <div class="row justify-content-between align-items-center py-3 border-bottom">
                                    <div class="col-md-6 d-flex align-items-center">
                                        <div class="queue-number-badge bg-primary text-white me-3 d-flex align-items-center justify-content-center">
                                            ${pg.queue_number}
                                        </div>
                                        <div>
                                            <h5 class="m-0">${toTitleCase(pg.full_name)} <small class="text-muted">[${pg.nik}]</small></h5>
                                            <span class="d-block">${pg.institution_name}</span>
                                            <span class="text-muted">[${pg.phone_number}]</span>
                                            <span class="d-block text-muted"><strong>Pendamping:</strong> ${pg.companion_name ? pg.companion_name : '-'}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <p class="mb-0">
                                            <i class="uil uil-schedule font-16 me-1"></i>
                                            ${formattedDate}
                                        </p>
                                        <span class="badge badge-info-lighten p-1">Disposisi</span>
                                        ${pg.appointment == 'yes' 
                                            ? '<span class="badge badge-success-lighten p-1">Sudah Janji</span>'
                                            : '<span class="badge badge-danger-lighten p-1">Belum Janji</span>'}
                                        <a href="/guests/detail/${pg.id}" class="btn btn-sm btn-info ms-2">Detail</a>
                                    </div>
                                </div>
                            `;
                        });


                        $('#dispositionGuestsContainer').html(htmlContent);
                    }
                });
            }

            $('#searchGuestsForm').submit(function(e) {
                e.preventDefault();
                const searchQuery = $('#searchQuery').val();
                loadPendingGuests(searchQuery);
                loadAcceptedGuests(searchQuery);
                loadDispositionGuests(searchQuery);
            });


            Pusher.logToConsole = true;

            var pusher = new Pusher('4726565422b0bb85073b', {
                cluster: 'ap1'
            });

            var channel = pusher.subscribe('guest-channel');
            channel.bind('guest-added', function(data) {
                var audio = new Audio('{{ asset('ringtone.mp3') }}');
                audio.play();
                loadPendingGuests();
                loadAcceptedGuests();
                loadDispositionGuests();
            });

            loadPendingGuests();
            loadAcceptedGuests();
            loadDispositionGuests();
        </script>
    @endpush
@endsection
