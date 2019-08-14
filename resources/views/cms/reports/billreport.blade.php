@extends('layouts.app')
@section('content-header')
<h1>
	Billing report
</h1>
<ol class="breadcrumb">
	<li><a href="{{URL::asset('/')}}cms><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="#"><i class="fa fa-book"></i> Reports</a></li>
	<li class="active">Billing</li>
</ol>
@endsection
@section('content')
<div class="row">
	<div class="col-xs-6">
         <div class="box" style="height:320px;">
           <div class="box-header with-border">
              <h3 class="box-title">Current Plan</h3>
           </div>
           <!-- /.box-header -->
           <div class="box-body">
              <h2 class="text-purple"><strong>{{ $user->Plan()->First()->name }}</strong></h2>
              <hr>
              <strong>Payment: </strong>
              <span class="text-muted pull-right">${{ $user->Plan()->First()->price }}/Month</span>
              <hr>
              <strong>Next Payment: </strong>
              <span class="text-muted pull-right">{{ $user->expire_on }}</span>
              <hr>
              <strong>No of extensions: </strong>
              <span class="text-muted pull-right">{{ $user->Plan()->First()->no_of_extensions }} extensions</span>
           </div>
           <!-- /.box-body -->
        </div>
    </div>
    <!-- /.col-xs-4 -->
    
    <div class="col-xs-6">
         <div class="box" style="height:320px;">
           <div class="box-header with-border">
              <h3 class="box-title">Payment & Account detail</h3>
           </div>
           <!-- /.box-header -->
           <div class="box-body">
              <strong>Credit card: </strong>
              <span class="text-muted text-green pull-right">**** **** **** {{ substr($user->Payment()->First()->cardno,-4) }}</span>
              <hr>
              <strong>Account owner: </strong>
              <span class="text-muted pull-right">{{ $user->email }}</span>
              <hr>
              <strong>Send receipts to: </strong>
              <span class="text-muted pull-right">{{ $user->email }}</span>
              <hr>
           </div>
           <!-- /.box-body -->
        </div>
    </div>
    <!-- /.col-xs-4 -->
    
    <div class="col-xs-6">
         <div class="box" style="height:320px;">
           <div class="box-header with-border">
              <h3 class="box-title">Payment History</h3>
           </div>
           <!-- /.box-header -->
           <div class="box-body">
			<table class="table"> 
            	<thead>
                	<tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                  	</tr>
                </thead> 
                <tbody>              
                @foreach($user->Payment_history()->Get() as $data)
                	<tr>
                    	<td>{{ date('M d, Y', strtotime($data->created_at)) }}</td>
                        <td>{{ $data->amount }}</td>
                        <td>{{ ($data->status==1?'Done':'Pending') }}</td>
                    </tr>
                @endforeach
                </tbody>
           	</table>
           </div>
           <!-- /.box-body -->
        </div>
    </div>
    <!-- /.col-xs-4 -->
</div>
@endsection

