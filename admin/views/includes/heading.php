<div class="heading">

    <h3><?php echo isset($pageTitle) ? $pageTitle : 'Page Title' ;?></h3>                    

    <div class="resBtnSearch">
        <a href="#"><span class="icon16 icomoon-icon-search-3"></span></a>
    </div>
<!--
    <div class="search">

        <form id="searchform" action="#">
            <input type="text" id="tipue_search_input" class="top-search" placeholder="Search here ..." />
            <input type="submit" id="tipue_search_button" class="search-btn" value=""/>
        </form>

    </div><!-- End search -->

   <!-- <ul class="breadcrumb">
        <li>You are here:</li>
        <li>
            <a href="#" class="tip" title="back to dashboard">
                <span class="icon16 icomoon-icon-screen-2"></span>
            </a> 
            <span class="divider">
                <span class="icon16 icomoon-icon-arrow-right-3"></span>
            </span>
        </li>
        <li class="active">Dashboard</li>
    </ul>
   -->
   <?php echo $this->load->view('includes/breadcrumb');?>

</div><!-- End .heading-->