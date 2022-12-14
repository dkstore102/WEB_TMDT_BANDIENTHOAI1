<?php
	session_start();
	include_once '../sendmail/index.php';
	$send = new sendmail();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="../viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="">
        <title>VNPAY RESPONSE</title>
        <!-- Bootstrap core CSS -->
        <link href="/vnpay_php/assets/bootstrap.min.css" rel="stylesheet"/>
        <!-- Custom styles for this template -->
        <link href="/vnpay_php/assets/jumbotron-narrow.css" rel="stylesheet">     
        <!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="../images/icons/favicon.png"/>
    <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="../fonts/iconic/css/material-design-iconic-font.min.css">
    <!--================================================================================on-font.===============-->
        <link rel="stylesheet" type="text/css" href="../fonts/linearicons-v1.0.0/icmin.css">
    <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="../vendor/animate/animate.css">
    <!--===============================================================================================-->	
        <link rel="stylesheet" type="text/css" href="../vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="../vendor/animsition/css/animsition.min.css">
    <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="../vendor/select2/select2.min.css">
    <!--===============================================================================================-->	
        <link rel="stylesheet" type="text/css" href="../vendor/daterangepicker/daterangepicker.css">
    <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="../vendor/slick/slick.css">
    <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="../vendor/MagnificPopup/magnific-popup.css">
    <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="../vendor/perfect-scrollbar/perfect-scrollbar.css">
    <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="../css/util.css">
        <link rel="stylesheet" type="text/css" href="../css/main.css">
        <link rel="stylesheet" type="text/css" href="../css/style.css">
        <link rel="stylesheet" media="screen" href="../login/demo/css/style.css">
    <!--===============================================================================================-->
        <script src="/vnpay_php/assets/jquery-1.11.3.min.js"></script>
    </head>
        <body class="animsition">
            <?php
            require_once("./config.php");
            $vnp_SecureHash = $_GET['vnp_SecureHash'];
            $inputData = array();
            foreach ($_GET as $key => $value) {
                if (substr($key, 0, 4) == "vnp_") {
                    $inputData[$key] = $value;
                }
            }
            
            unset($inputData['vnp_SecureHash']);
            ksort($inputData);
            $i = 0;
            $hashData = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
            }

            $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
            ?>
            <header>
            <!-- Header desktop -->
            <div class="container-menu-desktop">
                <div class="wrap-menu-desktop">
                    <nav class="limiter-menu-desktop container">
                        
                        <!-- Logo desktop -->		
                        <a href="index.php" class="logo">
                            <img src="../images/logo_new.png" alt="IMG-LOGO">
                        </a>
                    </nav>
                </div>	
            </div>

            <!-- Header Mobile -->
            <div class="wrap-header-mobile">
                <!-- Logo moblie -->		
                <div class="logo-mobile">
                    <a href="index.php"><img src="../images/logo_new.png" alt="IMG-LOGO"></a>
                </div>
            </div>
        </header>
        <section class="bg0 p-t-150 p-b-140">
            <div class="container">
                <div class="row ">
                    <div class="col-3"></div>
                    <table class="col-6 bg-bg table bor14">
                    <thead>
                    <tr>
                        <th colspan="2" class="text-center "><b style="font-size:22px;">H??a ????n mua h??ng</b></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>M?? ????n h??ng:</td>
                        <td><?php echo $_GET['vnp_TxnRef'] ?></td>
                    </tr>
                    <tr>
                        <td>S??? ti???n:</td>
                        <td><?php echo $_GET['vnp_Amount'] ?></td>
                    </tr>
                    <tr>
                        <td>N???i dung thanh to??n:</td>
                        <td><?php echo $_GET['vnp_OrderInfo'] ?></td>
                    </tr>
                    <tr>
                        <td>M?? ph???n h???i (vnp_ResponseCode):</td>
                        <td><?php echo $_GET['vnp_ResponseCode'] ?></td>
                    </tr>
                    <tr>
                        <td>M?? GD T???i VNPAY:</td>
                        <td><?php echo $_GET['vnp_TransactionNo'] ?></td>
                    </tr>
                    <tr>
                        <td>M?? Ng??n h??ng:</td>
                        <td><?php echo $_GET['vnp_BankCode'] ?></td>
                    </tr>
                    <tr>
                        <td>Th???i gian thanh to??n:</td>
                        <td><?php echo $_GET['vnp_PayDate'] ?></td>
                    </tr>
                    <tr>
                        <td>K???t qu???:</td>
                        <td><?php
                            if ($secureHash == $vnp_SecureHash) {
                                if ($_GET['vnp_ResponseCode'] == '00') {
                                    //c???p nh???t tr???ng thai
                                    $iddh = substr($_REQUEST['vnp_TxnRef'], -4);
                                    $con = mysqli_connect("localhost","dkstoreq_dkstore","Dai061220","dkstoreq_dkphone");
                                    $con->set_charset("utf8");
                                    mysqli_query($con,"update donhang set TrangThai = 3 where ID_DH = $iddh");
                                    $send->sendorder("phandai032@gmail.com");
                                    echo "<span style='color:blue'>GD Thanh cong</span>";
                                } else {
                                    echo "<span style='color:red'>GD Khong thanh cong</span>";
                                }
                            } else {
                                echo "<span style='color:red'>Chu ky khong hop le</span>";
                            }
                            ?></td>
                    </tr>
                    </tbody>
                </table>
                    <div class="col-3"></div>
                </div>
            </div>
        </section>
        <footer class="bg3 p-t-75 p-b-32">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-lg-3 p-b-50">
                        <h4 class="stext-301 cl0 p-b-30">
                            Support
                        </h4>

                        <ul>
                            <li class="p-b-10">
                                <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
                                    t?? v???n mua h??ng
                                </a>
                            </li>

                            <li class="p-b-10">
                                <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
                                    ????ng g??p ?? ki???n - khi???u n???i
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-sm-6 col-lg-3 p-b-50">
                        <h4 class="stext-301 cl0 p-b-30">
                            Ch??nh s??ch
                        </h4>

                        <ul>
                            <li class="p-b-10">
                                <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
                                    ch??nh s??ch ?????i - tr???
                                </a>
                            </li>

                            <li class="p-b-10">
                                <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
                                    Ch??nh s??ch b???o m???t
                                </a>
                            </li>

                            <li class="p-b-10">
                                <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
                                    Ch??nh s??ch b???o h??nh
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-sm-6 col-lg-3 p-b-50">
                        <h4 class="stext-301 cl0 p-b-30">
                            T???ng ????i h??? tr???
                        </h4>

                        <ul>
                            <li class="p-b-10">
                                <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
                                    1800 77 1243
                                </a>
                            </li>

                            <li class="p-b-10">
                                <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
                                    0967 122 784
                                </a>
                            </li>

                            <li class="p-b-10">
                                <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
                                    0123 456 789
                                </a>
                            </li>
                        </ul>

                        
                    </div>

                    <div class="col-sm-6 col-lg-3 p-b-50">
                        <h4 class="stext-301 cl0 p-b-30">
                            li??n k???t
                        </h4>
                        <div class="p-t-27">
                            <a href="#" class="fs-18 cl7 hov-cl1 trans-04 m-r-16">
                                <i class="fa fa-facebook"></i>
                            </a>

                            <a href="#" class="fs-18 cl7 hov-cl1 trans-04 m-r-16">
                                <i class="fa fa-instagram"></i>
                            </a>

                            <a href="#" class="fs-18 cl7 hov-cl1 trans-04 m-r-16">
                                <i class="fa fa-pinterest-p"></i>
                            </a>
                        </div>
                    </div>
                </div>

                
            </div>
        </footer>


            <!-- Back to top -->
            <div class="btn-back-to-top" id="myBtn">
                <span class="symbol-btn-back-to-top">
                    <i class="zmdi zmdi-chevron-up"></i>
                </span>
            </div>
    </body>
</html>
<!--===============================================================================================-->	
<script src="../vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="../vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="../vendor/bootstrap/js/popper.js"></script>
	<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="../vendor/select2/select2.min.js"></script>
	<script>
		$(".js-select2").each(function(){
			$(this).select2({
				minimumResultsForSearch: 20,
				dropdownParent: $(this).next('.dropDownSelect2')
			});
		})
	</script>
<!--===============================================================================================-->
	<script src="../vendor/daterangepicker/moment.min.js"></script>
	<script src="../vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="../vendor/slick/slick.min.js"></script>
	<script src="../js/slick-custom.js"></script>
<!--===============================================================================================-->
	<script src="../vendor/parallax100/parallax100.js"></script>
	<script>
        $('.parallax100').parallax100();
	</script>
<!--===============================================================================================-->
	<script src="../vendor/MagnificPopup/jquery.magnific-popup.min.js"></script>
	<script>
		$('.gallery-lb').each(function() { // the containers for all your galleries
			$(this).magnificPopup({
		        delegate: 'a', // the selector for gallery item
		        type: 'image',
		        gallery: {
		        	enabled:true
		        },
		        mainClass: 'mfp-fade'
		    });
		});
	</script>
<!--===============================================================================================-->
	<script src="../vendor/isotope/isotope.pkgd.min.js"></script>
<!--===============================================================================================-->
	<script src="../vendor/sweetalert/sweetalert.min.js"></script>
	
<!--===============================================================================================-->
	<script src="../vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
	<script>
		$('.js-pscroll').each(function(){
			$(this).css('position','relative');
			$(this).css('overflow','hidden');
			var ps = new PerfectScrollbar(this, {
				wheelSpeed: 1,
				scrollingThreshold: 1000,
				wheelPropagation: false,
			});

			$(window).on('resize', function(){
				ps.update();
			})
		});
	</script>
<!--===============================================================================================-->
	<script src="../js/main.js"></script>