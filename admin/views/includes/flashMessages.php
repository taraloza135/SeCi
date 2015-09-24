<?php if ($this->session->flashdata('success')): ?>
    <div class="msg">
        <div class="alert alert-success">
            <button data-dismiss="alert" class="close" type="button">×</button>
            <strong><i class="icon24 i-checkmark-circle"></i> <?php echo $this->session->flashdata('success'); ?></strong> 
        </div>
    </div>

<?php endif; ?>    
<?php if ($this->session->flashdata('error')): ?>
    <div class="msg">
        <div class="alert alert-danger">
            <button data-dismiss="alert" class="close" type="button">×</button>
            <strong><i class="icon24 i-checkmark-circle"></i> <?php echo $this->session->flashdata('error'); ?></strong> 
        </div>
    </div>
<?php endif; ?>
<?php if ($this->session->flashdata('warning')): ?>
    <div class="msg">
        <div class="alert alert-warning">
            <button data-dismiss="alert" class="close" type="button">×</button>
            <strong><i class="icon24 i-checkmark-circle"></i> <?php echo $this->session->flashdata('warning'); ?></strong> 
        </div>
    </div>
<?php endif; ?>
<?php if ($this->session->flashdata('info')): ?>
    <div class="msg">
        <div class="alert alert-info">
            <button data-dismiss="alert" class="close" type="button">×</button>
            <strong><i class="icon24 i-checkmark-circle"></i> <?php echo $this->session->flashdata('info'); ?></strong> 
        </div>
    </div>
<?php endif; ?>