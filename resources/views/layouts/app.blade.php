<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>User Portal</title>
    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
    <link rel="icon" href="{{ URL::asset('/') }}home/favicon.png">

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{{ URL::asset('/') }}adminLTE/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ URL::asset('/') }}adminLTE/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="{{ URL::asset('/') }}adminLTE/dist/css/table.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ URL::asset('/') }}adminLTE/dist/css/skins/_all-skins.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ URL::asset('/') }}adminLTE/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="{{ URL::asset('/') }}adminLTE/plugins/datatables/responsive.bootstrap.min.css">
    <!-- select2 -->
    <link rel="stylesheet" href="{{ URL::asset('/') }}adminLTE/plugins/select2/select2.min.css">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ URL::asset('/') }}adminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ URL::asset('/') }}adminLTE/plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="{{ URL::asset('/') }}jqplot/jquery.jqplot.min.css">


@stack('style')

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="{{ URL::asset('/') }}adminLTE/https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="{{ URL::asset('/') }}adminLTE/https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition sidebar-mini skin-red sidebar-collapse">
<div id="preloader"
     style=" display: none; position: fixed;width: 100%;height: 100%;z-index: 1000;text-align: center;padding-top: 20%;font-size: -webkit-xxx-large;"
     class="overlay">
    <i class="fa fa-refresh fa-spin"></i>
</div>
<div class="fixed">
    <header class="main-header">
        <!-- Logo -->
        <a href="{{URL::asset('/')}}cms" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
        <!--<span class="logo-mini"><img src="{{ URL::asset('/') }}box/logo_head.png" alt="ERizQ online Ecommerce solution with online Shopping Store" title="ERizQ online Ecommerce solution with online Shopping Store" /></span>-->
        <!--<span class="logo-lg"><img src="{{ URL::asset('/') }}box/logo.png" style="width:50%" alt="ERizQ online Ecommerce solution with online Shopping Store" title="ERizQ online Ecommerce solution with online Shopping Store" /></span>-->

            <span class="logo-mini"><b>U</b>P</span>
            <span class="logo-lg"><b>User</b>Portal</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{ URL::asset('/') }}adminLTE/dist/img/user2-160x160.jpg" class="user-image"
                                 alt="User Image">
                            <span class="hidden-xs">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="{{ URL::asset('/') }}adminLTE/dist/img/user2-160x160.jpg" class="img-circle"
                                     alt="User Image">

                                <p>
                                    {{ Auth::user()->name }}
                                    <small>Member since {{ Auth::user()->created_at->format('M Y') }}</small>
                                </p>
                            </li>
                            <!-- Menu Body -->
                            <!--<li class="user-body">
                              <div class="row">
                                <div class="col-xs-4 text-center">
                                  <a href="#">Followers</a>
                                </div>
                                <div class="col-xs-4 text-center">
                                  <a href="#">Sales</a>
                                </div>
                                <div class="col-xs-4 text-center">
                                  <a href="#">Friends</a>
                                </div>
                              </div>
                              <!-- /.row --
                            </li>-->
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="{{ url('cms/changepassword') }}" class="btn btn-default btn-flat">Change
                                        Password</a>
                                </div>
                                <div class="pull-right">
                                    <a href="{{ url('logout') }}" class="btn btn-default btn-flat"
                                       onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ url('logout') }}" method="POST"
                                          style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </div>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">

                @if(request()->user()->can('dashboard_view'))
                    <li>
                        <a href="{{URL::asset('/')}}cms">
                            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                        </a>
                    </li>
                @endif
                @if(request()->user()->can("sub_users"))
                    <li>
                        <a href="{{URL::asset('/')}}cms/users">
                            <i class="fa fa-users"></i> <span>Users</span>
                        </a>
                    </li>
                @endif
                @if(request()->user()->can('view_combined'))
                    <li>
                        <a href="#">
                            <i class="fa fa-bar-chart"></i> <span>Combined Reports</span>
                            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{URL::asset('/')}}cms/iouserreport"><i class="fa fa-circle-o"></i> By User
                                </a></li>
                            <li><a href="{{URL::asset('/')}}cms/iocallreport"><i class="fa fa-circle-o"></i> Complete
                                    detail </a></li>
                        </ul>
                    </li>
                @endif
                @if(request()->user()->can('view_outgoing'))

                    <li>
                        <a href="#">
                            <i class="fa fa-level-up"></i> <span>Outgoing Reports</span>
                            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{URL::asset('/')}}cms/ouserreport"><i class="fa fa-circle-o"></i> By User </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if(request()->user()->can('view_incoming'))
                    <li>
                        <a href="#">
                            <i class="fa fa-level-down"></i> <span>Incoming Reports</span>
                            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{URL::asset('/')}}cms/iuserreport"><i class="fa fa-circle-o"></i> By User </a>
                            </li>
                        </ul>
                    </li>
                @endif
                    @if(request()->user()->can("view_realtime"))
                        <li>
                            <a href="#">
                                <i class="fa fa-list-alt"></i> <span>Realtime Extension</span>
                                <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                            </a>
                            <ul class="treeview-menu">
                                @if(request()->user()->can("realtime_ext_simple"))

                                    <li>
                                        <a href="{{URL::asset('/')}}cms/realtime">
                                             <span>Real Time</span>
                                        </a>
                                    </li>
                                @endif

                                @if(request()->user()->can("realtime_ext_advance"))

                                    <li>
                                        <a href="{{route("realtime_ext.index")}}">
                                            <span>Real Time Details</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>


                    @endif



                @if(request()->user()->can('view_queue_status'))
                    <li>
                        <a href="{{URL::asset('/')}}cms/queuestats">
                            <i class="fa fa-circle-o-notch"></i> <span>Queue Stats</span>

                        </a>


                    </li>
                @endif
                @if(request()->user()->can('view_callback'))

                    <li>
                        <a href="{{ route("backcalls")  }} ">
                            <i class="fa fa-phone"></i> <span>Back Calls</span>

                        </a>


                    </li>
                @endif
                @if(request()->user()->can('view_distribution'))

                    <li>
                        <a href="{{URL::asset('/')}}cms/distribution">
                            <i class="fa fa-bar-chart"></i> <span>Distribution</span>
                            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{URL::asset('/')}}cms/distribution"><i class="fa fa-circle-o"></i>
                                    Distribution </a></li>
                            <li><a href="{{URL::asset('/')}}cms/distribution#distribution_summary"><i
                                            class="fa fa-circle-o"></i> Distribution Summary </a></li>
                            <li><a href="{{URL::asset('/')}}cms/distribution#distribution_by_queue"><i
                                            class="fa fa-circle-o"></i> Distribution by queue </a></li>
                            <li><a href="{{URL::asset('/')}}cms/distribution#distribution_per_month"><i
                                            class="fa fa-circle-o"></i> Distribution per month </a></li>
                            <li><a href="{{URL::asset('/')}}cms/distribution#distribution_per_week"><i
                                            class="fa fa-circle-o"></i> Distribution per week </a></li>
                            <li><a href="{{URL::asset('/')}}cms/distribution#distribution_per_day"><i
                                            class="fa fa-circle-o"></i> Distribution per day </a></li>
                            <li><a href="{{URL::asset('/')}}cms/distribution#distribution_per_hour"><i
                                            class="fa fa-circle-o"></i> Distribution per hour </a></li>
                            <li><a href="{{URL::asset('/')}}cms/distribution#distribution_per_dayofweek"><i
                                            class="fa fa-circle-o"></i> Distribution per day of week </a></li>
                        </ul>
                    </li>
                @endif

                {{--<li>--}}
                {{--<a href="{{ route("case_management.index") }}">--}}
                {{--<i class="fa fa-briefcase"></i> <span>Case Management</span>--}}

                {{--</a>--}}
                {{--</li>--}}

            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            @yield('content-header')
        </section>


    {{--@if ($message = Session::get('success'))--}}
    {{--<div class="alert alert-success">--}}
    {{--<p>{{ $message }}</p>--}}
    {{--</div>--}}
    {{--@endif--}}
    {{--@if($errors->any())--}}
    {{--<div class="alert alert-error">--}}
    {{--{{ $errors->first() }}--}}
    {{--</div>--}}
    {{--@endif--}}

    <!-- Main content -->
        <section class="content">
            @yield('content')
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <strong>Copyright &copy; 2018 <a href="http://45.63.91.236">User Portal</a>.</strong> All rights reserved.
    </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="https://code.jquery.com/jquery-2.2.3.js"></script>
<!-- jQuery ui 1.12.0 -->
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{ URL::asset('/') }}adminLTE/bootstrap/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="{{ URL::asset('/') }}adminLTE/dist/js/app.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<!-- <script src="{{ URL::asset('/') }}adminLTE/plugins/datatables/jquery.dataTables.min.js"></script>-->
<script src="{{ URL::asset('/') }}adminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="{{ URL::asset('/') }}adminLTE/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="{{ URL::asset('/') }}adminLTE/plugins/datatables/responsive.bootstrap.min.js"></script>
<!-- Select2 -->
<script src="{{ URL::asset('/') }}adminLTE/plugins/select2/select2.full.min.js"></script>
<!-- date-range-picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="{{ URL::asset('/') }}adminLTE/plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="{{ URL::asset('/') }}adminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- jsCharts -->
<script src="{{ URL::asset('/') }}adminLTE/plugins/chartjs/Chart.min.js"></script>

<!-- jqplot (Chart) -->
<script src="{{ URL::asset('/') }}jqplot/jquery.jqplot.min.js"></script>
<script src="{{ URL::asset('/') }}jqplot/plugins/jqplot.barRenderer.min.js"></script>
<script src="{{ URL::asset('/') }}/jqplot/plugins/jqplot.highlighter.min.js"></script>
<script src="{{ URL::asset('/') }}jqplot/plugins/jqplot.categoryAxisRenderer.min.js"></script>
<script src="{{ URL::asset('/') }}jqplot/plugins/jqplot.canvasTextRenderer.min.js"></script>
<script src="{{ URL::asset('/') }}jqplot/plugins/jqplot.canvasAxisTickRenderer.min.js"></script>
<script src="{{ URL::asset('/') }}jqplot/plugins/jqplot.pointLabels.min.js"></script>

<script type="text/javascript">
    $(".select2").select2({dropdownAutoWidth: true, width: '100%'});
    //Date range as a button
    $('#daterange-btn').daterangepicker(
        {
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'This Year': [moment().startOf('year'), moment()]
            },
            startDate: moment(),
            endDate: moment()
        },
        function (start, end) {
            $('#daterange-btn span').html(start.format('YYYY-M-D') + ' - ' + end.format('YYYY-M-D'))
            $('#dateFrom').val(start.format('YYYY-M-D'))
            $('#dateTo').val(end.format('YYYY-M-D'))
        }
    )

    function GetTodayDate() {
        var tdate = new Date();
        var dd = tdate.getDate(); //yields day
        var MM = tdate.getMonth(); //yields month
        var yyyy = tdate.getFullYear(); //yields year
        var currentDate = yyyy + "-" + (MM + 1) + "-" + dd;
        return currentDate;
    }

    function GetCurrentMonthLastDate() {
        var date = new Date();
        var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
        var dd = lastDay.getDate(); //yields day
        var MM = lastDay.getMonth(); //yields month
        var yyyy = lastDay.getFullYear(); //yields year
        var currentDate = yyyy + "-" + (MM + 1) + "-" + dd;
        return currentDate;
    }
</script>
@stack('scripts')
</body>
</html>
