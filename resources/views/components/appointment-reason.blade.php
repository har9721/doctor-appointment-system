<div>
    <div class="modal fade" id="bookModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Appointment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <small class="text-muted" style="color:#f59e0b;font-weight:500;">Please review details before confirming</small>
                        <p class="mt-2" id="available-date" value=""></p>
                        <p id="selectedTimeSlot" value=""></p>
                        <p id="consultationFeesInfo" value=""></p>
                        <p id="advanceFees" value=""></p>

                        @if(in_array(Auth::user()->role_ID, config('constant.admin_and_doctor_role_ids')))
                            <div class="form-group">
                                <label class="text-muted"><strong>
                                    Select Patient :
                                </strong></label>
                                <select id="patients" class="form-control col-md-6" style="border-radius:10px;height:45px;">
                                    <option value="" selected>Select patient</option>
                                </select>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="reason" class="form-label"><strong>Reason for Appointment <small class="text-muted">(optional)</small></strong></label>
                            <textarea name="reason" id="reason" class="form-control" placeholder="e.g., headache, routine checkup"></textarea>
                        </div>
                        <input type="hidden" name="slot_id" id="slot_id" value="" />
                        <input type="hidden" name="consult_fees" id="consultation_fees" value="" />
                        <input type="hidden" name="appointment_date" id="appointment_date" value="" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="reasonModal" class="btn btn-success">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>