<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    
        <meta charset="utf-8" />
        <title><?php echo (C("APP_NAME")); ?></title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
    
    
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="/assets/<?php echo (C("DEFAULT_THEME")); ?>/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/<?php echo (C("DEFAULT_THEME")); ?>/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/<?php echo (C("DEFAULT_THEME")); ?>/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/<?php echo (C("DEFAULT_THEME")); ?>/css/style-metro.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/<?php echo (C("DEFAULT_THEME")); ?>/css/style.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/<?php echo (C("DEFAULT_THEME")); ?>/css/style-responsive.css" rel="stylesheet" type="text/css"/>
        <?php if( C("PAGE_COLOR_STYLE")== 0): ?><link href="/assets/<?php echo (C("DEFAULT_THEME")); ?>/css/default.css" rel="stylesheet" type="text/css" id="style_color"/>
            <?php else: ?>
            <link href="/assets/<?php echo (C("DEFAULT_THEME")); ?>/css/light.css" rel="stylesheet" type="text/css" id="style_color"/><?php endif; ?>
        <link href="/assets/<?php echo (C("DEFAULT_THEME")); ?>/css/uniform.default.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/<?php echo (C("DEFAULT_THEME")); ?>/css/bootstrap-toggle-buttons.css" rel="stylesheet" type="text/css" />
        <link href="/assets/<?php echo (C("DEFAULT_THEME")); ?>/css/glyphicons.css" rel="stylesheet" />
        <!-- END GLOBAL MANDATORY STYLES -->
    
    
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="/assets/<?php echo (C("DEFAULT_THEME")); ?>/css/jquery.gritter.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/<?php echo (C("DEFAULT_THEME")); ?>/css/daterangepicker.css" rel="stylesheet" type="text/css" />
        <link href="/assets/<?php echo (C("DEFAULT_THEME")); ?>/css/fullcalendar.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/<?php echo (C("DEFAULT_THEME")); ?>/css/jqvmap.css" rel="stylesheet" type="text/css" media="screen"/>
        <link href="/assets/<?php echo (C("DEFAULT_THEME")); ?>/css/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen"/>
        <link href="/assets/<?php echo (C("DEFAULT_THEME")); ?>/css/jquery.tagsinput.css" type="text/css" rel="stylesheet"  />
        <link href="/assets/<?php echo (C("DEFAULT_THEME")); ?>/css/multi-select-metro.css" rel="stylesheet" type="text/css" />
        <link href="/assets/<?php echo (C("DEFAULT_THEME")); ?>/css/DT_bootstrap.css" rel="stylesheet" />
        <!-- END PAGE LEVEL STYLES -->
    
    <link rel="shortcut icon" href="/assets/<?php echo (C("DEFAULT_THEME")); ?>/image/favicon.ico" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-header-fixed">

    <!-- BEGIN HEADER -->
    <div class="header navbar navbar-inverse navbar-fixed-top">
        <!-- BEGIN TOP NAVIGATION BAR -->
        <div class="navbar-inner">
            <div class="container-fluid">
                <!-- BEGIN LOGO -->
                <a class="brand" href="<?php echo U('/Admin/Index/index');?>">
                    <img src="/assets/<?php echo (C("DEFAULT_THEME")); ?>/image/rdlogo.png" alt="logo"/>
                    <!--<?php echo (C("APP_NAME")); ?>-->
                </a>
                <!-- END LOGO -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <a href="javascript:;" class="btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">
                    <img src="/assets/<?php echo (C("DEFAULT_THEME")); ?>/image/menu-toggler.png" alt="" />
                </a>
                <!-- END RESPONSIVE MENU TOGGLER -->
                <ul class="nav pull-right">
                    <li class="dropdown user">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img alt="" class="img-circle" src="/assets/<?php echo (C("DEFAULT_THEME")); ?>/image/avatar1_small.jpg" />
                            当前用户：<span class="username"><?php echo ($uname); ?></span>
                            <i class="icon-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo U('/Admin/Profile/pwd');?>"><i class="icon-key"></i> 修改密码</a></li>
                            <li><a href="<?php echo U('/Admin/Logout/index');?>"><i class="icon-signout"></i> 注销</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!-- END TOP NAVIGATION BAR -->
    </div>

<!-- END HEADER -->
<!-- BEGIN CONTAINER -->
<div class="page-container">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar nav-collapse collapse">
        
            <!-- BEGIN SIDEBAR MENU -->
            <ul class="page-sidebar-menu" id="page-sidebar-menu">
                <li>
                    <div class="sidebar-toggler hidden-phone"></div>
                </li>
                <li></li>
                <!--<?php if(!empty($_extra_menu)): ?>-->
                <!---->
                <!--<?php echo extra_menu($_extra_menu,$__MENU__);?>-->
                <!--<?php endif; ?>-->
                <li class="start">
                    <a href="<?php echo U('/Admin/Index/index');?>">
                        <i class="icon-home"></i>
                        <span class="title">系统首页</span>
                        <span class="selected"></span>
                    </a>
                </li>
                <?php if(is_array($__MENU__["child"])): $i = 0; $__LIST__ = $__MENU__["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub_menu): $mod = ($i % 2 );++$i;?><!-- 子导航 -->
                    <li class="p_menu">
                        <?php if(!empty($sub_menu)): if(!empty($sub_menu["group"])): ?><a href="javascript:;">
                                    <?php if(!empty($sub_menu["group"]["icon"])): ?><i class="<?php echo ($sub_menu["group"]["icon"]); ?>"></i><?php endif; ?>
                                    <span class="title"><?php echo ($sub_menu["group"]["group"]); ?></span>
                                    <span class="selected"></span>
                                    <span class="arrow "></span>
                                </a><?php endif; ?>
                            <ul class="sub-menu">
                                <?php if(is_array($sub_menu["subMenu"])): $i = 0; $__LIST__ = $sub_menu["subMenu"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li class="">
                                        <a href="<?php echo (u($menu["url"])); ?>">
                                            <?php if(empty($menu["icon"])): ?><i class="icon-file"></i>
                                                <?php else: ?>
                                                <i class="<?php echo ($menu["icon"]); ?>"></i><?php endif; ?>
                                            <?php echo ($menu["title"]); ?>
                                        </a>
                                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
                            </ul><?php endif; ?>
                    </li>
                    <!-- /子导航 --><?php endforeach; endif; else: echo "" ;endif; ?>
                <!--//////////////////////////////-->
                <!-- 数据模型 -->
                <!--<?php if(($showDataModelMenu) == "1"): ?><li class="p_menu">
                        <a href="<?php echo U('Datamodel/index');?>">
                            <i class="icon-cogs"></i>
                            <span class="title">数据模型</span>
                            <span class="selected"></span>
                        </a>
                    </li><?php endif; ?>-->
            </ul>
            <!-- END SIDEBAR MENU -->
        
    </div>
    <!-- END SIDEBAR -->
    <!-- BEGIN PAGE -->
    <div class="page-content">

        <!-- BEGIN PAGE CONTAINER-->
        <div class="container-fluid">

            <!--面包屑导航-->
            
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <div class="page-title">
                    系统首页
                <!--<small>(<?php echo ($subTitle); ?>)</small>-->
            </div>
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <ul class="breadcrumb">
                <?php if(is_array($nav)): $i = 0; $__LIST__ = $nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$n): $mod = ($i % 2 );++$i;?><li>
                        <?php if($key == 0): ?><i class="icon-home"></i>
                            <a href="<?php echo ($n["url"]); ?>"><?php echo ($n["title"]); ?></a>
                            <?php else: ?>
                            <?php if($key == 1): ?><a href="javascript:void(0)"><?php echo ($n["title"]); ?></a>
                                <?php else: ?>
                                <?php if($key == count($nav)-1): ?><a href="javascript:void(0)"><?php echo ($n["title"]); ?></a>
                                    <?php else: ?>
                                    <a href="<?php echo ($n["url"]); ?>"><?php echo ($n["title"]); ?></a><?php endif; endif; endif; ?>
                        <?php if($key == count($nav)-1): else: ?>
                            <i class="icon-angle-right"></i><?php endif; ?>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->


            <div id="dashboard">
                
    <!-- BEGIN DASHBOARD STATS -->
    <div class="row-fluid">
    </div>
    <!-- END DASHBOARD STATS -->
    <div class="clearfix"></div>
    <div class="row-fluid">
        <div class="span12">
        </div>
    </div>

            </div>
        </div>
        <!-- END PAGE CONTAINER-->
    </div>
    <!-- END PAGE -->
</div>
<!-- END CONTAINER -->

    <!-- BEGIN FOOTER -->
    <div class="footer">
        <div class="footer-inner">
            &copy;<?php echo (C("APP_COPYRIGHT")); ?>
        </div>
        <div class="footer-tools">
				<span class="go-top">
				<i class="icon-angle-up"></i>
				</span>
        </div>
    </div>
    <!-- END FOOTER -->


    <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
    <script src="/assets/<?php echo (C("DEFAULT_THEME")); ?>/js/jquery-1.10.1.min.js" type="text/javascript"></script>
    <script src="/assets/<?php echo (C("DEFAULT_THEME")); ?>/js/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
    <!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
    <script src="/assets/<?php echo (C("DEFAULT_THEME")); ?>/js/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
    <script src="/assets/<?php echo (C("DEFAULT_THEME")); ?>/js/bootstrap.min.js" type="text/javascript"></script>
    <!--[if lt IE 9]>
    <script src="/assets/<?php echo (C("DEFAULT_THEME")); ?>/js/excanvas.min.js"></script>
    <script src="/assets/<?php echo (C("DEFAULT_THEME")); ?>/js/respond.min.js"></script>
    <![endif]-->
    <script src="/assets/<?php echo (C("DEFAULT_THEME")); ?>/js/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
    <script src="/assets/<?php echo (C("DEFAULT_THEME")); ?>/js/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="/assets/<?php echo (C("DEFAULT_THEME")); ?>/js/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="/assets/<?php echo (C("DEFAULT_THEME")); ?>/js/jquery.cookie.min.js" type="text/javascript"></script>
    <script src="/assets/<?php echo (C("DEFAULT_THEME")); ?>/js/jquery.uniform.min.js" type="text/javascript" ></script>
    <script type="text/javascript" src="/assets/<?php echo (C("DEFAULT_THEME")); ?>/js/jquery.toggle.buttons.js"></script>
    <script src="/assets/<?php echo (C("DEFAULT_THEME")); ?>/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="/assets/<?php echo (C("DEFAULT_THEME")); ?>/js/common/validate.js"></script>
    <!-- END CORE PLUGINS -->


    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="/assets/<?php echo (C("DEFAULT_THEME")); ?>/js/app.js"></script>
    <script>
        jQuery(document).ready(function() {
            App.init();
        });
    </script>
    <!-- END JAVASCRIPTS -->

<script type="text/javascript">
    +function(){
        var $window = $(window), subnav = $("#page-sidebar-menu"), url;
        /* 左边菜单高亮 */
        url = window.location.pathname + window.location.search;
        url = url.replace(/(\/(p)\/\d+)|(&p=\d+)|(\/(id)\/\d+)|(&id=\d+)|(\/(group)\/\d+)|(&group=\d+)/i, "");
        var module = '<?php echo (MODULE_NAME); ?>';
        var controller = '<?php echo (CONTROLLER_NAME); ?>';
        var action = '<?php echo (ACTION_NAME); ?>';//'index';
        var uri = '';
        if (module) {
            uri += '/' + module ;
        }
        if (controller) {
            uri += '/' + controller;
        }
        var urii = uri;
        urii += '/index';
        if (action) {
            uri += '/' + action;
        }
        var setActive = function( node ,isOpen ){
            if (node) {
                node.addClass("active");
                var nodepp = node.parent();
                if( nodepp.parent() ) {
                    nodepp.parent().addClass('active');
                    if (isOpen) {
                        var arrow = $('.p_menu.active').find('.arrow');
                        if (arrow) {
                            arrow.addClass('open');
                        }
                    }
                }
            }
        };
        var nodep = subnav.find("a[href='" + uri + "']").parent();
        var nodepi = subnav.find("a[href='" + urii + "']").parent();
        if (nodep || nodepi) {
            setActive(nodep,true);
            setActive(nodepi,true);
        }
    }();

    //
    var isFluid = <?php echo (C("PAGE_SCREEN_STYLE")); ?>;
    var headerIsFixed = <?php echo (C("PAGE_HEADER_FIXED")); ?>;
    var footerIsFixed = <?php echo (C("PAGE_FOOTER_FIXED")); ?>;
    App.layoutOptionSetScreen(isFluid == '1', footerIsFixed == '0');
    App.headerFixed(headerIsFixed);
    App.footerFixed(footerIsFixed);
</script>
</body>
<!-- END BODY -->
</html>