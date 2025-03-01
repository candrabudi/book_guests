@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-center">
                <h4 class="page-title mb-0">Dashboard</h4>
                <div class="page-title-right">
                    <form class="d-flex align-items-center">
                        <div class="input-group" id="datepicker1">
                            <input type="text" class="form-control" data-provide="datepicker"
                                data-date-container="#datepicker1" value="{{ \Carbon\Carbon::now()->format('d/m/Y') }}">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="mdi mdi-calendar-range font-13"></i>
                            </span>
                        </div>
                        <div class="ms-2" style="flex-grow: 1;">
                            <button class="btn btn-primary w-100">
                                <i class="mdi mdi-filter-variant"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-xl-5 col-lg-6">

            <div class="row">
                <div class="col-sm-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="mdi mdi-account-multiple widget-icon"></i>
                            </div>
                            <h5 class="text-muted fw-normal mt-0" title="Number of Customers">Data Tamu Pending</h5>
                            <h3 class="mt-3 mb-3">{{ $pendingGuests }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="mdi mdi-account-check widget-icon"></i>
                            </div>
                            <h5 class="text-muted fw-normal mt-0" title="Number of Accepted Guests">Data Tamu Diterima</h5>
                            <h3 class="mt-3 mb-3">{{ $acceptedGuests }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="mdi mdi-account-remove widget-icon"></i>
                            </div>
                            <h5 class="text-muted fw-normal mt-0" title="Number of Rejected Guests">Data Tamu Ditolak</h5>
                            <h3 class="mt-3 mb-3">{{ $rejectedGuests }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="mdi mdi-account-alert widget-icon"></i>
                            </div>
                            <h5 class="text-muted fw-normal mt-0" title="Number of Disposition Guests">Data Tamu Disposisi
                            </h5>
                            <h3 class="mt-3 mb-3">{{ $dispositionGuests }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="mdi mdi-calendar-clock widget-icon"></i>
                            </div>
                            <h5 class="text-muted fw-normal mt-0" title="Number of Rescheduled Guests">Data Tamu Dijadwalkan
                                Ulang</h5>
                            <h3 class="mt-3 mb-3">{{ $rescheduleGuests }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="mdi mdi-calendar-clock widget-icon"></i>
                            </div>
                            <h5 class="text-muted fw-normal mt-0" title="Number of Rescheduled Guests">Data Notulensi</h5>
                            <h3 class="mt-3 mb-3">{{ $countNotulensi }}</h3>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-xl-7 col-lg-6">
            <div class="card card-h-100">
                <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">Comparison Hari ini dan Kemarin</h4>
                </div>
                <div class="card-body pt-0">
                    <div dir="ltr">
                        <div id="guest-comparison-chart" class="apex-charts" data-colors="#727cf5,#91a6bd40"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-7 col-lg-6">
            <div class="card card-h-100">
                <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">Comparison Sudah dan Belum Buat Janji </h4>
                </div>
                <div class="card-body pt-0">
                    <div dir="ltr">
                        <div style="width: 50%; margin: 0 auto;">
                            <canvas id="appointmentChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-5 col-lg-6">
            <div class="card card-h-100">
                <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">Top 10 Dinas yang sering di assign</h4>
                </div>
                <div class="card-body pt-0">
                    <div dir="ltr">
                        <div style="width: 100%; margin: 0 auto;">
                            <div id="chartCompanion" style="width: 100%; margin-top: 30px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            var options = {
                series: [{
                    data: @json($companionCounts) // Data jumlah penggunaan companion
                }],
                chart: {
                    type: 'bar',
                    height: 350
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        borderRadiusApplication: 'end',
                        horizontal: true,
                    }
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: @json($companionNames), // Nama-nama companions
                }
            };
    
            var chart = new ApexCharts(document.querySelector("#chartCompanion"), options);
            chart.render();
        </script>
        <script>
            var ctx = document.getElementById('appointmentChart').getContext('2d');
            var appointmentChart = new Chart(ctx, {
                type: 'pie', // Tipe chart pie
                data: {
                    labels: ['Appointment Yes', 'Appointment No'],
                    datasets: [{
                        label: 'Appointments',
                        data: [{{ $yesCount }}, {{ $noCount }}], 
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(255, 99, 132, 0.7)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': ' + tooltipItem.raw;
                                }
                            }
                        }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });
        </script>
        <script>
            fetch('/dashboard/guest-comparison')
                .then(response => response.json())
                .then(data => {
                    const chartOptions = {
                        series: [{
                            name: 'Today',
                            data: Object.values(data.today)
                        }, {
                            name: 'Yesterday',
                            data: Object.values(data.yesterday)
                        }],
                        chart: {
                            type: 'bar',
                            height: 350
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '55%',
                                endingShape: 'rounded'
                            },
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            show: true,
                            width: 2,
                            colors: ['transparent']
                        },
                        xaxis: {
                            categories: Object.keys(data.today),
                        },
                        fill: {
                            opacity: 1
                        },
                        tooltip: {
                            y: {
                                formatter: function(val) {
                                    return val + " guests"
                                }
                            }
                        }
                    };

                    var chart = new ApexCharts(document.querySelector("#guest-comparison-chart"), chartOptions);
                    chart.render();
                })
                .catch(error => console.error('Error:', error));
        </script>
    @endpush
@endsection
