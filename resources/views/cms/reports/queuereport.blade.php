@extends('layouts.app')
@section('content-header')
<h1>
	Real Time
</h1>
<ol class="breadcrumb">
	<li><a href="{{URL::asset('/')}}cms"><i class="fa fa-dashboard"></i> Dashboard</a></li>
	<li class="active">Queues report</li>
</ol>
@endsection
@section('content')
<div class="row">
   <div class="col-xs-12">
      <div class="box">
         <div class="box-header">
            <div class="box-tools">
               <!--<div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">
                  <div class="input-group-btn">
                     <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
               </div>-->
            </div>
         </div>
          <div class="row" style="padding:20px">
              {!! Form::open(['method'=>'post','id'=>'report_form']) !!}
              <div class="row">
                  <div class="col-md-3 form-group">
                      <ul class="nav navbar-nav">
                          <li class="nav-item"><a  href="#"  id="minute" >Minute</a></li>
                          <li class="nav-item"> <a  href="#" id="hour" >Hour</a></li>
                          <li class="nav-item"><a href="#"   id="day">Day</a></li>
                          <li class="nav-item"><a  href="#"   id="week">Week</a></li>
                          <li class="nav-item"><a  href="#"   id="month">Month</a></li>
                          <input type="hidden" value="month"  name="queryby" id="queryby">

                      </ul>
                  </div>
                  <div class="col-sm-2 form-group" style="display: none;">
                      <label>Date range</label>

                      <input type="text" name="daterange" disabled="disabled" class="form-control pull-right" autocomplete="off" id="daterange">


                  </div>

                  <div class="col-sm-2 form-group">
                      <label>Select Year</label>

                      {!! Form::select('year',$year,Date('Y'),['id'=>'year','class'=>'form-control']) !!}


                  </div>

                  <div class="col-sm-2 form-group" style="display: none;">
                      <label>Select Hour</label>

                      {!! Form::select('timepicker',$hour,null,['id'=>'timepicker','disabled'=>'disabled','class'=>'form-control']) !!}


                  </div>




                      <div class="col-sm-3 form-group">
                      <label for="queue">Queue</label>
                      {!! Form::select('queue',$queue['options'], $queue['selected'],['id'=>'queue','class'=>'form-control']) !!}
                  </div>
                  <div class="col-sm-1"><button type="submit" id="submit" class="btn btn-primary">
                          Search
                      </button></div>



              </div>
              {!! Form::close() !!}

                <div class="row">
                    <div class="col-md-5">
                    <ul class="nav navbar-nav">
                        <li class="nav-item"><a  href="#"  id="ENTERQUEUE" >Total Calls</a></li>
                        <li class="nav-item"> <a  href="#" id="CONNECT" >Answered</a></li>
                        <li class="nav-item"><a href="#"   id="ABANDON">Unanswered</a></li>
                        <li class="nav-item"><a  href="#"   id="DISTRIBUTION">Distribution</a></li>
                    </ul>
                    </div>
                </div>

              <div class="row">
                  <div class="col-md-12">
                      <table class="table table-hover">
                          <thead id="queue_graph">
                          <tr>
                              <td class="col-md-4">
                              </td>
                              <td class="col-md-4">
                                  <div class="col-sm-4 bg-blue-active">
                                      <span  id="totalcalls"></span>
                                      <div>Total Calls</div>
                                  </div>
                                  <div class="col-sm-4 bg-green">

                                  <span  id="answeredcals"></span>
                                 <div> Answered Calls</div>
                                  </div>
                                  <div class="col-sm-4 bg-red">
                                      <span  id="unansweredcalls"></span>
                                      <div>Unanswered Calls</div>
                                  </div>
                              </td>
                              <td class="col-md-4">
                              </td>

                          </tr>
                          </thead>
                      </table>
                  </div>

              </div>

              <div class="row">


                  <div class="col-md-12">
                      <div class="box-header with-border">


                      </div>
                      <div class="box-body chart-responsive">
                          <div class="chart" id="chartbox">
                              <canvas id="barchart" style="height:250px"></canvas>

                          </div>
                      </div>
                      <!-- /.box-body -->
                  </div>
              </div>

          <div class="row" style="display:none">
              <div class="col-md-12">
                  <!-- Custom Tabs -->

                      <div class="tab-content">
                          <div class="box-body table-responsive no-padding">
                              <table class="table table-hover" id="datareport">
                                  <thead id="thead"><tr><th>id</th><th>time_id</th><th>call_id</th><th>queue</th><th>verb</th><th>queuename</th><th>agent</th><th>event</th><th>data</th><th>data1</th><th>data2</th><th>data3</th><th>data4</th><th>data5</th><th>partition</th><th>created</th></tr></thead>
                                  <tbody id="realBody">
                                  <tr><td>47453</td><td>1542168435</td><td>1542168425.12</td>
                                      <td>4002</td><td>ENTERQUEUE</td><td></td><td>NONE</td>
                                      <td></td><td></td><td></td><td>83213141</td><td>1</td><td></td><td></td><td>P001</td><td>2018-11-14 09:07:15</td></tr>


                                  </tbody>
                              </table>

                          </div>
                          <!-- /.tab-pane -->
                      </div>
                      <!-- /.tab-content -->
                  </div>
                  <!-- nav-tabs-custom -->
              </div>
              <!-- /.col -->


          </div>
         <!-- /.box-header -->
         <!-- /.box-body -->
      </div>
      <!-- /.box -->
   </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">

    var dateTo =  "";
    var dateFrom = "";
    var chartData = {};

    $(function () {
        //$('#daterange-btn span').html(GetTodayDate()+" - "+GetTodayDate())

   /* $("#submit").click(function () {
        var year;var hr; var mn; var tm;
        if($('#year').attr('disabled')!='disabled'){
            year =
        }
        if($('#year').attr('disabled')!='disabled'){

        }
        if($('#year').attr('disabled')!='disabled'){

        }
        if($('#year').attr('disabled')!='disabled'){

        }





        });*/

        var status = 'ENTERQUEUE';
        $( "#ENTERQUEUE,#CONNECT,#ABANDON,#DISTRIBUTION" ).click(function() {

           // var newChartData ['labels'] = chartData['labels'];
            var datasets = [];
            switch ($(this).attr('id')){
                case 'ENTERQUEUE':
                    refreshChart(newChartData = {
                        labels:chartData['labels'],
                        datasets: [
                            chartData['datasets'][0]
                        ]
                    })
                    break;
                case 'CONNECT':
                    refreshChart(newChartData = {
                        labels:chartData['labels'],
                        datasets: [
                            chartData['datasets'][1]
                        ]
                    })
                    break;
                case 'ABANDON':
                    refreshChart(newChartData = {
                        labels:chartData['labels'],
                        datasets: [
                            chartData['datasets'][2]
                        ]
                    })

                    break;
                case 'DISTRIBUTION':
                    refreshChart(chartData);
                    break;


            }

            function  refreshChart(newChartData) {
                barChart(newChartData);
            }

        });
        $("#minute,#hour,#day,#week,#month").click(function(){
            $('#year').parent().hide();
            $('#year').attr("disabled",'disabled');
            $('#timepicker').parent().hide()
            $('#timepicker').attr("disabled",'disabled');

            $('#daterange').parent().show();
            $('#daterange').attr("disabled",false);
            $("#queryby").val($(this).attr('id'))

            if($(this).attr("id")=='minute'){

                $('#daterange').daterangepicker({
                    singleDatePicker: true,
                    datePiker: false,
                    format: 'YYYY/MM/DD'
                });
                $('#timepicker').parent().show();
                $('#timepicker').attr("disabled",false);

            }
            else if($(this).attr("id")=='hour'){

                $('#daterange').daterangepicker({
                    singleDatePicker: true,
                    datePiker: false,
                    format: 'YYYY-MM-DD'
                });
                $("#daterange").attr("disabled",false)

            }
            else if($(this).attr('id')=='day' || $(this).attr('id')=='week'){
                $('#daterange').daterangepicker({format: 'YYYY-MM-DD'});
                $("#daterange").attr("disabled",false)
            }

            else {
                $('#daterange').parent().hide();
                $("#daterange").attr('disabled','disabled');
                $('#timepicker').val("0").parent().hide()
                $('#year').parent().show();
                $("#timepicker").attr("disabled",false)
                $("#year").attr("disabled",false)
            }

        })



        $('#daterange').daterangepicker({
            singleDatePicker: true,
            datePiker: false,
            format: 'YYYY-MM-DD'
        });
        // $("#queue").change(function () { getQueueStats();//getRealTime();
        //
        //
        // });

        setTimeout(getData(),2000);
        $("#submit").on('click',function(e) {

            getData();

            e.preventDefault();
        });


    });

    function getData() {

        var data = $("#report_form").serializeArray();
        $.ajax({

            type:'POST',

            url:"{{ url('/cms/queuereport') }}",

            data:data,

            success:function(data){
                var label = [];
                var received = [];
                var abondaned = [];
                var answered =[];
                switch (data[1]['queryby']){
                    case 'minute':
                        pushlabel(data[0],'minute');
                        break;
                    case 'hour':
                        pushlabel(data[0],'hour');
                        break;
                    case 'day':
                        pushlabel(data[0],'day');
                        break;
                    case 'week':
                        pushlabel(data[0],'week');
                        break;
                    case 'month':
                        pushlabel(data[0],'month');
                        break;
                }
                function pushlabel(data,key){
                    console.log(data);
                    for(d in data)
                    {
                        label.push(data[d][key]);
                        received.push(data[d]['Received'])
                        abondaned.push(data[d]['Abandoned'])
                        answered.push(data[d]['Answered'])
                    }
                }
                chartData = {
                    labels  : label,
                    datasets: [
                        {
                            label               : 'Received',
                            fillColor           : '#005384',
                            strokeColor         : '#005384',
                            pointColor          : '#005384',
                            //pointStrokeColor    : '#c1c7d1',
                            pointHighlightFill  : '#fff',
                            pointHighlightStroke: 'rgba(220,220,220,1)',
                            data                : received
                        },
                        {
                            label               : 'Answered',
                            fillColor           : '#00a65a',
                            strokeColor         : '#00a65a',
                            pointColor          : '#00a65a',
                            // pointStrokeColor    : 'rgba(60,141,188,1)',
                            pointHighlightFill  : '#fff',
                            pointHighlightStroke: 'rgba(60,141,188,1)',
                            data                : answered
                        },
                        {
                            label               : 'Abandoned',
                            fillColor           : '#dd4b39',
                            strokeColor         :  '#dd4b39',
                            pointColor          :  '#dd4b39',
                            // pointStrokeColor    : 'rgba(60,141,188,1)',
                            pointHighlightFill  : '#fff',
                            pointHighlightStroke: 'rgba(60,141,188,1)',
                            data                : abondaned
                        }
                    ]

                }
                barChart(chartData);


            }

        });

    }

    function refreshData() {
        if(dateTo !=  $("#dateTo").val() || dateFrom != $("#dateFrom").val() ) {
            dateTo =  $("#dateTo").val();
            dateFrom = $("#dateFrom").val();
            getQueueStats();//getRealTime();

        }
        else if(GetTodayDate()!=$("#dateTo").val() && $("#dateTo").val()!="" && GetCurrentMonthLastDate()!=$("#dateTo").val()) {
            return "";
        }

        else {
            getQueueStats();//getRealTime();
        }

    }

    function getQueueStats()  {

        return "";
        var url = "{{ url('/cms/queuestats/stats') }}"

        var pieData = [];
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            data: {method: '_GET', "dateFrom": $("#dateFrom").val(), "dateTo": $("#dateTo").val(), "queue":$("#queue").val(),"status":status, "_token": "{{ csrf_token() }}" , submit: true},
            success: function (response) {
                if(response.length>0) {

                    $.each(response, function (key, value) {
                        if ($("#totalcalls").val() != value.Received && $("#totalcalls").text() != value.Answered && $("#unansweredcalls").text() != value.Abandoned)
                        {

                            $("#chart").html($(' <canvas id="piChart" style="height:250px"></canvas>'));
                            $("#totalcalls").text(value.Received);
                            $("#answeredcals").text(value.Answered);
                            $("#unansweredcalls").text(value.Abandoned);

                            var areaChartData = {
                                //labels  : ['1', '2', '3', '4', 'May', 'June', 'July'],
                                datasets: [
                                    {
                                        label               : 'Electronics',
                                        fillColor           : 'rgba(210, 214, 222, 1)',
                                        strokeColor         : 'rgba(210, 214, 222, 1)',
                                        pointColor          : 'rgba(210, 214, 222, 1)',
                                        pointStrokeColor    : '#c1c7d1',
                                        pointHighlightFill  : '#fff',
                                        pointHighlightStroke: 'rgba(220,220,220,1)',
                                        data                : [65, 59, 80, 81, 56, 55, 40]
                                    },
                                    {
                                        label               : 'Digital Goods',
                                        fillColor           : 'rgba(60,141,188,0.9)',
                                        strokeColor         : 'rgba(60,141,188,0.8)',
                                        pointColor          : '#3b8bba',
                                        pointStrokeColor    : 'rgba(60,141,188,1)',
                                        pointHighlightFill  : '#fff',
                                        pointHighlightStroke: 'rgba(60,141,188,1)',
                                        data                : [28, 48, 40, 19, 86, 27, 90]
                                    }
                                ]
                            }
                            PieData = [
                                {
                                value: value.Answered,
                                color: '#f56954',
                                highlight: '#f56954',
                                label: 'Answered Calls'
                            },
                                {
                                    value: value.Received,
                                    color: '#00a65a',
                                    highlight: '#00a65a',
                                    label: 'Total Calls'
                                },
                                {
                                    value: value.Abandoned,
                                    color: '#f39c12',
                                    highlight: '#f39c12',
                                    label: 'Abandoned Calls'
                                }
                            ]
                            pieChart(pieData)
                        }


                    });
                }
                else {
                    $("#chart").html($(' <div> No Data Found</div>'));
                    $("#totalcalls").text("");
                    $("#answeredcals").text("");
                    $("#unansweredcalls").text("");
                }
            },
            error: function (result, status, err) {
                ///alert(result.responseText);
                ///alert(status.responseText);
                ///alert(err.Message);
            },
        });
    }

    function getRealTime() {

        var url = "{{ url('/cms/queuereport/stats') }}"
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            data: {method: '_GET', "dateFrom": $("#dateFrom").val(), "dateTo": $("#dateTo").val(), "queue":$("#queue").val(),"status":status, "_token": "{{ csrf_token() }}" , submit: true},
            success: function (response) {
                var i=0;

                $("#realBody").html("");
                $("#thead").html("");
                $.each(response,function(key,value){
                    if(i==0) {
                        var tr = "<tr>";
                        $.each(value, function (k, v) {
                            tr += '<th>' + k + '</th>';
                        })
                        tr += '</tr>';

                        $("#thead").append(tr);
                        i=1;
                    }
                    var tr="<tr>";
                    $.each(value, function (k,v) {
                        tr+='<td>'+v+'</td>';
                    })
                    tr+='</tr>';

                    $("#realBody").append(tr);
                });
            },
            error: function (result, status, err) {
                ///alert(result.responseText);
                ///alert(status.responseText);
                ///alert(err.Message);
            },
        });
    }

    function pieChart(pieData) {

        // Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $('#piChart').get(0).getContext('2d')
        var pieChart       = new Chart(pieChartCanvas)
        var pieOptions     = {
            //Boolean - Whether we should show a stroke on each segment
            segmentShowStroke    : true,
            //String - The colour of each segment stroke
            segmentStrokeColor   : '#fff',
            //Number - The width of each segment stroke
            segmentStrokeWidth   : 2,
            //Number - The percentage of the chart that we cut out of the middle
            percentageInnerCutout: 0, // This is 0 for Pie charts
            //Number - Amount of animation steps
            animationSteps       : 100,
            //String - Animation easing effect
            animationEasing      : 'easeOutBounce',
            //Boolean - Whether we animate the rotation of the Doughnut
            animateRotate        : true,
            //Boolean - Whether we animate scaling the Doughnut from the centre
            animateScale         : false,
            //Boolean - whether to make the chart responsive to window resizing
            responsive           : true,
            // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
            maintainAspectRatio  : true,
            //String - A legend template
        }
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        pieChart.Doughnut(PieData, pieOptions)
    }

    function barChart(barChartData)
    {
        $("#chartbox").html("").append($('<canvas id= "barchart" style= "height:250px"></canvas>'));
        var barChartCanvas                   = $('#chartbox').find("#barchart").get(0).getContext('2d')
        var barChart                         = new Chart(barChartCanvas)
        //var barChartData                     = areaChartData

        var barChartOptions                  = {

            //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
            scaleBeginAtZero        : true,
            //Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines      : true,
            //String - Colour of the grid lines
            scaleGridLineColor      : 'rgba(0,0,0,.05)',
            //Number - Width of the grid lines
            scaleGridLineWidth      : 1,
            //Boolean - Whether to show horizontal lines (except X axis)
            scaleShowHorizontalLines: true,
            //Boolean - Whether to show vertical lines (except Y axis)
            scaleShowVerticalLines  : true,
            //Boolean - If there is a stroke on each bar
            barShowStroke           : true,
            //Number - Pixel width of the bar stroke
            barStrokeWidth          : 1,
            //Number - Spacing between each of the X value sets
            barValueSpacing         : 5,
            //Number - Spacing between data sets within X values

            barDatasetSpacing       : 1,
            //String - A legend template

      //Boolean - whether to make the chart responsive
      responsive              : true,
      maintainAspectRatio     : true
    }

    barChartOptions.datasetFill = false
    barChart.Bar(barChartData, barChartOptions)

    }

</script>
@endpush


