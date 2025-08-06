@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between">
            <h4 class="mt-2 font-weight-bold text-primary">Monthly Revenue Report</h4>
        </div>
        <div class="card-body">
            <form id="dateFilterForm" method="GET"  action="">
                <div class="row">
                    <div class="col-md-3">
                        <label for="date" class="font-weight-bold">From Date : <span style="color: red;">*</span></label>
                        <input type="text" id="from_date" name="from_date" class="datetimepicker form-control" value="<?php echo date('01-m-Y') ?>" onkeydown="return false;">
                    </div>

                    <div class="col-md-3">
                        <label for="date" class="font-weight-bold">To Date : <span style="color: red;">*</span></label>
                        <input type="text" id="to_date" name="to_date" class="datetimepicker form-control" value="<?php echo date('d-m-Y',strtotime(date('t-m-Y'))) ?>" onkeydown="return false;">
                    </div>

                    <div class="col-md-3 mt-4">
                        <button type="button" onclick="reload_table()" class="btn btn-success form-group mt-2" id="search">Search</button>
                    </div>
                </div>
            </form>

            <div class="chart-pie pt-4 pb-2">
                <canvas id="revenuePieChart"></canvas>
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
@endsection
@push('scripts')

<script type="text/javascript">
    let getRevenue = "{{ route('admin.fetch-revenue-details') }}";
    let loadMyChart = false;
    let loadRevenueChart = true;

    $('#from_date').datetimepicker({
        format: "d-m-Y",
        timepicker: false,
        datepicker: true,
        changeMonth: true,
        changeYear: true,
        scrollInput: false,
        onChangeDateTime: function (dp, $input) {
            let selectedDate = $input.val();
            if (selectedDate) {
                // Convert d-m-Y to Y-m-d
                let parts = selectedDate.split("-");
                let reformattedDate = `${parts[2]}-${parts[1]}-${parts[0]}`;
                
                $('#to_date').datetimepicker('setOptions', {
                    minDate: reformattedDate
                });
            }
        }
    });

    $('#to_date').datetimepicker({
        format: "d-m-Y",
        timepicker: false,
        datepicker: true,
        changeMonth: true,
        changeYear: true,
        scrollInput: false,
        onShow: function (ct) {
            let fromDate = $('#from_date').val();
            if (fromDate) {
                let parts = fromDate.split("-");
                let reformattedDate = `${parts[2]}-${parts[1]}-${parts[0]}`;
                this.setOptions({
                    minDate: reformattedDate
                });
            }
        }
    });

</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ URL::to('assets/js/demo/chart-pie-demo.js') }}"></script>

<!-- <script src="{{ asset('js/reports/revenue.js') }}"></script> -->
@endpush