@extends('layouts.app')
@section('title', 'Edit Tamu')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Edit Tamu</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xxl-6">
            <div class="card">
                <div class="card-body">
                    <form id="guestForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="mb-3">
                                    <label for="queue_number" class="form-label">Nomor Antrian</label>
                                    <input type="number" id="queue_number" name="queue_number" class="form-control"
                                        value="{{ $guest->queue_number }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Nama Lengkap</label>
                                    <input type="text" id="full_name" name="full_name" class="form-control"
                                        value="{{ $guest->identity->full_name }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="phone_number" class="form-label">Nomor Handphone</label>
                                    <input type="text" id="phone_number" name="phone_number" class="form-control"
                                        value="{{ $guest->identity->phone_number }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="institution" class="form-label">Lembaga</label>
                                    <input type="text" id="institution" name="institution" class="form-control"
                                        value="{{ $guest->institution->institution_name }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="total_audience" class="form-label">Total Audience</label>
                                    <input type="text" id="total_audience" name="total_audience" class="form-control"
                                        value="{{ $guest->total_audience }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="appointment" class="form-label">Sudah Buat Janji?</label>
                                    <select id="appointment" name="appointment" class="form-control" required>
                                        <option value="">Pilih Apakah Sudah Buat Janji ?</option>
                                        <option value="yes" {{ $guest->appointment == 'yes' ? 'selected' : '' }}>Yes
                                        </option>
                                        <option value="no" {{ $guest->appointment == 'no' ? 'selected' : '' }}>No
                                        </option>
                                    </select>
                                </div>

                                <div class="mb-3" id="photoUpload"
                                    style="{{ $guest->appointment == 'yes' ? 'block' : 'none' }}">
                                    <label for="photo" class="form-label">Upload Foto</label>
                                    <input type="file" id="photo" name="photo" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label for="purpose" class="form-label">Purpose</label>
                                    <textarea id="purpose" name="purpose" class="form-control" rows="5" required>{{ $guest->purpose }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col text-end">
                                <button type="button" id="submitGuestForm" class="btn btn-primary">Update Tamu</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
            integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.getElementById('appointment').addEventListener('change', function() {
                const photoUpload = document.getElementById('photoUpload');
                if (this.value === 'yes') {
                    photoUpload.style.display = 'block';
                } else {
                    photoUpload.style.display = 'none';
                }
            });

            document.getElementById('submitGuestForm').addEventListener('click', function() {
                const form = document.getElementById('guestForm');
                const formData = new FormData(form);

                axios.post('{{ route('guests.update', $guest->id) }}', formData)
                    .then(function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.data.message || 'Guest updated successfully!',
                        }).then(() => {
                            window.location.reload();
                        });
                    })
                    .catch(function(error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.response.data.message ||
                                'Failed to update guest. Please try again.',
                        });
                    });
            });
        </script>
    @endpush
@endsection
