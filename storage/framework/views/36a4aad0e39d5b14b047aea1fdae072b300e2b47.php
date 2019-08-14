<!DOCTYPE html>
<html><head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

  <title>User Portal</title>
  <!-- Scripts -->
	<script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
  <link rel="icon" href="<?php echo e(URL::asset('/')); ?>home/favicon.png">
  
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo e(URL::asset('/')); ?>adminLTE/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="<?php echo e(URL::asset('/')); ?>adminLTE/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo e(URL::asset('/')); ?>adminLTE/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo e(URL::asset('/')); ?>adminLTE/dist/css/skins/_all-skins.min.css">
  <!-- dropzone -->
  <link rel="stylesheet" href="<?php echo e(URL::asset('/')); ?>dropzone/dropzone.css">  
  <!-- select2 -->
  <link rel="stylesheet" href="<?php echo e(URL::asset('/')); ?>adminLTE/plugins/select2/select2.min.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="<?php echo e(URL::asset('/')); ?>adminLTE/plugins/colorpicker/bootstrap-colorpicker.min.css">

  <?php echo $__env->yieldPushContent('style'); ?>
  <style>
  	.pagination > .active > a, .pagination > .active > a:focus, .pagination > .active > a:hover, .pagination > .active > span, .pagination > .active > span:focus, .pagination > .active > span:hover
	{
		border-color:#dd4b39;
		background-color:#dd4b39; !important
	}
  </style>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="<?php echo e(URL::asset('/')); ?>adminLTE/https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="<?php echo e(URL::asset('/')); ?>adminLTE/https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition sidebar-mini skin-red">
<div class="wrapper">
  <header class="main-header">
    <!-- Logo -->
    <a href="/admin/home" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <!--<span class="logo-mini"><img src="<?php echo e(URL::asset('/')); ?>box/logo_head.png" alt="ERizQ online Ecommerce solution with online Shopping Store" title="ERizQ online Ecommerce solution with online Shopping Store" /></span>-->
      <!--<span class="logo-lg"><img src="<?php echo e(URL::asset('/')); ?>box/logo.png" style="width:50%" alt="ERizQ online Ecommerce solution with online Shopping Store" title="ERizQ online Ecommerce solution with online Shopping Store" /></span>-->
      
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
              <img src="<?php echo e(URL::asset('/')); ?>adminLTE/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo e(Auth::guard('admin')->user()->name); ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo e(URL::asset('/')); ?>adminLTE/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                <p>
                  <?php echo e(Auth::guard('admin')->user()->name); ?>

                  <small>Member since <?php echo e(Auth::guard('admin')->user()->created_at->format('M Y')); ?></small>
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
                  <a href="<?php echo e(url('admin/changepassword')); ?>" class="btn btn-default btn-flat">Change Password</a>
                </div>
                <div class="pull-right">
                    <a href="<?php echo e(url('admin/logout')); ?>" class="btn btn-default btn-flat"
                        onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                        Logout
                    </a>

                    <form id="logout-form" action="<?php echo e(url('admin/logout')); ?>" method="POST" style="display: none;">
                        <?php echo e(csrf_field()); ?>

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
        <li>
          <a href="<?php echo e(URL::asset('/')); ?>admin/home">
            <i class="fa fa-home"></i> <span>Home</span>
          </a>
        </li>
        <li>
          <a href="<?php echo e(URL::asset('/')); ?>/admin/nusers">
            <i class="fa fa-user"></i> <span>User</span>
          </a>
        </li>
        <!--<li>
          <a href="/admin/extensions">
            <i class="fa fa-exchange"></i> <span>Extension</span>
          </a>
        </li>-->
        
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <?php echo $__env->yieldContent('content-header'); ?>
    </section>
    
    
    <?php if($message = Session::get('success')): ?>
        <div class="alert alert-success">
            <p><?php echo e($message); ?></p>
        </div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="alert alert-error">
            <?php echo e($errors->first()); ?>

        </div>
    <?php endif; ?>

    <!-- Main content -->
    <section class="content">
    <?php echo $__env->yieldContent('content'); ?>
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
<script src = "https://code.jquery.com/jquery-2.2.3.js"></script>
<!-- jQuery ui 1.12.0 -->
<script src = "https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo e(URL::asset('/')); ?>adminLTE/bootstrap/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="<?php echo e(URL::asset('/')); ?>adminLTE/plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo e(URL::asset('/')); ?>adminLTE/dist/js/app.min.js"></script>
<!-- Sparkline -->
<script src="<?php echo e(URL::asset('/')); ?>adminLTE/plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="<?php echo e(URL::asset('/')); ?>adminLTE/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo e(URL::asset('/')); ?>adminLTE/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- SlimScroll 1.3.0 -->
<script src="<?php echo e(URL::asset('/')); ?>adminLTE/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- ChartJS 1.0.1 -->
<script src="<?php echo e(URL::asset('/')); ?>adminLTE/plugins/chartjs/Chart.min.js"></script>

<!-- Select2 -->
<script src="<?php echo e(URL::asset('/')); ?>adminLTE/plugins/select2/select2.full.min.js"></script>
<!-- Validator -->
<script src="<?php echo e(URL::asset('/')); ?>adminLTE/validator/validator.js"></script>
<!-- CK Editor -->
<script src="<?php echo e(URL::asset('/')); ?>adminLTE/plugins/ckeditor/ckeditor.js"></script>
<!-- bootstrap color picker -->
<script src="<?php echo e(URL::asset('/')); ?>adminLTE/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>


<script type="text/javascript">
  $(".select2").select2({dropdownAutoWidth : true,width: '100%'});
</script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
