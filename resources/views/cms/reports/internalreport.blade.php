@extends('layouts.app')
@section('content-header')
    <h1>
        Outbound call detail report
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{URL::asset('/')}}cms"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-book"></i> Reports</a></li>
        <li class="active">Internal call detail report</li>
    </ol>
@endsection
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Internal call detail report (Per User)</h3>
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
                    <input type="hidden" id="type" name="type" value="">
                    <div class="row">
                        <div class="col-sm-3 form-group">
                            <label for="exampleInputEmail1">Date range</label>
                            <button type="button" class="btn btn-default form-control" id="daterange-btn">
                        <span class="pull-left">
                        	@if(Session::get('dateFrom')!=NULL)
                                <i class="fa fa-calendar"></i> {{ Session::get('dateFrom') }}
                                - {{ Session::get('dateTo') }}
                            @else
                                <i class="fa fa-calendar"></i> Date range picker
                            @endif
                        </span>
                                <i class="fa fa-caret-down pull-right"></i>
                            </button>
                            <input type="hidden" name="dateFrom" id="dateFrom" value="{{ Session::get('dateFrom') }}"/>
                            <input type="hidden" name="dateTo" id="dateTo" value="{{ Session::get('dateTo') }}"/>
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
                    @if(request()->user()->can("download_outgoing"))
                    <div class="pull-right">

                        <div class="col-sm-12">
                            <a href="#" class="download" id="xls">Download Excel xls</a> |

                            <a href="#" class="download" id="xlsx">Download Excel xlsx</a> |

                            <a href="#" class="download" id="csv">Download CSV</a>
                        </div>

                    </div>
                    @endif
                    <table class="table table-hover ouserreport" width="100%">
                        <thead>
                        <tr>
                            <th style="width:10%">User</th>
                            <th style="width:10%">Count</th>
                            <th style="width:10%">Answered</th>
                            <th style="width:10%">Unanswered</th>
                            <th style="width:10%">Duration</th>
                            {{--<th style="width:10%">Cost</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1; ?>
                        @foreach($oReport as $dataMain)
                            <?php $i++; ?>

                                    @if($dataMain->caller_id_number!="")
                                    <tr>
                                        <td style="width:10%">
                                            <a href="#!" data-id="{{ $i }}" data-src="{{$dataMain->caller_id_number}}" class="showHide"><i
                                                        class="fa fa-plus"></i>&nbsp;{{ $dataMain->caller_id_number }}
                                            </a></td>
                                        <td style="width:10%">{{ $dataMain->Total }}</td>
                                        <td style="width:10%">{{ ($dataMain->Total - $dataMain->Missed) }}</td>
                                        <td style="width:10%">{{ $dataMain->Missed }}</td>
                                        <td style="width:10%">{{ gmdate("H:i:s", (int)$dataMain->Duration) }}</td>
{{--                                        <td style="width:10%">${{ (int)$dataMain->Billing /60 * 0.06  }}</td>--}}
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
        $(document).on('click', '.showHide[data-id]', function (e) {

            var url = '{{ route("internalreport_subdata")}}';
            $that = $(this);

            if ($that.find('i').hasClass("fa-plus")) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        "userid": $(this).data("remote"),
                        "_token": "{{ csrf_token() }}",
                        "calling_from": $('input[name="calling_from"]').val(),
                        "dateFrom": $("#dateFrom").val(),
                        "dateTo": $("#dateTo").val(),
                        "timeFrom": $("#timeFrom").val(),
                        "timeTo": $("#timeTo").val(),
                        "agent": $(this).data("src"),
                        "submit": true
                    },
                    beforeSend: function () {
                        $('#preloader').css("display", "block");
                    },
                    success: function (res) {
                        $('#preloader').css("display", "none");
                        $that.find('i').removeClass("fa fa-plus").addClass("fa fa-minus");
                        console.log($that.closest("tr").html());
                        $that.closest("tr").after($(res))
                        if ( $.fn.DataTable.isDataTable('.subtable') ) {
                            $('.subtable').DataTable().destroy();
                        }
                        $('.subtable').DataTable({
                            "pageLength": 50
                        });

                    },
                    error: function (result, status, err) {
                        $('#preloader').css("display", "none");
                        console.log(result.responseText);
                        console.log(status.responseText);
                        console.log(err.Message);
                    }
                })

            }
            else {
                $that.closest("tr").next('tr.sub_tr').remove()
                $that.closest("tr").next('tr.sub_tr').remove()
                $that.find('i').removeClass("fa fa-minus").addClass("fa fa-plus");
            }


        });

        $(document).ready(function() {
            if ( $.fn.DataTable.isDataTable('.ouserreport') ) {
                $('.ouserreport').DataTable().destroy();
            }
            $('.ouserreport').DataTable({
                "pageLength": 50
            });

        } );

        $(function () {
            $("#btnsubmit").click(function (e) {
                $("#type").val("");

            });

            $(".download").click(function () {
                $("#type").val($(this).attr('id'));
                $("#iocallreportfrm").submit();
            })
        });
    </script>

@endpush


