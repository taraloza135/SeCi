<!DOCTYPE html>
<!--[if lt IE 7]> <html class="ie lt-ie9 lt-ie8 lt-ie7 paceSimple sidebar sidebar-fusion"> <![endif]-->
<!--[if IE 7]>    <html class="ie lt-ie9 lt-ie8 paceSimple sidebar sidebar-fusion"> <![endif]-->
<!--[if IE 8]>    <html class="ie lt-ie9 paceSimple sidebar sidebar-fusion"> <![endif]-->
<!--[if gt IE 8]> <html class="ie paceSimple sidebar sidebar-fusion"> <![endif]-->
<!--[if !IE]><!--><html class="paceSimple sidebar sidebar-fusion"><!-- <![endif]-->
    <head>
        <?php $this->load->view('includes/meta'); ?>
        <?php $this->load->view('includes/CSS'); ?>   
        <?php $this->load->view('includes/headJS'); ?>   

    </head>
    <body class="">

        <!-- Main Container Fluid -->
        <div class="container-fluid menu-hidden" style="visibility:hidden;">
            <!-- Sidebar Menu -->
            <?php $this->load->view('includes/sidebar'); ?>  
            <!-- // Sidebar Menu END -->

            <!-- Content -->
            <div id="content">

                <?php $this->load->view('includes/header'); ?>  
                <?php $this->load->view('includes/flashMessages'); ?>
                <?php $this->load->view((isset($pageView) && $pageView != '') ? $pageView : 'error/index' ); ?>

            </div>
            <!-- // Content END -->

            <div class="clearfix"></div>
            <!-- // Sidebar menu & content wrapper END -->

            <?php $this->load->view('includes/footer'); ?>

            <!-- // Footer END -->

        </div>
        <!-- // Main Container Fluid END -->


        <?php $this->load->view('includes/JS'); ?>
    </body>
</html>