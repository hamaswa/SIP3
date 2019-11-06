@extends('admin.layouts.app')
@section('content-header')
    <h1>
        Dashboard
    </h1>
    <ol class="breadcrumb">
        <li><a href="/admin"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="/admin/nusers"><i class="fa fa-user"></i> Users</a></li>
        <li class="active">Permissions</li>
    </ol>
@endsection
@section('content')
    <section class="content-header">
        <h1 class="pull-left">User Permissions</h1>
        <h1 class="pull-right">
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box">
            <div class="box-body">
                <form action="{{ route("permissions.store")  }}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="user_id"  value="{{ $user_id }}">
                    <table class="table table-responsive table-bordered table-dark">
                        <tr>
                            <th class="col-lg-4">Users</th>
                            <td>
                                <div class="col-lg-3">
                                    <input type="checkbox" name="sub_users" {{ array_key_exists("sub_users",$user_permissions)?"checked=checked":"" }}
                                    value="{{ $permissions['sub_users']['id'] }}" class="parent"
                                           data-target="user_add,user_edit,user_delete,user_permission">
                                    <label for="sub_users">Manage Users</label>
                                </div>
                                <div class="col-lg-3">
                                    <input type="checkbox" name="user_add" {{ array_key_exists("user_add",$user_permissions)?"checked=checked":"" }}
                                    value="{{ $permissions['user_add']['id'] }}">
                                    <label for="user_add">Add</label>
                                </div>
                                <div class="col-lg-3">
                                    <input type="checkbox" name="user_edit" {{ array_key_exists("user_edit",$user_permissions)?"checked=checked":"" }}  value="{{ $permissions['user_edit']['id'] }}">
                                    <label for="user_edit">Edit</label>

                                </div>
                                <div class="col-lg-3">
                                    <input type="checkbox" name="user_delete" {{ array_key_exists("user_delete",$user_permissions)?"checked=checked":"" }}  value="{{ $permissions['user_delete']['id'] }}">
                                    <label for="user_delete">Delete</label>
                                </div>
                                <div class="col-lg-3">
                                    <input type="checkbox" name="user_permission" {{ array_key_exists("user_permission",$user_permissions)?"checked=checked":"" }}  value="{{ $permissions['user_permission']['id'] }}">
                                    <label for="user_permission">Permissions</label>
                                </div>
                            </td>

                        </tr>
                        <tr>
                            <th colspan="2">Reporting Permissions</th>
                        </tr>
                        <tr>
                            <th class="col-lg-4">Dashboard</th>
                            <td>
                                <div class="col-lg-12 form-group">

                                    <input type="checkbox" class="form-check" name="dashboard_view" {{ array_key_exists("dashboard_view",$user_permissions)?"checked=checked":"" }}  value="{{ $permissions['dashboard_view']['id'] }}">
                                    <label for="dashboard_view">
                                        Dashboard View
                                    </label>

                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="col-lg-4">Combined Report</th>
                            <td>
                                <div class="col-lg-6">

                                    <input type="checkbox" name="view_combined"
                                           {{ array_key_exists("view_combined",$user_permissions)?"checked=checked":"" }}
                                    value="{{ $permissions['view_combined']['id'] }}"  class="parent"
                                           data-target="download_combined">
                                    <label for="view_combined">View Report</label>
                                </div>
                                <div class="col-lg-6">
                                    <input type="checkbox" name="outbound_idd"
                                           {{ array_key_exists("outbound_idd",$user_permissions)?"checked=checked":"" }}
                                    value="{{ $permissions['outbound_idd']['id'] }}">
                                    <label for="outbound_idd">Outbound IDD</label>
                                </div>
                                <div class="col-lg-6">
                                    <input type="checkbox" name="download_combined" {{ array_key_exists("download_combined",$user_permissions)?"checked=checked":"" }}  value="{{ $permissions['download_combined']['id'] }}">
                                    <label for="download_combined">Download Combined Report</label>
                                </div>


                            </td>

                        </tr>
                        <tr>
                            <th class="col-lg-4">Distribution</th>
                            <td>

                                <div class="col-lg-12">
                                    <input type="checkbox" name="view_distribution" {{ array_key_exists("view_distribution",$user_permissions)?"checked=checked":"" }}
                                    value="{{ $permissions['view_distribution']['id'] }}" class="parent"
                                           data-target="download_distribution">
                                    <label for="view_distribution">View Distribution</label>
                                </div>
                                <div class="col-lg-12">
                                    <input type="checkbox" name="download_distribution" {{ array_key_exists("download_distribution",$user_permissions)?"checked=checked":"" }}  value="{{ $permissions['download_distribution']['id'] }}">
                                    <label for="view_distribution">Download Distribution</label>
                                </div>
                                {{--<div class="col-lg-12">--}}
                                    {{--<input type="checkbox" name="download_queue_recording"--}}
                                           {{--{{ array_key_exists("download_queue_recording",$user_permissions)?"checked=checked":"" }}--}}
                                           {{--value="{{ $permissions['download_queue_recording']['id'] }}">--}}
                                    {{--<label for="download_queue_recording">Download Recording from Queue</label>--}}
                                {{--</div>--}}

                            </td>

                        </tr>

                        <tr>
                            <th class="col-lg-4">Outgoing Report</th>
                            <td>

                                <div class="col-lg-6">
                                    <input type="checkbox" name="view_outgoing" {{ array_key_exists("view_outgoing",$user_permissions)?"checked=checked":"" }}
                                    value="{{ $permissions['view_outgoing']['id'] }}" class="parent"
                                           data-target="download_outgoing">
                                    <label for="view_outgoing">View Outgoing</label>
                                </div>
                                <div class="col-lg-6">
                                    <input type="checkbox" name="download_outgoing" {{ array_key_exists("download_outgoing",$user_permissions)?"checked=checked":"" }}  value="{{ $permissions['download_outgoing']['id'] }}">
                                    <label for="download_outgoing">Download Outgoing</label>
                                </div>
                                <div class="col-lg-6">
                                    <input type="checkbox" name="view_user_name"
                                           {{ array_key_exists("view_user_name",$user_permissions)?"checked=checked":"" }}
                                           value="{{ $permissions['view_user_name']['id'] }}">
                                    <label for="view_user_name">User Name Column</label>
                                </div>
                            </td>

                        </tr>

                        <tr>
                            <th class="col-lg-4">Incomming Report</th>
                            <td>

                                <div class="col-lg-6">
                                    <input type="checkbox" name="view_incoming" {{ array_key_exists("view_incoming",$user_permissions)?"checked=checked":"" }}
                                    value="{{ $permissions['view_incoming']['id'] }}" class="parent"
                                           data-target="download_incoming">
                                    <label for="view_incoming">View Incoming</label>
                                </div>
                                <div class="col-lg-6">
                                    <input type="checkbox" name="download_incoming" {{ array_key_exists("download_incoming",$user_permissions)?"checked=checked":"" }}  value="{{ $permissions['download_incoming']['id'] }}">
                                    <label for="download_incoming">Download Incoming</label>
                                </div>
                            </td>

                        </tr>

                        <tr>
                            <th class="col-lg-4">Real Time</th>
                            <td>

                                <div class="col-lg-12">
                                    <input type="checkbox" name="view_realtime" {{ array_key_exists("view_realtime",$user_permissions)?"checked=checked":"" }}
                                    value="{{ $permissions['view_realtime']['id'] }}"
                                           data-target="realtime_ext_simple,realtime_ext_advance" class="parent">
                                    <label for="view_realtime">Enable Realtime extensions</label>
                                </div>

                                <div class="col-lg-6">
                                    <input type="checkbox" name="realtime_ext_simple"
                                           {{ array_key_exists("realtime_ext_simple",$user_permissions)?"checked=checked":"" }}
                                    value="{{ $permissions['realtime_ext_simple']['id'] }}">
                                    <label for="realtime_ext_simple">Realtime extensions Status</label>
                                </div>

                                <div class="col-lg-6">
                                    <input type="checkbox" name="realtime_ext_advance"
                                           {{ array_key_exists("realtime_ext_advance",$user_permissions)?"checked=checked":"" }}
                                    value="{{ $permissions['realtime_ext_advance']['id'] }}">
                                    <label for="realtime_ext_advance">Enable Realtime extension details</label>
                                </div>


                            </td>

                        </tr>
                        <tr>
                            <th class="col-lg-4">Queue Stats</th>
                            <td>

                                <div class="col-lg-12">
                                    <input type="checkbox" name="view_queue_status" {{ array_key_exists("view_queue_status",$user_permissions)?"checked=checked":"" }}
                                    value="{{ $permissions['view_queue_status']['id'] }}">
                                    <label for="view_queue_status">View Queue Status</label>
                                </div>

                            </td>

                        </tr>
                        <tr>
                            <th class="col-lg-4">Call Back</th>
                            <td>

                                <div class="col-lg-6">

                                    <input type="checkbox" name="view_callback" {{ array_key_exists("view_callback",$user_permissions)?"checked=checked":"" }}
                                    value="{{ $permissions['view_callback']['id'] }}">

                                    <label for="view_callback">View Callback</label>
                                </div>


                            </td>

                        </tr>
                        <tr>
                            <td colspan="2"><input type="submit"  value="Update Permissions"></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>


    </div>
@endsection

@push('scripts')
    <script>
        $(document.body).on("click","input.parent",function (e) {
            data = $(this).data("target").split(",");
            if($(this).prop("checked") == true) {
                data.forEach(function (item) {
                    $("input[name=\"" + item + "\"").prop("disabled", false)
                    $("input[name=\"" + item + "\"").prop("checked", true)
                })
            }
            else {
                data.forEach(function (item) {
                    $("input[name=\"" + item + "\"").prop("disabled", true)
                    $("input[name=\"" + item + "\"").prop("checked", false)
                })
            }
        })

    </script>
@endpush

