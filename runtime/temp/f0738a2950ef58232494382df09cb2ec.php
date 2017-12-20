<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:83:"/Users/linsonggao/Sites/APP/admin/public/../application/admin/view/index/index.html";i:1512984546;s:78:"/Users/linsonggao/Sites/APP/admin/public/../application/admin/view/layout.html";i:1512955052;s:78:"/Users/linsonggao/Sites/APP/admin/public/../application/admin/view/header.html";i:1513754748;s:76:"/Users/linsonggao/Sites/APP/admin/public/../application/admin/view/left.html";i:1513590085;s:76:"/Users/linsonggao/Sites/APP/admin/public/../application/admin/view/menu.html";i:1513653508;s:78:"/Users/linsonggao/Sites/APP/admin/public/../application/admin/view/footer.html";i:1512955052;}*/ ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo \think\Config::get('object_name'); ?> | 后台管理系统</title>
        <link rel="icon" type="image/ico" href="__PUBLIC__/images/favicon.ico" />
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- ============================================
        ================= Stylesheets ===================
        ============================================= -->
        <!-- vendor css files -->
        <link rel="stylesheet" href="__PUBLIC__/css/vendor/bootstrap.min.css">
        <link rel="stylesheet" href="__PUBLIC__/css/vendor/docs.min.css">
        <link rel="stylesheet" href="__PUBLIC__/css/vendor/animate.css">
        <!-- <script src="https://use.fontawesome.com/70e96d4855.js"></script> -->
        <link rel="stylesheet" href="__PUBLIC__/css/vendor/font-awesome.min.css">
        <link rel="stylesheet" href="__PUBLIC__/js/vendor/magnific-popup/magnific-popup.css">
        <link rel="stylesheet" href="__PUBLIC__/js/vendor/daterangepicker/daterangepicker-bs3.css">
        <link rel="stylesheet" href="__PUBLIC__/js/vendor/morris/morris.css">
        <link rel="stylesheet" href="__PUBLIC__/js/vendor/owl-carousel/owl.carousel.css">
        <link rel="stylesheet" href="__PUBLIC__/js/vendor/owl-carousel/owl.theme.css">
        <link rel="stylesheet" href="__PUBLIC__/js/vendor/rickshaw/rickshaw.min.css">
        <link rel="stylesheet" href="__PUBLIC__/js/vendor/datetimepicker/css/bootstrap-datetimepicker.min.css">
        <link rel="stylesheet" href="__PUBLIC__/js/vendor/datatables/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="__PUBLIC__/js/vendor/datatables/datatables.bootstrap.min.css">
        <link rel="stylesheet" href="__PUBLIC__/js/vendor/chosen/chosen.css">
        <link rel="stylesheet" href="__PUBLIC__/js/vendor/summernote/summernote.css">
        <link rel="stylesheet" href="__PUBLIC__/js/vendor/touchspin/jquery.bootstrap-touchspin.min.css">
        <link rel="stylesheet" href="__PUBLIC__/js/vendor/colorpicker/css/bootstrap-colorpicker.min.css">
        <link rel="stylesheet" href="__PUBLIC__/js/vendor/nestable/css/style.css">

        <!-- project main css files -->
        <link rel="stylesheet" href="__PUBLIC__/css/main.css">
        <!--/ stylesheets -->

        <link rel="stylesheet" href="__PUBLIC__/css/custom.css">

        <!-- ==========================================
        ================= Modernizr ===================
        =========================================== -->
        <script src="__PUBLIC__/js/vendor/modernizr/modernizr-2.8.3-respond-1.4.2.min.js"></script>
        <!--/ modernizr -->

        <script src="__PUBLIC__/js/vendor/jquery/jquery-1.11.2.min.js"></script>

        <script src="__PUBLIC__/js/vendor/bootstrap/bootstrap.min.js"></script>

        <script src="__PUBLIC__/js/vendor/jRespond/jRespond.min.js"></script>


    </head>
    <body id="minovate" class="appWrapper">


        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->



        <!-- ====================================================
        ================= Application Content ===================
        ===================================================== -->
        <div id="wrap" class="animsition">


            <!-- ===============================================
            ================= HEADER Content ===================
            ================================================ -->
            <section id="header">
                <header class="clearfix">

                    <!-- Branding -->
                    <div class="branding">
                        <a class="brand" href="<?php echo url('/admin'); ?>" target="_blank">
                            <span><?php echo \think\Config::get('object_name'); ?></span>
                        </a>
                        <a role="button" tabindex="0" class="offcanvas-toggle visible-xs-inline"><i class="fa fa-bars"></i></a>
                    </div>
                    <!-- Branding end -->

                    <!-- Right-side navigation -->
                    <ul class="nav-right pull-right list-inline">

                        <li class="dropdown nav-profile">

                            <a href class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-user"></i>
                                <span><?php echo \think\Session::get('admin_nickname'); ?> <i class="fa fa-angle-down"></i></span>
                            </a>

                            <ul class="dropdown-menu animated littleFadeInRight" role="menu">

                                <li>
                                    <a role="button" tabindex="0" href="<?php echo url('/admin/administrator/'); ?><?php echo \think\Session::get('uid'); ?>">
                                        <i class="fa fa-user"></i>管理员信息
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="<?php echo url('/admin/logout'); ?>" role="button" tabindex="0">
                                        <i class="fa fa-sign-out"></i>登出
                                    </a>
                                </li>

                            </ul>

                        </li>
                    </ul>
                    <!-- Right-side navigation end -->



                </header>

            </section>
            <!--/ HEADER Content  -->

<!-- =================================================
================= CONTROLS Content ===================
================================================== -->
<div id="controls">

    <!-- ================================================
    ================= SIDEBAR Content ===================
    ================================================= -->
    <aside id="sidebar">


        <div id="sidebar-wrap">

            <div class="panel-group slim-scroll" role="tablist">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" href="#sidebarNav">
                                Navigation <i class="fa fa-angle-up"></i>
                            </a>
                        </h4>
                    </div>
                    <div id="sidebarNav" class="panel-collapse collapse in" role="tabpanel">
                        <div class="panel-body">
                            <script>
                                $(window).load(function(){
                                    var moduleUrl = '<?php echo !empty($data['module_slug'])?$data['module_slug'] : "admin"; ?>';
                                    if(moduleUrl){
                                        var currentMenuItem = $('#navigation .'+moduleUrl);
                                        if(currentMenuItem.hasClass('dropdown')){
                                            currentMenuItem.addClass('open');
                                            currentMenuItem.find('ul').show();

                                        }else{
                                            currentMenuItem.addClass('active');
                                        }
                                    }
                                });
                            </script>
                            <!-- ===================================================
                            ================= NAVIGATION Content ===================
                            ==================================================== -->
                            
<ul id="navigation">
    <li class="manage"><a class="ajax-link" href="<?php echo url('/admin'); ?>"><i class="fa fa-home" aria-hidden="true"></i>
		起始页</a>
	</li>

<?php foreach($menu as $key => $value): ?>
<li class="privs<?php echo $key; ?>">
    <a role="button" tabindex="0"><i class="fa <?php echo $value['icon']; ?>" aria-hidden="true"></i> <span><?php echo $value['name']; ?></span></a>
    <?php if(isset($value['children']) && $value['children']): ?>
    <ul>
    	<?php foreach($value['children'] as $k => $v): ?>
        <li><a class="ajax-link" href="<?php echo url($v['url']); ?>"><i class="fa fa-caret-right" aria-hidden="true"></i> <?php echo $v['name']; ?></a></li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
</li>
<?php endforeach; ?>
<!-- 

    <li class="privs1">
        <a role="button" tabindex="0"><i class="fa fa-user-secret" aria-hidden="true"></i> <span>管理员管理</span></a>
        <ul>
            <li><a class="ajax-link" href="<?php echo url('/admin/administrator'); ?>"><i class="fa fa-caret-right" aria-hidden="true"></i> 管理员列表</a></li>
            <li><a class="ajax-link" href="<?php echo url('/admin/administrator/create'); ?>"><i class="fa fa-caret-right"></i> 新增管理员</a></li>
        </ul>
    </li>



    <li class="privs2">
        <a role="button" tabindex="0"><i class="fa fa-cog" aria-hidden="true"></i> <span>设置</span></a>
        <ul>
            <li><a class="ajax-link" href="<?php echo url('/admin/set'); ?>"><i class="fa fa-caret-right" aria-hidden="true"></i> 权限管理</a></li>
            <li><a class="ajax-link" href="<?php echo url('/admin/set/rspwd'); ?>"><i class="fa fa-caret-right"></i> 重置密码</a></li>
        </ul>
    </li>


 -->
</ul>
                            <!--/ NAVIGATION Content -->
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </aside>
    <!--/ SIDEBAR Content -->

</div>
<!--/ CONTROLS Content -->

<!-- ====================================================
================= CONTENT ===============================
===================================================== -->
<section id="content">

    <div class="page page-dashboard">

        <div class="pageheader">

            <h2>管理面板 <span></span></h2>

            <div class="page-bar">

                <ul class="page-breadcrumb">
                    <li>
                        <a href="<?php echo url('/admin'); ?>"><i class="fa fa-home"></i> 起始页</a>
                    </li>
                    <li>
                        <a href="<?php echo url('/admin'); ?>">管理面板</a>
                    </li>
                </ul>

                <div class="page-toolbar">
                    <a role="button" tabindex="0" class="btn btn-lightred no-border">
                        <i class="fa fa-calendar"></i>&nbsp;&nbsp;<span><?php echo date("Y年m月d日"); ?></span>
                    </a>
                </div>

            </div>

        </div>

        <!-- cards row -->
        <div class="row">

            <!-- col -->
            <div class="card-container col-lg-3 col-sm-6 col-sm-12">
                <div class="card">
                    <div class="front bg-greensea">

                        <!-- row -->
                        <div class="row">
                            <!-- col -->
                            <div class="col-xs-4">
                                <i class="fa fa-users fa-4x"></i>
                            </div>
                            <!-- /col -->
                            <!-- col -->
                            <div class="col-xs-8">
                                <p class="text-elg text-strong mb-0"><?php echo $data['admin_count']; ?></p>
                                <span>管理员人数</span>
                            </div>
                            <!-- /col -->
                        </div>
                        <!-- /row -->

                    </div>
                    <div class="back bg-greensea">

                        <!-- row -->
                        <div class="row">
                            <!-- col -->
                            <div class="col-xs-6">
                                <a href="<?php echo url('/admin/administrator'); ?>"><i class="fa fa-info fa-2x"></i> 查看</a>
                            </div>
                            <!-- /col -->
                            <!-- col -->
                            <div class="col-xs-6">
                                <a href="<?php echo url('/admin/administrator/create'); ?>"><i class="fa fa-plus fa-2x"></i> 新增</a>
                            </div>
                            <!-- /col -->
                        </div>
                        <!-- /row -->

                    </div>
                </div>
            </div>
            <!-- /col -->


            <!-- col -->
            <div class="card-container col-lg-3 col-sm-6 col-sm-12">
                <div class="card">
                    <div class="front bg-blue">

                        <!-- row -->
                        <div class="row">
                            <!-- col -->
                            <div class="col-xs-4">
                                <i class="fa fa-list fa-4x" aria-hidden="true"></i>
                            </div>
                            <!-- /col -->
                            <!-- col -->
                            <div class="col-xs-8">
                                <p class="text-elg text-strong mb-0">999</p>
                                <span>总文章数</span>
                            </div>
                            <!-- /col -->
                        </div>
                        <!-- /row -->

                    </div>
                    <div class="back bg-blue">

                        <!-- row -->
                        <div class="row">

                            <!-- col -->
                            <div class="col-xs-12">
                                <a href="<?php echo url('/admin/posts'); ?>"><i class="fa fa-ellipsis-h fa-2x"></i> 更多</a>
                            </div>
                            <!-- /col -->

                        </div>
                        <!-- /row -->

                    </div>
                </div>
            </div>
            <!-- /col -->

            <!-- col -->
            <div class="card-container col-lg-3 col-sm-6 col-sm-12">
                <div class="card">
                    <div class="front bg-slategray">

                        <!-- row -->
                        <div class="row">
                            <!-- col -->
                            <div class="col-xs-4">
                                <i class="fa fa-list-alt fa-4x" aria-hidden="true"></i>
                            </div>
                            <!-- /col -->
                            <!-- col -->
                            <div class="col-xs-8">
                                <p class="text-elg text-strong mb-0">999</p>
                                <span>当月发布</span>
                            </div>
                            <!-- /col -->
                        </div>
                        <!-- /row -->

                    </div>
                    <div class="back bg-slategray">

                        <!-- row -->
                        <div class="row">
                            <!-- col -->
                            <div class="col-xs-6">
                                <a href="<?php echo url('/admin/posts'); ?>?start_time=<?php echo date('Y-m-01'); ?>&end_time="><i class="fa fa-chain-broken fa-2x"></i> 详细</a>
                            </div>
                            <!-- /col -->
                            <!-- col -->
                            <div class="col-xs-6">
                                <a href="<?php echo url('/admin/posts'); ?>"><i class="fa fa-ellipsis-h fa-2x"></i> 更多</a>
                            </div>
                            <!-- /col -->
                        </div>
                        <!-- /row -->

                    </div>
                </div>
            </div>
            <!-- /col -->

        </div>
        <!-- /row -->





    </div>


</section>
<!--/ CONTENT -->

        </div>
        <!--/ Application Content -->



        <!-- ============================================
        ============== Vendor JavaScripts ===============
        ============================================= -->


        <script src="__PUBLIC__/js/vendor/d3/d3.min.js"></script>
        <script src="__PUBLIC__/js/vendor/d3/d3.layout.min.js"></script>

        <script src="__PUBLIC__/js/vendor/rickshaw/rickshaw.min.js"></script>

        <script src="__PUBLIC__/js/vendor/sparkline/jquery.sparkline.min.js"></script>

        <script src="__PUBLIC__/js/vendor/slimscroll/jquery.slimscroll.min.js"></script>

        <script src="__PUBLIC__/js/vendor/animsition/js/jquery.animsition.min.js"></script>

        <script src="__PUBLIC__/js/vendor/daterangepicker/moment.min.js"></script>
        <script src="__PUBLIC__/js/vendor/daterangepicker/daterangepicker.js"></script>

        <script src="__PUBLIC__/js/vendor/screenfull/screenfull.min.js"></script>

        <script src="__PUBLIC__/js/vendor/flot/jquery.flot.min.js"></script>
        <script src="__PUBLIC__/js/vendor/flot/jquery.flot.resize.min.js"></script>
        <script src="__PUBLIC__/js/vendor/flot/jquery.flot.orderBars.js"></script>
        <script src="__PUBLIC__/js/vendor/flot/jquery.flot.stack.min.js"></script>
        <script src="__PUBLIC__/js/vendor/flot/jquery.flot.pie.min.js"></script>
        <script src="__PUBLIC__/js/vendor/flot-spline/jquery.flot.spline.min.js"></script>
        <script src="__PUBLIC__/js/vendor/flot-tooltip/jquery.flot.tooltip.min.js"></script>

        <script src="__PUBLIC__/js/vendor/gaugejs/gauge.min.js"></script>

        <script src="__PUBLIC__/js/vendor/raphael/raphael-min.js"></script>
        <script src="__PUBLIC__/js/vendor/d3/d3.v2.js"></script>
        <script src="__PUBLIC__/js/vendor/rickshaw/rickshaw.min.js"></script>



        <script src="__PUBLIC__/js/vendor/morris/morris.min.js"></script>

        <script src="__PUBLIC__/js/vendor/easypiechart/jquery.easypiechart.min.js"></script>



        <script src="__PUBLIC__/js/vendor/owl-carousel/owl.carousel.min.js"></script>

        <script src="__PUBLIC__/js/vendor/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>

        <script src="__PUBLIC__/js/vendor/datatables/js/jquery.dataTables.min.js"></script>
        <script src="__PUBLIC__/js/vendor/datatables/extensions/dataTables.bootstrap.js"></script>

        <script src="__PUBLIC__/js/vendor/chosen/chosen.jquery.min.js"></script>

        <script src="__PUBLIC__/js/vendor/summernote/summernote.min.js"></script>

        <script src="__PUBLIC__/js/vendor/coolclock/coolclock.js"></script>
        <script src="__PUBLIC__/js/vendor/coolclock/excanvas.js"></script>

        <script src="__PUBLIC__/js/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>

        <script src="__PUBLIC__/js/vendor/touchspin/jquery.bootstrap-touchspin.min.js"></script>
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
                // Initialize Statistics chart
                var data = [{
                    data: [[1,15],[2,40],[3,35],[4,39],[5,42],[6,50],[7,46],[8,49],[9,59],[10,60],[11,58],[12,74]],
                    label: 'Unique Visits',
                    points: {
                        show: true,
                        radius: 4
                    },
                    splines: {
                        show: true,
                        tension: 0.45,
                        lineWidth: 4,
                        fill: 0
                    }
                }, {
                    data: [[1,50],[2,80],[3,90],[4,85],[5,99],[6,125],[7,114],[8,96],[9,130],[10,145],[11,139],[12,160]],
                    label: 'Page Views',
                    bars: {
                        show: true,
                        barWidth: 0.6,
                        lineWidth: 0,
                        fillColor: { colors: [{ opacity: 0.3 }, { opacity: 0.8}] }
                    }
                }];

                var options = {
                    colors: ['#e05d6f','#61c8b8'],
                    series: {
                        shadowSize: 0
                    },
                    legend: {
                        backgroundOpacity: 0,
                        margin: -7,
                        position: 'ne',
                        noColumns: 2
                    },
                    xaxis: {
                        tickLength: 0,
                        font: {
                            color: '#fff'
                        },
                        position: 'bottom',
                        ticks: [
                            [ 1, 'JAN' ], [ 2, 'FEB' ], [ 3, 'MAR' ], [ 4, 'APR' ], [ 5, 'MAY' ], [ 6, 'JUN' ], [ 7, 'JUL' ], [ 8, 'AUG' ], [ 9, 'SEP' ], [ 10, 'OCT' ], [ 11, 'NOV' ], [ 12, 'DEC' ]
                        ]
                    },
                    yaxis: {
                        tickLength: 0,
                        font: {
                            color: '#fff'
                        }
                    },
                    grid: {
                        borderWidth: {
                            top: 0,
                            right: 0,
                            bottom: 1,
                            left: 1
                        },
                        borderColor: 'rgba(255,255,255,.3)',
                        margin:0,
                        minBorderMargin:0,
                        labelMargin:20,
                        hoverable: true,
                        clickable: true,
                        mouseActiveRadius:6
                    },
                    tooltip: true,
                    tooltipOpts: {
                        content: '%s: %y',
                        defaultTheme: false,
                        shifts: {
                            x: 0,
                            y: 20
                        }
                    }
                };




                // Initialize owl carousels
                $('#todo-carousel, #feed-carousel, #notes-carousel').owlCarousel({
                    autoPlay: 5000,
                    stopOnHover: true,
                    slideSpeed : 300,
                    paginationSpeed : 400,
                    singleItem : true,
                    responsive: true
                });

                $('#appointments-carousel').owlCarousel({
                    autoPlay: 5000,
                    stopOnHover: true,
                    slideSpeed : 300,
                    paginationSpeed : 400,
                    navigation: true,
                    navigationText : ['<i class=\'fa fa-chevron-left\'></i>','<i class=\'fa fa-chevron-right\'></i>'],
                    singleItem : true
                });
                //* Initialize owl carousels


                //Initialize mini calendar datepicker
                $('#mini-calendar').datetimepicker({
                    inline: true
                });
                //*Initialize mini calendar datepicker

                //todo's
                $('.widget-todo .todo-list li .checkbox').on('change', function() {
                    var todo = $(this).parents('li');

                    if (todo.hasClass('completed')) {
                        todo.removeClass('completed');
                    } else {
                        todo.addClass('completed');
                    }
                });
                //* todo's


                //initialize datatable
                $('#project-progress').DataTable({
                    "aoColumnDefs": [
                      { 'bSortable': false, 'aTargets': [ "no-sort" ] }
                    ],
                });
                //*initialize datatable

                //load wysiwyg editor
                $('#summernote').summernote({
                    toolbar: [
                        //['style', ['style']], // no style button
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['height', ['height']],
                        //['insert', ['picture', 'link']], // no insert buttons
                        //['table', ['table']], // no table button
                        //['help', ['help']] //no help button
                    ],
                    height: 143   //set editable area's height
                });
                //*load wysiwyg editor
            });
        </script>
        <!--/ Page Specific Scripts -->


    </body>
</html>
