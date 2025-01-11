<!-- --------------------make payment modal-------------- -->
<div class="modal modal-md" id="makePaymentModal" tabindex="-1" role="modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Payment Details</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="submitPaymentForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="appointment_id" name="appointment_id" value="">

                    <div class="mb-3">
                        <label for="doctor_name" class="form-label>">Doctor Name</label>
                        <input type="text" class="form-control" id="doctor_name" name="doctor_name" value="" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="appointment_Date" class="form-label>">Appointment Date & Time</label>
                        <input type="text" class="form-control" id="appointment_Date" name="appointment_Date" value="" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount (₹)</label>
                        <input type="text" class="form-control" id="amount" name="amount" value="" readonly>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" title="Pay" class="btn btn-success" id="rzp-button">Pay</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
