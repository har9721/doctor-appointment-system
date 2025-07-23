var table = $('#timePreference').DataTable({
        paging: true,
        pageLength:10,
        processing:true,
        serverside:true,
        Bsort: true,
        order : [],
        dom: 'lBfrtip',
        ajax : {
            url : timePreferenceUrl,
            beforeSend : function()
            {
                $('#search').attr('disabled',true);
            },
            data: function(d)
            {
                
            },
            complete: function()
            {
                $('#search').attr('disabled',false);
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex',"sortable": true, "searchable": false},
            {data: 'time', name:'time',"sortable": true, "searchable": true},
            {data: 'timeCount', name:'timeCount',"sortable": true, "searchable": true}
        ],
    });