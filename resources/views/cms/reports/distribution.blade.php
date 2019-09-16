@extends('layouts.app')
@section('content-header')

    <h1>Distribution</h1>
    <ol class="breadcrumb">
        <li><a href="{{URL::asset('/')}}cms"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-book"></i> Reports</a></li>
        <li class="active">Distribution report</li>
    </ol>
@endsection
@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="box">

                <div class="box-body">
                    <div class="col-lg-12">
                        <input type="hidden" name="dateFrom" id="dateFrom" value="{{ $datefrom }}"/>
                        <input type="hidden" name="timeFrom" id="timeFrom" value="{{ $timefrom }}"/>
                        <input type="hidden" name="dateTo" id="dateTo" value="{{ $dateto }}"/>
                        <input type="hidden" name="timeTo" id="timeTo" value="{{ $timeto }}"/>
                        <input type="hidden" name="queue" id="queue" value="{{ $available_queue }}"/>
                        <input type="hidden" name="agent" id="agent" value="{{ $agents }}"/>

                        <div class="col-md-6" id="distribution_summary">
                            <table class="table table-bordered">
                                <caption>Distribution Summary</caption>
                                <tbody>
                                <tr>
                                    <td>
                                        Queue:
                                    </td>
                                    <td>{!! $available_queue !!}</td>
                                </tr>
                                <tr>
                                    <td>Start Date:</td>
                                    <td>{!! date('d-m-Y',strtotime($start_date)) !!}</td>
                                </tr>
                                <tr>
                                    <td>End Date:</td>
                                    <td>{!! date('d-m-Y',strtotime($end_date)) !!}</td>
                                </tr>
                                <tr>
                                    <td>Hour Range:</td>
                                    <td>{!! $hour_range !!}</td>
                                </tr>
                                <tr>
                                    <td>Period:</td>
                                    <td>{!! $period !!}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">

                            <table class="table table-bordered">
                                <caption>Total Calls</caption>
                                <tbody>
                                <tr>
                                    <td>Number of Received Calls:</td>
                                    <td>{!! $total_calls['Received'] !!}</td>
                                </tr>
                                <tr>
                                    <td>Number of Answered Calls:</td>
                                    <td>{!! $total_calls['Answered'] !!}</td>
                                </tr>

                                <tr>
                                    <td>Number of Abandoned Calls:</td>
                                    <td>{!! $total_calls['Abandoned'] !!}</td>
                                </tr>

                                <tr>
                                    <td>Answered Rate:</td>
                                    <td>{!! $total_calls['AnswerRate'] !!} %</td>
                                </tr>
                                <tr>
                                    <td>Abandon Rate:</td>
                                    <td>{!! $total_calls['AbandonRate'] !!} %</td>
                                </tr>
                                </tbody>
                            </table>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="box">

                <div class="box-body">
                    <div class="col-lg-12" id="distribution_by_queue">

                        <table id="distributionbyqueue" class="table striped table-bordered dataTable" role="grid">
                            <caption><h3>
                                    Distribution By Queue
                                </h3>
                            </caption>
                            <thead>
                            <tr>
                                <th style="width:10%">Queue</th>
                                <th style="width:10%">Received</th>
                                <th style="width:10%">Answered</th>
                                <th style="width:10%">%Answered</th>
                                <th style="width:10%">Abandoned</th>
                                <th style="width:10%">%Abandoned</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php $i = 1; ?>
                            @foreach($dist_by_queue as $queue)
                                <?php $i++; ?>
                                <tr>
                                    <td style="width:10%">
                                        <a id="{{ $queue->queue }}" data-id="{{ $i }}" type="queue"
                                           class="showHide queue">
                                            <i class="fa fa-plus"></i>{{ $queue->queue }}</a>
                                    </td>
                                    <td style="width:10%">{{ $queue->received }}</td>
                                    <td style="width:10%">{{ $queue->answered }}</td>
                                    <td style="width:10%">{{ $queue->answeravg }}</td>
                                    <td style="width:10%">{{ $queue->abandon }}</td>
                                    <td style="width:10%">{{ $queue->abandonavg }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                    <div class="col-md-6">
                        <div id="queue1" class="resizeDiv" style="height: 371px;">
                            <div id="chartqueue1"
                                 style="width: 90%; height: 345.03px; margin: 25px auto; position: relative;"
                                 class="jqplot-target">

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="box">

                <div class="box-body">
                    <div class="col-lg-12" id="distribution_per_month">

                        <table id="distributionbymonth" class="table table-hover" width="100%">
                            <caption><h3>
                                    Distribution By month
                                </h3>
                            </caption>
                            <thead>
                            <tr>
                                <th style="width:10%">Month</th>
                                <th style="width:10%">Received</th>
                                <th style="width:10%">Answered</th>
                                <th style="width:10%">%Answered</th>
                                <th style="width:10%">Abandoned</th>
                                <th style="width:10%">%Abandoned</th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1; ?>
                            @foreach($dist_by_month as $month)
                                <?php $i++; ?>
                                <tr>
                                    <td style="width:10%">
                                        <a id="{{ $month->month}}" href="#!" data-id="{{ $i }}" class="showHide"
                                           type="month">
                                            <i class="fa fa-plus"></i>
                                            {{ $month->month }}
                                        </a>
                                    </td>
                                    <td style="width:10%">{{ $month->received }}</td>
                                    <td style="width:10%">{{ $month->answered }}</td>
                                    <td style="width:10%">{{ $month->answeravg }}</td>
                                    <td style="width:10%">{{ $month->abandon }}</td>
                                    <td style="width:10%">{{ $month->abandonavg }}</td>
                                </tr>

                            @endforeach
                            </tbody>
                        </table>


                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="box">

                <div class="box-body">
                    <div class="col-lg-12" id="distribution_per_dayofweek">

                        <table id="distributionbydayweek" class="table table-hover " width="100%">
                            <caption><h3>
                                    Distribution By Day of Week
                                </h3>
                            </caption>
                            <thead>
                            <tr>
                                <th class="sorting_disabled" style="width:10%">Day</th>
                                <th style="width:10%">Received</th>
                                <th style="width:10%">Answered</th>
                                <th style="width:10%">%Answered</th>
                                <th style="width:10%">Abandoned</th>
                                <th style="width:10%">%Abandoned</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1; ?>
                            @foreach($dist_by_weekday as $day)
                                <?php $i++; ?>
                                <tr>
                                    <td style="width:10%">
                                        <a id="{{$day->day}}" href="#!" data-id="{{ $i }}" class="showHide"
                                           type="dayweek">
                                            <i class="fa fa-plus"></i>
                                            {{ $day->day }}
                                        </a>

                                    </td>
                                    <td style="width:10%">{{ $day->received }}</td>
                                    <td style="width:10%">{{ $day->answered }}</td>
                                    <td style="width:10%">{{ $day->answeravg }}</td>
                                    <td style="width:10%">{{ $day->abandon }}</td>
                                    <td style="width:10%">{{ $day->abandonavg }}</td>
                                </tr>

                            @endforeach
                            </tbody>
                        </table>


                    </div>

                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-12">
            <div class="box">

                <div class="box-body">
                    <div class="col-lg-12" id="distribution_per_day">
                        <table id="distributionbyday" class="table table-hover" width="100%">
                            <caption><h3>
                                    Distribution By Day
                                </h3>
                            </caption>
                            <thead>
                            <tr>
                                <th style="width:10%">Date</th>
                                <th style="width:10%">Received</th>
                                <th style="width:10%">Answered</th>
                                <th style="width:10%">%Answered</th>
                                <th style="width:10%">Abandoned</th>
                                <th style="width:10%">%Abandoned</th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1; ?>
                            @foreach($dist_by_day as $day)
                                <?php $i++; ?>
                                <tr>
                                    <td style="width:10%">
                                        <a id="{{$day->day}}" href="#!" data-id="{{ $i }}" class="showHide"
                                           type="day">
                                            <i class="fa fa-plus"></i>
                                            {{ $day->day }}
                                        </a>
                                    </td>
                                    <td style="width:10%">{{ $day->received }}</td>
                                    <td style="width:10%">{{ $day->answered }}</td>
                                    <td style="width:10%">{{ $day->answeravg }}</td>
                                    <td style="width:10%">{{ $day->abandon }}</td>
                                    <td style="width:10%">{{ $day->abandonavg }}</td>
                                </tr>

                            @endforeach
                            </tbody>
                        </table>

                    </div>
                    <div class="col-md-12">
                        <div id="day1" class="resizeDiv" style="height: 371px;">
                            <div id="chartday1"
                                 style="width: 90%; height: 345.03px; margin: 25px auto; position: relative;"
                                 class="jqplot-target">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="box">

                <div class="box-body">
                    <div class="col-lg-12" id="distribution_per_hour">

                        <table id="distributionbyhour" class="table table-hover" width="100%">
                            <caption><h3>
                                    Distribution By Hour
                                </h3>
                            </caption>
                            <thead>
                            <tr>
                                <th style="width:10%">Hour</th>
                                <th style="width:10%">Received</th>
                                <th style="width:10%">Answered</th>
                                <th style="width:10%">%Answered</th>
                                <th style="width:10%">Abandoned</th>
                                <th style="width:10%">%Abandoned</th>
                                <!--th style="width:10%">%Duration</th>
                                <th style="width:10%">Cost</th-->
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1; ?>
                            @foreach($dist_by_hour as $hour)
                                <?php $i++; ?>
                                <tr>
                                    <td style="width:10%">
                                        <a id="{{$hour->hour}}" href="#!" data-id="{{ $i }}" class="showHide"
                                           type="hour">
                                            <i class="fa fa-plus"></i>
                                            {{ $hour->hour.":00 - ". $hour->hour .":59" }}
                                        </a>
                                    </td>
                                    <td style="width:10%">{{ $hour->received }}</td>
                                    <td style="width:10%">{{ $hour->answered }}</td>
                                    <td style="width:10%">{{ $hour->answeravg }}</td>
                                    <td style="width:10%">{{ $hour->abandon }}</td>
                                    <td style="width:10%">{{ $hour->abandonavg }}</td>
                                </tr>

                            @endforeach
                            </tbody>
                        </table>


                    </div>
                    <div class="col-md-6">
                        <div id="hour1" class="resizeDiv" style="height: 371px;">
                            <div id="charthour1"
                                 style="width: 90%; height: 345.03px; margin: 25px auto; position: relative;"
                                 class="jqplot-target">

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="box">

                <div class="box-body">
                    <div class="col-lg-12" id="distribution_per_week">

                        <table id="distributionbyweek" class="table table-hover" width="100%">
                            <caption><h3>
                                    Distribution By Week
                                </h3>
                            </caption>
                            <thead>
                            <tr>
                                <th style="width:10%">Week</th>
                                <th style="width:10%">Received</th>
                                <th style="width:10%">Answered</th>
                                <th style="width:10%">%Answered</th>
                                <th style="width:10%">Abandoned</th>
                                <th style="width:10%">%Abandoned</th>
                                <!--th style="width:10%">%Duration</th>
                                <th style="width:10%">Cost</th-->
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1; ?>
                            @foreach($dist_by_week as $week)
                                <?php $i++; ?>
                                <tr>
                                    <td style="width:10%">
                                        <a id="{{$week->week}}" href="#!" data-id="{{ $i }}" class="showHide"
                                           type="week">
                                            <i class="fa fa-plus"></i>
                                            Week {{ $week->week }}
                                        </a>
                                    </td>
                                    <td style="width:10%">{{ $week->received }}</td>
                                    <td style="width:10%">{{ $week->answered }}</td>
                                    <td style="width:10%">{{ $week->answeravg }}</td>
                                    <td style="width:10%">{{ $week->abandon }}</td>
                                    <td style="width:10%">{{ $week->abandonavg }}</td>
                                </tr>

                            @endforeach
                            </tbody>
                        </table>


                    </div>

                </div>
            </div>
        </div>
    </div>



@endsection
@push('scripts')

    <script type="text/javascript">
        $(document).ready(function () {
            $('#distributionbyqueue').DataTable({
                "pageLength": 50
            });
            // $('#distributionbymonth').DataTable({
            //     "pageLength": 50
            // });
            $('#distributionbyweek').DataTable({
                "pageLength": 50
            });
            $('#distributionbyday').DataTable({
                "pageLength": 50
            });
            $('#distributionbyhour').DataTable({
                "pageLength": 50
            });
            // $('#distributionbydayweek').DataTable({
            //     "pageLength": 50,
            //     "aoColumnDefs": [
            //         {
            //             "bSortable": false,
            //             "aTargets": ["sorting_disabled"]
            //         }
            //     ]
            // });
        });

        $('#distributionbyweek').on('page.dt', function () {

        })
        $('#distributionbyday').on('page.dt', function () {

            //$(this).find("#sub_tr").remove()
            $(this).find(".fa-minus").removeClass("fa-minus").addClass("fa-plus")

        })


        $(document).on('click', '.showHide[data-id]', function (e) {


            var url = '{{url("/")}}' + '/cms/subdata';
            $that = $(this);

            if ($that.find('i').hasClass("fa-plus")) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        "userid": $(this).data("remote"),
                        "_token": "{{ csrf_token() }}",
                        "queue": $('#queue').val(),
                        "type": $(this).attr("type"),
                        "dateFrom": $("#dateFrom").val(),
                        "dateTo": $("#dateTo").val(),
                        "timeFrom": $("#timeFrom").val(),
                        "timeTo": $("#timeTo").val(),
                        "agent": $("#agent").val(),
                        "typeval": $(this).attr("id"),
                        "submit": true
                    },
                    beforeSend: function () {
                        $('#preloader').css("display", "block");
                    },
                    success: function (res) {
                        $('#preloader').css("display", "none");
                        $that.find('i').removeClass("fa fa-plus").addClass("fa fa-minus");
                        $that.closest("tr").after($(res))
                        if ( $.fn.DataTable.isDataTable('.subdata') ) {
                            $('.subdata').DataTable().destroy();
                        }
                        $('.subdata').DataTable({
                            "pageLength": 50
                        });

                    },
                    error: function (result, status, err) {
                        $('#preloader').css("display", "none");
                        console.log(result.responseText);
                        console.log(status.responseText);
                        console.log(err.Message);
                    }
                })

            }
            else {
                $that.closest("tr").next('tr.sub_tr').remove()
                $that.closest("tr").next('tr.sub_tr').remove()
                $that.find('i').removeClass("fa fa-minus").addClass("fa fa-plus");
            }


        });
        dist_by_queue_data = <?php print_r($dist_by_queue_chart) ?>;

        dist_by_day_data=<?php print_r($dist_by_day_chart)?>;
        dist_by_hour_data=<?php print_r($dist_by_hour_chart)?>;

        jqplotchart(dist_by_queue_data,'chartqueue1',"Distribution by Queue ")
        jqplotchart(dist_by_hour_data,'charthour1',"Distribution per hour ")
        jqplotchart(dist_by_day_data,'chartday1',"Distribution per day ")

        function jqplotchart(data,elm,text) {
            var label = [];
            var received = [];
            var abandaned = [];
            var answered = [];


            if(elm=='chartqueue1'){
                label = [<?=$available_queue?>];
                for (d in data) {
                    abandaned.push(data[d]['abandon'])
                    answered.push(data[d]['answered'])
                }
            }
            else if(elm=="charthour1"){
                for (d in data) {
                    label.push(data[d]['hour'])
                    abandaned.push(data[d]['abandon'])
                    answered.push(data[d]['answered'])
                }
            }
            else if(elm=="chartday1"){
                for (d in data) {
                    label.push(data[d]['day'])
                    abandaned.push(data[d]['abandon'])
                    answered.push(data[d]['answered'])
                }
            }


            var queue1 = $.jqplot(elm, [answered, abandaned], {
                stackSeries: false,
                seriesDefaults: {

                    renderer: $.jqplot.BarRenderer,
                    rendererOptions: {fillToZero: false, barPadding: 2, barMargin: 4}
                    //pointLabels: { show: true }

                },
                series: [{label: 'Answered'}, {label: 'Abandoned'}],

                legend: {
                    show: true,
                    placement: 'outsideGrid'
                },

                axesDefaults: {
                    tickRenderer: $.jqplot.CanvasAxisTickRenderer,
                    tickOptions: {
                        angle: -30,
                        fontSize: '9pt'
                    }
                },
                axes: {
                    xaxis: {
                        renderer: $.jqplot.CategoryAxisRenderer,
                        tickOptions: {formatString: '%s %s %s'},
                        ticks: label
                    },
                    yaxis: {
                        pad: 1.05,
                        min: 0,
                        tickOptions: {formatString: '%2d'},
                    }
                },
                title: {text: text, fontSize: 20, escapeHtml: false},
                highlighter: {
                    show: true,
                    usesAxesFormatters: true,
                    tooltipAxes: 'xy',
                    useXTickMarks: true,
                    tooltipOffset: 0,
                    tooltipLocation: 'nw',
                    sizeAdjust: 7.5,
                    formatString: '%s<br/>%3$s'
                },
                seriesColors: ['#FF6600', '#528252', '#E91A22', '#05449A', '#511A7F', '#901118', '#A32382', '#000000']

            });


            /*/*var imgData = $('#chartqueue01inbound').jqplotToImageStr({});
          $.post( 'save.php', { id: 'chartqueue01inbound', imgdata: imgData } );
          */


            /*
                    chartData = {
                        labels: label,
                        datasets: [
                            {
                                label: 'Received',
                                fillColor: '#005384',
                                strokeColor: '#005384',
                                pointColor: '#005384',
                                //pointStrokeColor    : '#c1c7d1',
                                pointHighlightFill: '#fff',
                                pointHighlightStroke: 'rgba(220,220,220,1)',
                                data: received
                            },
                            {
                                label: 'Answered',
                                fillColor: '#00a65a',
                                strokeColor: '#00a65a',
                                pointColor: '#00a65a',
                                // pointStrokeColor    : 'rgba(60,141,188,1)',
                                pointHighlightFill: '#fff',
                                pointHighlightStroke: 'rgba(60,141,188,1)',
                                data: answered
                            }
                        ]

                    }
                    console.log(chartData);
                    var barChartCanvas  = $('#queue1').find("#queuechart1").get(0).getContext('2d')
                    barChart(chartData,barChartCanvas); */


        }


        function barChart(barChartData, barChartCanvas) {

            // var barChartCanvas                   = $('#chartbox').find("#barchart").get(0).getContext('2d')
            var barChart = new Chart(barChartCanvas)


            var barChartOptions = {

                //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
                scaleBeginAtZero: true,
                //Boolean - Whether grid lines are shown across the chart
                scaleShowGridLines: true,
                //String - Colour of the grid lines
                scaleGridLineColor: 'rgba(0,0,0,.05)',
                //Number - Width of the grid lines
                scaleGridLineWidth: 1,
                //Boolean - Whether to show horizontal lines (except X axis)
                scaleShowHorizontalLines: true,
                //Boolean - Whether to show vertical lines (except Y axis)
                scaleShowVerticalLines: true,
                //Boolean - If there is a stroke on each bar
                barShowStroke: true,
                //Number - Pixel width of the bar stroke
                barStrokeWidth: 1,
                //Number - Spacing between each of the X value sets
                barValueSpacing: 5,
                //Number - Spacing between data sets within X values

                barDatasetSpacing: 1,
                //String - A legend template

                //Boolean - whether to make the chart responsive
                responsive: true,
                maintainAspectRatio: true
            }

            barChartOptions.datasetFill = false
            barChart.Bar(barChartData, barChartOptions)

        }
    </script>

@endpush
