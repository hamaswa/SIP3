@extends('layouts.app')
@section('content-header')
<h1>
	Combined call detail report
</h1>
<ol class="breadcrumb">
	<li><a href="{{URL::asset('/')}}cms"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="#"><i class="fa fa-book"></i> Reports</a></li>
	<li class="active">Combined call detail report</li>
</ol>
@endsection
@section('content')
<div class="row">
   <div class="col-xs-12">
      <div class="box">
         <div class="box-header">
            <h3 class="box-title">Combined call detail report (Per User)</h3>
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
                    <label for="exampleInputEmail1">User</label>
                    {!! Form::text('calling_from', null, ['class' => 'form-control']) !!}
                </div>
                <div class="col-sm-3 form-group">
                    <label for="exampleInputEmail1">&nbsp;</label>
                    <div class="input-group">
                        <!--<input class="form-control" id="search"
                               value="{{ request('search') }}"
                               placeholder="Search name" name="search"
                               type="text" id="search"/>-->
                        <div class="input-group-btn">
                            <button id="btnsubmit" class="btn btn-primary"
                            >
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

                 {{--<div class="col-sm-12">--}}
                     {{--<a href="#" class="download" id="xls">Download Excel xls</a> |--}}

                     {{--<a href="#" class="download" id="xlsx">Download Excel xlsx</a> |--}}

                     {{--<a href="#" class="download" id="csv">Download CSV</a>--}}
                 {{--</div>--}}

             </div>
             @endif
            <table class="table table-hover" id="ioUser">
                <thead>
                  <tr>
                    <th>User</th>
                    <th>Total</th>
                    <th>Incoming</th>
                    <th>Outgoing</th>
                    <th>Answered</th>
                    <th>Unanswered</th>
                    <th>Duration</th>
                    <th>Avg Duration</th>
                  </tr>
                </thead>
                <tbody>

                @foreach($ioReport as $key => $data)

                      @if((isset($data['inbound']['Total']) and $data['inbound']['Total'] !=0)
                          OR (isset($data['outbound']['Total']) and $data['outbound']['Total']!=0))

                     <tr>
                        <td>{{ $key }}</td>
                        <td><?php
                           if(isset($data['inbound']['Total']) and  isset($data['outbound']['Total']))
                                $total = ($data['inbound']['Total'] +  $data['outbound']['Total']);
                            else if(isset($data['inbound']['Total']))
                                $total= $data['inbound']['Total'];
                            else
                                $total = isset($data['outbound']['Total'])?$data['outbound']['Total']:1;
                            echo $total;
                           ?>
                        </td>
                         <td>{{ isset($data['inbound']['Total'])? $data['inbound']['Total']:"0"}}</td>
                         <td>{{ isset($data['outbound']['Total'])? $data['outbound']['Total']:"0"}}</td>
                        <td>
                            <?php
                            if(isset($data['inbound']['Completed']) and  isset($data['outbound']['Completed']))
                                echo ($data['inbound']['Completed'] +  $data['outbound']['Completed']);
                            else if(isset($data['inbound']['Completed']))
                                echo $data['inbound']['Completed'];
                            else
                               echo isset($data['outbound']['Completed'])?$data['outbound']['Completed']:"0";
                         ?>
                        </td>
                        <td>
                            <?php
                            if(isset($data['inbound']['Missed']) and  isset($data['outbound']['Missed']))
                                echo ($data['inbound']['Missed'] +  $data['outbound']['Missed']);
                            else if(isset($data['inbound']['Missed']))
                                echo $data['inbound']['Missed'];
                            else
                              echo  isset($data['outbound']['Missed'])?$data['outbound']['Missed']:"0";
                            ?>
                        </td>

                            <?php
                            if(isset($data['inbound']['Duration']) and  isset($data['outbound']['Duration']))
                                $duration =  ($data['inbound']['Duration'] +  $data['outbound']['Duration']);
                            else if(isset($data['inbound']['Duration']))
                                $duration =  $data['inbound']['Duration'];
                            else
                                $duration =  isset($data['outbound']['Duration'])?$data['outbound']['Duration']:1;
                            ?>
                         <td>
                            {{ gmdate("H:i:s", (int)$duration) }}
                        </td>
                        <td>{{ gmdate("H:i:s", (int)round((int)$duration/(int)$total)) }}</td>
                     </tr>
                     @endif
                 @endforeach
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
        $(function () {
            $("#btnsubmit").click(function (e) {
                $("#type").val("");
                $( "#iocallreportfrm").submit();

            });

            $(".download").click(function () {
                $("#type").val($(this).attr('id'));
                $( "#iocallreportfrm").submit();
            })
        });

        $(document).ready(function() {
            if ( $.fn.DataTable.isDataTable('#ioUser') ) {
                $('#ioUser').DataTable().destroy();
            }
            $('#ioUser').DataTable({
                "pageLength": 50
            });

        } );

    </script>
@endpush
