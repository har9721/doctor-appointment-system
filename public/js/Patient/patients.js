const state_ID = $('#hidden_state_ID').val();
const city_Id = $('#hidden_city_ID').val();
const genderId = $('#hidden_gender_ID').val();
const smoking_status_id = $('#smoking_status_hidden_id').val();
const alcohol_status_id = $('#alcohol_status_hidden_id').val();

getStateList(state_ID);
getGenderList(genderId);
getSmokingStatus(smoking_status_id);
getAlcoholStatus(alcohol_status_id);

if(state_ID != null && city_Id != null)
{
    getCityList(state_ID,city_Id); 
}

var table = $('#patientsList').DataTable({
    paging: true,
    pageLength:10,
    processing:true,
    serverside:true,
    Bsort: true,
    order : [],
    dom: 'lBfrtip',
    ajax : {
        url : getPatientList,
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex',"sortable": true, "searchable": false},
        {data: 'name', name:'name',"sortable": true, "searchable": true},
        {data: 'email', name:'email',"sortable": true, "searchable": true},
        {data: 'mobile', name:'mobile',"sortable": true, "searchable": true},
        {data: 'gender', name:'gender',"sortable": true, "searchable": true},
        {data: 'age', name:'age',"sortable": true, "searchable": true},
        {data: 'city', name:'city',"sortable": true, "searchable": true},
        {data: 'address', name:'address',"sortable": true, "searchable": true},
        {data: 'edit', name: 'action', orderable: false, searchable: false},
        {data: 'delete', name: 'delete', orderable: false, searchable: false},
    ],
});

$(document).on('click','#submitForm',function(e){
    e.preventDefault();
    const formData = new FormData($('#patientEditForm')[0]);
    const email = $('#email').val();
    formData.append('email',email);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type : 'POST',
        url : savePatientsData,
        data : formData,
        processData : false,
        contentType : false,
        beforeSend : function ()
        {  
            $('#submitForm').attr('disabled',true);
        },
        success: function(response)
        {
            let data = JSON.parse(response);
            
            if(data.status == 'success'){
                Swal.fire({
                    title: "Success",
                    text: data.message,
                    icon: "success",
                    timer: 4000
                });

                if(data.url === '')
                {
                    setTimeout(function(){
                        window.location.reload();
                    },4000);
                }else
                {
                    setTimeout(function(){
                        window.location.href = patientsList;
                    },4000);
                }
            }else{    
                Swal.fire({
                    title: "Success",
                    text: data.message,
                    icon: "success",
                    timer: 4000
                });
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
        complete : function(){
            $('#submitForm').attr('disabled',false);
        }
    });
    
});

$(document).on('change', '#state',function(){
    let state_ID = $(this).val();
    getCityList(state_ID,city_Id); 
});

function getStateList(selectedStateID = null){
    var selectedTrue = false;

    $('#state').append("<option value='' selected disabled>Select State</option>");

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type : "get",
        url : getStates,
        success : function (response){
            $.each(response, function (key, val) 
            { 
                if(response[key].id ==selectedStateID)
                    selectedTrue = true;
                else
                    selectedTrue = false;

                $('#state').append($("<option></option>")
                .attr("value", response[key].id)
                .text(val.name)
                .prop('selected', selectedTrue));
            });
        }
    });

    $('#state').select2();
}

function getCityList(state_ID,selectedCity_ID = null){
    var selectedTrue = false;
    $('#city_ID').empty();

    $('#city_ID').append("<option value='' selected disabled>Select City</option>");

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type : "get",
        url : getCity,
        data : {'state_Id' : state_ID},
        success : function (response){
            $.each(response, function (key, val) 
            { 
                if(response[key].id == selectedCity_ID)
                    selectedTrue = true;
                else
                    selectedTrue = false;

                $('#city_ID').append($("<option></option>")
                    .attr("value", response[key].id)
                    .text(val.name)
                    .prop('selected',selectedTrue));
            });
        }
    });

    $('#city_ID').select2();
}

function getGenderList(selectedGenderId = null){
    var selectedTrue = false;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type : "get",
        url : getGender,
        success : function (response){
            $.each(response, function (key, val) 
            {
                if(response[key].id ==  selectedGenderId)
                    selectedTrue = true;
                else
                    selectedTrue = false;

                $('#gender_ID').append($("<option></option>")
                    .attr("value", response[key].id)
                    .text(val.gender)
                    .prop('selected',selectedTrue));
            });
        }
    })
}

function getSmokingStatus(id){
    let selectedTrue = false;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type : "get",
        url : smokingStatus,
        success : function (response){
            $.each(response, function (key, val) 
            {
                if(val.id == id)
                    selectedTrue = true;
                else
                    selectedTrue = false;
 
                $('#smoking_status').append($("<option></option>")
                    .attr("value", response[key].id)
                    .text(val.statusName)
                    .prop('selected',selectedTrue)
                );
            });
        }
    })
}

function getAlcoholStatus(id){
    let selectedTrue = false;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type : "get",
        url : alcoholStatus,
        success : function (response){
            $.each(response, function (key, val) 
            {
                if(val.id == id)
                    selectedTrue = true;
                else
                    selectedTrue = false; 

                $('#alcohol_status').append($("<option></option>")
                    .attr("value", response[key].id)
                    .text(val.statusName)
                    .prop('selected', selectedTrue)
                );
            });
        }
    })
}