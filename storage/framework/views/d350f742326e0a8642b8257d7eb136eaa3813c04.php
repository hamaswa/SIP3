<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 4 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Title -->
    <title>PBX User Portal</title>

    <!-- Favicon -->
    <link rel="icon" href="<?php echo e(URL::asset('/')); ?>home/img/core-img/favi.png">

    <!-- Core Stylesheet -->
    <link href="<?php echo e(URL::asset('/')); ?>home/style.css" rel="stylesheet">

    <!-- Responsive CSS -->
    <link href="<?php echo e(URL::asset('/')); ?>home/css/responsive.css" rel="stylesheet">
    
    <!-- Wizard CSS -->
    <link href="<?php echo e(URL::asset('/')); ?>home/css/wiz.css" rel="stylesheet">

</head>

<body>
    <!-- ***** Wellcome Area Start ***** -->
    <section class="pricing-plane-area section_padding_25 clearfix" id="pricing">
        <div class="container h-100">
        	<div class="row">
                <div class="col-12">
                	<a itemprop="url" href="<?php echo e(env('APP_URL')); ?>">
                    	<img src="<?php echo e(URL::asset('/')); ?>home/img/logo-final1.png" alt="" style="height: 70px; visibility: visible;">
                    </a>
                </div>
            </div>
            <div class="row h-75 align-items-center">
                <div class="col-12 col-md">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Wellcome Area End ***** -->
<!-- Jquery-2.2.4 JS -->
    <script src="<?php echo e(URL::asset('/')); ?>home/js/jquery-2.2.4.min.js"></script>
    <!-- Popper js -->
    <script src="<?php echo e(URL::asset('/')); ?>home/js/popper.min.js"></script>
    <!-- Bootstrap-4 Beta JS -->
    <script src="<?php echo e(URL::asset('/')); ?>home/js/bootstrap.min.js"></script>
    <!-- Wizard JS -->
    <script src="<?php echo e(URL::asset('/')); ?>home/js/wiz.js"></script>
    <script>
		$(document).on('click', '#pModel[data-type]', function (e) { 
			$('#planName').html($(this).data("type"))
			$('#planNameI').html($(this).data("type"))
			$('#amount').val($(this).data("price"))
			$('#planid').val($(this).data("pid"))
			$('#amountShow').html("Amount: $"+$(this).data("price"))
			//alert($(this).data("pid"))
			$('#myModal').modal('show')
		});
		
		$("#newTab").on('click', function (e) { 
			$('#reqType').val(1);
			$(".paymentInfo").find('input[type="text"], input[type="password"], textarea').each(function() {
				$(this).prop('required', true);
			});
			$("#didNumber2").prop('required', false);
			$("#noExtension2").prop('required', false);
		});
		
		$("#existTab").on('click', function (e) { 
			$('#reqType').val(2);
			$(".paymentInfo").find('input[type="text"], input[type="password"], textarea').each(function() {
				$(this).prop('required', false);
			});
			$("#didNumber2").prop('required', true);
			$("#noExtension2").prop('required', true);
		});
		
	</script>
</body>

</html>
