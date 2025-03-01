@extends('layouts.app')
@section('title', 'Data Notulensi')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Data Notulensi</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-xl-8">
                            <form class="row gy-2 gx-2 align-items-center justify-content-xl-start justify-content-between">
                                <div class="col-auto">
                                    <label for="inputPassword2" class="visually-hidden">Search</label>
                                    <input type="search" class="form-control" id="inputPassword2" placeholder="Search...">
                                </div>
                                <div class="col-auto">
                                    <div class="d-flex align-items-center">
                                        <label for="status-select" class="me-2">Follow Up Status</label>
                                        <select class="form-select" id="status-select">
                                            <option selected>Choose...</option>
                                            <option value="1">Tindah Lanjut</option>
                                            <option value="2">Tidak Tindak Lanjut</option>
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
                                    <th>Judul</th>
                                    <th>Tindah Lanjut ?</th>
                                    <th>Total Foto</th>
                                    <th style="width: 125px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($notulensis as $notulensi)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($notulensi->created_at)->format('Y/m/d H:i') }}</td>
                                        <td>
                                            <h5 class="my-0">{{ $notulensi->title }}</h5>
                                        </td>

                                        <td>
                                            @if ($notulensi->appointment == 1)
                                                <h5 class="my-0"><span class="badge badge-warning-lighten">Ditindak Lanjut</span>
                                                </h5>
                                            @else
                                                <h5 class="my-0"><span
                                                        class="badge badge-danger-lighten">Tidak Tindak Lanjut</span></h5>
                                            @endif
                                        </td>
                                        <td>
                                            {{ count($notulensi->photos) }} Foto
                                        </td>
                                        <td>
                                            <a href="{{ route('guests.detail', $notulensi->guest_id) }}" class="btn btn-sm btn-info">Detail</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
