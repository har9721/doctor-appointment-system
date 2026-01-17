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
                        <p id="available-date" value=""></p>
                        <p id="selectedTimeSlot" value=""></p>
                        <p id="consultationFeesInfo" value=""></p>
                        <p id="advanceFees" value=""></p>
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