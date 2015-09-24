<?php
$asUser = $this->session->userdata(SESSION_VARIABLE);
$bBerifyAuth = false;
$bBerifyAuth = $this->session->userdata('verified_auth');
?>
<div class="navbar hidden-print main navbar-default" role="navigation">
    <div class="user-action user-action-btn-navbar pull-right">
        <button class="btn btn-sm btn-navbar btn-inverse btn-stroke hidden-lg hidden-md"><i class="fa fa-bars fa-2x"></i></button>
    </div>
    <a href="<?php echo site_url('dashboard'); ?>" class="logo">
        <!--<img src="<?php echo BASE_URL; ?>assets/images/logo/logo.png"  alt="<?php echo COMPANY_NAME; ?>" title="<?php echo COMPANY_NAME; ?>" />-->
        <b>LOGO</b>
    </a>
    <?php
    
    if ($asUser != FALSE && $bBerifyAuth != FALSE):
        ?>
        <ul class="main pull-right">
            <li class="dropdown username hidden-xs ">
                <a href="" class="dropdown-toggle" data-toggle="dropdown"><img src="<?php echo BASE_URL; ?>assets/images/people/35/no_image.jpg" class="img-circle" alt="<?php echo $this->lang->line('profile'); ?>" /> <?php echo COMPANY_NAME; ?> Administrator. <span class="caret"></span></a>
                <ul class="dropdown-menu pull-right">
                    <li><a href="<?php echo site_url('user/account'); ?>" class="glyphicons user" title="<?php echo $this->lang->line('account'); ?>"><i></i> <?php echo $this->lang->line('account'); ?></a></li>
                    <!-- <li><a href="user/messages.html" class="glyphicons envelope"><i></i>Messages</a></li>
                    <li><a href="user/projects.html" class="glyphicons settings"><i></i>Projects</a></li> -->
                    <li><a href="<?php echo site_url('user/logout'); ?>" class="glyphicons lock no-ajaxify" title="<?php echo $this->lang->line('logout'); ?>"><i></i><?php echo $this->lang->line('logout'); ?></a></li>
                </ul>
            </li>
        </ul> 
    <?php endif; ?>
</div>