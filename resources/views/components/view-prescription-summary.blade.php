<div class="modal fade" id="prescriptionSummaryModal" tabindex="-1" aria-labelledby="prescriptionSummaryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="prescriptionSummaryModalLabel">Prescriptions Summary</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="invoice-box">
                    <table id="prescriptions_details" class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>SrNo.</th>
                                <th>Medicine Name</th>
                                <th>Dosage</th>
                                <th>Instructions</th>
                            </tr>
                        </thead>
                        <tbody class="details"> 
                        </tbody>
                    </table>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <b>Additional Instructions : </b>
                            <span id="additional_instructions"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
