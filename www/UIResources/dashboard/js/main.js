$(function() {



    $.ajax({
        type: "POST",
        url: "/dashboard/chart",
        success:function(datas) { 
            console.log(datas)
            const v = JSON.parse(datas)
            console.log(v)
            var datapie = {
                labels: ['Marchand Eco', 'Marchand Premuim'],
                datasets: [{
                    data: v.offres,
                    backgroundColor: [
                        'rgb(255, 172, 100)',
                        'rgb(0, 168, 243)'
                      ],
                }]
            };
            var optionpie = {
                maintainAspectRatio: false,
                responsive: true,
                legend: {
                    display: true,
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            };
            
            /* Pie Chart*/
            var ctx7 = document.getElementById('chartDonut');
            var myPieChart7 = new Chart(ctx7, {
                type: 'pie',
                data: datapie,
                options: optionpie
            });
            
              
         }
    });

    $.ajax({
        type: "POST",
        url: "/dashboard/line",
        success:function(datas) { 
            console.log(datas)
            const v = JSON.parse(datas)
            console.log(v)
            var ctxx = document.getElementById("chartBar1");
            var myChart = new Chart(ctxx, {
                type: 'bar',
                data: {
                    labels: v.mois,
                    datasets: [{
                        label: 'Nombre de transaction',
                        data: v.total,
                        fill: true,
                        borderWidth: 2,
                        backgroundColor: '#00A8F3',
                        borderColor: '#00A8F3',
                        borderRadius:'100',
                        maxBarThickness:8,

                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    legend: {
                        display: true
                    },
                    scales: {
                        xAxes: [{
                            barThickness: 5,
                            categoryPercentage: 4,
                            barPercentage: 4,
                            stacked: true,
                             display: true,
                            gridLines: {
                                display: true,
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                stepSize: 150,
                                fontColor: "#77778e",
                            },
                            gridLines: {
                                color: 'rgba(119, 119, 142, 0.2)'
                            }
                        }],
                        
                        
                    },
                    legend: {
                        labels: {
                            fontColor: "#77778e"
                        },
                    },
                }
            });
            
         }
    });

    
	

});