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
                <div class="box-body table-responsive no-padding">

                    <div class="row realTimeExt col-lg-12">
                        <table class="table table-responsive table-bordered realtime">
                            <thead style="background-color:#a0a0a0;text-align:center">
                            <tr>
                                <th rowspan="2">Status</th>
                                <th rowspan="2">User Extension</th>
                                <th rowspan="2">User Name</th>
                                <th colspan="4">Inbound</th>
                                <th colspan="4" style="border: solid #fff 1px;">Outbound</th>
                            </tr>
                            <tr>
                                <th>Count</th>
                                <th>Answered</th>
                                <th>Unanswered</th>
                                <th>Duration</th>
                                <th style="border: solid #fff 1px;">Count</th>
                                <th style="border: solid #fff 1px;">Answered</th>
                                <th style="border: solid #fff 1px;">Unanswered</th>
                                <th style="border: solid #fff 1px;">Duration</th>
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
            setInterval("getRealTime()", 2000);

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
                                        sts = "Talking";
                                        break;
                                    case 'Ringing':
                                        style = 'background-color:orange;color:#fff';
                                        sts = "Ringing";
                                        break;
                                }
                                html += '<tr style="' + style + '"><td>' + sts + '</td><td>' + v.status[0] + '</td><td>' + v.status[2] + "</td>";
                                if (inbound != 'no_data') {
                                    html += '<td>' + inbound.Total + '</td>';
                                    if(inbound.Completed!=null)
                                        html += '<td>' + inbound.Completed + '</td>';
                                    else
                                        html += '<td>0</td>';
                                    if(inbound.Missed!=null)
                                        html += '<td>' + inbound.Missed + '</td>';
                                    else
                                        html += '<td>0</td>';
                                    html += '<td>' + getTime(inbound.Duration) + '</td>'; // <td>$' + Math.round((inbound.Billing / 60 * 0.06)*100)/100 + '</td>
                                }
                                else {
                                    html += '<td>0</td><td>0</td>';
                                    html += '<td>0</td><td>00:00:00</td>';
                                }
                                if (outbound != 'no_data') {
                                    html += '<td>' + outbound.Total + '</td>';
                                    if(outbound.Completed!=null)
                                    html += '<td>' + outbound.Completed + '</td>';
                                    else
                                        html += '<td>0</td>';
                                    if(outbound.Missed!=null)
                                    html += '<td>' + outbound.Missed + '</td>';
                                    else
                                        html += '<td>0</td>';
                                    html += '<td>' + getTime(outbound.Duration) + '</td></tr>';
                                }
                                else {
                                    html += '<td>0</td>';
                                    html += '<td>0</td><td>0</td>';
                                    html += '<td>00:00:00</td><tr>';
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
            setInterval("getRealTime()", 2000);

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

