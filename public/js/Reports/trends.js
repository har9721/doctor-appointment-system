// set dynamic value according to current date
let quaterValue = $('#periodFilter').val(quater);

var myChart;

// initial call
loadData(quater);

// on change event on select tag
$('#periodFilter').on('change', function (){  
    const quater = $(this).val();

    if(quater == 'all')
        loadTableData(quater);
    else
        loadData(quater);
});

function loadData(quater)
{
    $('#charts').css('display','block');
    $('#yearlyDataDiv').css('display','none');

    // destroy the table here
    $('#allYearData').DataTable().destroy();

    $.ajax({
        type: "GET",
        url: trendsUrl,
        data : {'quater' : quater},
        success: function (response) {
     
           showChart(response); 
        }
    });
}

function showChart(response)
{  
    console.log(response);
    
    const isEmpty = response.length
    console.log(isEmpty);
    
   
    let labels = (isEmpty) ? response.map(item => item.showLabel) : null;

    const chartData = {
        labels: labels,
        datasets: [
            {
                label: 'Completed',
                data: response.map(item => item.completed),
                backgroundColor: '#4caf50'
            },
            {
                label: 'Confirmed',
                data: response.map(item => item.confirmed),
                backgroundColor: '#2196f3'
            },
            {
                label: 'Pending',
                data: response.map(item => item.pending),
                backgroundColor: '#ffc107'
            },
            {
                label: 'Cancelled',
                data: response.map(item => item.cancelled),
                backgroundColor: '#f44336'
            }
        ]
    };

    const config = {
        type: 'bar',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Monthly Appointments Status'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                x: {
                    stacked: true,
                    ticks: {
                        autoSkip: false, // show all month labels
                    },
                    grid: {
                        display: false
                    },
                    barPercentage: 0.6,
                    categoryPercentage: 0.5,
                },
                y: {
                    stacked: true,
                    beginAtZero: true
                }
            }
        }
    };

    if (myChart) {
        myChart.destroy();
    }
    const ctx = document.getElementById('myStackBarChart').getContext('2d');
    myChart = new Chart(ctx, config);
}

function loadTableData(quater)
{
    $('#charts').css('display','none');
    $('#yearlyDataDiv').css('display','block');

    var table = $('#allYearData').DataTable({
        paging: true,
        pageLength:10,
        processing:true,
        serverside:true,
        Bsort: true,
        order : [],
        dom: 'lBfrtip',
        ajax : {
            url : trendsUrl,
            beforeSend : function()
            {
                $('#search').attr('disabled',true);
            },
            data: function(d)
            {
                d.quater = quater
            },
            complete: function()
            {
                $('#search').attr('disabled',false);
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex',"sortable": true, "searchable": false},
            {data: 'showLabel', name:'showLabel',"sortable": true, "searchable": true},
            {data: 'total_appointment', name:'total_appointment',"sortable": true, "searchable": true},
            {data: 'completed', name:'completed',"sortable": true, "searchable": true},
            {data: 'confirmed', name:'confirmed',"sortable": true, "searchable": true},
            {data: 'pending', name:'pending',"sortable": true, "searchable": true},
            {data: 'cancelled', name:'cancelled',"sortable": true, "searchable": true}
        ],
    });
}
