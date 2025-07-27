fetchDoctorList();

$('.js-example-basic-multiple').select2();

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

if(loadDoctorPerformanceTable)
{
    var table = $('#doctorPerformanceReport').DataTable({
        paging: true,
        pageLength:10,
        processing:true,
        serverside:true,
        Bsort: true,
        columnDefs: [
            { orderable: true, className: 'reorder', targets: [3] },
        ],
        dom: 'lBfrtip',
        ajax : {
            url : doctorPerformance,
            beforeSend : function()
            {
                $('#search').attr('disabled',true);
            },
            data: function(d)
            {
                d.from_date = $('#from_date').val()
                d.to_date = $('#to_date').val()
                d.id = $('#doctor_name_list').val()
            },
            complete: function()
            {
                $('#search').attr('disabled',false);
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex',"sortable": true, "searchable": false},
            {data: 'doctor_full_name', name:'doctor_full_name',"sortable": true, "searchable": true},
            {data: 'completed_count', name:'completed_count',"sortable": true, "searchable": true},
            {data: 'cancelled_count', name:'cancelled_count',"sortable": true, "searchable": true}
        ],
    });
}

if(loadAppointmentDetailsTable)
{
    $('#appointmentDetailsTable').DataTable({
        paging: true,
        pageLength:10,
        processing:true,
        serverside:true,
        Bsort: true,
        columnDefs: [
            { orderable: true, className: 'reorder', targets: [3] },
        ],
        dom: 'lBfrtip',
        ajax : {
            url : getAppointmentDetails,
            beforeSend : function()
            {
                $('#search').attr('disabled',true);
            },
            data: function(d)
            {
                d.id = doctor_id,
                d.status = statusofAppointment,
                d.reportKey = reportKey
            },
            complete: function()
            {
                $('#search').attr('disabled',false);
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex',"sortable": true, "searchable": false},
            {data: 'patients_full_name', name:'patients_full_name',"sortable": true, "searchable": true},
            {data: 'appointmentDate', name:'appointmentDate',"sortable": true, "searchable": true},
            {data: 'appointmentTime', name:'appointmentTime',"sortable": true, "searchable": true}
        ],
    });
}

function reload_table() {
    var startDate = $('#from_date').val();
    var toDate = $('#to_date').val();
    var doctor = $('#doctor_name_list').val();
    
    if (startDate != '' && toDate != '')
    {
        $('#doctorPerformanceReport').DataTable().ajax.reload();
    }
    else
    {
        Swal.fire("Please select the Date");
    }
}

function fetchDoctorList()
{
    $.ajax({
        type : 'GET',
        url : getDoctorList,
        success : function(response) {
            console.log(response);
            
            $.each(response, function (indexInArray, valueOfElement) 
            {                
                $('#doctor_name_list').append($("<option></option>")
                    .attr('value',response[indexInArray].id)
                    .text(valueOfElement.user.doctor_name));
            });
        }
    });

    $('#doctor_name_list').select2();
}