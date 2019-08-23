@extends('layouts.app')
@section('content-header')
<ol class="breadcrumb">
	<li><a href="{{URL::asset('/')}}cms"><i class="fa fa-dashboard"></i> Dashboard</a></li>
	<li class="active">Real Time Extensions</li>
</ol>
@endsection
@section('content')
<div class="row">
   <div class="col-xs-12">
      <div class="box">
         <div class="box-header">
            <h3 class="box-title">Real Time Extensions</h3>
            <div class="box-tools">
            </div>
         </div>
         <!-- /.box-header -->

         <div class="box-body table-responsive no-padding">


             <div class="row col-lg-12" id="realTimeExt" style="text-align:center">

             </div>
         </div>
         <!-- /.box-body -->
      </div>
      <!-- /.box -->
   </div>
</div>
@endsection



@push('scripts')
    {!! $dataTable->scripts() !!}
    <script type="text/javascript">
        setInterval("getRealTime()",1000);
        function getRealTime() {
            var url = "{{ route('realtimeextensions') }}"
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                data: {method: '_GET', "_token": "{{ csrf_token() }}", submit: true},
                success: function (response) {
                    html = "";
                    $.each(response.reception_console,function(k,v) {

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
                    $("#realTimeExt").html($(html));


                },
                error: function (result, status, err) {

                },
            });
        }

        getRealTime();
    </script>
@endpush

