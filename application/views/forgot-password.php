<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title><?= !empty($this->lang->line('label_forgot_password')) ? $this->lang->line('label_forgot_password') : 'Forgot Password'; ?> &mdash; <?= get_compnay_title(); ?></title>
  <?php include('include-css.php'); ?></head>
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
              <div class="card-header"><h4><?= !empty($this->lang->line('label_forgot_password')) ? $this->lang->line('label_forgot_password') : 'Forgot Password'; ?></h4></div>

              <div class="card-body">
                <p class="text-muted"><?= !empty($this->lang->line('label_we_will_send_a_link_to_reset_your_password')) ? $this->lang->line('label_we_will_send_a_link_to_reset_your_password') : 'We will send a link to reset your password'; ?></p>
                
                <form id="forgot_password_form">
                  <div class="form-group">
                    <label for="identity"><?= !empty($this->lang->line('label_email')) ? $this->lang->line('label_email') : 'Email'; ?></label>
                    <input id="identity" type="email" class="form-control" name="identity" tabindex="1" autofocus>
                  </div>

                  <div class="form-group">
                    <a href="#" class='btn btn-primary btn-lg btn-block' id="submit_btn"><?= !empty($this->lang->line('label_submit')) ? $this->lang->line('label_submit') : 'Submit'; ?></a>
                  </div> 
                </form>
                <div class="form-group">
                    <div class="d-none" id="result"></div>
                </div>
              </div>
            </div>
            <div class="simple-footer">
            <?= !empty($this->lang->line('label_copyright')) ? $this->lang->line('label_copyright') : 'Copyright'; ?> &copy; <?= date('Y'); ?> <div class="bullet"></div> <?= !empty($this->lang->line('label_design_developed_by')) ? $this->lang->line('label_design_developed_by') : 'Design & Developed By'; ?> <a href="<?= footer_url() ?>" target="_blank"><?= get_compnay_title() ?></a>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>


<script>
    csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
    csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    base_url = '<?php echo base_url(); ?>';
</script>

<?php include('include-js.php'); ?>
<script src="<?= base_url('assets/js/page/components-forgot-password.js'); ?>"></script>
</body>

</html>