<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= !empty($this->lang->line('label_add_contracts_type')) ? $this->lang->line('label_add_contracts_type') : 'Add Contracts Type'; ?> &mdash; <?= get_compnay_title(); ?></title>
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
                        <h1><?= !empty($this->lang->line('label_add_contracts_type')) ? $this->lang->line('label_add_contracts_type') : 'Add Contracts Type'; ?></h1>
                        <div class="section-header-breadcrumb">
                            <?php if (check_permissions("contracts", "create")) { ?>
                                <div class="btn-group mr-2 no-shadow">
                                    <a class="btn btn-primary text-white" href="<?= base_url('contracts') ?>" class="btn"><i class="fas fa-list"></i> <?= !empty($this->lang->line('label_create_contracts')) ? $this->lang->line('label_create_contracts') : 'Create Contracts'; ?></a>
                                </div>
                                <i class="btn btn-primary btn-rounded no-shadow" id="modal-add-contracts-type" data-value="add"><?= !empty($this->lang->line('label_add_contracts_type')) ? $this->lang->line('label_add_contracts_type') : 'Add Contracts Type'; ?></i>
                            <?php } ?>
                        </div>

                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class='col-md-12'>
                                <div class="card">
                                    <div class="card-body">
                                        <table class='table-striped' id='contracts_type_list' data-toggle="table" data-url="<?= base_url('contracts/get_contracts_type_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-options='{ "fileName": "Contracts-type-list" }' data-query-params="queryParams">
                                            <thead>
                                                <tr>
                                                    <th data-field="id" data-visible="false" data-sortable="true"><?= !empty($this->lang->line('label_id')) ? $this->lang->line('label_id') : 'ID'; ?></th>
                                                    <th data-field="type" data-sortable="true"><?= !empty($this->lang->line('label_type')) ? $this->lang->line('label_type') : 'Type'; ?></th>
                                                    <?php if ((check_permissions("contracts", "update")) || (check_permissions("contracts", "delete"))) { ?>
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
            <?php if (check_permissions("contracts", "create")) { ?>
                <?= form_open('contracts/contracts_type_create', 'id="modal-add-contracts-type-part"', 'class="modal-part"'); ?>
                <div id="modal-title" class="d-none"> <?= !empty($this->lang->line('label_add_contracts_type')) ? $this->lang->line('label_add_contracts_type') : 'Add Contracts Type'; ?></div>
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
                </div>
                </form>
            <?php } ?>
            <div class="modal-edit-contracts-type"></div>
            <?= form_open('contracts/edit_contracts_type', 'id="modal-edit-contracts-type-part"', 'class="modal-part"'); ?>
            <div id="modal-title" class="d-none"><?= !empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit'; ?><?= !empty($this->lang->line('label_contracts_type')) ? ' ' . $this->lang->line('label_contracts_type') : ' Contracts Type'; ?></div>
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
            </div>
            </form>
        </div>

        <?php
        require_once(APPPATH . 'views/include-js.php');
        ?>

        <!-- Page Specific JS File -->

        <script src="<?= base_url('assets/js/page/components-contracts.js'); ?>"></script>

</body>

</html>