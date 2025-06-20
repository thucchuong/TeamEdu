<!DOCTYPE html>
<html lang="en">
<?php $data = get_system_settings('general'); ?>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= !empty($this->lang->line('label_settings')) ? $this->lang->line('label_settings') : 'Settings'; ?> &mdash; <?= get_compnay_title(); ?></title>
    <?php include('include-css.php'); ?>

    <style>
        body {
            --primary-color: <?= $primary_color ?>;
            --secondary-color: <?= $secondary_color ?>;
        }
    </style>
</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">

            <?php include('include-header.php'); ?>

            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1><?= !empty($this->lang->line('label_settings')) ? $this->lang->line('label_settings') : 'Settings'; ?> (<?= !empty($this->lang->line('label_version')) ? $this->lang->line('label_version') : 'Version'; ?> <?= get_system_version(); ?>) </h1> <a href="<?= base_url('updater'); ?>"> <?= !empty($this->lang->line('label_update')) ? $this->lang->line('label_update') : 'Update'; ?></a>
                    </div>

                    <div class="section-body">

                        <div id="output-status"></div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h4><?= !empty($this->lang->line('label_jump_to')) ? $this->lang->line('label_jump_to') : 'Jump To'; ?></h4>
                                    </div>
                                    <div class="card-body">
                                        <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="general-tab4" data-toggle="tab" href="#general-settings" role="tab" aria-controls="general" aria-selected="true"><?= !empty($this->lang->line('label_general')) ? $this->lang->line('label_general') : 'General'; ?></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="email-tab4" data-toggle="tab" href="#email-settings" role="tab" aria-controls="email" aria-selected="false"><?= !empty($this->lang->line('label_email')) ? $this->lang->line('label_email') : 'Email'; ?></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="system-tab4" data-toggle="tab" href="#system-settings" role="tab" aria-controls="system" aria-selected="false"><?= !empty($this->lang->line('label_system')) ? $this->lang->line('label_system') : 'System'; ?></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="system-tab4" data-toggle="tab" href="#crone-jobs" role="tab" aria-controls="cronw" aria-selected="false"><?= !empty($this->lang->line('label_crone_jobs')) ? $this->lang->line('label_crone_jobs') : 'Crone jobs'; ?></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="tab-content no-padding" id="myTab2Content">


                                    <div class="tab-pane fade show active" id="general-settings" role="tabpanel" aria-labelledby="general-tab4">
                                        <form action="<?= base_url('settings/save_settings'); ?>" method="POST" id="general-setting-form"   ="off">
                                            <div class="card" id="general-settings-card">
                                                <div class="card-header">
                                                    <h4><?= !empty($this->lang->line('label_general_settings')) ? $this->lang->line('label_general_settings') : 'General Settings'; ?></h4>
                                                </div>

                                                <div class="card-body">
                                                    <p class="text-muted"><?= !empty($this->lang->line('label_general_settings_intro')) ? $this->lang->line('label_general_settings_intro') : 'General settings such as, company title, company logo and so on.'; ?></p>

                                                    <div class="form-group row align-items-center">
                                                        <label for="company_title" class="form-control-label col-sm-3 text-md-right"><?= !empty($this->lang->line('label_company_title')) ? $this->lang->line('label_company_title') : 'Company Title'; ?></label>
                                                        <div class="col-sm-6 col-md-9">

                                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name();
                                                                                        ?>" class="form-control" value="<?= $this->security->get_csrf_hash(); ?>">

                                                            <input type="hidden" name="setting_type" class="form-control" value="general">

                                                            <input type="text" name="company_title" class="form-control" id="company_title" value="<?= !empty($data['company_title']) ? $data['company_title'] : '' ?>" required>

                                                        </div>
                                                    </div>

                                                    <div class="form-group row align-items-center">
                                                        <label class="form-control-label col-sm-3 text-md-right"><?= !empty($this->lang->line('label_full_logo')) ? $this->lang->line('label_full_logo') : 'Full Logo'; ?></label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <input type="file" class="filepond" name="full_logo" data-max-file-size="30MB" data-max-files="20" />
                                                            <input type="hidden" name="full_logo_old" class="custom-file-input" id="full_logo_old" value="<?= !empty($data['full_logo']) ? $data['full_logo'] : '' ?>">
                                                            <div class="form-text text-muted"><?= !empty($this->lang->line('label_image_size_intro')) ? $this->lang->line('label_image_size_intro') : 'The image must have a maximum size of 1MB'; ?></div>
                                                        </div>
                                                        <div class="col-md-3 col-sm-4">
                                                        </div>
                                                        <div class="col-md-4 col-sm-4">
                                                            <div class="container-fluid row image-upload-section">
                                                                <div class="col-md-9 col-sm-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image store-image-container">
                                                                    <div class='image-upload-div'>
                                                                        <img class="img-fluid mb-2" src="<?= !empty($data['full_logo']) ? base_url('assets/icons/' . $data['full_logo']) : base_url('assets/icons/logo.png'); ?>" alt="Image Not Found" />
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row align-items-center">
                                                        <label class="form-control-label col-sm-3 text-md-right"><?= !empty($this->lang->line('label_half_logo')) ? $this->lang->line('label_half_logo') : 'Half Logo'; ?></label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <input type="file" class="filepond" name="half_logo" data-max-file-size="30MB" data-max-files="20" />
                                                            <input type="hidden" name="half_logo_old" class="custom-file-input" id="half_logo_old" value="<?= !empty($data['half_logo']) ? $data['half_logo'] : ''; ?>">
                                                            <div class="form-text text-muted"><?= !empty($this->lang->line('label_image_size_intro')) ? $this->lang->line('label_image_size_intro') : 'The image must have a maximum size of 1MB'; ?></div>
                                                        </div>
                                                        <div class="col-md-3 col-sm-4">
                                                        </div>
                                                        <div class="col-md-4 col-sm-4">
                                                            <div class="container-fluid row image-upload-section">
                                                                <div class="col-md-9 col-sm-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image store-image-container">
                                                                    <div class='image-upload-div'>
                                                                        <img class="img-fluid mb-2" src="<?= !empty($data['half_logo']) ? base_url('assets/icons/' . $data['half_logo']) : base_url('assets/icons/logo.png'); ?>" alt="Image Not Found" />
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row align-items-center">
                                                        <label class="form-control-label col-sm-3 text-md-right"><?= !empty($this->lang->line('label_favicon')) ? $this->lang->line('label_favicon') : 'Favicon'; ?></label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <input type="file" class="filepond" name="favicon" data-max-file-size="30MB" data-max-files="20" />
                                                            <input type="hidden" name="favicon_old" class="custom-file-input" id="favicon_old" value="<?= !empty($data['favicon']) ? $data['favicon'] : ''; ?>">
                                                            <div class="form-text text-muted"><?= !empty($this->lang->line('label_image_size_intro')) ? $this->lang->line('label_image_size_intro') : 'The image must have a maximum size of 1MB'; ?></div>
                                                        </div>
                                                        <div class="col-md-3 col-sm-4">
                                                        </div>
                                                        <div class="col-md-4 col-sm-4">
                                                            <div class="container-fluid row image-upload-section">
                                                                <div class="col-md-9 col-sm-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image store-image-container">
                                                                    <div class='image-upload-div'>
                                                                        <img class="img-fluid mb-2" src="<?= !empty($data['favicon']) ? base_url('assets/icons/' . $data['favicon']) : base_url('assets/icons/logo.png'); ?>" alt="Image Not Found" />
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="form-control-label col-sm-3 mt-3 text-md-right"><?= !empty($this->lang->line('label_timezone')) ? $this->lang->line('label_timezone') : 'Timezone'; ?></label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <input type="hidden" id="mysql_timezone" name="mysql_timezone" value="<?php if (!empty($data['mysql_timezone'])) {
                                                                                                                                        echo $data['mysql_timezone'];
                                                                                                                                    } else {
                                                                                                                                        echo '+05:30';
                                                                                                                                    } ?>">
                                                            <select class="form-control select2" name="php_timezone" id="php_timezone">
                                                                <?php $options = getTimezoneOptions(); ?>
                                                                <?php foreach ($options as $option) { ?>
                                                                    <option value="<?= $option[2] ?>" data-gmt="<?= $option['1']; ?>" <?= (isset($data['php_timezone']) && $data['php_timezone'] == $option[2]) ? 'selected' : ''; ?>><?= $option[2] ?> - GMT <?= $option[1] ?> - <?= $option[0] ?></option>
                                                                <?php } ?>
                                                            </select>

                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="form-control-label col-sm-3 mt-3 text-md-right"><?= !empty($this->lang->line('label_system_fonts')) ? $this->lang->line('label_system_fonts') : 'System Fonts'; ?> <br><a href="#" id="modal-add-fonts"><?= !empty($this->lang->line('label_manage')) ? $this->lang->line('label_manage') : 'Manage'; ?></a></label>

                                                        <div class="col-sm-6 col-md-9">

                                                            <select class="form-control select2" name="system_fonts" id="system_fonts">
                                                                <option value="default">Default</option>
                                                                <?php
                                                                $my_system_fonts = get_system_fonts();
                                                                $my_fonts = json_decode($my_fonts);
                                                                if (!empty($my_fonts) && is_array($my_fonts)) {
                                                                    foreach ($my_fonts as $my_font) {
                                                                        if (!empty($my_font->id) && !empty($my_font->font_cdn) && !empty($my_font->font_name) && !empty($my_font->font_family) && !empty($my_font->class)) { ?>
                                                                            <option value="<?= $my_font->id ?>" <?= ($my_system_fonts != 'default' && $my_system_fonts->id == $my_font->id) ? 'selected' : '' ?>><?= $my_font->font_name ?></option>
                                                                <?php }
                                                                    }
                                                                } ?>
                                                            </select>
                                                        </div>
                                                    </div>


                                                    <div class="form-group row align-items-center">
                                                        <label for="currency_full_form" class="form-control-label col-md-3 text-md-right"><?= !empty($this->lang->line('currency_full_form')) ? $this->lang->line('currency_full_form') : 'Currency Full Form'; ?></label>
                                                        <div class="col-md-3">

                                                            <input type="text" name="currency_full_form" class="form-control" id="currency_full_form" value="<?= !empty($data['currency_full_form']) ? $data['currency_full_form'] : '' ?>" required>

                                                        </div>
                                                        <label for="currency_symbol" class="form-control-label col-md-3 text-md-right"><?= !empty($this->lang->line('label_currency_symbol')) ? $this->lang->line('label_currency_symbol') : 'Currency Symbol'; ?></label>
                                                        <div class="col-md-3">

                                                            <input type="text" name="currency_symbol" class="form-control" id="currency_symbol" value="<?= !empty($data['currency_symbol']) ? $data['currency_symbol'] : '' ?>" required>

                                                        </div>

                                                        <label for="currency_shortcode" class="form-control-label col-md-3 text-md-right"><?= !empty($this->lang->line('label_currency_shortcode')) ? $this->lang->line('label_currency_shortcode') : 'Currency Shortcode'; ?></label>
                                                        <div class="col-md-3">

                                                            <input type="text" name="currency_shortcode" class="form-control" id="currency_shortcode" value="<?= !empty($data['currency_shortcode']) ? $data['currency_shortcode'] : '' ?>" required>

                                                        </div>
                                                    </div>

                                                    <div class="form-group row align-items-center">
                                                        <label for="footer_url" class="form-control-label col-sm-3 text-md-right"><?= !empty($this->lang->line('label_footer_url')) ? $this->lang->line('label_footer_url') : 'Footer URL'; ?></label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <input type="text" name="footer_url" class="form-control" id="footer_url" value="<?= !empty($data['footer_url']) ? $data['footer_url'] : '' ?>" required>

                                                        </div>
                                                    </div>

                                                    <!-- Header colour -->
                                                    <div class="form-group row align-items-center">
                                                        <label class="form-control-label col-md-3 text-md-right" for="primary_color"><?= labels('priamry_color', "Primary Color") ?></label>
                                                        <div class="col-md-3">
                                                            <input type="text" class="coloris form-control" name="primary_color" id="primary_color" value="<?= isset($primary_color) ? $primary_color : '' ?>" />

                                                        </div>
                                                        <label class="form-control-label col-md-3 text-md-right" for="secondary_color"><?= labels('secondary_color', "Secondary Color") ?></label>
                                                        <div class="col-md-3">

                                                            <input type="text" class="coloris form-control" name="secondary_color" id="secondary_color" value="<?= isset($secondary_color) ? $secondary_color : '' ?>" />

                                                        </div>
                                                    </div>

                                                    <div class="form-group row align-items-center">
                                                        <label class="form-control-label col-sm-3 text-md-right"></label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <div class="form-check">
                                                                <input class="form-check-input" name="hide_budget" type="checkbox" id="defaultCheck1" <?= (isset($data->hide_budget) && !empty($data['hide_budget']) && $data['hide_budget'] == 1) ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="defaultCheck1">
                                                                    <?= !empty($this->lang->line('label_hide_budget_costs_from_users?')) ? $this->lang->line('label_hide_budget_costs_from_users') : 'Hide Budget/Costs From Users?'; ?>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row d-none setting-result"></div>
                                                    <div class="card-footer bg-whitesmoke text-md-right">
                                                        <button class="btn btn-primary" id="general-save-btn"><?= !empty($this->lang->line('label_save_changes')) ? $this->lang->line('label_save_changes') : 'Save Changes'; ?></button>
                                                    </div>
                                                </div>
                                            </div>

                                        </form>

                                    </div>
                                    <?php $data = get_system_settings('email');
                                    // echo "<pre>";
                                    $dataemail = $data;
                                    ?>
                                    <div class="tab-pane fade" id="email-settings" role="tabpanel" aria-labelledby="email-tab4">

                                        <form action="<?= base_url('settings/save_settings'); ?>" id="email-setting-form" autocomplete="off">
                                            <div class="card" id="email-settings-card">
                                                <div class="card-header">
                                                    <h4><?= !empty($this->lang->line('label_email_settings')) ? $this->lang->line('label_email_settings') : 'Email Settings'; ?></h4>
                                                </div>

                                                <div class="card-body">
                                                    <p class="text-muted"><?= !empty($this->lang->line('label_email_settings_intro')) ? $this->lang->line('label_email_settings_intro') : 'Email SMTP settings, notifications and others related to email.'; ?></p>

                                                    <?php

                                                    if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                                                        $email = 'YOUR EMAIL';
                                                    } else {
                                                        if (!empty($dataemail['email'])) {
                                                            $email = $dataemail['email'];
                                                        } else {
                                                            $email = 'EMAIL ADDRESS';
                                                        }
                                                    }
                                                    $password =  (isset($dataemail['password']) && !empty($dataemail['password'])) ? $dataemail['password'] : '';
                                                    $smtp_host =  (isset($dataemail['smtp_host']) && !empty($dataemail['smtp_host'])) ? $dataemail['smtp_host'] : '';
                                                    $smtp_port =  (isset($dataemail['smtp_port']) && !empty($dataemail['smtp_port'])) ? $dataemail['smtp_port'] : '';
                                                    ?>
                                                    <div class="form-group row align-items-center">
                                                        <label for="email-set" class="form-control-label col-sm-3 text-md-right"><?= !empty($this->lang->line('label_email')) ? $this->lang->line('label_email') : 'Email'; ?></label>
                                                        <div class="col-sm-6 col-md-9">

                                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" class="form-control" value="<?= $this->security->get_csrf_hash(); ?>">

                                                            <input type="hidden" name="setting_type" class="form-control" value="email">

                                                            <input type="email" name="email" class="form-control" id="email-set" required value="<?= $email ?>">
                                                            <div class="form-text text-muted"><?= !empty($this->lang->line('label_email_intro')) ? $this->lang->line('label_email_intro') : 'This is the email address that the contact and report emails will be sent to, aswell as being the from address in signup and notification emails.'; ?></div>
                                                        </div>

                                                    </div>
                                                    <?php
                                                    if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                                                        $password = 'YOUR PASSWORD';
                                                    } else {
                                                        if (!empty($dataemail['password'])) {
                                                            $password = $dataemail['password'];
                                                        } else {
                                                            $password = 'YOUR PASSWORD';
                                                        }
                                                    }
                                                    ?>
                                                    <div class="form-group row align-items-center">
                                                        <label for="password" class="form-control-label col-sm-3 text-md-right"><?= !empty($this->lang->line('label_password')) ? $this->lang->line('label_password') : 'Password'; ?></label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <input type="text" name="password" class="form-control" id="password" required value="<?= $password ?>">
                                                            <div class="form-text text-muted"><?= !empty($this->lang->line('label_password_intro')) ? $this->lang->line('label_password_intro') : 'Password of above given email.'; ?></div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                                                        $smtp_host = 'YOUR SMTP HOST';
                                                    } else {
                                                        if (!empty($dataemail['smtp_host'])) {
                                                            $smtp_host = $dataemail['smtp_host'];
                                                        } else {
                                                            $smtp_host = 'YOUR SMTP HOST';
                                                        }
                                                    }
                                                    ?>
                                                    <div class="form-group row align-items-center">
                                                        <label for="smtp_host" class="form-control-label col-sm-3 text-md-right"><?= !empty($this->lang->line('label_smtp_host')) ? $this->lang->line('label_smtp_host') : 'SMTP Host'; ?></label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <input type="text" name="smtp_host" class="form-control" id="smtp_host" value="<?= $smtp_host ?>">
                                                            <div class="form-text text-muted">T<?= !empty($this->lang->line('label_smtp_host_intro')) ? $this->lang->line('label_smtp_host_intro') : 'This is the host address for your smtp server, this is only needed if you are using SMTP as the Email Send Type.'; ?></div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                                                        $smtp_port = 'YOUR SMTP PORT';
                                                    } else {
                                                        if (!empty($dataemail['smtp_port'])) {
                                                            $smtp_port = $dataemail['smtp_port'];
                                                        } else {
                                                            $smtp_port = 'YOUR SMTP PORT';
                                                        }
                                                    }
                                                    ?>

                                                    <div class="form-group row align-items-center">
                                                        <label for="smtp_port" class="form-control-label col-sm-3 text-md-right"><?= !empty($this->lang->line('label_smtp_port')) ? $this->lang->line('label_smtp_port') : 'SMTP Port'; ?></label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <input type="text" name="smtp_port" class="form-control" id="smtp_port" value="<?= $smtp_port ?>">
                                                            <div class="form-text text-muted"><?= !empty($this->lang->line('label_smtp_port_intro')) ? $this->lang->line('label_smtp_port_intro') : 'SMTP port this will provide your service provider.'; ?></div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="form-control-label col-sm-3 mt-3 text-md-right"><?= !empty($this->lang->line('label_email_content_type')) ? $this->lang->line('label_email_content_type') : 'Email Content Type'; ?></label>
                                                        <div class="col-sm-6 col-md-9">

                                                            <select class="form-control" name="mail_content_type" id="mail_content_type">
                                                                <?php
                                                                if (!empty($dataemail['mail_content_type'])) {

                                                                    if ($dataemail['mail_content_type'] == 'text') { ?>
                                                                        <option value="text" selected>Text</option>
                                                                        <option value="html">HTML</option>
                                                                    <?php } else { ?>
                                                                        <option value="text">Text</option>
                                                                        <option value="html" selected>HTML</option>
                                                                    <?php }
                                                                } else { ?>
                                                                    <option value="text" selected>Text</option>
                                                                    <option value="html">HTML</option>

                                                                <?php } ?>

                                                            </select>
                                                            <div class="form-text text-muted"><?= !empty($this->lang->line('label_email_content_type_intro')) ? $this->lang->line('label_email_content_type_intro') : 'Text-plain or HTML content chooser.'; ?></div>
                                                        </div>
                                                    </div>


                                                    <div class="form-group row">
                                                        <label class="form-control-label col-sm-3 mt-3 text-md-right"><?= !empty($this->lang->line('label_smtp_encryption')) ? $this->lang->line('label_smtp_encryption') : 'SMTP Encryption'; ?></label>
                                                        <div class="col-sm-6 col-md-9">

                                                            <select class="form-control" name="smtp_encryption" id="smtp_encryption">
                                                                <?php
                                                                if (!empty($dataemail['smtp_encryption'])) {

                                                                    if ($dataemail['smtp_encryption'] == 'ssl') { ?>
                                                                        <option value="off">off</option>
                                                                        <option value="ssl" selected>SSL</option>
                                                                        <option value="tls">TLS</option>
                                                                    <?php } elseif ($dataemail['smtp_encryption'] == 'tls') { ?>
                                                                        <option value="off">off</option>
                                                                        <option value="ssl">SSL</option>
                                                                        <option value="tls" selected>TLS</option>
                                                                    <?php } else {  ?>
                                                                        <option value="off" selected>off</option>
                                                                        <option value="ssl">SSL</option>
                                                                        <option value="tls">TLS</option>
                                                                    <?php   }
                                                                } else { ?>
                                                                    <option value="off" selected>off</option>
                                                                    <option value="ssl">SSL</option>
                                                                    <option value="tls">TLS</option>

                                                                <?php } ?>

                                                            </select>
                                                            <div class="form-text text-muted"><?= !empty($this->lang->line('label_smtp_encryption_intro')) ? $this->lang->line('label_smtp_encryption_intro') : 'If your e-mail service provider supported secure connections, you can choose security method on list.'; ?></div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="row d-none setting-result"></div>
                                                <div class="card-footer bg-whitesmoke text-md-right">
                                                    <button class="btn btn-primary" id="modal-add-test-mail"><?= !empty($this->lang->line('label_smtp_mail')) ? $this->lang->line('label_smtp_mail') : 'Test SMTP mail'; ?></button>
                                                    <button class="btn btn-primary" id="eamil-save-btn"><?= !empty($this->lang->line('label_save_changes')) ? $this->lang->line('label_save_changes') : 'Save Changes'; ?></button>
                                                </div>
                                            </div>
                                        </form>
                                        <?= form_open('settings/smtp_test_mail', 'id="modal-add-test-mail-part"', 'class="modal-part modal-lg"'); ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= !empty($this->lang->line('label_to')) ? $this->lang->line('label_to') : 'To' ?></label>
                                                    <div class="input-group">
                                                        <input type="email" class="form-control" placeholder=<?= !empty($this->lang->line('label_to')) ? $this->lang->line('label_to') : 'To' ?> name="email">
                                                    </div>
                                                </div>
                                            </div>
                                            <?php $errors = '';
                                            $errors = isset($errors) ? $errors : '' ?>
                                            <div class="col-md-12">
                                                <div id="error_data">
                                                    <?php $errors; ?>
                                                </div>
                                            </div>
                                        </div>
                                        </form>

                                    </div>
                                    <!-- <?php $data = get_system_settings('screenshot_set_interval');
                                            $datasystem = $data;

                                            ?>
                                    <div class="tab-pane fade" id="screenshot-settings" role="tabpanel" aria-labelledby="screenshot-tab4">

                                        <form action="<?= base_url('settings/save_settings'); ?>" id="screenshot-setting-form" autocomplete="off">
                                            <div class="card" id="screenshot-settings-card">
                                                <div class="card-header">
                                                    <h4><?= !empty($this->lang->line('label_screenshot_settings')) ? $this->lang->line('label_screenshot_settings') : 'Screenshot Settings'; ?></h4>
                                                </div>

                                                <div class="card-body">
                                                    <?php
                                                    if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                                                        $screenshot_set_interval1 = 'TIME SET INTERVAL';
                                                    } else {
                                                        if (!empty($datasystem)) {
                                                            $screenshot_set_interval1 = $datasystem;
                                                        } else {
                                                            $screenshot_set_interval1 = 'TIME SET INTERVAL';
                                                        }
                                                    }
                                                    ?>
                                                    <div class="form-group row align-items-center">
                                                        <label for="screenshot_set_interval" class="form-control-label col-sm-3 text-md-right"><?= !empty($this->lang->line('label_screenshot_set_interval')) ? $this->lang->line('label_screenshot_set_interval') : 'Screenshot set interval'; ?></label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name();
                                                                                        ?>" class="form-control" value="<?= $this->security->get_csrf_hash(); ?>">
                                                            <input type="hidden" name="setting_type" class="form-control" value="screenshot_set_interval">
                                                            <input type="text" name="data" class="form-control" id="screenshot_set_interval" value="<?= $screenshot_set_interval1 ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row d-none setting-result"></div>
                                                <div class="card-footer bg-whitesmoke text-md-right">
                                                    <button class="btn btn-primary" id="screenshot-save-btn"><?= !empty($this->lang->line('label_save_changes')) ? $this->lang->line('label_save_changes') : 'Save Changes'; ?></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <?php $data = get_system_settings('web_fcm_settings');
                                    $datasystem = $data;

                                    ?> -->

                                    <div class="tab-pane fade" id="system-settings" role="tabpanel" aria-labelledby="system-tab4">

                                        <form action="<?= base_url('settings/save_settings'); ?>" id="system-setting-form" autocomplete="off">
                                            <div class="card" id="system-settings-card">
                                                <div class="card-header">
                                                    <h4><?= !empty($this->lang->line('label_system_settings')) ? $this->lang->line('label_system_settings') : 'System Settings'; ?></h4>
                                                </div>

                                                <div class="card-body">
                                                    <p class="text-muted"><?= !empty($this->lang->line('label_system_settings_intro')) ? $this->lang->line('label_system_settings_intro') : 'FCM and other important settings.'; ?></p>
                                                    <?php
                                                    if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                                                        $fcm_server_key = 'YOUR FCM SERVER KEY';
                                                    } else {
                                                        if (!empty($datasystem['fcm_server_key'])) {
                                                            $fcm_server_key = $datasystem['fcm_server_key'];
                                                        } else {
                                                            $fcm_server_key = 'YOUR FCM SERVER KEY';
                                                        }
                                                    }
                                                    ?>
                                                    <div class="form-group row align-items-center">
                                                        <label for="fcm_server_key" class="form-control-label col-sm-3 text-md-right"><?= !empty($this->lang->line('label_fcm_server_key')) ? $this->lang->line('label_fcm_server_key') : 'FCM Server Key'; ?></label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <input type="text" name="fcm_server_key" class="form-control" id="fcm_server_key" value="<?= $fcm_server_key ?>">
                                                        </div>
                                                    </div>

                                                    <?php
                                                    if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                                                        $apiKey = 'YOUR API KEY';
                                                    } else {
                                                        if (!empty($datasystem['apiKey'])) {
                                                            $apiKey = $datasystem['apiKey'];
                                                        } else {
                                                            $apiKey = 'YOUR API KEY';
                                                        }
                                                    }
                                                    ?>
                                                    <div class="form-group row align-items-center">
                                                        <label for="apiKey" class="form-control-label col-sm-3 text-md-right">Web API Key</label>
                                                        <div class="col-sm-6 col-md-9">

                                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name();
                                                                                        ?>" class="form-control" value="<?= $this->security->get_csrf_hash(); ?>">

                                                            <input type="hidden" name="setting_type" class="form-control" value="system">

                                                            <input type="text" name="apiKey" class="form-control" id="apiKey" value="<?= $apiKey; ?>">
                                                        </div>
                                                    </div>
                                                    <?php
                                                    if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                                                        $projectId = 'YOUR PROJECT ID';
                                                    } else {
                                                        if (!empty($datasystem['projectId'])) {
                                                            $projectId = $datasystem['projectId'];
                                                        } else {
                                                            $projectId = 'YOUR PROJECT ID';
                                                        }
                                                    }
                                                    ?>
                                                    <div class="form-group row align-items-center">
                                                        <label for="projectId" class="form-control-label col-sm-3 text-md-right"><?= !empty($this->lang->line('label_project_id')) ? $this->lang->line('label_project_id') : 'Project ID'; ?></label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <input type="text" name="projectId" class="form-control" id="projectId" value="<?= $projectId; ?>">
                                                        </div>
                                                    </div>

                                                    <?php
                                                    if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                                                        $appId = 'YOUR MESSAGING APP ID';
                                                    } else {
                                                        if (!empty($datasystem['appId'])) {
                                                            $appId = $datasystem['appId'];
                                                        } else {
                                                            $appId = 'YOUR MESSAGING APP ID';
                                                        }
                                                    }
                                                    ?>
                                                    <div class="form-group row align-items-center">
                                                        <label for="appId" class="form-control-label col-sm-3 text-md-right"><?= !empty($this->lang->line('label_app_id')) ? $this->lang->line('label_app_id') : 'appId'; ?></label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <input type="text" name="appId" class="form-control" id="appId" value="<?= $appId; ?>">
                                                        </div>
                                                    </div>

                                                    <?php
                                                    if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                                                        $messagingSenderId = 'YOUR MESSAGING SENDER ID';
                                                    } else {
                                                        if (!empty($datasystem['messagingSenderId'])) {
                                                            $messagingSenderId = $datasystem['messagingSenderId'];
                                                        } else {
                                                            $messagingSenderId = 'YOUR MESSAGING SENDER ID';
                                                        }
                                                    }
                                                    ?>
                                                    <div class="form-group row align-items-center">
                                                        <label for="messagingSenderId" class="form-control-label col-sm-3 text-md-right"><?= !empty($this->lang->line('label_sender_id')) ? $this->lang->line('label_sender_id') : 'Sender ID'; ?></label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <input type="text" name="messagingSenderId" class="form-control" id="messagingSenderId" value="<?= $messagingSenderId; ?>">
                                                        </div>
                                                    </div>
                                                    <?php
                                                    if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                                                        $vapidKey = 'YOUR VAPID KEY';
                                                    } else {
                                                        if (!empty($datasystem['vapidKey'])) {
                                                            $vapidKey = $datasystem['vapidKey'];
                                                        } else {
                                                            $vapidKey = 'YOUR VAPID KEY';
                                                        }
                                                    }
                                                    ?>
                                                    <div class="form-group row align-items-center">
                                                        <label for="vapidKey" class="form-control-label col-sm-3 text-md-right">Vapid Key</label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <input type="text" name="vapidKey" class="form-control" id="vapidKey" value="<?= $vapidKey ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row d-none setting-result"></div>
                                                <div class="card-footer bg-whitesmoke text-md-right">
                                                    <button class="btn btn-primary" id="system-save-btn"><?= !empty($this->lang->line('label_save_changes')) ? $this->lang->line('label_save_changes') : 'Save Changes'; ?></button>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                    <div class="tab-pane fade" id="crone-jobs" role="tabpanel" aria-labelledby="system-tab4">

                                        <div class="card" id="">
                                            <div class="card-header">
                                                <h4><?= !empty($this->lang->line('label_crone_jobs')) ? $this->lang->line('label_crone_jobs') : 'Crone jobs'; ?></h4>
                                            </div>

                                            <div class="card-body">
                                                <p class="text-muted"><?= !empty($this->lang->line('label_crone_jobs_cpanel')) ? $this->lang->line('label_crone_jobs_cpanel') : 'Set below crone jobs on your cPanel.'; ?></p>

                                                <div class="form-group row align-items-center">
                                                    <label for="" class="form-control-label col-sm-3 text-md-right"><?= !empty($this->lang->line('label_projects_deadline')) ? $this->lang->line('label_projects_deadline') : 'Projects deadline'; ?></label>
                                                    <div class="col-sm-6 col-md-9">
                                                        <input type="text" name="" class="form-control" id="" value="<?= base_url('crone-jobs/projects-deadline-reminder') ?>" disabled>
                                                        <small><b>[>[<?= !empty($this->lang->line('label_set_once_day')) ? $this->lang->line('label_set_once_day') : 'Set it for once in a day.'; ?>]</b></small><br>
                                                        <small><b>Example : </b>[wget http://www.example.com/crone-jobs/projects-deadline-reminder]</small>
                                                    </div>

                                                </div>


                                                <div class="form-group row align-items-center">
                                                    <label for="apiKey" class="form-control-label col-sm-3 text-md-right"><?= !empty($this->lang->line('label_tasks_deadline')) ? $this->lang->line('label_tasks_deadline') : 'Tasks deadline'; ?></label>
                                                    <div class="col-sm-6 col-md-9">
                                                        <input type="text" name="" class="form-control" id="" value="<?= base_url('crone-jobs/tasks-deadline-reminder') ?>" disabled>
                                                        <small><b>[>[<?= !empty($this->lang->line('label_set_once_day')) ? $this->lang->line('label_set_once_day') : 'Set it for once in a day.'; ?>]</b></small><br>
                                                        <small><b>Example : </b>[wget http://www.example.com/crone-jobs/tasks-deadline-reminder]</small>
                                                    </div>
                                                </div>

                                                <div class="form-group row align-items-center">
                                                    <label for="apiKey" class="form-control-label col-sm-3 text-md-right"><?= !empty($this->lang->line('label_today_birthday')) ? $this->lang->line('label_today_birthday') : 'Today birthday'; ?></label>
                                                    <div class="col-sm-6 col-md-9">
                                                        <input type="text" name="" class="form-control" id="" value="<?= base_url('crone-jobs/today-birthday-reminder') ?>" disabled>
                                                        <small><b>[>[<?= !empty($this->lang->line('label_set_once_day')) ? $this->lang->line('label_set_once_day') : 'Set it for once in a day.'; ?>]</b></small><br>
                                                        <small><b>Example : </b>[wget http://www.example.com/crone-jobs/today-birthday-reminder]</small>
                                                    </div>
                                                </div>

                                                <div class="form-group row align-items-center">
                                                    <label for="apiKey" class="form-control-label col-sm-3 text-md-right"><?= !empty($this->lang->line('label_work_anniversaries')) ? $this->lang->line('label_work_anniversaries') : 'Work Anniversaries'; ?></label>
                                                    <div class="col-sm-6 col-md-9">
                                                        <input type="text" name="" class="form-control" id="" value="<?= base_url('crone-jobs/work-anniversary-reminder') ?>" disabled>
                                                        <small><b>[>[<?= !empty($this->lang->line('label_set_once_day')) ? $this->lang->line('label_set_once_day') : 'Set it for once in a day.'; ?>]</b></small><br>
                                                        <small><b>Example : </b>[wget http://www.example.com/crone-jobs/work-anniversary-reminder]</small>
                                                    </div>
                                                </div>

                                                <!-- <div class="form-group row align-items-center">
                                                    <label for="apiKey" class="form-control-label col-sm-3 text-md-right">Screenshot Remove Images</label>
                                                    <div class="col-sm-6 col-md-9">
                                                        <input type="text" name="" class="form-control" id="" value="<?= base_url('crone-jobs/remove-images') ?>" disabled>
                                                        <small><b>[>[<?= !empty($this->lang->line('label_set_once_day')) ? $this->lang->line('label_set_once_day') : 'Set it for once in a day.'; ?>]</b></small><br>
                                                        <small><b>Example : </b>[wget http://www.example.com/crone-jobs/remove-images]</small>
                                                    </div>
                                                </div> -->



                                            </div>
                                        </div>

                                    </div>


                                </div>
                                <div id="result"></div>
                            </div>

                        </div>
                    </div>
                </section>
            </div>

            <?php include('include-footer.php'); ?>

        </div>
    </div>

    <form action="<?= base_url('settings/create_fonts/'); ?>" method="post" class="modal-part" id="modal-add-font-part">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <div class="input-group">
                        <textarea type="textarea" class="form-control codeeditor" placeholder='Add Font in below given format.' name="fonts"><?php
                                                                                                                                                $file_get_contents_data = file_get_contents("assets/fonts/my-fonts.json");
                                                                                                                                                echo $file_get_contents_data;
                                                                                                                                                ?>
                    </textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <label class="form-control-label"><?= !empty($this->lang->line('label_font_format')) ? $this->lang->line('label_font_format') : 'Example Font Format : '; ?>
                    <small><a href="https://fonts.google.com/" target="_blank">Find more fonts here <i class="fas fa-external-link-alt"></i></a></small>
                </label><br>
                <pre>
[
  {
    "id": "1",
    "font_cdn": "https://fonts.googleapis.com/css?family=Roboto&display=swap",
    "font_name": "Roboto",
    "font_family": "'Roboto', sans-serif",
    "class": "roboto"
  },
  {
    "id": "2",
    "font_cdn": "https://fonts.googleapis.com/css?family=Noto+Sans&display=swap",
    "font_name": "Nato Sans",
    "font_family": "'Noto Sans', sans-serif",
    "class": "nato_sans"
  }
]

Explaination : 
    id - Give unique id to the font you add
    font_cdn - CDN or Server URL of the font
    font_name - Name of the font, you can give any name you want. Example : Noto Sans
    font_family - Font family of that font. Example : " 'Noto Sans', sans-serif "
    class - A valid class name for CSS, It should be without white spaces and not using 
            any special characters. Example : "noto_sans"
    </pre>
            </div>
        </div>
    </form>

    <?php include('include-js.php'); ?>

    <!-- Page Specific JS File -->
    <script src="<?= base_url('assets/js/page/features-setting-detail.js'); ?>"></script>

</body>

</html>