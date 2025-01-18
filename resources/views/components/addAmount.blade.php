<div class="modal modal-md" id="add_amount_modal" tabindex="-1" role="modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Add Fees</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="hidden" id="appointment_id" value="">

                        <label for="amount"><b>Fees </b><span style="color: red;">*</span> :</label>
                        <input type="text" name="amount" id="amount" class="form-control" value="" placeholder="enter amount" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success addAmount" id="addAmount">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
