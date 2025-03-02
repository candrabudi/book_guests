@extends('layouts.app')
@section('title', 'Laporan Buku Tamu')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Data Pengunjung</h4>
            </div>
        </div>
    </div>

    <div class="col-xl-12 col-lg-12">
        <div class="row">
            <div class="col-sm-2">
                <div class="card widget-flat">
                    <div class="card-body">
                        <h5 class="text-muted fw-normal mt-0" title="Number of Customers">Total Pengunjung</h5>
                        <h3 class="mt-3 mb-3">{{ $responseData['total_visitors'] }}</h3>
                    </div>
                </div>
            </div>
    
            <div class="col-sm-2">
                <div class="card widget-flat">
                    <div class="card-body">
                        <h5 class="text-muted fw-normal mt-0" title="Number of Orders">Mengunjungi 1x</h5>
                        <h3 class="mt-3 mb-3">{{ $responseData['one_visit'] }}</h3>
                    </div>
                </div>
            </div>
    
            <div class="col-sm-2">
                <div class="card widget-flat">
                    <div class="card-body">
                        <h5 class="text-muted fw-normal mt-0" title="Number of Orders">Lebih Dari 1 Kunjungan</h5>
                        <h3 class="mt-3 mb-3">{{ $responseData['more_than_one_visit'] }}</h3>
                    </div>
                </div>
            </div>
    
            <div class="col-sm-2">
                <div class="card widget-flat">
                    <div class="card-body">
                        <h5 class="text-muted fw-normal mt-0" title="Number of Orders">Lebih Dari 3 Kunjungan</h5>
                        <h3 class="mt-3 mb-3">{{ $responseData['more_than_three_visit'] }}</h3>
                    </div>
                </div>
            </div>
    
            <div class="col-sm-2">
                <div class="card widget-flat">
                    <div class="card-body">
                        <h5 class="text-muted fw-normal mt-0" title="Number of Orders">Lebih Dari 5 Kunjungan</h5>
                        <h3 class="mt-3 mb-3">{{ $responseData['more_than_five_visit'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <input type="text" id="search" class="form-control" placeholder="Cari NIK atau Nama Lengkap"
                            oninput="fetchData()" />
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>NIK</th>
                                    <th>Nama Lengkap</th>
                                    <th>Total Berkunjung</th>
                                    <th style="width: 125px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="guest-list">

                            </tbody>
                        </table>
                    </div>

                    <nav>
                        <ul class="pagination pagination-rounded mb-0" id="pagination-links">

                        </ul>
                    </nav>

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
            let currentPage = 1;

            function fetchData(page = 1) {
                const search = document.getElementById('search').value;

                axios.get('{{ route('visitors.list') }}', {
                    params: {
                        search: search,
                        page: page
                    }
                }).then(function(response) {
                    const data = response.data;

                    let guestList = '';
                    data.data.forEach(function(visitor) {
                        guestList += `
                            <tr>
                                <td>${visitor.nik}</td>
                                <td>${visitor.full_name}</td>
                                <td>${visitor.guests_count}</td>
                                <td>
                                    <a href="#" class="btn btn-primary">Detail</a>
                                </td>
                            </tr>
                        `;
                    });
                    document.getElementById('guest-list').innerHTML = guestList;

                    renderPagination(data);
                }).catch(function(error) {
                    console.error('Error fetching data:', error);
                });
            }

            function renderPagination(data) {
                let paginationLinks = '';

                if (data.links) {
                    data.links.forEach(function(link) {
                        paginationLinks += `
                            <li class="page-item ${link.active ? 'active' : ''}">
                                <a class="page-link" href="javascript:void(0);" onclick="fetchData(${link.label})">${link.label}</a>
                            </li>
                        `;
                    });
                }

                document.getElementById('pagination-links').innerHTML = paginationLinks;
            }

            document.addEventListener('DOMContentLoaded', function() {
                fetchData();
            });
        </script>
    @endpush
@endsection
