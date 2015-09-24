<?php
$ssHasErrors = (validation_errors() != '') ? 'has-error' : '';
?>
<div class="row innerT inner-2x">
    <div class="col-md-4 col-md-offset-4 innerT inner-2x">
        <div class="innerT inner-2x">
            <div class="widget innerLR innerB margin-none">
                <h3 class="innerTB text-center">Administrator Login Panel</h3>
                <div class="lock-container">
                    <div class=" text-center">
                        <a href="" > <img src="<?php echo BASE_URL . IMAGE_PATH ?>/people/100/no_image.jpg" alt="people" class=""/></a>
                        <div class="innerAll">
                            <?php echo form_open(site_url('user/login')); ?>
                            <div class="form-group <?php echo $ssHasErrors; ?>">  
                                <?php
                                $asUserNameInput = array(
                                    'name' => 'username',
                                    'id' => 'username',
                                    'maxlength' => '100',
                                    'size' => '50',
                                    'class' => "form-control text-center bg-gray",
                                    'placeholder' => $this->lang->line('enter_username'),
                                    'value' => field_value('username'),
                                );
                                echo form_input($asUserNameInput);
                                echo form_error('username');
                                ?>
                            </div>
                            <div class="form-group <?php echo $ssHasErrors; ?>">  
                                <?php
                                $asPasswordInput = array(
                                    'name' => 'password',
                                    'id' => 'password',
                                    'maxlength' => '100',
                                    'size' => '50',
                                    'class' => "form-control text-center bg-gray",
                                    'placeholder' => $this->lang->line('enter_password')
                                );
                                echo form_password($asPasswordInput);
                                echo form_error('password');
                                ?>
                            </div>
                            <div class="innerB half"></div>

                        </div>
                        <div class="innerT half">

                            <?php
                            $asSubmitInput = array(
                                'name' => 'submit',
                                'id' => 'submit',
                                'value' => $this->lang->line('login'),
                                'class' => "btn btn-primary"
                            );
                            echo form_submit($asSubmitInput);

                            echo form_close()
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-right innerT half">
                <?php echo $this->lang->line('forgot_your_password'); ?> <a href="" class=" strong margin-none"> <?php echo $this->lang->line('reset_password'); ?></a>
            </div>
        </div>
    </div>
</div>
