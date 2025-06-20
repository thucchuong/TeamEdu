<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= !empty($this->lang->line('label_add_statuses')) ? $this->lang->line('label_add_statuses') . '  ' : 'Add Statuses  '; ?> &mdash; <?= get_compnay_title(); ?></title>
    <?php
    require_once(APPPATH . 'views/include-css.php');
    ?>

</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">

            <?php
            require_once(APPPATH . 'views/include-header.php');
            ?>

            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1><?= !empty($this->lang->line('label_add_statuses')) ? $this->lang->line('label_add_statuses') . '  ' : 'Add Statuses  '; ?><i class="fas fa-question-circle text-danger" title="Use of this status for Task and Project"></i></h1>
                        <div class="section-header-breadcrumb">
                            <?php if (check_permissions("statuses", "create")) { ?>
                                <i class="btn btn-primary btn-rounded no-shadow" id="modal-add-statuses" data-value="add"><?= !empty($this->lang->line('label_add_statuses')) ? $this->lang->line('label_add_statuses') : 'Add Statuses'; ?></i>
                            <?php } ?>

                        </div>

                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class='col-md-12'>
                                <div class="card">
                                    <div class="card-body">
                                        <table class='table-striped' id='statuses_list' data-toggle="table" data-url="<?= base_url('statuses/get_statuses_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-options='{ "fileName": "statuses-list" }' data-query-params="queryParams">
                                            <thead>
                                                <tr>
                                                    <th data-field="id" data-visible="false" data-sortable="true"><?= !empty($this->lang->line('label_id')) ? $this->lang->line('label_id') : 'ID'; ?></th>
                                                    <th data-field="type" data-sortable="true"><?= !empty($this->lang->line('label_type')) ? $this->lang->line('label_type') : 'Type'; ?></th>
                                                    <th data-field="text_color" data-sortable="true"><?= !empty($this->lang->line('label_text_color')) ? $this->lang->line('label_text_color') : 'Text Color'; ?></th>
                                                    <?php if (check_permissions("statuses", "update") || (check_permissions("statuses", "delete"))) { ?>
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
            <?php if (check_permissions("statuses", "create")) { ?>
                <?= form_open('statuses/statuses_create', 'id="modal-add-statuses-part"', 'class="modal-part"'); ?>
                <div id="modal-title" class="d-none"> <?= !empty($this->lang->line('label_add_statuses')) ? $this->lang->line('label_add_statuses') : 'Add Statuses'; ?></div>
                <div id="modal-footer-add-title" class="d-none"><?= !empty($this->lang->line('label_add')) ? $this->lang->line('label_add') : 'Add'; ?></div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_type')) ? $this->lang->line('label_type') : 'Type'; ?></label><span class="asterisk"> *</span>
                            <div class="input-group">
                                <?= form_input(['name' => 'type', 'placeholder' => !empty($this->lang->line('label_type')) ? $this->lang->line('label_type') : 'Type', 'class' => 'form-control']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_text_color')) ? $this->lang->line('label_text_color') : 'Text Color'; ?></label><span class="asterisk"> *</span>
                            <select class="form-control" name="text_color">
                                <option value="info"><?= !empty($this->lang->line('label_info')) ? $this->lang->line('label_info') : 'Info'; ?></option>
                                <option value="secondary"><?= !empty($this->lang->line('label_secondary')) ? $this->lang->line('label_secondary') : 'Secondary'; ?></option>
                                <option value="success"><?= !empty($this->lang->line('label_success')) ? $this->lang->line('label_success') : 'Success'; ?></option>
                                <option value="warning"><?= !empty($this->lang->line('label_warning')) ? $this->lang->line('label_warning') : 'Warning'; ?></option>
                                <option value="danger"><?= !empty($this->lang->line('label_danger')) ? $this->lang->line('label_danger') : 'Danger'; ?></option>
                            </select>
                        </div>
                    </div>
                </div>
                </form>
            <?php } ?>
            <div class="modal-edit-statuses"></div>
            <?= form_open('statuses/edit_statuses', 'id="modal-edit-statuses-part"', 'class="modal-part"'); ?>
            <div id="modal-title" class="d-none"><?= !empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit'; ?><?= !empty($this->lang->line('label_statuses')) ? ' ' . $this->lang->line('label_statuses') : ' Statuses'; ?></div>
            <div id="modal-footer-edit-title" class="d-none"><?= !empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit'; ?></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_type')) ? $this->lang->line('label_type') : 'Type'; ?></label><span class="asterisk">*</span>
                        <div class="input-group">
                            <input name="id" type="hidden" id="update_id" value="">
                            <?= form_input(['name' => 'type', 'placeholder' => !empty($this->lang->line('label_type')) ? $this->lang->line('label_type') : 'Type', 'class' => 'form-control', 'id' => 'update_type']) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_text_color')) ? $this->lang->line('label_text_color') : 'Text Color'; ?></label><span class="asterisk"> *</span>
                        <select class="form-control" name="text_color" id="update_text_color">
                            <option value="info"><?= !empty($this->lang->line('label_info')) ? $this->lang->line('label_info') : 'Info'; ?></option>
                            <option value="secondary"><?= !empty($this->lang->line('label_secondary')) ? $this->lang->line('label_secondary') : 'Secondary'; ?></option>
                            <option value="success"><?= !empty($this->lang->line('label_success')) ? $this->lang->line('label_success') : 'Success'; ?></option>
                            <option value="warning"><?= !empty($this->lang->line('label_warning')) ? $this->lang->line('label_warning') : 'Warning'; ?></option>
                            <option value="danger"><?= !empty($this->lang->line('label_danger')) ? $this->lang->line('label_danger') : 'Danger'; ?></option>
                        </select>
                    </div>
                </div>
            </div>
            </form>
        </div>

        <?php
        require_once(APPPATH . 'views/include-js.php');
        ?>

        <!-- Page Specific JS File -->

        <script src="<?= base_url('assets/js/page/components-statuses.js'); ?>"></script>

</body>

</html>