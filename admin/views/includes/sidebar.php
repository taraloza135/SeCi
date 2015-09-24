<?php
$ssControllerName = $this->router->fetch_class();
$ssActionName = $this->router->fetch_method();
?>

<div id="menu" class="hidden-print hidden-xs">
    <div id="sidebar-fusion-wrapper">

        <ul class="menu list-unstyled" style="top:70px;">
            <li class="" id="dashboard">
                <a href="<?php echo site_url('dashboard'); ?>" class="index">
                    <i class="fa fa-home"></i>
                    <span><?php echo $this->lang->line('dashboard'); ?></span>
                </a>
            </li>
            
            <li class="" id="logout">
                <a href="<?php echo site_url('logout'); ?>" class="index">
                    <i class="fa fa-check"></i>
                    <span><?php echo $this->lang->line('logout'); ?></span>
                </a>
            </li>
        </ul>
    </div>
</div>

<script type="text/javascript" >
    $(document).ready(function ($) {
        var activeMenu = "<?php echo ((strtolower($this->uri->segment(1)) == strtolower($ssControllerName)) ? strtolower($ssControllerName) : ''); ?>";
        if (activeMenu)
            $("#" + activeMenu).addClass('active');
    });
</script>