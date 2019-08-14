@extends('layouts.app')
@section('content-header')
<h1>
	Dashboard
</h1>
<ol class="breadcrumb">
	<li><a href="/cms"><i class="fa fa-dashboard"></i> Home</a></li>
	<li class="active">Users</li>
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
				@include('cms.users.table')
			</div>
        </div>
        <div class="text-center">
        
        </div>
    </div>
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

@push('scripts')
    <script>
        $(document).on('click', '#addExtension[data-remote]', function (e) {
            $("#currentID").val($(this).data("remote"));
            //alert($("#currentID").val())
            var url = '{{url("/")}}' + '/admin/getextension';
            $.ajax({
                url: url,
                type: 'POST',
                data: {"user_id": $(this).data("remote"), "_token": "{{ csrf_token() }}", submit: true},
                success: function (res) {
                    $("#userExt").html(res);
                },
                error: function (result, status, err) {
                    alert(result.responseText);

                }
            })
        });

        $(document).on('click', '#deleteExtension[data-remote]', function (e) {
            if (confirm("Are you sure to delete this extension?")) {

                var url = '{{url("/")}}' + '/admin/deleteextension';
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {"extension_no": $(this).data("remote"), "_token": "{{ csrf_token() }}", submit: true},
                    success: function (res) {
                        //alert(res)
                        var url = '{{url("/")}}' + '/admin/getextension';
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {"user_id": $("#currentID").val(), "_token": "{{ csrf_token() }}", submit: true},
                            success: function (res) {
                                //alert(res)
                                $("#userExt").html(res);
                            },
                            error: function (result, status, err) {
                                alert(result.responseText);
                            }
                        })
                    },
                    error: function (result, status, err) {
                        alert(result.responseText);
                    }
                })
            }
            return false;
        });
    </script>
@endpush

