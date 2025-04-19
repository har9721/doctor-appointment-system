@extends('layouts.app')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        </div>
        
        <x-appointments-cards />

        @if(Auth::user()->isAdmin() || Auth::user()->isDoctor())
            <div class="row">
                <!-- Area Chart -->
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow mb-4">
                        <div
                            class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-area">
                                <canvas id="myAreaChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pie Chart -->
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <div
                            class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            @if(Auth::user()->isAdmin())
                                <h6 class="m-0 font-weight-bold text-primary">Doctors Overview</h6>
                            @else
                                <h6 class="m-0 font-weight-bold text-primary">Patients Overview</h6>
                            @endif
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="chart-pie pt-4 pb-2">
                                <canvas id="myPieChart"></canvas>
                            </div>
                            <div class="mt-4 text-center small" style="visibility: hidden;">
                                <span class="mr-2">
                                    <i class="fas fa-circle text-primary"></i> M.D
                                </span>
                                <span class="mr-2">
                                    <i class="fas fa-circle text-success"></i> Surgery
                                </span>
                                <span class="mr-2">
                                    <i class="fas fa-circle text-info"></i> Neroulogy
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
   
        <x-show-appointments />
    </div>

@endsection
@push('scripts')
    <script type="text/javascript">
        let myAreaChart = "{{ route('get-area-chart-data') }}";
        let myPieChart = "{{ route('get-pie-chart-data') }}";
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ URL::to('assets/js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ URL::to('assets/js/demo/chart-pie-demo.js') }}"></script>
@endpush