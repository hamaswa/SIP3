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
            <h3 class="box-title">Queues report</h3>
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
            <div class="col-sm-3 form-group">
                <label for="exampleInputEmail1">Date range</label>
                <button type="button" class="btn btn-default form-control" id="daterange-btn">
                    <span class="pull-left">

                    </span>
                    <i class="fa fa-caret-down pull-right"></i>
                </button>
                <input type="hidden" name="dateFrom" id="dateFrom" value="{{ Session::get('dateFrom') }}" />
                <input type="hidden" name="dateTo" id ="dateTo" value="{{ Session::get('dateTo') }}" />
            </div>
            <div class="col-sm-6 form-group">
                <div class="col-sm-12"> <label class="col-lg-12">Time Range</label></div>
                <div class="col-sm-6">

                    <div class="col-sm-6">
                        <input type="number" name="hourFrom" id="hourFrom" max="23" value="<?php echo $hourFrom ?>" class="form-control col-sm-3">
                    </div>
                    <div class="col-sm-6">
                        <input type="number" max="59" name="minFrom" id="minFrom" value="<?php echo $minFrom ?>" class="form-control col-sm-3">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="col-sm-6">
                        <input type="number" max="23" name="hourTo" id="hourTo" value="<?php echo $hourTo ?>" class="form-control col-sm-3">
                    </div>
                    <div class="col-sm-6">
                        <input type="number" max="59" name="minTo" id="minTo" value="<?php echo $minTo ?>" class="form-control col-sm-3">
                    </div>
                </div>
            </div>
        </div>
         <!-- /.box-header -->
         <div class="box-body table-responsive no-padding">
            <table class="table table-hover" width="100%">
               <thead>
                  <tr>
                    <th style="width:10%">Queue</th>
                    <th style="width:10%">Total Calls</th>
                    <th style="width:10%">Answer</th>
                    <th style="width:10%">Abandon</th>
                  </tr>
               </thead>
               <tbody id="realBody">
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
    $("#hourFrom, #hourTo, #minFrom, #minTo").change(function (e) {
        val = parseInt($(this).val());
        max = parseInt($(this).attr("max"));
        if(val > max ){
            $(this).val($(this).attr("max"));
        } else if(val < 0){
            $(this).val("00");
        }

    })

	setInterval("getRealTime()",1000);
	function getRealTime()
	{
		var url = "{{ url('/cms/queuestats/stats') }}"
		$.ajax({
			url: url,
			type: 'GET',
			dataType: 'json',
			data: {method: '_GET', "dateFrom": $("#dateFrom").val(), "dateTo": $("#dateTo").val(),"hourFrom": $("#hourFrom").val(), "hourTo": $("#hourTo").val(),"minFrom": $("#minFrom").val(), "minTo": $("#minTo").val(), "_token": "{{ csrf_token() }}" , submit: true},
			success: function (response) {
				$("#realBody").html("");
				$.each(response,function(key,value){
					$("#realBody").append('<tr><td><a href="queuereport?queue='+value.queue+'"> '+value.queue+'</a></td><td>'+value.Received+'</td><td>'+value.Answered+'</td><td>'+value.Abandoned+'</td><tr>');
				});
			},
			error: function (result, status, err) {
				///alert(result.responseText);
				///alert(status.responseText);
				///alert(err.Message);
			},
		});
	}
	$(function () {
        $('#daterange-btn span').html(GetTodayDate()+" - "+GetTodayDate())

    })
	

</script>
@endpush


