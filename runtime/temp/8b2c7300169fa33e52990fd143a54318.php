<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:91:"/Users/linsonggao/Sites/APP/admin/public/../application/admin/view/index/lost_password.html";i:1512955052;}*/ ?>
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->



    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo \think\Config::get('object_name'); ?> | 找回密码</title>
        <link rel="icon" type="image/ico" href="__PUBLIC__/images/favicon.ico" />
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">




        <!-- ============================================
        ================= Stylesheets ===================
        ============================================= -->
        <!-- vendor css files -->
        <link rel="stylesheet" href="__PUBLIC__/css/vendor/bootstrap.min.css">
        <link rel="stylesheet" href="__PUBLIC__/css/vendor/animate.css">
        <link rel="stylesheet" href="__PUBLIC__/css/vendor/font-awesome.min.css">

        <!-- project main css files -->
        <link rel="stylesheet" href="__PUBLIC__/css/main.css">
        <!--/ stylesheets -->



        <!-- ==========================================
        ================= Modernizr ===================
        =========================================== -->
        <script src="__PUBLIC__/js/vendor/modernizr/modernizr-2.8.3-respond-1.4.2.min.js"></script>
        <!--/ modernizr -->




    </head>





    <body id="minovate" class="appWrapper">






        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->












        <!-- ====================================================
        ================= Application Content ===================
        ===================================================== -->
        <div id="wrap" class="animsition">




            <div class="page page-core page-login">

                <div class="text-center"><h3 class="text-light text-white"><img src="__PUBLIC__/images/logo.png" alt="<?php echo \think\Config::get('object_name'); ?>" width="157" height="30"></h3></div>

                <div class="container w-420 p-15 bg-white mt-40 text-center">

                    <h2 class="text-light">忘记密码?</h2>

                    <form name="form" class="form-validation mt-20">

                        <p class="help-block text-left">
                            通过邮箱找回密码
                        </p>

                        <div class="input-group input-group-lg">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user red"></i></span>
                            <input type="text" class="form-control" placeholder="E-mail" name="admin_email" >
                        </div>
                        <div class="clearfix"></div>

                        <div class="bg-slategray lt wrap-reset mt-40 text-left">
                                <button type="submit" class="btn btn-primary" name="login_submit">提交</button>
                                <a href="<?php echo url('/admin/login'); ?>" class="btn btn-lightred b-0 text-uppercase pull-right">返回</a>
                                <div class="clearfix"></div>
                        </div>
                    </form>

                </div>

            </div>



        </div>
        <!--/ Application Content -->














        <!-- ============================================
        ============== Vendor JavaScripts ===============
        ============================================= -->
        <script src="__PUBLIC__/js/vendor/jquery/jquery-1.11.2.min.js"></script>

        <script src="__PUBLIC__/js/vendor/bootstrap/bootstrap.min.js"></script>

        <script src="__PUBLIC__/js/vendor/jRespond/jRespond.min.js"></script>

        <script src="__PUBLIC__/js/vendor/sparkline/jquery.sparkline.min.js"></script>

        <script src="__PUBLIC__/js/vendor/slimscroll/jquery.slimscroll.min.js"></script>

        <script src="__PUBLIC__/js/vendor/animsition/js/jquery.animsition.min.js"></script>

        <script src="__PUBLIC__/js/vendor/screenfull/screenfull.min.js"></script>
        <!--/ vendor javascripts -->




        <!-- ============================================
        ============== Custom JavaScripts ===============
        ============================================= -->
        <script src="__PUBLIC__/js/main.js"></script>
        <!--/ custom javascripts -->






        <!-- ===============================================
        ============== Page Specific Scripts ===============
        ================================================ -->
        <script>
            $(window).load(function(){


            });
        </script>
        <!--/ Page Specific Scripts -->

    </body>
</html>
