<div class="modal modal-md" id="add_prescription_modal" tabindex="-1" role="modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_heading"><b>Add Prescription</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addPrescriptions" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="appointmentID" name="appointment_id" value="">
                    <input type="hidden" id="presciptionsID" name="prescription_id" value="">
                    <input type="hidden" id="patient_id" name="patient_id" value="">
                    <input type="hidden" id="doctor_id" name="doctor_id" value="">
                    <input type="hidden" id="hidden_mode" name="hidden_mode" value="">

                    <label>Medicines:</label>
                    <div id="medicine-fields">
                        <fieldset class="border border-warning p-2 rounded mb-2 medicine-group">
                            <legend class="float-none w-auto px-2 text-success">Medicine :</legend>
                            <div class="">
                                <input type="text" name="medicines[]" class="form-control mb-2" placeholder="Medicine Name" autocomplete="off">
                                <input type="text" name="dosage[]" class="form-control mb-2" placeholder="Dosage (e.g., 1 tablet)" autocomplete="off">
                                <input type="text" name="instructions[]" class="form-control mb-2" placeholder="Instructions (e.g., After food)" autocomplete="off">
                            </div>
                        </fieldset>
                    </div>

                    <button type="button" class="btn btn-success mt-2" id="add-medicine">Add More Medicines</button>
                    <hr />
                    <label>Additional Instructions:</label>
                    <textarea name="general_instructions" id="general_instructions" class="form-control" placeholder="E.g., Take after food"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success addPrescriptions">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
