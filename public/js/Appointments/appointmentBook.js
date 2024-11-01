document.addEventListener('DOMContentLoaded', function() {
    //start date datepicker
    $('#date').datetimepicker({
        format:"d-m-Y",
        timepicker: false,
        datepicker : true,
        changeMonth:true,
        changeYear:true,
        minDate : "-1",
        scrollInput : false,
    });

    getCityList();
    getSpecialtyList();


    $('#search').on('click', function(){
        let speciality = $('#speciality').val();
        let city = $('#city').find(':selected').val();
        let date = $('#date').val();

        if(speciality != '' || city != '' || date != '')
        {
            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $.ajax({
                type : 'get',
                url : searchDoctor,
                data : {'speciality' : speciality,'date' : date, 'city' : city},
                success: function(response)
                {
                    // make empty div before append
                    $('#searchResults').empty();

                    if(response.length != 0)
                    {
                        response.forEach(doctor => {
                            const timeSlot = doctor.time_slot;
                            const baseUrl = window.location.origin;
                            const fileName = doctor.fileName;
                            let selectedTimeSlot = null;

                            // Access the image URL
                            const imageUrl = baseUrl + '/storage/doctorProfilePictures/'+fileName;

                            let doctorCard = `
                            <div class="col-md-6">
                                <div class="doctor-card">
                                    <img src="${imageUrl}" atr="${doctor.first_name}">
                                    <div class="doctor-info">
                                        <h4 style="color:black">Dr.${doctor.first_name}</h4>
                                        <p>${doctor.specialtyName}</p>
                                        <p>${doctor.gender}</p>
                                    </div>
                                    <div class="accordion" id="accordion${doctor.id}">
                                        <div class="card">
                                            <div class="card-header" id="heading${doctor.id}">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse${doctor.id}" aria-expanded="true" aria-controls="collapse${doctor.id}">
                                                        View Available Time Slots
                                                    </button>
                                                </h5>
                                            </div>
                                            <div id="collapse${doctor.id}" class="collapse" aria-labelledby="heading${doctor.id}" data-parent="#accordion${doctor.id}">
                                                <div class="card-body accordion-body">
                                                    <div id="timeSlotsContainer${doctor.id}" class="time-slots-container">
                                                    </div>
                                                    <div class="text-center">
                                                        <button class="book-btn" data-doctor-id="${doctor.id}">Book Appointment</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`;

                            $('#searchResults').append(doctorCard);

                            // append time slot
                            getTimeSlot(doctor.id,timeSlot);
                            
                        });
                        
                    }else{
                        return 'No results found.';
                    }
                },
                error: function(response)
                {
                    if(response.status === 422)
                    {
                        var errors = response.responseJSON;
                        Swal.fire({
                            title: "Error",
                            text: errors.message,
                            icon: "error",
                            timer: 4000
                        });
                    }
                },
            })    
        }else{
            Swal.fire({
                title: "Error",
                text: "Please select at least one field.",
                icon: "error",
                timer: 2000
            });
        }
    });
});

function getTimeSlot(doctorID,timeSlot)
{
    selectedTimeSlot = null;

    timeSlot.forEach(element => {
        const slot = `<div class="time-slot mr-1" onclick="clickOnTimeSlot(this)" data-time_slot_id ="${element.id}">${element.time}</div>`;

        $(`#timeSlotsContainer${doctorID}`).append(slot);
    });
}

function clickOnTimeSlot(timeSlot)
{
    selectedTimeSlot = $(timeSlot).data('time_slot_id');

    timeSlot.classList.add('selected');
}


$(document).on('click', '.book-btn',function(){

    if(selectedTimeSlot)
    {
        let time_slot = selectedTimeSlot;
        let date = $('#date').val();
        let patient_ID = $('#patients_ID').val();
        
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        $.ajax({
            type : 'post',
            url : bookingUrl,
            data : {'timeSlot' : time_slot,'date' : date,'patient_ID' : patient_ID},
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
                        title: "Error",
                        text: response['message'],
                        icon: "error",
                        timer: 3000
                    });
                }
            }
        })
        
    }else{
        return Swal.fire('Please select a time slot.');
    }
    
});

function getCityList(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type : "get",
        url : getCity,
        data : {'state_Id' : ''},
        success : function (response){
            $.each(response, function (key, val) 
            { 
                $('#city').append($("<option></option>")
                .attr("value", response[key].id)
                .text(val.name));
            });
        }
    });

    $("#city").select2({
        placeholder: "Select city name",
    });	
}

function getSpecialtyList(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type : "get",
        url : getSpecialty,
        success : function (response){
            $.each(response, function (key, val) 
            { 
                $('#speciality').append($("<option></option>")
                .attr("value", response[key].id)
                .text(val.specialtyName));
            });
        }
    })
}