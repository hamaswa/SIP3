@extends('layouts.app')
@section('content-header')
    <h1>
        Real Time Extension
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{URL::asset('/')}}cms"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Real Time report</li>
    </ol>
@endsection
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Real Time report</h3>
                    <div class="box-tools">
                    </div>
                </div>
                <!-- /.box-header -->

                <div class="box-body table-responsive no-padding">
                    {{--<div class="row">--}}
                    {{--<div class="col-lg-12">--}}
                    {{--{!! $dataTable->table(['width' => '100%']) !!}--}}

                    {{--</div>--}}

                    {{--</div>--}}
                    <div class="row realTimeExt col-lg-12" id="" style="text-align:center;">
                        <table class="table table-responsive table-bordered">
                            <thead>
                            <tr>
                                <th style="width:10%">Status</th>
                                <th style="width:10%">User Extension</th>
                                <th style="width:10%">User</th>
                                <th style="width:10%">Direction</th>
                                <th style="width:10%">Count</th>
                                <th style="width:10%">Answered</th>
                                <th style="width:10%">Unanswered</th>
                                <th style="width:10%">Duration</th>
                                {{--<th style="width:10%">Cost</th>--}}
                            </tr>
                            </thead>
                            <tbody id="realTimeExt">

                            </tbody>
                        </table>

                    </div>

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection

@if(isset($mode) and $mode=="advanced")
    @push('scripts')

        <script type="text/javascript">
            setInterval("getRealTime()", 1000);

            function getRealTime() {
                var url = "{{ route('realtime_ext.getdetails') }}"
                $.ajax({
                    url: url,
                    type: 'Post',
                    dataType: 'json',
                    data: {method: '_GET', "_token": "{{ csrf_token() }}", submit: true},
                    success: function (response) {
                        html = "";
                        $.each(response.reception_console, function (k, v) {
                            inbound = v.inbound;
                            outbound = v.outbound;
                            sts = "";
                            if (!(isNaN(v.status[0]))) {

                                switch (v.status[3]) {
                                    case 'Unavailable':
                                        style= 'background-color:lightgrey;color:#000';
                                        sts = "Offline";
                                        break;
                                    case 'Idle':
                                        style= 'background-color:green;color:#fff';
                                        sts = "Available"
                                        break;
                                    case 'InUse':
                                        style= 'background-color:red;color:#fff';
                                        sts = "Busy";
                                        break;
                                    case 'Ringing':
                                        style= 'background-color:orange;color:#fff';
                                        sts = "Ringing";
                                        break;
                                }
                                html += '<tr style="' + style +'"><td rowspan="2">' + sts + '</td><td rowspan="2">' + v.status[0] + '</td><td rowspan="2">' + v.status[2] + "</td>";
                                if (inbound != 'no_data') {
                                    html += '<td>Inbound</td><td>' + inbound.Total + '</td>';
                                    html += '<td>' + inbound.Completed + '</td><td>' + inbound.Missed + '</td>';
                                    html += '<td>' + getTime(inbound.Duration) + '</td></tr>'; //<td>$' + Math.round((inbound.Billing / 60 * 0.06)*100)/100 + '</td>
                                }
                                else {
                                    html += '<td>Inbound</td><td>0</td><td>0</td>';
                                    html += '<td>00:00:00</td><td>0</td></tr>';//<td>0</td>
                                }
                                if (outbound != 'no_data') {
                                    html += '<tr style="background-color:' + color + '"><td>Outbound</td><td>' + outbound.Total + '</td>';
                                    html += '<td>' + outbound.Completed + '</td><td>' + outbound.Missed + '</td>';
                                    html += '<td>' + getTime(outbound.Duration) + '</td></tr>'; // <td>$' + Math.round((outbound.Billing / 60 * 0.06)*100)/100 + '</td>
                                }
                                else {
                                    html += '<tr style="background-color:' + color + '"><td>Outbound</td><td>0</td>';
                                    html += '<td>0</td><td>0</td>';
                                    html += '<td>00:00:00</td></tr>';//<td>0</td>
                                }


                            }


                        })
                        $("#realTimeExt").html($(html));


                    },
                    error: function (result, status, err) {

                    },
                });
            }

            function getTime(diff) {

                var h = Math.floor(diff / (60 * 60));
                diff = diff - (h * 60 * 60);
                var m = Math.floor(diff / (60));
                diff = diff - (m * 60);
                var s = diff;

                return n(h) + h + ":" + n(m) + m + ":" + n(s) + s;

                function n(n) {
                    if (n < 10)
                        return "0";
                    else return "";
                }
            }

            getRealTime();
        </script>
    @endpush
@else
    @push('scripts')

        <script type="text/javascript">
            setInterval("getRealTime()", 1000);

            function getRealTime() {
                var url = "{{ url('/cms/realtime/stats') }}"
                $.ajax({
                    url: url,
                    type: 'get',
                    dataType: 'json',
                    data: {method: '_GET', "_token": "{{ csrf_token() }}", submit: true},
                    success: function (response) {
                        html = "";
                        $.each(response.reception_console, function (k, v) {

                            if (!(isNaN(v[0]))) {

                                switch (v[3]) {
                                    case 'Unavailable':
                                        html += "<div  class=\"col-lg-2\" style=\"padding-top:15px;padding-bottom:15px;min-height: 160px;\"><span class=\"glyphicon-class col-lg-12\">" + v[2] + "<br>(" + v[0] + ")</span><span class=\"glyphicon glyphicon-phone col-lg-12\" style='color:lightgrey;font-size:40px' aria-hidden=\"true\"></span><span class=\"col-lg-12\">Offline</span></div>"
                                        break;
                                    case 'Idle':
                                        html += "<div  class=\"col-lg-2\" style=\"padding-top:15px;padding-bottom:15px;min-height: 160px\"><span class=\"glyphicon-class col-lg-12\">" + v[2] + "<br>(" + v[0] + ")</span><span class=\"glyphicon glyphicon-phone col-lg-12\" style='color:green;font-size:40px' aria-hidden=\"true\"></span><span class=\"col-lg-12\">Available</span></div>"
                                        break;
                                    case 'InUse':
                                        html += "<div  class=\"col-lg-2\" style=\"padding-top:15px;padding-bottom:15px;min-height: 160px\"><span class=\"glyphicon-class col-lg-12\">" + v[2] + "<br>(" + v[0] + ")</span><span class=\"glyphicon glyphicon-phone col-lg-12\" style='color:red;font-size:40px' aria-hidden=\"true\"></span><span class=\"col-lg-12\">Busy</span></div>"
                                        break;
                                    case 'Ringing':
                                        html += "<div  class=\"col-lg-2\" style=\"padding-top:15px;padding-bottom:15px;min-height: 160px\"><span class=\"glyphicon-class col-lg-12\">" + v[2] + "<br>(" + v[0] + ")</span><span class=\"glyphicon glyphicon-phone col-lg-12\" style='color:red;font-size:40px' aria-hidden=\"true\"></span><span class=\"col-lg-12\">Ringing</span></div>"
                                        break;

                                }
                            }
                        })
                        $(".realTimeExt").html($(html));
                    },
                    error: function (result, status, err) {

                    },
                });
            }

            getRealTime();
        </script>
    @endpush

@endif

