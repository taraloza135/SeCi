<!DOCTYPE html>
<!--[if lt IE 7]> <html class="ie lt-ie9 lt-ie8 lt-ie7 paceSimple"> <![endif]-->
<!--[if IE 7]>    <html class="ie lt-ie9 lt-ie8 paceSimple"> <![endif]-->
<!--[if IE 8]>    <html class="ie lt-ie9 paceSimple"> <![endif]-->
<!--[if gt IE 8]> <html class="ie paceSimple"> <![endif]-->
<!--[if !IE]><!--><html class="paceSimple"><!-- <![endif]-->
    <head>
        <?php $this->load->view('includes/meta'); ?>
        <?php $this->load->view('includes/CSS'); ?>   
        <?php $this->load->view('includes/headJS'); ?>   
        <?php $this->load->view('includes/login_JS'); ?>
    </head>
    <body class=" loginWrapper">
        <!-- Main Container Fluid -->
        <div class="container-fluid menu-hidden" style="visibility:hidden;">
            <!-- Content -->
            <div id="content">
                <?php $this->load->view('includes/header'); ?>  
                <?php $this->load->view('includes/flashMessages'); ?>
                <div class="widget-body padding-none">
                    <div class="jumbotron margin-none center bg-white">
                        <h1 class="separator bottom">We are under maintainance!</h1>
                        <p>Be pations .</p>
                        <p class="margin-none innerT"><a class="btn btn-primary btn-lg">Very long and big button</a></p>
                    </div>
                </div>
            </div>
            <!-- // Content END -->
            <div class="clearfix"></div>
            <!-- // Sidebar menu & content wrapper END -->

            <?php $this->load->view('includes/footer'); ?>

        </div>
        <!-- // Main Container Fluid END -->
    </body>
</html>