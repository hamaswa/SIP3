@extends('layouts.app')
@section('content-header')
<h1>
	Call detail report
</h1>
<ol class="breadcrumb">
	<li><a href="{{URL::asset('/')}}cms"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="#"><i class="fa fa-book"></i> Reports</a></li>
	<li class="active">Complete detail report</li>
</ol>
@endsection
@section('content')
<div class="row">
   <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
            <h3 class="box-title">Complete detail report (Per User)</h3>
            <div class="box-tools">
               <!--<div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">
                  <div class="input-group-btn">
                     <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
               </div>-->
            </div>
            <hr/>
            {!! Form::open(['method'=>'get','id'=>"iocallreportfrm"]) !!}
            <input name="type" type="hidden" value="" id="type">
            <div class="row">
            	<div class="col-sm-3 form-group">
                	<label for="exampleInputEmail1">Date range</label>
                    <button type="button" class="btn btn-default form-control" id="daterange-btn">
                        <span class="pull-left">
                        	@if(Session::get('dateFrom')!=NULL)
                            	<i class="fa fa-calendar"></i> {{ Session::get('dateFrom') }} - {{ Session::get('dateTo') }}
                            @else
                          		<i class="fa fa-calendar"></i> Date range picker
                            @endif
                        </span>
                        <i class="fa fa-caret-down pull-right"></i>
                    </button>
                    <input type="hidden" name="dateFrom" id="dateFrom" value="{{ Session::get('dateFrom') }}" />
                    <input type="hidden" name="dateTo" id ="dateTo" value="{{ Session::get('dateTo') }}" />
                </div>
                <div class="col-sm-3 form-group">
                    <label for="exampleInputEmail1">Status</label>
                    {!! Form::select('dispo',[''=>'Select Disposition','1'=>'ANSWERED','0'=>'NO ANSWER' ,'2'=>'BUSY' ,'3'=>'FAILED'],null,['class'=>'form-control']) !!}
                </div>
                <div class="col-sm-3 form-group">
                    <label for="exampleInputEmail1">Direction</label>
                    {!! Form::select('direction',[''=>'Select Direction','1'=>'Outbound','0'=>'Inbound'],null,['class'=>'form-control']) !!}
                </div>
                <div class="col-sm-3 form-group">
                    <label for="exampleInputEmail1">From</label>
                    {!! Form::text('calling_from', null, ['class' => 'form-control']) !!}
                </div>
                <div class="col-sm-3 form-group">
                    <label for="exampleInputEmail1">To</label>
                    {!! Form::text('dialed_number', null, ['class' => 'form-control']) !!}
                </div>
                @if(request()->user()->can("outbound_idd"))
                    <div>
                        <div class="col-sm-3 form-group">
                            <label for="exampleInputEmail1" class="col-sm-12">Outbound IDD</label>
                            <div class="col-sm-3 form-group">
                            <input name="outbound_idd" type="checkbox" {{ (request()->input('outbound_idd')!=NULL?"checked":"")  }} value="outbound_idd" class="form-control">
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-sm-3 form-group">
                    <label for="exampleInputEmail1">&nbsp;</label>
                    <div class="input-group">
                        <!--<input class="form-control" id="search"
                               value="{{ request('search') }}"
                               placeholder="Search name" name="search"
                               type="text" id="search"/>-->
                        <div class="input-group-btn">
                            <button id="btnsubmit" class="btn btn-primary">
                                Search
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
        
         
         <!-- /.box-header -->
         <div class="box-body table-responsive no-padding">
             @if(request()->user()->can("download_combined"))
             <div class="pull-right">

                 <div class="col-sm-12">
                     {{--<a href="#" class="download" id="xls">Download Excel xls</a> |--}}

                     {{--<a href="#" class="download" id="xlsx">Download Excel xlsx</a> |--}}

                     <a href="#" class="download" id="csv">Download CSV</a>
                 </div>

             </div>
             @endif
            <table class="table table-hover" width="100%" id="iocall_report">
               <thead>
                  <tr>
                    <th>Date</th>
                    <th>Caller ID</th>
                    <th>From</th>
                    <th>To</th>
					<th>Direction</th>
                    <th>Ring Time</th>
                    <th>Duration</th>
                    <th>Recording</th>
                    <th>Status</th>
                  </tr>
               </thead>

            </table>
            {{--<nav>--}}
                {{--<ul class="pagination pagination-sm no-margin pull-right">--}}
                    {{--{{ $ioReport->links('vendor.pagination.bootstrap-4')}}--}}
                {{--</ul>--}}
            {{--</nav>--}}
         </div>
         <!-- /.box-body -->
      </div>
      <!-- /.box -->
   </div>
</div>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(function () {
            $("#btnsubmit").click(function (e) {
                $("#type").val("");
               // $( "#iocallreportfrm").submit();
               // $(e).preventDefault()
            });
           $(".download").click(function () {
                $("#type").val($(this).attr('id'));
                $("#iocallreportfrm").submit();
            })
        });
        $(document).ready(function(){
            $('#iocall_report').DataTable({
                columns: [
                    { data: "calldate" },
                    { data: "CallerID" },
                    { data: "outbound_caller_id" },
                    { data: "destination" },
                    { data: "Direction" },
                    { data: "ringtime" },
                    { data: "billsec" },
                    {
                        data: "Recording",
                        render:function(data, type, row) {
                            if(data=="No Data" || data=="No Recording Found"){
                                return "No Recording"
                            }
                            else {
                                return "<a href=\"{{ asset("/") }}download.php?id=" + encodeURI(data) + "\">" +
                                    "<i class =\"fa fa-file-audio-o\"></i> Recording </a>";
                            }

                        }
                    },
                    { data: "disposition" }
                ],
                dom: 'lBfrtip',
                "iDisplayLength": 30,
                "lengthMenu": [ 10, 25,30, 50, 75, 100,200 ],
                buttons: [
                    'copy',  'print',
                    {extend: 'excel',
                        filename: 'PartDetails', footer:true},
                    {extend: 'pdf',
                        filename:  'PartDetails'},
                    {extend:'csvHtml5',
                        filename: 'PartDetails'},
                    {extend: 'collection',
                        text: 'columns',
                        buttons:['columnsVisibility'] }
                ],
                processing: true,
                serverSide: true,
                ajax:
                    {
                        url: ' {{ route('iocall_report') }}',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                        data:$("#iocallreportfrm").serializeArray(),
                        type: 'POST'
                    }
            })
        })
    </script>
@endpush
