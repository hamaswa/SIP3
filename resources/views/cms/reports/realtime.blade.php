@extends('layouts.app')
@section('content-header')
<h1>
	Real Time
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
             <div class="row">
                 <div class="col-lg-12">
                     {!! $dataTable->table(['width' => '100%']) !!}

                 </div>

             </div>
             <div class="row">
                 <div class="col-lg-12">

            <table class="table table-dark table-hover align-content-center" width="100%">
               <tbody>
               <tr><th colspan="5">Agent</th> </tr>
                  <tr>
                      <th style="width:10%">Agent</th>
                      <th style="width:10%">Extension</th>
                      <th style="width:10%">Login Time</th>
                      <th style="width:10%">Status</th>
                      <th style="width:10%">Info</th>
                  </tr>
               <tbody id="realBody">
               </tbody>
            </table>
         </div>
      </div>
         </div>
         <!-- /.box-body -->
      </div>
      <!-- /.box -->
   </div>
</div>
@endsection


@push('style')
    @include('admin.layouts.datatables_css')
@endpush

@push('scripts')
    @include('admin.layouts.datatables_js')
    {!! $dataTable->scripts() !!}
    <script type="text/javascript">
        setInterval("getRealTime()",5000);
        function getRealTime() {
            var url = "{{ url('/cms/realtime/stats') }}"
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                data: {method: '_GET', "_token": "{{ csrf_token() }}", submit: true},
                success: function (response) {
                    if(response.length>0) {
                        $("#realBody").html("");
                    } else {
                        $("#realBody").html("<tr><td colspan='4'><b>No Agent Login</b></td></tr>");

                    }                $.each(response, function (key, value) {
                        timeOnline = updateClock(value.login_time)
                        status = value.status;
                        color = '#ffffaa'
                        switch (value.status){
                            case '0':
                                status = "Unknown";
                                color ='#ffcf00';
                                break;
                            case '2':
                                status = "Online";
                                color ='#00ff00';
                                break;
                            case '4':
                                status = "Invalid";
                                color ='#ff4400';
                                break;
                            case '6':
                                status = "Ringing";
                                color ='#004ff0';
                                break;
                            case '8':
                                status = "OnHold";
                                color ='#aaffff';
                                break;
                        }
                        html = '<tr>';
                        html += '<td>' + value.membername + '<td>' + value.interface + '</td><td now="' + response.currenttime + '"  start="' + value.login_time + '" id=' + value.id + ' class="time-elasped">' + timeOnline + '</td>';
                        html += '<td><span style="background-color:'+color+'">' + status + '</span></td><td></td>';
                        html +='<tr>';

                        $("#realBody").append($(html));
                    });

                    function updateClock(startDateTime, nowTime) {
                        var startDateTime = new Date(startDateTime); // YYYY (M-1) D H m s (start time and date from DB)
                        var startStamp = startDateTime.getTime();

                        var estTime = new Date();
                        var nowTime = new Date(estTime.toLocaleString('en-US', {timeZone: 'Asia/Singapore'}));
                        var nowStamp = nowTime.getTime();

                        var diff = Math.round((nowStamp - startStamp) / 1000);

                        var d = Math.floor(diff / (24 * 60 * 60));
                        diff = diff - (d * 24 * 60 * 60);
                        var h = Math.floor(diff / (60 * 60));
                        diff = diff - (h * 60 * 60);
                        var m = Math.floor(diff / (60));
                        diff = diff - (m * 60);
                        var s = diff;

                        return   h + ":" + m + ":" + s;

                    }
                },
                error: function (result, status, err) {

                },
            });
        }



        setInterval("updateClock($('.time-elasped'))",1000);

        function updateClock(td) {
            td.each(function(index){
                var startDateTime = new Date($(this).attr("start")); // YYYY (M-1) D H m s (start time and date from DB)
                var startStamp = startDateTime.getTime();

                var estTime = new Date();
                var nowTime = new Date(estTime.toLocaleString('en-US', { timeZone: 'Asia/Singapore' }));
                var nowStamp = nowTime.getTime();
                console.log(nowTime);
                console.log(startDateTime);

                var diff = Math.round((nowStamp-startStamp)/1000);

                var d = Math.floor(diff/(24*60*60));
                diff = diff-(d*24*60*60);
                var h = Math.floor(diff/(60*60));
                diff = diff-(h*60*60);
                var m = Math.floor(diff/(60));
                diff = diff-(m*60);
                var s = diff;

                document.getElementById($(this).attr('id')).innerHTML = h+":"+m+":"+s;
            });

        }


        getRealTime();
    </script>
@endpush

