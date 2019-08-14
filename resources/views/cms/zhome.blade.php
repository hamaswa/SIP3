@extends('layouts.app')

@section('content-header')
    <h1>
        Inbound Queue Daily Stats
    </h1>
    <ol class="breadcrumb">
	    <li><a href="/cms/"><i class="fa fa-home"></i> Dashboard</a></li>
    </ol>
@endsection


@section('content')
  <!--<div class="nav-tabs-custom">
    <div class="nav nav-tabs pull-right">
       <li class="pull-right header"><a href="javascript:void(0);" data-type="w" class="btn btn-default stats">Week</a></li> 
       <li class="pull-right header"><a href="javascript:void(0);" data-type="m" class="btn btn-default stats">Month</a></li> 
       <li class="pull-right header"><a href="javascript:void(0);" data-type="y" class="btn btn-default stats">Year</a></li> 
    </div>
  </div>-->
  <!-- Small boxes (Stat box) -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-aqua">
        <div class="inner">
          <p>Total Time</p>
          <h3 id="totalcalls">{{ gmdate("H:i:s", $dashboardReport['TotalTime']) }}</h3>
        </div>
        <div class="icon">
          <i class="ion ion-android-time"></i>
        </div>
      </div>
    </div>
    <?php
    if($dashboardReport['TotalTime']==0) $dashboardReport['TotalTime']=1;
	?>
    
    <?php if($dashboardReport['Received']==0) $Connect=1; else $Connect=$dashboardReport['Received'];?>

    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-red">
        <div class="inner">
          <p>TOTAL OFFERED</p>
          <h3  id="missed">{{ $dashboardReport['Received'] }}</h3>
        </div>
        <div class="icon">
          <i class="fa fa-check"></i>
        </div>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-purple">
        <div class="inner">
          <p>Answered</p>
          <h3  id="answer">{{ $dashboardReport['Answered'] }}</h3>
        </div>
        <div class="icon">
          <i class="fa fa-phone"></i>
        </div>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-green">
        <div class="inner">
          <p>Abandoned</p>
          <h3  id="billing">{{ $dashboardReport['Abandoned'] }}</h3>
        </div>
        <div class="icon">
          <i class="fa fa-times-circle"></i>
        </div>
      </div>
    </div>
 
    
  </div>
  
  <style>
  	.small-box>.inner {
		padding: 40px; !important
	}
	.small-box p {
		font-size: 20px; !important
	}
  </style>
@endsection


@push('scripts')

<script type="text/javascript">		
	$(document).on('click', '.stats[data-type]', function (e) { 
		var url = "{{ url('/cms/dstats/') }}/"+$(this).data('type');
		$.ajax({
			url: url,
			type: 'GET',
			dataType: 'json',
			data: {method: '_POST', "_token": "{{ csrf_token() }}", submit: true},
			success: function(data) {
				for (var i=0;i<data.length;++i)
				{
					$('#totalcalls').html(data[i].Total);
					$('#ob').html(data[i].Outbound);
					$('#ib').html(data[i].Inbound);
					$('#duration').html(data[i].Duration);
					$('#answer').html(data[i].Completed);
					$('#missed').html(data[i].Missed);
					$('#billing').html(data[i].Billing);
					$('#cost').html(data[i].Cost);
				}
			},
			error: function (result, status, err) {
				/*alert(result.responseText);
				alert(status.responseText);
				alert(err.Message);*/
			}
		});
	});
</script>

@endpush