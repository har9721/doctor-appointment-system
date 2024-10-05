
var table = $('#specialtyList').DataTable({
    paging: true,
    pageLength:10,
    processing:true,
    serverside:true,
    Bsort: true,
    order : [],
    dom: 'lBfrtip',
    ajax : {
        url : specialtyList,
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex',"sortable": true, "searchable": false},
        {data: 'specialtyName', name:'specialtyName',"sortable": true, "searchable": true},
        {data: 'edit', name: 'action', orderable: true, searchable: true},
        {data: 'delete', name: 'delete', orderable: false, searchable: false},
    ],
});

$(document).on('click','#submit', function()
{
    let specialty = $('#specialty').val();
    let hidden_id = $('#hidden_id').val();

    if(specialty.trim() === ''){
        return $('.errorMessage').css('display','block').text('Please enter specialty name.');
    }else{
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type : "post",
            url : saveSpecialty,
            data : {'name' : specialty,'hidden_id' : hidden_id},
            beforeSend: function(){
                $('#submit').attr('disabled', true);
            },
            success : function (response){
                let data = JSON.parse(response);

                if(data.status == 'success'){
                    Swal.fire({
                        title: "Success",
                        text: data.message,
                        icon: "success",
                        timer: 5000
                    });

                    $('#specialty').val('');
                    $('#specialtyModal').modal('hide');
                    $('#specialtyList').DataTable().ajax.reload();

                }else{    
                    Swal.fire({
                        title: "Success",
                        text: data.message,
                        icon: "success",
                        timer: 5000
                    });
                }
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
            },
            complete : function(){ 
                $('#submit').attr('disabled', false); 
            }
        })
    }
});

$(document).on('keyup', 'input[type=text]', function(){
    $('.errorMessage').css('display','none').text('');
})

$(document).on('click','.editSpecialty', function(){
    let id = $(this).data('id');
    let specialty = $(this).data('specialty');

    $('#specialtyModal').modal('show');
    $('#specialty').val(specialty);
    $('#hidden_id').val(id);
});

$(document).on('click','.deleteSpecialty', function(){
    let id = $(this).data('id');

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
                url : deleteSpecialty,
                data : {'id' : id},
                success : function (response){
    console.log(response);
    
                    if(response['status'] == 'success'){
                        Swal.fire({
                            title: "Success",
                            text: response['message'],
                            icon: "success",
                            timer: 5000
                        });
    
                        $('#specialtyList').DataTable().ajax.reload();
    
                    }else{    
                        Swal.fire({
                            title: "Success",
                            text: response['message'],
                            icon: "success",
                            timer: 5000
                        });
                    }
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

    