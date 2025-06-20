<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= !empty($this->lang->line('label_system_regsitrations')) ? $this->lang->line('label_system_regsitrations') : 'System Regsitration'; ?> &mdash; <?= get_compnay_title(); ?></title>
    <?php include('include-css.php'); ?>
</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <?php include('include-header.php'); ?>
            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1><?= !empty($this->lang->line('label_system_regsitrations')) ? $this->lang->line('label_system_regsitrations') : 'System Regsitration'; ?></h1>
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class='col-md-12'>
                                <div class="card">
                                    <div class="card-body">
                                        <form class="form-horizontal form-submit-event" action="<?= base_url('purchase_code/validator'); ?>" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name();
                                                                        ?>" class="form-control" value="<?= $this->security->get_csrf_hash(); ?>">
                                            <div class="form-group row">
                                                <label for="purchase_code" class="col-md-3 col-form-label">TaskHub Purchase Code<span class='text-danger text-sm'>*</span></label>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" id="purchase_code" placeholder="Enter your purchase code here" name="purchase_code" value="">
                                                </div>
                                            </div>

                                            <div class="form-group mt-3">
                                                <button type="reset" class="btn btn-warning font-weight-bold btn-lg">Reset</button>
                                                <button type="submit" class="btn btn-primary font-weight-bold btn-lg" id="submit_btn">Register Now</button>
                                            </div>
                                        </form>
                                        <?php $doctor_brown = get_system_settings('doctor_brown');
                                       
                                        if (!empty($doctor_brown) && isset($doctor_brown['code_bravo'])) { ?>
                                            <div class="alert alert-success">
                                                Your system is successfully registered with us!
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <?php include('include-footer.php'); ?>
        </div>
    </div>
    <?php include('include-js.php'); ?>
    <!-- Page Specific JS File -->

</body>

</html>