

<div class="widget widget-heading-simple widget-body-gray" >

    <!-- Widget heading -->
    <div class="widget-head" id="search_widget" style="cursor: pointer;">
        <h4 class="heading">Search Fields  <span class="right"><i class="fa fa-fw icon-medical-symbol-fill"></i></span></h4>
       
    </div>
    <!-- // Widget heading END -->

    <div class="widget-body" style="display:none;" id="widget-body">
        <div class="row">
            <?php echo $ssSearchFields; ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $("#search_widget").click(function() {
        $("#widget-body").toggle();
    });
</script>