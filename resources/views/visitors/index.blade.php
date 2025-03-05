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
                    <div class="mb-3 row">
                        <div class="col-md-4">
                            <label for="has_similarity">Cari Data (Berdasarkan: Nama)</label>
                            <input type="text" id="search" class="form-control"
                                placeholder="Cari Nomor Handphone atau Nama Lengkap" oninput="fetchData()" />
                        </div>

                        <div class="col-md-4">
                            <label for="has_similarity">Kategori Kunjungan</label>
                            <select id="visit_category" class="form-control" onchange="fetchData()">
                                <option value="">Semua Kategori</option>
                                <option value="1x">1x Kunjungan</option>
                                <option value="more_than_1x">Lebih dari 1x (1-3)</option>
                                <option value="more_than_3x">Lebih dari 3x (3-5)</option>
                                <option value="more_than_5x">Lebih dari 5x (5-10)</option>
                                <option value="more_than_10x">Lebih dari 10x</option>
                            </select>
                        </div>


                        <div class="col-md-4">
                            <label for="has_similarity">Filter Similarity</label>
                            <select id="has_similarity" class="form-control" onchange="fetchData()">
                                <option value="">Semua</option>
                                <option value="true">Dengan Similarity</option>
                                <option value="false">Tanpa Similarity</option>
                            </select>
                        </div>
                        
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nomor Handphone</th>
                                    <th>Nama Lengkap</th>
                                    <th>Total Berkunjung</th>
                                    <th>Kategori Kunjungan</th>
                                    <th>Status Data Mirip</th>
                                    <th>Label Kemiripan</th>
                                    <th style="width: 125px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="guest-list"></tbody>
                        </table>
                    </div>

                    <nav>
                        <ul class="pagination pagination-rounded mb-0" id="pagination-links"></ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
            integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script>
            let currentPage = 1;
        
            function fetchData(page = 1) {
                const search = document.getElementById('search').value;
                const visitCategory = document.getElementById('visit_category').value;
                const hasSimilarity = document.getElementById('has_similarity').value;
        
                axios.get('{{ route('visitors.list') }}', {
                    params: {
                        search: search,
                        page: page,
                        visit_category: visitCategory,
                        has_similarity: hasSimilarity,
                    }
                }).then(function(response) {
                    const data = response.data;
        
                    let guestList = '';
                    data.data.forEach(function(visitor) {
                        let visitCategoryText = '';
                        switch (visitor.visit_category) {
                            case '1x':
                                visitCategoryText = '1 kali';
                                break;
                            case 'more_than_1x':
                                visitCategoryText = 'Lebih dari 1 kali';
                                break;
                            case 'more_than_3x':
                                visitCategoryText = 'Lebih dari 3 kali';
                                break;
                            case 'more_than_5x':
                                visitCategoryText = 'Lebih dari 5 kali';
                                break;
                            case 'more_than_10x':
                                visitCategoryText = 'Lebih dari 10 kali';
                                break;
                            default:
                                visitCategoryText = visitor.visit_category;
                                break;
                        }
        
                        let similarityText = visitor.is_similar_data ? visitor.similarity_label.join(', ') : 'Tidak ada kemiripan';
        
                        guestList += `
                            <tr>
                                <td>${visitor.phone_number}</td>
                                <td>${visitor.full_name}</td>
                                <td>${visitor.guests_count}</td>
                                <td>${visitCategoryText}</td>
                                <td>${visitor.is_similar_data ? 'Ya' : 'Tidak'}</td>
                                <td>${similarityText}</td> <!-- Tampilkan kemiripan di sini -->
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
