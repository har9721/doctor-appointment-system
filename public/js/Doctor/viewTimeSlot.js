document.addEventListener('DOMContentLoaded', function() {

    //start date datepicker
    $('#start_time').datetimepicker({
        format:"H:i",
        timepicker: true,
        datepicker : false,
        minTime:'10:00',
        maxTime:'19:30',
        step : 30,
        scrollInput : false,
    });

    //start date datepicker
    $('#end_time').datetimepicker({
        format:"H:i",
        timepicker: true,
        datepicker : false,
        minTime:'10:00',
        maxTime:'19:30',
        step : 30,
        scrollInput : false,
    });

    var calendarEl = document.getElementById('calendar');
 
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        timeZone: 'local',
        height: 800,
        contentHeight: 750,
        aspectRatio: 3,
        slotMinTime: '10:00:00',
        slotMaxTime: '19:00:00',
        // validRange: {
        //     start: new Date()
        // },

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },    

        events:  fetchAllEvents,

        eventRender: function(event, element, view) {
            if (event.allDay === 'true') {
                event.allDay = true;
            } else {
                event.allDay = false;
            }
        },
        dateClick: function(info) {            
            let date = info.dateStr;
            const newDate = convertTimestampToDate(date);
            $('#hidden_date').val(newDate);

            $('#manageAvailability').modal('show');
            $('#start_time').val('');
            $('#end_time').val('');
        },

        editable : true,
        selectable : true,

        eventClick : function(event)
        {
            const dropdown = [
                { value : 'edit' , text : 'Edit'},
                { value : 'delete' , text : 'Delete'}
            ];

            // append values to dropdown
            appendValuesToDropdown(dropdown);

            showEditAndDeleteActionModal(event);
        },

        eventDrop : function(info)
        {
            let date = new Date(info.event.start);
            
            if (date < new Date()) {
                info.revert();
                Swal.fire({
                    title: "Success",
                    text: "You can't drag in past dates.",
                    icon: "warning",
                    timer: 2000
                });
            }else{

                const dropdown = [
                    { value : 'copy' , text : 'Copy'},
                    { value : 'move' , text : 'Move'}
                ];

                // append values to dropdown
                appendValuesToDropdown(dropdown);

                showEditAndDeleteActionModal(info);
            }  
        },

        eventRemove : function()
        {
            console.log('remove');
        }
    });

    calendar.render();
});

document.getElementById('recurrence').addEventListener('change',function(){
    const value = $('#recurrence').val();
    if (value === 'weekly') {
        $('#weeklyOptions').removeClass('d-none');
    } else {
        $('#weeklyOptions').addClass('d-none');
    }
});

const submitBtn = document.getElementById('submit');

submitBtn.addEventListener('click', function(){
    let eventDate = $('#hidden_date').val();
    
    let startTime = $('#start_time').val();
    let endTime = $('#end_time').val();
    let doctor_ID = $('#hidden_login_user_id').val();
    let hidden_timeslot_id = $('#hidden_timeslot_id').val();
    let isEdit = (hidden_timeslot_id !== '') ? '1' : '0';
    let recurrence = $('#recurrence').val();

    const days = [];

    if(recurrence === 'weekly')
    {
        $('#weeklyOptions input:checked').each(function(){
            days.push($(this).val());
        });

        if(days.length == 0)
        {
            return Swal.fire({
                title: "Error",
                text: "Please select atleast one day for recurrence!",
                icon: "error",
                timer: 3000
            });
        }
    }

    if(startTime !== '' && endTime !== '')
    {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type : 'post',
            url : addTimeSlot,
            beforeSend : function()
            {
                $('#end_time_error').css('display','none');
                $('#start_time_error').css('display','none');
                $('#submit').attr('disabled',true);
            },
            data : {'date' : eventDate, 'startTime' : startTime, 'endTime': endTime,'doctor_ID': doctor_ID, 'hidden_timeslot_id' : hidden_timeslot_id, 'isEdit' : isEdit, 'days' : days, 'recurrence': recurrence},
            success : function(response)
            {
                if(response['status'] == 'success'){
                    Swal.fire({
                        title: "Success",
                        text: response['message'],
                        icon: "success",
                        timer: 3000
                    });

                    setTimeout(function(){
                        window.location.reload();
                    },2000);
                }else{    
                    Swal.fire({
                        title: "Success",
                        text: response['message'],
                        icon: "success",
                        timer: 3000
                    });
                }
            },
            error : function(response){
                if(response.status === 422)
                {
                    var errors = response.responseJSON;
                    Swal.fire({
                        title: "Error",
                        text: errors.message,
                        icon: "error",
                        timer: 3000
                    });
                }
            },
            complete : function()
            {
                $('#submit').attr('disabled',false);
            }
        })
    }
    else{
        if(startTime == '')
            $('#start_time_error').css('display','block');
        else
            $('#start_time_error').css('display','none');

        if(endTime == '')
            $('#end_time_error').css('display','block');
        else
            $('#end_time_error').css('display','none');
    }
});

function showEditAndDeleteActionModal(event)
{
    $('#actionModal').modal('show');

    document.getElementById('submitAction').addEventListener('click', function(){
        let action = $('#action').val();
    
        if(action)
        {
            if(action === 'edit')
                editEventDetails(event);
            else if(action === 'delete')
                deleteEventDetails(event);
            else if(action === 'copy')
                copyEventDetails(event);
            else if(action === 'move')
                moveEventDetails(event);
        }else
        {
            Swal.fire({
                title: "Error",
                text: "Please select an action",
                icon: "error",
                timer: 3000
            });
        }
    });
}

function editEventDetails(event)
{    
    $('#actionModal').modal('hide');
    let id = event.event.id;
    let date = event.event.start;
    let startHours = date.getHours();
    let startMinutes = date.getMinutes();
    let startTime = convertTimestampToTime(date);

    const newDate = convertTimestampToDate(date);

    let enddate = event.event.end;
    let endHours = enddate.getHours();
    let endMinutes = enddate.getMinutes();
    let endTime = convertTimestampToTime(enddate);
        
    // show manage availability modal
    $('#manageAvailability').modal('show');

    // append data to input box
    $('#start_time').val(startTime);
    $('#end_time').val(endTime);
    $('#hidden_date').val(newDate);

    // add hidden id in modal
    $('#hidden_timeslot_id').val(id);
}

function deleteEventDetails(event)
{
    $('#actionModal').modal('hide');

    let id = event.event.id;
    
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
    if (result.isConfirmed) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type : 'post',
            url : deleteTimeSlot,
            beforeSend : function()
            {
                $('#submit').attr('disabled',true);
            },
            data : {'id' : id},
            success : function(response)
            {
                if(response['status'] == 'success'){
                    Swal.fire({
                        title: "Success",
                        text: response['message'],
                        icon: "success",
                        timer: 3000
                    });

                    setTimeout(function(){
                        window.location.reload();
                    },3000);
                }else{    
                    Swal.fire({
                        title: "Success",
                        text: response['message'],
                        icon: "success",
                        timer: 3000
                    });
                }
            },
            error : function(response){
                if(response.status === 422)
                {
                    var errors = response.responseJSON;
                    Swal.fire({
                        title: "Error",
                        text: errors.message,
                        icon: "error",
                        timer: 3000
                    });
                }
            },
            completed : function()
            {
                $('#submit').attr('disabled',false);
                $('#manageAvailability').modal('hide');
            }
        })
    }
    });
}

function updateDropEventsDetails(event)
{
    let id = event.event.id;
    let date = event.event.start;
    const newDate = convertTimestampToDate(date);
    const time = convertTimestampToTime(date);
    let doctor_ID = $('#hidden_login_user_id').val();
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type : 'post',
        url : updateTimeSlot,
        data : {'id' : id, 'date' : newDate,'startTime' : time,'doctor_ID' : doctor_ID},
        success : function(response)
        {
            if(response['status'] == 'success'){
                Swal.fire({
                    title: "Success",
                    text: response['message'],
                    icon: "success",
                    timer: 3000
                });

                setTimeout(function(){
                    window.location.reload();
                },2000);
            }else{    
                Swal.fire({
                    title: "Success",
                    text: response['message'],
                    icon: "success",
                    timer: 2000
                });
            }
        },
        error : function(response){
            if(response.status === 422)
            {
                var errors = response.responseJSON;
                Swal.fire({
                    title: "Error",
                    text: errors.message,
                    icon: "error",
                    timer: 3000
                });

                setTimeout(function(){
                    window.location.reload();
                },2000);
            }
        },
    })
}

function convertTimestampToDate(eventDate)
{
    let dateObject = new Date(eventDate);
    let year = dateObject.getFullYear();
    let month = dateObject.getMonth() + 1;
    let day = dateObject.getDate();

    return `${year}-${(month < 10) ? '0'+month : month}-${(day < 10) ? '0'+day : day}`;  
}

function convertTimestampToTime(eventDate)
{
    let timeObject = new Date(eventDate);
    let hours = timeObject.getHours();
    let minutes = timeObject.getMinutes();

    return `${hours}:${(minutes < 10) ? '0'+minutes : minutes}`;  
}

function appendValuesToDropdown(values)
{
    $('#action').empty();

    $('#action').append(
        $('<option></option>').attr('value', 'select_actions').text('Select Actions').prop('selected', true).prop('disabled', true)
    );

    $.each(values, function (key, val) 
    {
        $('#action').append($("<option></option>")
        .attr("value", val.value)
        .text(val.text));
    });
}

function copyEventDetails(event)
{
    let startDate = event.event.start;
    let endDate = event.event.end;
    const newDate = convertTimestampToDate(startDate);
    const startTime = convertTimestampToTime(startDate);
    const endTime = convertTimestampToTime(endDate);
    const doctor_ID = $('#hidden_login_user_id').val();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type : "post",
        url : addTimeSlot,
        data : {'date' : newDate, 'startTime' : startTime, 'endTime' : endTime, 'doctor_ID' : doctor_ID, 'isEdit' : 0},
        beforeSend : function()
        {
            $('#submitAction').attr('disabled',true);
        },
        success : function(response)
        {
            if(response['status'] == 'success'){
                Swal.fire({
                    title: "Success",
                    text: response['message'],
                    icon: "success",
                    timer: 3000
                });

                setTimeout(function(){
                    window.location.reload();
                },2000);
            }else{    
                Swal.fire({
                    title: "Success",
                    text: response['message'],
                    icon: "success",
                    timer: 2000
                });
            }
        },
        error : function(response){
            if(response.status === 422)
            {
                var errors = response.responseJSON;
                Swal.fire({
                    title: "Error",
                    text: errors.message,
                    icon: "error",
                    timer: 3000
                });

                setTimeout(function(){
                    window.location.reload();
                },2000);
            }
        },
        complete : function(){
            $('#submitAction').attr('disabled',false);
        }
    });
    
}

function moveEventDetails(event)
{
    updateDropEventsDetails(event);
}