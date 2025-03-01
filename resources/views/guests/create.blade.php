@extends('layouts.app')
@section('title', 'Tambah Tamu')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Tambah Tamu</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('guests.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="mb-3">
                                    <label for="queue_number" class="form-label">Nomor Antrian</label>
                                    <input type="number" id="queue_number" name="queue_number" class="form-control" value="{{ $queue }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="current_datetime" class="form-label">Tanggal dan Waktu</label>
                                    <input type="text" id="current_datetime" name="current_datetime" class="form-control" readonly>
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
                                    <label for="full_name" class="form-label">Nama Lengkap</label>
                                    <input type="text" id="full_name" name="full_name" class="form-control"
                                        placeholder="Masukan nama lengka" required>
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
                                    <input type="text" id="total_audience" name="total_audience" class="form-control"
                                        placeholder="Masukan Total Audience" required>
                                </div>

                                <div class="mb-3">
                                    <label for="appointment" class="form-label">Sudah Buat Janji ?

                                    </label>
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
                                <button type="submit" class="btn btn-success">Tambah Tamu</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
