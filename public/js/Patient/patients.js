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
        {data: 'city', name:'city',"sortable": true, "searchable": true},
        {data: 'address', name:'address',"sortable": true, "searchable": true},
        {data: 'edit', name: 'action', orderable: false, searchable: false},
        {data: 'delete', name: 'delete', orderable: false, searchable: false},
    ],
});