<div class="row">
    <div class="col-xl-6 col-lg-7">
        <div class="card shadow mb-4">
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Upcomig Appointments</h6>
            </div>
            <div class="card-body">
                <div class="table-rescol-sm-12 table-responsive" id="tableContent">  
                    <table class="table toggle-arrow-tiny border-bottom-0" id="RecentProjectList" data-page-size="15" style="width:100%">
                        <thead class="bg-gradient-info text-white">                                               
                            <tr>
                                @if(Auth::user()->isDoctor() != 'Doctor')
                                    <th scope="col">Doctor</th>
                                @endif
                                @if(Auth::user()->isPatients() != 'Patients')
                                    <th scope="col">Patient</th>
                                @endif
                                <th scope="col">Date</th>
                                <th scope="col">Time</th>
                                <th scope="col">Status</th>
                            </tr>                                                
                        </thead>
                        <tbody class="bg-white">
                            @if(count($appointments['upcoming_appointments']) == 0)
                                <tr>
                                    <td colspan="5" class="text-center">No Upcoming Appointments</td>
                                </tr>
                            @else
                                @foreach($appointments['upcoming_appointments'] as $appointment)
                                    <tr>
                                        @if(Auth::user()->isDoctor() != 'Doctor')
                                            <td>{{ $appointment->doctor_full_name }}</td>
                                        @endif
                                        @if(Auth::user()->isPatients() != 'Patients')
                                            <td>{{ $appointment->patient_full_name }}</td>
                                        @endif
                                        <td>{{ $appointment->appointmentDate }}</td>
                                        <td>{{ $appointment->time }}</td>
                                        <td>{{ $appointment->status }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>                
                </div>
            </div>
        </div>
    </div>

    <!-- -------------------------------------past appointments----------------------------------------- -->
    <div class="col-xl-6 col-lg-7">
        <div class="card shadow mb-4">
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Recent Appointments</h6>
            </div>
            <div class="card-body">
                <div class="table-rescol-sm-12 table-responsive" id="tableContent">  
                    <table class="table toggle-arrow-tiny border-bottom-0" id="RecentProjectList" data-page-size="15" style="width:100%">
                        <thead class="bg-gradient-success text-white">                                               
                            <tr>
                                @if(Auth::user()->isDoctor() != 'Doctor')
                                    <th scope="col">Doctor</th>
                                @endif
                                @if(Auth::user()->isPatients() != 'Patients')
                                    <th scope="col">Patient</th>
                                @endif
                                <th scope="col">Date</th>
                                <th scope="col">Time</th>
                                <th scope="col">Payment</th>
                            </tr>                                                
                        </thead>
                        <tbody class="bg-white">
                            @if(count($appointments['completed_appointments']) == 0)
                                <tr>
                                    <td colspan="5" class="text-center">No Completed Appointments</td>
                                </tr>
                            @else
                                @foreach($appointments['completed_appointments'] as $appointment)
                                    <tr>
                                        @if(Auth::user()->isDoctor() != 'Doctor')
                                            <td>{{ $appointment->doctor_full_name }}</td>
                                        @endif
                                        @if(Auth::user()->isPatients() != 'Patients')
                                            <td>{{ $appointment->patient_full_name }}</td>
                                        @endif
                                        <td>{{ $appointment->appointmentDate }}</td>
                                        <td>{{ $appointment->time }}</td>
                                        <td>{{ $appointment->payment_status }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>                
                </div>
            </div>
        </div>
    </div>
</div>