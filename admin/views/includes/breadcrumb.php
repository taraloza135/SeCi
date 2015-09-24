<ul class="breadcrumb">
    <li><?php echo $this->lang->line('you_are_here'); ?> :</li>
    <li>
        <a href="<?php echo site_url('dashboard');?>" class="tip" title="back to dashboard">
            <span class="icon16 icomoon-icon-screen-2"></span>
        </a> 
        <span class="divider">
            <span class="icon16 icomoon-icon-arrow-right-3"></span>
        </span>
    </li>
    <?php
    if (!empty($asBreadcrumb) && sizeof($asBreadcrumb) > 0):
        foreach ($asBreadcrumb as $ssTitle => $ssUrl):
            ?>
            <?php if ($ssUrl == ''): ?>
                <li class="active"><?php echo $ssTitle; ?></li>
                <?php else : ?>
                <li><a href="<?php echo $ssUrl; ?> "><?php echo $ssTitle; ?></a></li>
            <?php endif; ?>
            <?php
        endforeach;
    endif;
    ?>
</ul>