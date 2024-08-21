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
        {data: 'city', name:'city',"sortable": true, "searchable": true},
        {data: 'specialty', name:'specialty',"sortable": true, "searchable": true},
        {data: 'licenseNumber', name:'licenseNumber',"sortable": true, "searchable": true},
        {data: 'edit', name: 'action', orderable: false, searchable: false},
        {data: 'delete', name: 'delete', orderable: false, searchable: false},
    ],
});