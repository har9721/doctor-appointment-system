@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between">
            <h4 class="mt-2 font-weight-bold text-primary">Patient Booking Time Preferences Report</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive mt-4">
                <table class="table table-bordered" id="timePreference" width="100%" cellspacing="0">
                    <thead>
                        <tr class="text-center">
                            <th>Sr.No</th>
                            <th>Time Slot</th>
                            <th>No of Booking</th>
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
    let timePreferenceUrl = "{{ route('appointments.reports.fetchTimePreferenceReport') }}";
</script>

<script src="{{ asset('js/Reports/timePreference.js') }}"></script>
@endpush