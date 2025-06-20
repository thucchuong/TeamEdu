<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title><?= !empty($this->lang->line('label_change_password')) ? $this->lang->line('label_change_password') : 'Change Password'; ?> &mdash; <?= get_compnay_title(); ?></title>
  <?php include('include-css.php'); ?>
</head>

<body>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="login-brand">
            <img src="<?= base_url('assets/icons/'.(!empty(get_full_logo())?get_full_logo():'logo.png')); ?>" alt="logo" width="350">
            </div>

            <div class="card card-primary">
              <div class="card-header"><h4><?= !empty($this->lang->line('label_change_password')) ? $this->lang->line('label_change_password') : 'Change Password'; ?></h4></div>

              <div class="card-body">
                <form action=<?= base_url("forgot_password/recover_password") ?> id="reset_password_form">
                  <input type="hidden" id="code" name="code" value="<?= $code ?>" />
                  <div class="form-group">
                    <label for="email">New Password</label>
                    <input type="password" name="password" id="psw" class="form-control" placeholder="New password">
                    <div class="invalid-feedback">
                    <?= !empty($this->lang->line('label_enter_new_password')) ? $this->lang->line('label_enter_new_password') : 'Enter new password.'; ?>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="d-block">
                    	<label for="password" class="control-label"><?= !empty($this->lang->line('label_confirm_password')) ? $this->lang->line('label_confirm_password') : 'Confirm Password'; ?></label>
                    </div>

                    <input type="password" name="confirm_password" id="confirm_psw" class="form-control" placeholder="Confirm new password">
                    <div class="invalid-feedback">
                    <?= !empty($this->lang->line('label_confirm_new_password')) ? $this->lang->line('label_confirm_new_password') : 'Confirm New Password'; ?>
                  </div>

                  <?php echo form_hidden($user_id); ?>
                  <?php echo form_hidden($csrf); ?>

                  <div id="login-result" class="alert alert-info" style="display: none;">
                  </div>
                  <div class="form-group">
                  <button class='btn btn-primary btn-lg btn-block submit_btn' tabindex="4"><?= !empty($this->lang->line('label_submit')) ? $this->lang->line('label_submit') : 'Submit'; ?>
                    </button>
                  </div>
                </form>
              </div>
            </div>
            <div class="simple-footer">
              Copyright &copy; <?= get_compnay_title(); ?> <?=date('Y');?>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div> 

  <?php include('include-js.php'); ?>
  <script src="<?= base_url('assets/js/page/components-reset-password.js'); ?>"></script>

</body>

</html>