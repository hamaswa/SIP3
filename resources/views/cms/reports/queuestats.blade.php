@extends('layouts.app')
@section('content-header')
    <h1>
        Realtime Wallboard
    </h1>

@endsection
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Realtime Wallboard</h3>
                    <div class="box-tools">
                        <!--<div class="input-group input-group-sm" style="width: 150px;">
                           <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">
                           <div class="input-group-btn">
                              <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                           </div>
                        </div>-->
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="col-lg-12">

                    <table class="table table-dark table-hover align-content-center" width="100%">
                        <tbody>
                        <tr>
                            <th style="width:20%">Queue</th>
                            <th style="width:20%">Total Calls</th>
                            <th style="width:20%">Answered Calls</th>
                            <th style="width:20%">Abandoned Calls</th>
                            <th style="width:20%">Average Ans Calls</th>
                        </tr>
                        <tbody id="realTimeQueue">
                        </tbody>
                    </table>
                </div>

                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">


        setInterval("getRealTime()",3000);
        function getRealTime()
        {
            var url = "{{ url('/cms/queuestats/stats') }}"
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                data: {method: '_GET', "_token": "{{ csrf_token() }}" , submit: true},
                success: function (response) {

                    html = "";
                    $.each(response.queue_data, function (key, value) {
                        if(key%2==0)
                            color="#aaaaaa";
                        else color="#ffffff"
                        if(value.queue_description=="Total")
                            html += '<tr style="font-weight:bolder;background-color:'+color+'">';
                        else
                            html += '<tr style="background-color:'+color+'">';
                        html += '<td>' + value.queue_description + '</td>';
                        html += '<td>' + value.received + '</td>';
                        html += '<td>' + value.answered + '</td>';
                        html += '<td>' + value.abandon + '</td>';
                        html += '<td>' + value.talk_time + '</td>';
                        html += '</tr>';

                    });
                    $("#realTimeQueue").html($(html));

                },
                error: function (result, status, err) {
                    ///alert(result.responseText);
                    ///alert(status.responseText);
                    ///alert(err.Message);
                },
            });
        }



    </script>
@endpush


