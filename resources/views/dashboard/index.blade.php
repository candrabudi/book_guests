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
        <!-- Cards Section -->
        <div class="col-xl-3 col-lg-4">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="mdi mdi-account-multiple widget-icon"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0">Total Visitors</h5>
                    <h3 class="mt-3 mb-3">{{ $totalVisitors }}</h3>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-4">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="mdi mdi-account-check widget-icon"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0">Average Visits</h5>
                    <h3 class="mt-3 mb-3">{{ round($averageVisits) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-4">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="mdi mdi-account-remove widget-icon"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0">Similarity Percentage</h5>
                    <h3 class="mt-3 mb-3">{{ round($similarityPercentage, 2) }}%</h3>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-4">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="mdi mdi-account-multiple widget-icon"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0">Pending Guests</h5>
                    <h3 class="mt-3 mb-3">{{ $pendingGuests }}</h3>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-4">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="mdi mdi-account-check widget-icon"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0">Accepted Guests</h5>
                    <h3 class="mt-3 mb-3">{{ $acceptedGuests }}</h3>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-4">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="mdi mdi-account-remove widget-icon"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0">Rejected Guests</h5>
                    <h3 class="mt-3 mb-3">{{ $rejectedGuests }}</h3>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-4">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="mdi mdi-account-alert widget-icon"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0">Disposition Guests</h5>
                    <h3 class="mt-3 mb-3">{{ $dispositionGuests }}</h3>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-4">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="mdi mdi-calendar-clock widget-icon"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0">Rescheduled Guests</h5>
                    <h3 class="mt-3 mb-3">{{ $rescheduleGuests }}</h3>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-4">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="mdi mdi-calendar-clock widget-icon"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0">Notulensi</h5>
                    <h3 class="mt-3 mb-3">{{ $countNotulensi }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mt-4">
        <div class="col-xl-6 col-lg-6">
            <div class="card card-h-100">
                <div class="card-header">
                    <h4 class="header-title">Comparison Hari ini dan Kemarin</h4>
                </div>
                <div class="card-body">
                    <div id="visitChart" class="apex-charts"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6">
            <div class="card card-h-100">
                <div class="card-header">
                    <h4 class="header-title">Guest Comparison Chart</h4>
                </div>
                <div class="card-body">
                    <div id="guest-comparison-chart" class="apex-charts"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6">
            <div class="card card-h-100">
                <div class="card-header">
                    <h4 class="header-title">Comparison Sudah dan Belum Buat Janji</h4>
                </div>
                <div class="card-body">
                    <div id="appointmentChart" class="apex-charts"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6">
            <div class="card card-h-100">
                <div class="card-header">
                    <h4 class="header-title">Top 10 Dinas yang Sering di Assign</h4>
                </div>
                <div class="card-body">
                    <div id="chartCompanion" class="apex-charts"></div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 24px rgba(34, 41, 47, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            border: none;
        }

        .card-header {
            border-bottom: 1px solid #ebebeb;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .header-title {
            font-size: 18px;
            font-weight: 600;
            color: #343a40;
        }

        .card-body {
            padding: 0;
        }

        .apex-charts {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .apexcharts-canvas {
            max-width: 100%;
            height: auto;
        }

        .apexcharts-legend {
            margin-top: 15px;
        }

        .apexcharts-tooltip {
            background: #343a40 !important;
            color: #fff !important;
            border-radius: 5px;
        }

        .apexcharts-legend-text {
            font-size: 14px;
            font-weight: 500;
            color: #495057;
        }

        .apexcharts-pie-label {
            font-size: 14px;
            font-weight: 600;
            fill: #343a40;
        }

        .apexcharts-legend {
            margin-top: 10px;
            font-size: 13px;
            font-weight: 500;
            color: #495057;
        }

        .apexcharts-xaxis-label,
        .apexcharts-yaxis-label {
            font-size: 12px;
            color: #6c757d;
        }

        .apexcharts-tooltip {
            background-color: #212529;
            color: #fff;
            border-radius: 5px;
            font-size: 13px;
        }

        @media (max-width: 767px) {
            .card {
                padding: 15px;
            }

            .header-title {
                font-size: 16px;
            }
        }
    </style>

    <style>
        .card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.05);
            border: none;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s ease-in-out;
        }

        .card-header {
            border-bottom: 1px solid #f1f1f1;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header-title {
            font-size: 18px;
            font-weight: 600;
            color: #495057;
        }

        .card-body {
            padding: 0;
        }

        /* Gaya Chart */
        .apexcharts-xaxis-label,
        .apexcharts-yaxis-label {
            font-size: 14px;
            color: #7f8c8d;
        }

        .apexcharts-tooltip {
            background-color: #2c3e50;
            color: #fff;
            border-radius: 4px;
            font-size: 13px;
        }

        /* Hover effect pada card */
        .card:hover {
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.1);
        }

        /* Responsif untuk tampilan mobile */
        @media (max-width: 767px) {
            .card {
                padding: 15px;
            }

            .header-title {
                font-size: 16px;
            }
        }
    </style>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            var options = {
                chart: {
                    type: 'bar',
                    height: 400,
                    toolbar: {
                        show: false
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '50%',
                        endingShape: 'rounded',
                        borderRadius: 5
                    }
                },
                dataLabels: {
                    enabled: false
                },
                colors: ['#3498db', '#2ecc71', '#e74c3c', '#f39c12', '#8e44ad'],
                series: [{
                    name: 'Visit Categories',
                    data: [
                        {{ $chartData['visitCategories']['1x'] }},
                        {{ $chartData['visitCategories']['more_than_1x'] }},
                        {{ $chartData['visitCategories']['more_than_3x'] }},
                        {{ $chartData['visitCategories']['more_than_5x'] }},
                        {{ $chartData['visitCategories']['more_than_10x'] }},
                    ]
                }],
                xaxis: {
                    categories: ['1x', 'Lebih dari 1x', 'Lebih dari 3x', 'Lebih dari 5x', 'Lebih dari 10x'],
                    labels: {
                        style: {
                            fontSize: '14px',
                            fontFamily: 'Helvetica, Arial, sans-serif',
                            colors: '#7f8c8d'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            fontSize: '14px',
                            fontFamily: 'Helvetica, Arial, sans-serif',
                            colors: '#7f8c8d'
                        }
                    },
                    min: 0
                },
                grid: {
                    borderColor: '#ecf0f1',
                    strokeDashArray: 4
                },
                tooltip: {
                    theme: 'dark',
                    x: {
                        show: true
                    }
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right',
                    fontSize: '13px',
                    markers: {
                        radius: 12,
                        width: 12,
                        height: 12,
                        offsetX: -5
                    },
                    itemMargin: {
                        horizontal: 10,
                        vertical: 5
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#visitChart"), options);
            chart.render();
        </script>


        <script>
            var options = {
                series: [{
                    data: @json($companionCounts)
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: {
                        show: false
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800
                    }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 0,
                        horizontal: true, 
                        barHeight: '50%', 
                        endingShape: 'flat' 
                    }
                },
                dataLabels: {
                    enabled: false 
                },
                xaxis: {
                    categories: @json($companionNames), 
                    labels: {
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Helvetica, Arial, sans-serif',
                            colors: '#6c757d' 
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Helvetica, Arial, sans-serif',
                            colors: '#6c757d'
                        }
                    },
                    min: 0
                },
                grid: {
                    borderColor: '#ebedef',
                    strokeDashArray: 3 
                },
                tooltip: {
                    theme: 'dark',
                    x: {
                        show: true
                    },
                    y: {
                        formatter: function(val) {
                            return val + " companions";
                        }
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#chartCompanion"), options);
            chart.render();
        </script>
        <script>
            var options = {
                chart: {
                    type: 'donut',
                    height: 350,
                    toolbar: {
                        show: false 
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800
                    },
                    dropShadow: {
                        enabled: true,
                        top: 2,
                        left: 2,
                        blur: 3,
                        color: '#000',
                        opacity: 0.15
                    }
                },
                series: [{{ $yesCount }}, {{ $noCount }}],
                labels: ['Appointment Yes', 'Appointment No'],
                colors: ['#2196F3', '#FF5722'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        type: 'horizontal',
                        shadeIntensity: 0.5,
                        gradientToColors: ['#64B5F6', '#FF8A65'],
                        opacityFrom: 0.8,
                        opacityTo: 0.8,
                        stops: [0, 100]
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val, opts) {
                        return val.toFixed(1) + '%';
                    },
                    style: {
                        fontSize: '14px',
                        fontWeight: 600,
                        colors: ['#495057']
                    }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '75%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total',
                                    fontSize: '22px',
                                    fontWeight: 600,
                                    color: '#343a40'
                                }
                            }
                        }
                    }
                },
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                    fontSize: '14px',
                    labels: {
                        colors: ['#343a40']
                    },
                    markers: {
                        radius: 12,
                        width: 12,
                        height: 12,
                        offsetX: -5
                    },
                    itemMargin: {
                        horizontal: 10,
                        vertical: 5
                    }
                },
                stroke: {
                    show: false
                },
                tooltip: {
                    theme: 'dark',
                    y: {
                        formatter: function(val) {
                            return val + '%';
                        }
                    }
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            height: 300
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            var chart = new ApexCharts(document.querySelector("#appointmentChart"), options);
            chart.render();
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
                            height: 350,
                            toolbar: {
                                show: false
                            },
                            animations: {
                                enabled: true,
                                easing: 'easeinout',
                                speed: 800
                            }
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '55%',
                                endingShape: 'rounded',
                                borderRadius: 5
                            }
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
                            labels: {
                                style: {
                                    colors: '#6c757d',
                                    fontSize: '12px'
                                }
                            }
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    colors: '#6c757d',
                                    fontSize: '12px'
                                }
                            }
                        },
                        fill: {
                            opacity: 1,
                            colors: ['#2196F3', '#FF5722']
                        },
                        tooltip: {
                            theme: 'dark',
                            y: {
                                formatter: function(val) {
                                    return val + " guests";
                                }
                            }
                        },
                        legend: {
                            position: 'top',
                            horizontalAlign: 'right',
                            fontSize: '13px',
                            markers: {
                                radius: 12,
                                width: 12,
                                height: 12,
                                offsetX: -5
                            },
                            itemMargin: {
                                horizontal: 10,
                                vertical: 5
                            }
                        },
                        grid: {
                            show: true,
                            borderColor: '#f1f1f1'
                        }
                    };

                    var chart = new ApexCharts(document.querySelector("#guest-comparison-chart"), chartOptions);
                    chart.render();
                })
                .catch(error => console.error('Error:', error));
        </script>
    @endpush
@endsection
