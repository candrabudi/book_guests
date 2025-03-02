@extends('layouts.app')
@section('title', 'Laporan Buku Tamu')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Laporan Buku Tamu</h4>
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
                                <div class="col-auto">
                                    <div class="d-flex align-items-center">
                                        <label for="status-select" class="me-2">Status</label>
                                        <select class="form-select" id="status-select">
                                            <option value="">Filter Status</option>
                                            <option value="pending">Pending</option>
                                            <option value="accepted">Diterima</option>
                                            <option value="disposition">Disposisi</option>
                                            <option value="reschedule">Reschedule</option>
                                            <option value="rejected">Ditolak</option>
                                            <option value="completed">Selesai</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>


                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal Input</th>
                                    <th>Nama Lengkap</th>
                                    <th>Nomor Handphone</th>
                                    <th>Status</th>
                                    <th>Sudah Janji ?</th>
                                    <th style="width: 125px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="guest-list">

                            </tbody>
                        </table>
                    </div>

                    <nav>
                        <ul class="pagination pagination-rounded mb-0" id="pagination-links">
                            <!-- Pagination links will be dynamically inserted here -->
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.7.8/axios.min.js"
            integrity="sha512-v8+bPcpk4Sj7CKB11+gK/FnsbgQ15jTwZamnBf/xDmiQDcgOIYufBo6Acu1y30vrk8gg5su4x0CG3zfPaq5Fcg=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const perPage = 10;
                let currentPage = 1;

                function fetchGuests(page = 1, search = '', status = '') {
                    axios.get(`/history/guests/list?page=${page}&search=${search}&per_page=${perPage}&status=${status}`)
                        .then(response => {
                            const data = response.data;
                            const guests = data.data;
                            const paginationLinks = data.links;
                            renderGuests(guests);
                            renderPagination(paginationLinks);
                        })
                        .catch(error => {
                            console.error("Error fetching guests:", error);
                        });
                }

                function formatDateIndo(dateString) {
                    const date = new Date(dateString);

                    const day = date.getDate().toString().padStart(2, '0');
                    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                        'September', 'Oktober', 'November', 'Desember'
                    ];
                    const month = months[date.getMonth()];
                    const year = date.getFullYear();

                    return `${day} ${month} ${year}`;
                }

                function toCamelCase(str) {
                    return str
                        .toLowerCase()
                        .split(' ')
                        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                        .join(' ');
                }



                function renderGuests(guests) {
                    let guestList = document.getElementById('guest-list');
                    guestList.innerHTML = '';

                    guests.forEach(guest => {
                        guestList.innerHTML += `
                            <tr>
                                <td>${formatDateIndo(guest.created_at)}</td>
                                <td>
                                    <h5 class="my-0">${toCamelCase(guest.full_name)}</h5>
                                    <p class="mb-0 txt-muted">${guest.institution_name}</p>
                                </td>
                                <td>${guest.phone_number}</td>
                                <td>
                                    ${getStatusBadge(guest.status)}
                                </td>
                                <td>${guest.appointment === 'yes' ? 'Ya' : 'Tidak'}</td>
                                <td><a href="/guests/detail/${guest.id}" class="btn btn-sm btn-info">Detail</a></td>
                            </tr>
                        `;
                    });
                }

                function getStatusBadge(status) {
                    switch (status) {
                        case 'pending':
                            return '<h5 class="my-0"><span class="badge badge-warning-lighten">Pending</span></h5>';
                        case 'accepted':
                            return '<h5 class="my-0"><span class="badge badge-success-lighten">Diterima</span></h5>';
                        case 'completed':
                            return '<h5 class="my-0"><span class="badge badge-success-lighten">Diterima</span></h5>';
                        case 'disposition':
                            return '<h5 class="my-0"><span class="badge badge-info-lighten">Disposisi</span></h5>';
                        case 'rejected':
                            return '<h5 class="my-0"><span class="badge badge-danger-lighten">Ditolak</span></h5>';
                        case 'reschedule':
                            return '<h5 class="my-0"><span class="badge badge-secondary-lighten">Reschedule</span></h5>';
                        default:
                            return '';
                    }
                }

                function renderPagination(links) {
                    let paginationLinks = document.getElementById('pagination-links');
                    paginationLinks.innerHTML = '';

                    const previousLink = links.find(link => link.label === '&laquo;');
                    paginationLinks.innerHTML += `
                        <li class="page-item ${previousLink.url ? '' : 'disabled'}">
                            <a class="page-link" href="javascript: void(0);" aria-label="Previous" data-page="${previousLink.url ? new URL(previousLink.url).searchParams.get('page') : null}">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    `;

                    links.forEach(link => {
                        if (!['&laquo;', '&raquo;'].includes(link.label)) {
                            paginationLinks.innerHTML += `
                                <li class="page-item ${link.active ? 'active' : ''}">
                                    <a class="page-link" href="javascript: void(0);" data-page="${link.url ? new URL(link.url).searchParams.get('page') : null}">
                                        ${link.label}
                                    </a>
                                </li>
                            `;
                        }
                    });

                    const nextLink = links.find(link => link.label === '&raquo;');
                    paginationLinks.innerHTML += `
                        <li class="page-item ${nextLink.url ? '' : 'disabled'}">
                            <a class="page-link" href="javascript: void(0);" aria-label="Next" data-page="${nextLink.url ? new URL(nextLink.url).searchParams.get('page') : null}">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    `;

                    document.querySelectorAll('.page-link').forEach(link => {
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            const page = e.target.getAttribute('data-page');
                            if (page) {
                                currentPage = page;
                                fetchGuests(currentPage);
                            }
                        });
                    });
                }


                fetchGuests(currentPage);

                document.getElementById('inputSearch').addEventListener('input', function(e) {
                    const search = e.target.value;
                    const status = document.getElementById('status-select').value;
                    fetchGuests(currentPage, search, status);
                });

                document.getElementById('status-select').addEventListener('change', function(e) {
                    const search = document.getElementById('inputSearch').value;
                    const status = e.target.value;
                    fetchGuests(currentPage, search, status);
                });
            });
        </script>
    @endpush
@endsection
