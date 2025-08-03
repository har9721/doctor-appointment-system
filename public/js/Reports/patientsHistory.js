getPatientsList();

let role = $('#role').val();

let isPatients = (role == 'Patients') ? false : true;

//start date datepicker
$('#from_date').datetimepicker({
    format:"d-m-Y",
    timepicker: false,
    datepicker : true,
    changeMonth:true,
    changeYear:true,
    // minDate : "-1",
    scrollInput : false,
});

//start date datepicker
$('#to_date').datetimepicker({
    format:"d-m-Y",
    timepicker: false,
    datepicker : true,
    changeMonth:true,
    changeYear:true,
    minDate : "-1",
    scrollInput : false,
});

$('#patientHistoryTable').DataTable({
    paging: true,
    pageLength:10,
    processing:true,
    serverside:true,
    Bsort: true,
    dom: 'lBfrtip',
    ajax : {
        url : getPatientsHistory,
        beforeSend : function()
        {
            $('#search').attr('disabled',true);
            $('#loader').css('display','block');
        },
        data: function(d)
        {
            d.from_date = $('#from_date').val()
            d.to_date = $('#to_date').val()
            d.id = $('#patient_name_list').val()
        },
        complete: function()
        {
            $('#search').attr('disabled',false);
            $('#loader').css('display','none');
        }
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex',"sortable": true, "searchable": false},
        {data: 'appointmentNo', name:'appointmentNo',"sortable": true, "searchable": true},
        {data: 'patients_full_name', name:'patients_full_name',"sortable": true, "searchable": true},
        {data: 'appointmentDate', name:'appointmentDate',"sortable": true, "searchable": true},
        {data: 'reason', name:'reason',"sortable": true, "searchable": true},
        // {data: 'diagnosis', name:'diagnosis',"sortable": true, "searchable": true},
        {data: 'status', name:'status',"sortable": true, "searchable": true},
        {data: 'payment', name:'payment',"sortable": true, "searchable": true},
        {data: 'prescriptions', name:'prescriptions',"sortable": true, "searchable": true}
    ],
});

$(document).on('click','.prescription_summary', function(){
    const prescription_id = $(this).data('prescriptions_id');

    $('#loader').css('display','block');

    $('#prescriptions_details tbody.details').empty();

    $.ajax({
        type : 'get',
        url : fetchPrescriptionsDetails,
        data : {prescription_id:prescription_id},
        success: function(response)
        {
            let details;
            let i = 1;
            response.medicines.forEach(medicine => {
                details = `
                    <tr>
                        <td>${i}</td>
                        <td>${medicine.medicine}</td>
                        <td>${medicine.dosage}</td>
                        <td>${medicine.instruction}</td>
                    </tr>
                `;
                i++;

                $('#prescriptions_details tbody.details').append(details);
            });

            $('#additional_instructions').text(response.instructions  ? response.instructions : 'No additional instructions provided.');

            $('#prescriptionSummaryModal').modal('show');
        },
        error: function () {
            $('#prescriptionSummaryModal .modal-body').html('<p>Unable to fetch details. Please try again later.</p>');
            $('#prescriptionSummaryModal').modal('show');
        },
        complete: function(){
            $('.prescription_summary').attr('disabled',false);
            $('#loader').css('display','none');
        }
    });
});

function reload_table() {
    var startDate = $('#from_date').val();
    var toDate = $('#to_date').val();
    var patient_name = $('#patient_name_list').val();
    
    if (startDate != '' && toDate != '')
    {
        $('#patientHistoryTable').DataTable().ajax.reload();
    }
    else
    {
        Swal.fire("Please select the Date");
    }
}

function getPatientsList()
{
    $.ajax({
        type : 'GET',
        url : getPatientList,
        success : function(response) {
            $.each(response, function (indexInArray, valueOfElement) 
            {                
                $('#patient_name_list').append($("<option></option>")
                    .attr('value',response[indexInArray].id)
                    .text(valueOfElement.patient_full_name));
            });
        }
    });

    $('#patient_name_list').select2();
}

$(document).on('click','.payment_summary', function(){
    const appointment_id = $(this).data('id');
    $('#loader').css('display','block');

    $.ajax({
        type : 'get',
        url : fetchPaymentSummary,
        data : {appointment_id:appointment_id},
        success: function(response)
        {
            const details = `
                <ul>
                    <li><strong>Payment ID:</strong> ${response.res_payment_id}</li>
                    <li><strong>Order ID:</strong> ${response.order_id}</li>
                    <li><strong>Amount:</strong> ₹ ${response.amount}.00</li>
                    <li><strong>Status:</strong> ${response.status}</li>
                    <li><strong>Transaction Date:</strong> ${response.formatted_date}</li>
                </ul>`;

            $('#paymentSummaryModal .modal-body').html(details);
            $('#paymentSummaryModal').modal('show');
        },
        error: function () {
            $('#paymentSummaryModal .modal-body').html('<p>Unable to fetch details. Please try again later.</p>');
            $('#paymentSummaryModal').modal('show');
        },
        complete: function(){
            $('.payment_summary').attr('disabled',false);
            $('#loader').css('display','none');
        }
    });
});