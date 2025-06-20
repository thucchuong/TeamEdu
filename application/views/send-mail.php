<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= !empty($this->lang->line('label_send_mail')) ? $this->lang->line('label_send_mail') : 'Send mail' ?> &mdash; <?= get_compnay_title(); ?></title>
    <?php
    require_once(APPPATH . 'views/include-css.php');
    ?>

</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">

            <?php
            require_once(APPPATH . '/views/include-header.php');
            ?>

            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1><?= !empty($this->lang->line('label_send_mail')) ? $this->lang->line('label_send_mail') : 'Send mail' ?></h1>
                    </div>
                    <div class="card card-primary">
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12">
                                <div class="card-body">
                                    <form method="POST" id="send_mail_form">
                                        <div class="form-group">
                                            <label><?= !empty($this->lang->line('label_to')) ? $this->lang->line('label_to') : 'To' ?></label>
                                            <span class="asterisk">*</span>
                                            <input id="to" type="text" class="" name="to" placeholder="<?= !empty($this->lang->line('label_to')) ? $this->lang->line('label_to') : 'To' ?>">
                                        </div>
                                        <div class="form-group">
                                            <label><?= !empty($this->lang->line('label_subject')) ? $this->lang->line('label_subject') : 'Subject' ?></label>
                                            <span class="asterisk">*</span>
                                            <input id="subject" type="text" class="form-control" name="subject" autofocus placeholder="<?= !empty($this->lang->line('label_subject')) ? $this->lang->line('label_subject') : 'Subject' ?>">
                                        </div>
                                        <div class="form-group">
                                            <label><?= !empty($this->lang->line('label_message')) ? $this->lang->line('label_message') : 'Message' ?></label>
                                            <span class="asterisk">*</span>
                                            <textarea name="message" id="message" class="form-control" placeholder="<?= !empty($this->lang->line('label_type_your_message')) ? $this->lang->line('label_type_your_message') : 'Type your message' ?>" data-height="150"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label><?= !empty($this->lang->line('label_attachments')) ? $this->lang->line('label_attachments') : 'Attachments' ?></label>
                                            <div class="dropzone dz-clickable" id="mail-files-dropzone">
                                                <div class="dz-default dz-message">
                                                    <span>
                                                        <?= !empty($this->lang->line('label_drop_files_here_and_click_button_below_to_proceed')) ? $this->lang->line('label_drop_files_here_and_click_button_below_to_proceed') : 'Drop files here and click button below to proceed'; ?>
                                                    </span>
                                                </div>
                                            </div>


                                        </div>
                                        <input type="hidden" name="is_draft" id="draft" value="">
                                        <div class="form-group text-right">
                                            <a href="#" class="btn btn-round btn-lg btn-primary submit_btn" id="submit_btn" data-draft="no">
                                                <?= !empty($this->lang->line('label_send_mail')) ? $this->lang->line('label_send_mail') : 'Send mail'; ?>
                                            </a>
                                            <a href="#" class="btn btn-round btn-lg btn-warning submit_btn" id="draft_btn" data-draft="yes">
                                                <?= !empty($this->lang->line('label_save_as_draft')) ? $this->lang->line('label_save_as_draft') : 'Save as draft'; ?>
                                            </a>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class='col-md-12'>
                                <div class="card">
                                    <div class="card-body">
                                        <table class='table-striped' id='mail_list' data-toggle="table" data-url="<?= base_url('Send_mail/get_mail_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-options='{
                      "fileName": "mail-list"
                    }' data-query-params="queryParams">
                                            <thead>
                                                <tr>
                                                    <th data-field="id" data-visible="false" data-sortable="true"><?= !empty($this->lang->line('label_id')) ? $this->lang->line('label_id') : 'ID'; ?></th>
                                                    <th data-field="workspace_id" data-visible="false" data-sortable="true"><?= !empty($this->lang->line('label_workspace_id')) ? $this->lang->line('label_workspace_id') : 'Workspace ID'; ?></th>
                                                    <th data-field="to" data-sortable="true" data-visible="false"><?= !empty($this->lang->line('label_to')) ? $this->lang->line('label_to') : 'To'; ?></th>
                                                    <th data-field="subject" data-sortable="true"><?= !empty($this->lang->line('label_subject')) ? $this->lang->line('label_subject') : 'Subject'; ?></th>
                                                    <th data-field="message" data-sortable="true" data-visible="false"><?= !empty($this->lang->line('label_message')) ? $this->lang->line('label_message') : 'Message'; ?></th>
                                                    <th data-field="status" data-sortable="true"><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></th>
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


                </section>
            </div>
            <?php
            require_once(APPPATH . '/views/include-footer.php');
            ?>
        </div>
    </div>

    <?php
    require_once(APPPATH . 'views/include-js.php');
    ?>

</body>
<?php
$to = [];
if (!empty($emails)) {
    foreach ($emails as $email) {
        $to[]['email'] = $email;
    }
}
?>
<script>
    to = <?= json_encode($to); ?>;
    dictDefaultMessage = "<?= !empty($this->lang->line('label_drop_files_here_to_upload')) ? $this->lang->line('label_drop_files_here_to_upload') : 'Drop Files Here To Upload'; ?>";
</script>
<script src="<?= base_url('assets/js/page/components-send-mail.js'); ?>"></script>

</html>