(function ($) {

    "use strict";

$(document).ready(function(){
    $('#select').select2();

    $('.arrow').on('click',function(){
        let children = $(this).parent().parent().children('.sub-menu').toggle('showMenu')
    })

    $('#label').on('click',function(){
        let sidebar = document.querySelector(".sidebar")
        if ($('input[id=sidebar-toggle]').is(':checked')) {
            $('.sidebar').removeClass('closes')
        }else{
            sidebar.classList.toggle("closes")
            /*$('.sidebar').addClass('closes')*/
        }
        
        
    })

    $(".dropbtn").on('click',function(e){
        if ($(this).next().hasClass("show")) {
            $(this).next().removeClass('show')
        }else{
            $(this).next().addClass('show')
        }
        
    })

    const prevBtns = document.querySelectorAll(".btn-prev")
    const nextBtns = document.querySelectorAll(".btn-next")
    const progress = document.getElementById("progress")
    const formSteps = document.querySelectorAll(".form-step")
    const progressSteps = document.querySelectorAll(".progressbar-step")

    let formStepsNum = 0

    nextBtns.forEach(btn => {
        btn.addEventListener("click",() => {
            formStepsNum++
            updateFormSteps()
            updateProgresBar()
        })
    });

    prevBtns.forEach(btn => {
        btn.addEventListener("click",() => {
            formStepsNum--
            updateFormSteps()
            updateProgresBar()
        })
    });

    function updateFormSteps() {
        formSteps.forEach(formStep => {
            formStep.classList.contains("form-step-active") &&
                formStep.classList.remove("form-step-active")
        })
        formSteps[formStepsNum].classList.add('form-step-active')
    }

    function updateProgresBar() {
        progressSteps.forEach((progressStep, idx) => {
            if (idx < formStepsNum + 1) {
                progressStep.classList.add('progressbar-step-active')
            }else{
                progressStep.classList.remove('progressbar-step-active')
            }
        })
        const progressActive = document.querySelectorAll(".progressbar-step-active")
        progress.style.width = ((progressActive.length - 1) / (progressSteps.length - 1)) * 100 + "%"
    }

  window.onload = function() {

    $.ajax({
        type: "POST",
        url: "/dashboard/chart",
        success:function(datas) { 
            console.log(datas)
            const v = JSON.parse(datas)
            console.log(v)
            const data1 = {
                labels: [
                  'Marchand Eco',
                  'Marchand Premium'
                ],
                datasets: [{
                  label: 'Marchands',
                  data: v.offres,
                  backgroundColor: [
                    'rgb(255, 172, 100)',
                    'rgb(0, 168, 243)'
                  ],
                  hoverOffset: 4
                }]
              };
              var ctx = document.getElementById('myChart2');
                var myChart2 = new Chart(ctx, {
                type: 'doughnut',
                data: data1,
                options:{
                    responsive:true,
                }
            });
         }
    });

    $.ajax({
        type: "GET",
        url: "/dashboard/camembertChart",
        success:function(chart2data) { 
            console.log(chart2data)
            const data1 = {
                labels: [
                  'SERVICES TELEPHONIQUES',
                  'SERVICES FINANCIERS'
                ],
                datasets: [{
                  label: '#marchands',
                  data: chart2data,
                  backgroundColor: [
                    'rgb(255, 172, 100)',
                    'rgb(0, 168, 243)'
                  ],
                  hoverOffset: 4
                }]
              };
              var ctx = document.getElementById('camenbertChart');
                var camenbertChart = new Chart(ctx, {
                type: 'doughnut',
                data: data1,
                options:{
                    responsive:true,
                }
            });
         }
    });


    $.ajax({
        type: "GET",
        url: "/dashboard/barChart",
        success:function(chartbar) { 
        console.log(chartbar);
        const v = JSON.parse(chartbar)
        var ctx = document.getElementById('barChart');
        var barChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: v.mois,
        datasets: [{
            label: 'Opérations',
            data: v.total,
            fill: true,
            tension:1,
            backgroundColor: [
                '#00A8F3'
            ],
            borderColor: [
                '#00A8F3'
            ],
            borderWidth: 2,
            borderRadius:10,
            maxBarThickness:8,
        }]
        },
        options: {
            responsive:true,
            scales: {
                yAxes: [{
                    ticks:{
                        beginAtZero: true
                    }
                    
                 }]
                },
            //aspectRatio:1
            }
        });

    }});



    $.ajax({
        type: "POST",
        url: "/dashboard/line",
        success:function(datas) { 
            console.log(datas)
            const v = JSON.parse(datas)
            console.log(v)
            var ctx = document.getElementById('myChart');
            var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                //labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                labels: v.mois,
                datasets: [{
                    label: 'Opérations',
                    data: v.total,
                    fill: true,
                    tension:1,
                    backgroundColor: [
                        '#00A8F3'
                    ],
                    borderColor: [
                        '#00A8F3'
                    ],
                    borderWidth: 2,
                    borderRadius:10,
                    maxBarThickness:8,
                }]
            },
            options: {
                responsive:true,
                scales: {
                    yAxes: [{
                        ticks:{
                            beginAtZero: true
                        }
                        
                    }]
                },
                //aspectRatio:1
            }
        });
            
         }
    });
      
    $('.sidebar-menu li').on('click','a',function(e){
        
        if ($(this).parent().children('ul').length) {
            e.preventDefault();
            $(this).parent().addClass('active')
            $(this).addClass('active')
            $(this).parent().children('ul').slideDown()
            
        }
    })

    $('.sidebar-menu li').on('click','a .active',function(e){
        e.preventDefault();
        $(this).removeClass('active')
        $(this).parent().removeClass('active')
        $(this).parent().children('ul').slideUp()
    })

    

const data3 = {
    labels: [
      'Marchand Basic',
      'Marchand Premium'
    ],
    datasets: [{
      label: 'Marchands',
      data: [50, 100],
      backgroundColor: [
        'rgb(255, 172, 100)',
        'rgb(0, 168, 243)'
      ],
      hoverOffset: 4
    }]
  };

var ctx = document.getElementById('myChart3');
    var myChart3 = new Chart(ctx, {
    type: 'doughnut',
    data: data3,
    options:{
        responsive:true,
    }
});

  }

    var datatable = $('#table').DataTable({
        "pageLength": 6,
        "language":{
            paginate:{
                "first":"Premier",
                "last":"Dernier",
                "next":"Suivant",
                "previous":"Antérieur"
            }
        },
        'aoColumnDefs':[
            {
                'bSortable':true,
                'aTargets':['nosort'],
            }
        ],
       // columnDefs:[
         //   {type:'date-dd-mm-yyyy',aTargets:[5]}
       // ],
        /*"aoColumns":[
            null,
            null,
            null,
            null,
            null,
            null,
            null
        ],*/
        "order":true,
        "bLengthChange": true,
        "dom":'<"top">ct<"top"p><"clear">'
    });

    $('#filter').on('keyup',function(){
        datatable.search(this.value).draw();
    });

    var datatables = $('#tables').DataTable({
        dom:'Bfrtip',
        buttons:['excel','csv','pdf'],
        "pageLength": 6,
        "language":{
            paginate:{
                "first":"Premier",
                "last":"Dernier",
                "next":"Suivant",
                "previous":"Antérieur"
            }
        },
        'aoColumnDefs':[
            {
                'bSortable':true,
                'aTargets':['nosort'],
            }
        ],
       // columnDefs:[
         //   {type:'date-dd-mm-yyyy',aTargets:[5]}
       // ],
        /*"aoColumns":[
            null,
            null,
            null,
            null,
            null,
            null,
            null
        ],*/
        "order":true,
        "bLengthChange": true,
        
    });

    $('#filter').on('keyup',function(){
        datatables.search(this.value).draw();
    });


    
})
})(window.jQuery);

