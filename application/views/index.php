<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Login &mdash; <?= !empty(get_compnay_title()) ? get_compnay_title() : 'Taskhub'; ?></title>
    <?php include('include-css.php'); ?>
</head>

<body>
    <div id="app">
        <section class="section">
            <div class="container mt-2">
                <div class="text-right d-none">
                    <a data-toggle="dropdown" class="nav-link dropdown-toggle" href="#"><i class="fas fa-language"></i>
                        <span>English</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-width">
                        <?php
                        $languages =  get_languages();
                        if (!empty($languages)) {
                            foreach ($languages as $lang) { ?>
                                <a href='<?= base_url("languageswitch/switchlang/" . $lang['language']); ?>' class="dropdown-item has-icon"><?= ucfirst($lang['language']); ?> </a>
                        <?php }
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <?php if (ALLOW_MODIFICATION == 0) { ?>
                        <div class="col-12 text-center">
                            <div class="alert alert-warning mb-0">
                                <b>Note:</b> If you cannot login here, please close the codecanyon frame by clicking on <b>x Remove Frame</b> button from top right corner on the page or <a href="<?= base_url('auth'); ?>" target="_blank">&gt;&gt; Click here &lt;&lt;</a>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                        <!-- <ul class="navbar-nav navbar-right"> -->
                        <!-- <li class="dropdown"> -->


                        <!-- </li> -->
                        <!-- </ul> -->
                        <div class="login-brand">
                            <img src="<?= base_url('assets/icons/' . (!empty(get_full_logo()) ? get_full_logo() : 'logo.png')); ?>" alt="logo" width="350">
                        </div>

                        <div class="card card-primary">
                            <div class="card-header">
                                <h4>Login</h4>
                            </div>
                            <div class="card-body">
                                <?= form_open('auth/login', 'id="loginpage"'); ?>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <?= form_input(['name' => 'identity', 'id' => 'identity', 'placeholder' => 'Email', 'class' => 'form-control', 'required' => 'required']) ?>
                                    <div class="invalid-feedback">
                                        Please fill in your email
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="d-block">
                                        <label for="password" class="control-label">Password</label>
                                        <div class="float-right">
                                            <a href=<?= base_url('auth/forgot') ?> class="text-small">
                                                Forgot Password?
                                            </a>
                                        </div>
                                    </div>

                                    <?= form_password(['name' => 'password', 'id' => 'password', 'placeholder' => 'Password', 'class' => 'form-control', 'required' => 'required']) ?>
                                    <div class="invalid-feedback">
                                        Please fill in your password
                                    </div>
                                </div>

                                <div id="login-result" class="alert alert-info d-none">
                                </div>
                                <div class="form-group">
                                    <button type="submit" id="loginbtn" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                        Login
                                    </button>
                                </div>
                                </form>
                                <?php if (ALLOW_MODIFICATION == 0) { ?>
                                    <div class="form-group">
                                        <button data-login_as="admin" class="btn btn-danger btn-lg btn-block login-as"> Login as Admin </button>
                                        <button data-login_as="team" class="btn btn-info btn-lg btn-block login-as"> Login as Team Member </button>
                                        <button data-login_as="client" class="btn btn-warning btn-lg btn-block login-as"> Login as Client </button>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="simple-footer">
                            Copyright &copy; <?= get_compnay_title(); ?> <?= date('Y'); ?>
                            <br>
                            Design & Developed By <a href="<?= footer_url() ?>" target="_blank"><?= get_compnay_title() ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        /* These must be here, We can not move this inside an external script, since its values are being read from PHP variables */
        csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
        csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    </script>

    <?php include('include-js.php'); ?>
    <script>
        $('.login-as').on('click', function(e) {
            e.preventDefault();
            var login_as = $(this).data('login_as');
            if (login_as == 'super-admin') {
                var identity = 'super@gmail.com';
                var password = 'super@0124';
            } else if (login_as == 'admin') {
                var identity = 'admin@gmail.com';
                var password = '12345678';
            } else if (login_as == 'team') {
                var identity = 'john.smith@example.com';
                var password = '12345678';
            } else if (login_as == 'client') {
                var identity = 'james.anderson@example.com';
                var password = '12345678';
            } else {
                var identity = '';
                var password = '';
            }
            $('#identity').val(identity);
            $('#password').val(password);
        });
    </script>
</body>

</html>