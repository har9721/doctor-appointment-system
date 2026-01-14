var table = $('#doctorList').DataTable({
    paging: true,
    pageLength:10,
    processing:true,
    serverside:true,
    order : [],
    Bsort: true,
    dom: 'lBfrtip',
    ajax : {
        url : getDoctorList,
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex',"sortable": true, "searchable": false},
        {data: 'fullname', name:'fullname',"sortable": true, "searchable": true},
        {data: 'email', name:'email',"sortable": true, "searchable": true},
        {data: 'mobile', name:'mobile',"sortable": true, "searchable": true},
        {data: 'gender', name:'gender',"sortable": true, "searchable": true},
        {data: 'age', name:'age',"sortable": true, "searchable": true},
        {data: 'city', name:'city',"sortable": true, "searchable": true},
        {data: 'specialty', name:'specialty',"sortable": true, "searchable": true},
        {data: 'licenseNumber', name:'licenseNumber',"sortable": true, "searchable": true},
        {data: 'experience', name:'experience',"sortable": true, "searchable": true},
        {data: 'timeSlot', name:'timeSlot',"sortable": true, "searchable": true},
        {data: 'consultationFees', name:'consultationFees',"sortable": true, "searchable": true},
        {data: 'followUpFees', name:'followUpFees',"sortable": true, "searchable": true},
        {data: 'payment_mode', name:'payment_mode',"sortable": true, "searchable": true},
        {data: 'advanceFees', name:'advanceFees',"sortable": true, "searchable": true},
        {data: 'action', name: 'action', orderable: false, searchable: false}
    ],
});

$(document).on('click','.deleteDoctor', function(){
    let id = $(this).data('id');
    let user_id = $(this).data('user_id');

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
                type : "post",
                url : deleteDoctor,
                data : {'id' : id, 'user_id' : user_id},
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

$(document).on('click','.sendMail', function(){
    let id = $(this).data('id');

    let finalRoute = sendMail.replace(':id', id);

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
            $.ajax({
                url : finalRoute,
                type : 'post',
                data : { 'id' : id, '_token' : csrfToken},
                beforeSend : function(){
                    $('sendMail').attr('disabled',true);
                },
                success : function(response)
                {
                    if(response['status'] == 'success')
                    {
                        Swal.fire({
                            title : "Success",
                            text : response['message'],
                            icon : "success",
                            timer : 2000
                        });
                    }else{
                        Swal.fire({
                            title : "Error",
                            text : response['message'],
                            icon : "error",
                            timer : 2000
                        })
                    }
                },
                complete :  function()
                {
                    $('sendMail').attr('disabled',false)
                }
            });
        }
    });
})