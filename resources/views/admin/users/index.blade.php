@extends('admin.layouts.app')
@section('content-header')
<h1>
	Dashboard
</h1>
<ol class="breadcrumb">
	<li><a href="/admin"><i class="fa fa-dashboard"></i> Dashboard</a></li>
	<li class="active">User</li>
</ol>
@endsection
@section('content')
    <section class="content-header">
        <h1 class="pull-left">Users</h1>
        <h1 class="pull-right">
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box">
            <div class="box-body">
				@include('admin.users.table')
			</div>
        </div>
        <div class="text-center">
        
        </div>
    </div>
	{{--@include("admin.users.extension")--}}
@endsection

@push('scripts')
<script>
	$(document).on('click', '#addQueue[data-remote]', function (e) {
		$("#currentID").val($(this).data("remote"));
		var url = '{{ route("getqueue")}}';
		$.ajax({
			url: url,
			type: 'POST',
			data: {"user_id":$(this).data("remote"), "_token": "{{ csrf_token() }}" , submit: true},
			success: function(res)
			{
				$("#user_queue").html(res);
			},
			error: function (result, status, err) {
				alert(result.responseText);
			}
		})
	});

	$(document).on('click', '#save_queue', function (e) {
		if($("#queue").val()=="" || !$.isNumeric($("#queue").val()))
		{
			$("#queue").focus();
			alert("Invalid Queue")
			return false;
		}
		//alert($("#currentID").val())
        var url = '{{ route("addqueue")}}';
		$.ajax({
			url: url,
			type: 'POST',
			data: $("#queue_form").serializeArray(),
			success: function(res)
			{
				$("#ext").val("");
				//alert(res)
                var url = '{{ route("getqueue")}}';
				$.ajax({
					url: url,
					type: 'POST',
					data: {"user_id":$("#currentID").val(), "_token": "{{ csrf_token() }}" , submit: true},
					success: function(res)
					{
						//alert(res)
						$("#user_queue").html(res);
					},
					error: function (result, status, err) {
						alert(result.responseText);
					}
				})
			},
			error: function (result, status, err) {
				alert(status.responseText);
			}
		})
	});
	
	
	$(document).on('click', '.deleteQueue[data-remote]', function (e) {
		if (confirm("Are you sure to delete this Queue?"))
		{
			//alert($("#currentID").val())
            var url = '{{ route("deletequeue")}}';
			$.ajax({
				url: url,
				type: 'POST',
				data: {"id":$(this).data("remote"), "_token": "{{ csrf_token() }}" , submit: true},
				success: function(res)
				{
					//alert(res)
                    var url = '{{ route("getqueue")}}';
					$.ajax({
						url: url,
						type: 'POST',
						data: {"user_id":$("#currentID").val(), "_token": "{{ csrf_token() }}" , submit: true},
						success: function(res)
						{
							//alert(res)
							$("#user_queue").html(res);
						},
						error: function (result, status, err) {
							alert(status.responseText);
						}
					})
				},
				error: function (result, status, err) {
					alert(status.responseText);
				}
			})
		}
		return false;
	});
</script>
@endpush

