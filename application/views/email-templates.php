<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= !empty($this->lang->line('label_email_templates')) ? $this->lang->line('label_email_templates') : 'Email-Templates'; ?> &mdash; <?= get_compnay_title(); ?></title>
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
                        <h1> <?= !empty($this->lang->line('label_email_templates')) ? $this->lang->line('label_email_templates') : 'Email-Templates'; ?></h1>
                    </div>
                    <div class="card card-primary">
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12">
                                <div class="card-body">
                                    <?php
                                    $type = ['contact_us', 'projects_deadline_reminder', 'tasks_deadline_reminder', 'forgot_password', 'reset_password', 'project_create', 'project_edit', 'project_assigned', 'task_create', 'task_edit', 'task_assigned', 'added_new_workspace', 'added_company'];
                                    ?>
                                    <form action="<?= base_url('email_templates/create') ?>" id="create_email_templates_form">
                                        <input type="hidden" name="update_type_id" id="update_type_id">
                                        <div class="form-group">
                                            <label><?= !empty($this->lang->line('label_email_templates_type')) ? $this->lang->line('label_email_templates_type') : 'Email templates type' ?></label>
                                            <span class="asterisk">*</span>
                                            <select name="type" class="form-control type">
                                                <option value=" "><?= !empty($this->lang->line('label_select_type')) ? $this->lang->line('label_select_type') : 'Select Types'; ?></option>
                                                <?php foreach ($type as $row) : ?>
                                                    <option value="<?= $row ?>" <?= (isset($fetched_data[0]['id']) &&  $fetched_data[0]['type'] == $row) ? "Selected" : "" ?>><?= ucwords(str_replace('_', ' ', $row)) ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label><?= !empty($this->lang->line('label_subject')) ? $this->lang->line('label_subject') : 'Subject' ?></label>
                                            <span class="asterisk">*</span>
                                            <input id="subject" type="text" class="form-control" name="subject" autofocus placeholder=<?= !empty($this->lang->line('label_subject')) ? $this->lang->line('label_subject') : 'Subject' ?>>
                                        </div>

                                        <div class="form-group">
                                            <label><?= !empty($this->lang->line('label_message')) ? $this->lang->line('label_message') : 'Message' ?></label>
                                            <span class="asterisk">*</span>
                                            <textarea name="message" id="email_message" class="form-control" placeholder="<?= !empty($this->lang->line('label_type_your_message')) ? $this->lang->line('label_type_your_message') : 'Type your message' ?>" data-height="150"></textarea>
                                        </div>
                                        <div class="form-group row contact_us <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'contact_us') ? '' : 'd-none' ?>">
                                            <label for="type" class="ol-form-label"></label>
                                            <div class="form-group col-md-12">
                                                <?php
                                                $hashtag = ['{full_name},{email},{mobile_no},{message}'];
                                                foreach ($hashtag as $row) { ?>
                                                    <div class="hashtag"><?= $row ?></div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="form-group row projects_deadline_reminder <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'projects_deadline_reminder') ? '' : 'd-none' ?>">
                                            <label for="type" class="ol-form-label"></label>
                                            <div class="form-group col-md-12">
                                                <?php
                                                $hashtag = ['{first_name},{last_name},{project_title},{project_id},{get_compnay_title}'];
                                                foreach ($hashtag as $row) { ?>
                                                    <div class="hashtag"><?= $row ?></div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="form-group row tasks_deadline_reminder <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'tasks_deadline_reminder') ? '' : 'd-none' ?>">
                                            <label for="type" class="ol-form-label"></label>
                                            <div class="form-group col-md-12">
                                                <?php
                                                $hashtag = ['{first_name},{last_name},{task_title},{task_id},{get_compnay_title}'];
                                                foreach ($hashtag as $row) { ?>
                                                    <div class="hashtag"><?= $row ?></div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="form-group row forgot_password  <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'forgot_password ') ? '' : 'd-none' ?>">
                                            <label for="type" class="ol-form-label"></label>
                                            <div class="form-group col-md-12">
                                                <?php
                                                $hashtag = ['{user_name},{full_link},{company_title}'];
                                                foreach ($hashtag as $row) { ?>
                                                    <div class="hashtag"><?= $row ?></div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="form-group row reset_password  <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'reset_password ') ? '' : 'd-none' ?>">
                                            <label for="type" class="ol-form-label"></label>
                                            <div class="form-group col-md-12">
                                                <?php
                                                $hashtag = ['{user_name},{password},{company_title}'];
                                                foreach ($hashtag as $row) { ?>
                                                    <div class="hashtag"><?= $row ?></div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="form-group row project_create  <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'project_create ') ? '' : 'd-none' ?>">
                                            <label for="type" class="ol-form-label"></label>
                                            <div class="form-group col-md-12">
                                                <?php
                                                $hashtag = ['{first_name},{last_name},{project_title},{project_id}'];
                                                foreach ($hashtag as $row) { ?>
                                                    <div class="hashtag"><?= $row ?></div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="form-group row project_edit  <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'project_edit ') ? '' : 'd-none' ?>">
                                            <label for="type" class="ol-form-label"></label>
                                            <div class="form-group col-md-12">
                                                <?php
                                                $hashtag = ['{first_name},{last_name},{project_title},{project_id}'];
                                                foreach ($hashtag as $row) { ?>
                                                    <div class="hashtag"><?= $row ?></div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="form-group row project_assigned  <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'project_assigned ') ? '' : 'd-none' ?>">
                                            <label for="type" class="ol-form-label"></label>
                                            <div class="form-group col-md-12">
                                                <?php
                                                $hashtag = ['{first_name},{last_name},{type},{project_title},{project_id}'];
                                                foreach ($hashtag as $row) { ?>
                                                    <div class="hashtag"><?= $row ?></div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="form-group row task_create  <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'task_create ') ? '' : 'd-none' ?>">
                                            <label for="type" class="ol-form-label"></label>
                                            <div class="form-group col-md-12">
                                                <?php
                                                $hashtag = ['{first_name},{last_name},{task_title},{task_id},{project_title},{project_id}'];
                                                foreach ($hashtag as $row) { ?>
                                                    <div class="hashtag"><?= $row ?></div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="form-group row task_edit  <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'task_edit ') ? '' : 'd-none' ?>">
                                            <label for="type" class="ol-form-label"></label>
                                            <div class="form-group col-md-12">
                                                <?php
                                                $hashtag = ['{first_name},{last_name},{task_title},{task_id},{project_title},{project_id}'];
                                                foreach ($hashtag as $row) { ?>
                                                    <div class="hashtag"><?= $row ?></div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="form-group row task_assigned  <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'task_assigned ') ? '' : 'd-none' ?>">
                                            <label for="type" class="ol-form-label"></label>
                                            <div class="form-group col-md-12">
                                                <?php
                                                $hashtag = ['{first_name},{last_name},{type},{task_title},{task_id}'];
                                                foreach ($hashtag as $row) { ?>
                                                    <div class="hashtag"><?= $row ?></div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="form-group row added_new_workspace  <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'added_new_workspace ') ? '' : 'd-none' ?>">
                                            <label for="type" class="ol-form-label"></label>
                                            <div class="form-group col-md-12">
                                                <?php
                                                $hashtag = ['{workspace},{get_compnay_title}'];
                                                foreach ($hashtag as $row) { ?>
                                                    <div class="hashtag"><?= $row ?></div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="form-group row added_company  <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'added_company ') ? '' : 'd-none' ?>">
                                            <label for="type" class="ol-form-label"></label>
                                            <div class="form-group col-md-12">
                                                <?php
                                                $hashtag = ['{email},{password},{get_compnay_title}'];
                                                foreach ($hashtag as $row) { ?>
                                                    <div class="hashtag"><?= $row ?></div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="form-group text-right">
                                            <button class="btn btn-primary" id="submit_button">Submit</button>
                                            <div id="result" class="disp-none"></div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="section-body">
                            <div class="row">
                                <div class='col-md-12'>
                                    <div class="card">
                                        <div class="card-body">
                                            <table class='table-striped' id='email_templates' data-toggle="table" data-url="<?= base_url($role . '/Email_templates/get_mail_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                      "fileName": "email-templates-list"
                    }' data-query-params="queryParams">
                                                <thead>
                                                    <tr>
                                                        <th data-field="id" data-sortable="true"><?= !empty($this->lang->line('label_id')) ? $this->lang->line('label_id') : 'ID'; ?></th>
                                                        <th data-field="subject" data-sortable="true"><?= !empty($this->lang->line('label_subject')) ? $this->lang->line('label_subject') : 'Subject'; ?></th>
                                                        <th data-field="message" data-sortable="true"><?= !empty($this->lang->line('label_message')) ? $this->lang->line('label_message') : 'Message'; ?></th>
                                                        <th data-field="type" data-sortable="true"><?= !empty($this->lang->line('label_type')) ? $this->lang->line('label_type') : 'Status'; ?></th>
                                                        <th data-field="date_sent" data-sortable="false"><?= !empty($this->lang->line('label_date_sent')) ? $this->lang->line('label_date_sent') : 'Date Sent'; ?></th>
                                                        <?php if ($this->ion_auth->is_admin()) { ?>
                                                            <th data-field="action" data-sortable="false"><?= !empty($this->lang->line('label_action')) ? $this->lang->line('label_action') : 'Action'; ?></th>
                                                        <?php } ?>

                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-edit-email-templates"></div>
                        <form action="<?= base_url('email_templates/edit'); ?>" method="post" class="modal-part" id="modal-edit-email-templates-part">
                            <?php
                            $type = ['contact_us', 'projects_deadline_reminder', 'tasks_deadline_reminder', 'forgot_password', 'reset_password', 'project_create', 'project_edit', 'project_assigned', 'task_create', 'task_edit', 'task_assigned', 'added_new_workspace', 'added_company'];
                            ?>
                            <div class="form-group">
                                <label><?= !empty($this->lang->line('label_email_templates_type')) ? $this->lang->line('label_email_templates_type') : 'Email templates type' ?></label>
                                <input type="hidden" name="update_id" id="update_id">
                                <span class="asterisk">*</span>
                                <select id="update_type" name="type" class="form-control">
                                    <option value=" "><?= !empty($this->lang->line('label_select_type')) ? $this->lang->line('label_select_type') : 'Select Types'; ?></option>
                                    <?php foreach ($type as $row) { ?>
                                        <option value="<?= $row ?>" <?= (isset($fetched_data[0]['id']) &&  $fetched_data[0]['type'] == $row) ? "Selected" : "" ?>><?= ucwords(str_replace('_', ' ', $row)) ?></option>
                                    <?php
                                    } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><?= !empty($this->lang->line('label_subject')) ? $this->lang->line('label_subject') : 'Subject' ?></label>
                                <span class="asterisk">*</span>
                                <input id="update_subject" type="text" class="form-control" name="subject" autofocus placeholder=<?= !empty($this->lang->line('label_subject')) ? $this->lang->line('label_subject') : 'Subject' ?>>
                            </div>
                            <div class="form-group">
                                <label><?= !empty($this->lang->line('label_message')) ? $this->lang->line('label_message') : 'Message' ?></label>
                                <span class="asterisk">*</span>
                                <textarea name="message" id="update_message" class="form-control" placeholder="<?= !empty($this->lang->line('label_type_your_message')) ? $this->lang->line('label_type_your_message') : 'Type your message' ?>" data-height="150"></textarea>
                            </div>
                            <div id="update_type" class="form-group row contact_us <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'contact_us') ? '' : 'd-none' ?>">
                                <label for="type" class="ol-form-label"></label>
                                <div class="form-group col-md-12">
                                    <?php
                                    $hashtag = ['{full_name},{email},{mobile_no},{message}'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="hashtag"><?= $row ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div id="update_type" class="form-group row projects_deadline_reminder <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'projects_deadline_reminder') ? '' : 'd-none' ?>">
                                <label for="type" class="ol-form-label"></label>
                                <div class="form-group col-md-12">
                                    <?php
                                    $hashtag = ['{first_name},{last_name},{project_title},{project_id},{get_compnay_title}'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="hashtag"><?= $row ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div id="update_type" class="form-group row tasks_deadline_reminder <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'tasks_deadline_reminder') ? '' : 'd-none' ?>">
                                <label for="type" class="ol-form-label"></label>
                                <div class="form-group col-md-12">
                                    <?php
                                    $hashtag = ['{first_name},{last_name},{task_title},{task_id},{get_compnay_title}'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="hashtag"><?= $row ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div id="update_type" class="form-group row forgot_password  <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'forgot_password ') ? '' : 'd-none' ?>">
                                <label for="type" class="ol-form-label"></label>
                                <div class="form-group col-md-12">
                                    <?php
                                    $hashtag = ['{user_name},{full_link},{company_title}'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="hashtag"><?= $row ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div id="update_type" class="form-group row reset_password  <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'reset_password ') ? '' : 'd-none' ?>">
                                <label for="type" class="ol-form-label"></label>
                                <div class="form-group col-md-12">
                                    <?php
                                    $hashtag = ['{user_name},{password},{company_title}'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="hashtag"><?= $row ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div id="update_type" class="form-group row project_create  <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'project_create ') ? '' : 'd-none' ?>">
                                <label for="type" class="ol-form-label"></label>
                                <div class="form-group col-md-12">
                                    <?php
                                    $hashtag = ['{first_name},{last_name},{project_title},{project_id}'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="hashtag"><?= $row ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div id="update_type" class="form-group row project_edit  <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'project_edit ') ? '' : 'd-none' ?>">
                                <label for="type" class="ol-form-label"></label>
                                <div class="form-group col-md-12">
                                    <?php
                                    $hashtag = ['{first_name},{last_name},{project_title},{project_id}'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="hashtag"><?= $row ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div id="update_type" class="form-group row project_assigned  <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'project_assigned ') ? '' : 'd-none' ?>">
                                <label for="type" class="ol-form-label"></label>
                                <div class="form-group col-md-12">
                                    <?php
                                    $hashtag = ['{first_name},{last_name},{type},{project_title},{project_id}'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="hashtag"><?= $row ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div id="update_type" class="form-group row task_create  <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'task_create ') ? '' : 'd-none' ?>">
                                <label for="type" class="ol-form-label"></label>
                                <div class="form-group col-md-12">
                                    <?php
                                    $hashtag = ['{first_name},{last_name},{task_title},{task_id},{project_title},{project_id}'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="hashtag"><?= $row ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div id="update_type" class="form-group row task_edit  <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'task_edit ') ? '' : 'd-none' ?>">
                                <label for="type" class="ol-form-label"></label>
                                <div class="form-group col-md-12">
                                    <?php
                                    $hashtag = ['{first_name},{last_name},{task_title},{task_id},{project_title},{project_id}'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="hashtag"><?= $row ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div id="update_type" class="form-group row task_assigned  <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'task_assigned ') ? '' : 'd-none' ?>">
                                <label for="type" class="ol-form-label"></label>
                                <div class="form-group col-md-12">
                                    <?php
                                    $hashtag = ['{first_name},{last_name},{type},{task_title},{task_id}'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="hashtag"><?= $row ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group row added_new_workspace  <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'added_new_workspace ') ? '' : 'd-none' ?>" id="update_type">
                                <label for="type" class="ol-form-label"></label>
                                <div class="form-group col-md-12">
                                    <?php
                                    $hashtag = ['{workspace},{get_compnay_title}'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="hashtag"><?= $row ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group row added_company  <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'added_company ') ? '' : 'd-none' ?>" id="update_type">
                                <label for="type" class="ol-form-label"></label>
                                <div class="form-group col-md-12">
                                    <?php
                                    $hashtag = ['{email},{password},{get_compnay_title}'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="hashtag"><?= $row ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
            <?php
            require_once(APPPATH . '/views' . '/' . $role . '/include-footer.php');
            ?>
        </div>
    </div>

    <?php
    require_once(APPPATH . 'views/include-js.php');
    ?>

</body>
<script src="<?= base_url('assets/js/page/components-email-templates.js'); ?>"></script>

</html>