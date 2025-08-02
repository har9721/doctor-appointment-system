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

const role_Id = $('#role_id').val();
let isHideDoctor = (roleName === 'Doctor') ? false : true;
let isHidePatien = (roleName === 'Patients') ? false : true;

var table = $('#appointmentHistory').DataTable({
    paging: true,
    pageLength:10,
    processing:true,
    serverside:true,
    Bsort: true,
    order : [],
    dom: 'lBfrtip',
    ajax : {
        url : getAppointmentList,
        beforeSend : function()
        {
            $('#search').attr('disabled',true);
        },
        data: function(d)
        {
            d.from_date = $('#from_date').val()
            d.to_date = $('#to_date').val()
            d.status = $('#status').val()
            d.appointment_no = $('#appointment_no').val()
        },
        complete: function()
        {
            $('#search').attr('disabled',false);
        }
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex',"sortable": true, "searchable": false},
        {data: 'appointment_no', name: 'appointment_no',"sortable": true, "searchable": false},
        {data: 'doctor_full_name', name:'doctor_full_name',"sortable": true, "searchable": true, "visible" : isHideDoctor},
        {data: 'patient_full_name', name:'patient_full_name',"sortable": true, "searchable": true, "visible" : isHidePatien},
        {data: 'appointmentDate', name:'appointmentDate',"sortable": true, "searchable": true},
        {data: 'time', name:'time',"sortable": true, "searchable": true},
        {data: 'status', name:'status',"sortable": true, "searchable": true},
        {data: 'amount', name:'amount',"sortable": true, "searchable": true},
        {data: 'payment_status', name:'payment_status',"sortable": true, "searchable": true},
        {data: 'action', name:'action',"sortable": false, "searchable": false},
    ],
});

$(document).on('click','.sendMail', function(){
    let id = $(this).data('id');

    Swal.fire({
        title: "Are you sure?",
        text: "You really want to send mail",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, send it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type : "post",
                url : sendMail,
                data : {'id' : id},
                success : function (response){
    
                    if(response['status'] == 'success'){
                        Swal.fire({
                            title: "Success",
                            text: response['message'],
                            icon: "success",
                            timer: 5000
                        });
    
                    }else{    
                        Swal.fire({
                            title: "Success",
                            text: response['message'],
                            icon: "success",
                            timer: 5000
                        });
                    }

                    $('#doctorList').DataTable().ajax.reload();
                },
                error : function(response)
                {
                    if(response.status === 422)
                    {
                        var errors = response.responseJSON;
                        Swal.fire({
                            title: "Error",
                            text: errors.message,
                            icon: "error",
                            timer: 5000
                        });
                    }
                }
            })
        }
    });
});

function reload_table() {
    var startDate = $('#from_date').val();
    var toDate = $('#to_date').val();
    var appointment_no = $('#appointment_no').val();
    
    if (startDate != '' && toDate != '')
    {
        $('#appointmentHistory').DataTable().ajax.reload();
    }
    else
    {
        Swal.fire("Please select the Date");
    }
}

$(document).on('click','#offline-pay', function()
{
    const appointment_id = $('#appointment_id').val();
    const amount = $('#amount_hidden').val();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type : "post",
        url : markPaymentDone,
        data : {'appointment_id' : appointment_id,'amount' : amount,'email': 'johndoe@yopmail.com', 'name' : 'John Doe', 'contact' : '9857551454'},
        success : function (response){
            if(response['status'] == 'success'){
                Swal.fire({
                    title: "Success",
                    text: response['message'],
                    icon: "success",
                    timer: 5000
                });

            }else{    
                Swal.fire({
                    title: "Success",
                    text: response['message'],
                    icon: "success",
                    timer: 5000
                });
            }

            setTimeout(function(){
                window.location.reload();
            },2000);
        },
        error : function(response)
        {
            if(response.status === 422)
            {
                var errors = response.responseJSON;
                Swal.fire({
                    title: "Error",
                    text: errors.message,
                    icon: "error",
                    timer: 5000
                });
            }
        }
    })
});