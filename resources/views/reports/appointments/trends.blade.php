@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between">
            <h4 class="mt-2 font-weight-bold text-primary">Appointments Trends Report</h4>
        </div>
        <div class="card-body">
            <div class="col-md-3">
                <label for="monthRangeInput" class="form-label"><b>Select Month Range :</b></label>
                <select id="periodFilter" class="form-control">
                    <option value="q1">Q1 (Jan-Mar)</option>
                    <option value="q2">Q2 (Apr-Jun)</option>
                    <option value="h1">First Half (Jan–Jun)</option>
                    <option value="h2">Second Half (Jul–Dec)</option>
                    <option value="all">Full Year</option>
                </select>
            </div>

            <div class="chart-area" id="charts" style="overflow-x: auto;">
                <div style="min-width: 1200px;">
                    <canvas id="myStackBarChart"></canvas>
                </div>
            </div>

            <div class="table-responsive mt-4" id="yearlyDataDiv" style="display: none;">
                <table class="table table-bordered" id="allYearData" width="100%" cellspacing="0">
                    <thead>
                        <tr class="text-center">
                            <th>Sr.No</th>
                            <th>Month Name</th>
                            <th>Total Appointment</th>
                            <th>Completed</th>
                            <th>Confirmed</th>
                            <th>Pending</th>
                            <th>Canceled</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')

<script type="text/javascript">
    let trendsUrl = "{{ route('appointments.reports.fetchTrendsReport') }}";
    let quater = @json($quater);
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/Reports/trends.js') }}"></script>
@endpush