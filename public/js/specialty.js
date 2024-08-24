
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
            data : {'name' : specialty},
            beforeSend: function(){
                $('#submit').attr('disabled', true);
            },
            success : function (response){
                let data = JSON.parse(response);
                console.log(data);
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