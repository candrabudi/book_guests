@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Data Pendamping</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-xl-8">
                            <form class="row gy-2 gx-2 align-items-center justify-content-xl-start justify-content-between"
                                id="filterForm">
                                <div class="col-auto">
                                    <label for="inputSearch" class="visually-hidden">Search</label>
                                    <input type="search" class="form-control" id="inputSearch" placeholder="Search...">
                                </div>
                            </form>
                        </div>
                        <div class="col-xl-4 text-end">
                            <!-- Tombol untuk membuka modal tambah data -->
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crudModal">
                                Tambah Pendamping
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nama Pendamping</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Tanggal Update</th>
                                    <th style="width: 125px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="guest-list">
                                <!-- Data Pendamping akan diisi melalui JavaScript/AJAX -->
                            </tbody>
                        </table>
                    </div>

                    <nav>
                        <ul class="pagination pagination-rounded mb-0" id="pagination-links">
                            <!-- Pagination -->
                        </ul>
                    </nav>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Tambah/Edit Data -->
    <div class="modal fade" id="crudModal" tabindex="-1" aria-labelledby="crudModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="crudModalLabel">Tambah Pendamping</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="crudForm">
                        <input type="hidden" id="companionId">
                        <div class="mb-3">
                            <label for="companionName" class="form-label">Nama Pendamping</label>
                            <input type="text" class="form-control" id="companionName" placeholder="Masukkan Nama Pendamping">
                        </div>
                        <button type="submit" class="btn btn-primary" id="saveButton">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        // Fungsi untuk memuat data pendamping
        function loadCompanions() {
            $.ajax({
                url: '{{ route('companions.list') }}',
                type: 'GET',
                success: function (response) {
                    $('#guest-list').html(response.html);
                }
            });
        }

        // Panggil loadCompanions saat halaman pertama kali dimuat
        loadCompanions();

        // Simpan atau Update Data Pendamping
        $('#crudForm').on('submit', function (e) {
            e.preventDefault();
            let id = $('#companionId').val();
            let url = id ? '{{ route('companions.update', ':id') }}'.replace(':id', id) : '{{ route('companions.store') }}';
            let method = id ? 'PUT' : 'POST';
            let data = {
                companion_name: $('#companionName').val(),
                _token: '{{ csrf_token() }}'
            };

            if (id) data._method = 'PUT';

            $.ajax({
                url: url,
                type: method,
                data: data,
                success: function (response) {
                    $('#crudModal').modal('hide');
                    loadCompanions();
                    alert('Data berhasil disimpan!');
                }
            });
        });

        // Edit Data Pendamping
        $(document).on('click', '.edit-btn', function () {
            let id = $(this).data('id');
            let url = '{{ route('companions.edit', ':id') }}'.replace(':id', id);

            $.ajax({
                url: url,
                type: 'GET',
                success: function (response) {
                    $('#crudModalLabel').text('Edit Pendamping');
                    $('#companionId').val(response.id);
                    $('#companionName').val(response.companion_name);
                    $('#crudModal').modal('show');
                }
            });
        });

        // Hapus Data Pendamping
        $(document).on('click', '.delete-btn', function () {
            if (confirm('Yakin ingin menghapus data ini?')) {
                let id = $(this).data('id');
                let url = '{{ route('companions.destroy', ':id') }}'.replace(':id', id);

                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function () {
                        loadCompanions();
                        alert('Data berhasil dihapus!');
                    }
                });
            }
        });

        // Reset form ketika modal ditutup
        $('#crudModal').on('hidden.bs.modal', function () {
            $('#crudModalLabel').text('Tambah Pendamping');
            $('#crudForm')[0].reset();
            $('#companionId').val('');
        });
    });
</script>
@endpush
