<?php
$ssHasErrors = (validation_errors() != '') ? 'has-error' : '';
?>
<div class="row innerT inner-2x">
    <div class="col-md-4 col-md-offset-4 innerT inner-2x">
        <div class="innerT inner-2x">
            <div class="widget innerLR innerB margin-none">
                <!--<h3 class="innerTB text-center"><?php echo $this->lang->line('opt_verification'); ?></h3>-->
                <div class="lock-container">
                    <div class=" text-center">
                        <div class="form-group <?php echo $ssHasErrors; ?>">
                            <div class="innerAll ">
                                <?php echo form_open(site_url('user/verification')); ?>

                                <?php
                                $asUserOTP = array(
                                    'name' => 'otp',
                                    'id' => 'otp',
                                    'maxlength' => '100',
                                    'maxlength' => '100',
                                    'size' => '50',
                                    'class' => "form-control text-center bg-gray",
                                    'placeholder' => $this->lang->line('enter_otp')
                                );

                                echo form_input($asUserOTP);
                                echo form_error('otp');
                                ?>
                            </div>
                        </div>
                        <div class="innerT half">

                            <?php
                            $asSubmitInput = array(
                                'name' => 'submit',
                                'id' => 'submit',
                                'value' => $this->lang->line('login'),
                                'class' => "btn btn-primary"
                            );

                            $asSubmitInput = array(
                                'name' => 'submit',
                                'id' => 'submit',
                                'value' => $this->lang->line('verify'),
                                'class' => "btn btn-primary"
                            );
                            echo form_submit($asSubmitInput);

                            echo form_close()
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- CREATE TASK MODAL -->
<!-- Modal -->
<div class="modal fade" id="modal-task">

    <div class="modal-dialog">
        <div class="modal-content bg-white">
            <!-- Modal body -->
            <div class="modal-body padding-none ">

                <form class="form-horizontal " role="form">

                    <div class="innerLR innerT">
                        <div class="form-group">
                            <label for="to" class="col-sm-2 control-label">Task:</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="to">
                                    <div class="input-group-btn">
                                        <button type="button" data-toggle="collapse" data-target="#cc" class="btn btn-default">Schedule <span class="caret"></span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="cc" class="collapse">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Starts:</label>
                                <div class="col-sm-10">
                                    <div class="input-group date datepicker2">
                                        <input class="form-control" type="text" value="14 February 2013" />
                                        <span class="input-group-addon"><i class="fa fa-th"></i></span>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Ends:</label>
                                <div class="col-sm-10">
                                    <div class="input-group date datepicker2">
                                        <input class="form-control" type="text" value="14 February 2013" />
                                        <span class="input-group-addon"><i class="fa fa-th"></i></span>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Milestone:</label>
                            <div class="col-sm-6">
                                <select class="selectpicker">
                                    <option>HTML Validation</option>
                                    <option>User Interface Design</option>
                                    <option>Update Bootstrap 3.2</option>
                                </select>
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Project:</label>
                            <div class="col-sm-6">
                                <select class="selectpicker">
                                    <option>Project #1</option>
                                    <option>Project #2</option>
                                    <option>Project #3</option>
                                </select>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>


                    <div class="innerAll bg-white">
                        <textarea class="notebook border-none form-control padding-none" rows="4" placeholder="Task description..."></textarea>
                        <div class="clearfix"></div>
                    </div>
                </form>

            </div>
            <!-- // Modal body END -->

            <div class="innerLR innerB text-right">
                <a href="" class="btn btn-default"><i class="fa fa-times"></i> Cancel</a>
                <a href="" class="btn btn-primary"><i class="fa  fa-check"></i> Submit</a>
            </div>

        </div>
    </div>

</div>
<!-- // Modal END -->
<!-- // END MODAL -->