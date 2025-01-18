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

var table = $('#completeAppointmentList').DataTable({
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
        },
        complete: function()
        {
            $('#search').attr('disabled',false);
        }
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex',"sortable": true, "searchable": false},
        {data: 'doctor_full_name', name:'doctor_full_name',"sortable": true, "searchable": true},
        {data: 'appointmentDate', name:'appointmentDate',"sortable": true, "searchable": true},
        {data: 'time', name:'time',"sortable": true, "searchable": true},
        {data: 'status', name:'status',"sortable": true, "searchable": true},
        {data: 'amount', name:'amount',"sortable": true, "searchable": true},
        {data: 'payment_status', name:'payment_status',"sortable": true, "searchable": true},
        {data: 'action', name:'action',"sortable": false, "searchable": false},
    ],
});

function reload_table() {
    var startDate = $('#from_date').val();
    var toDate = $('#to_date').val();
    
    if (startDate != '' && toDate != '')
    {
        $('#completeAppointmentList').DataTable().ajax.reload();
    }
    else
    {
        Swal.fire("Please select the Date");
    }
}