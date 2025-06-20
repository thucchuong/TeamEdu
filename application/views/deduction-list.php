<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= !empty($this->lang->line('label_add_deduction')) ? $this->lang->line('label_add_deduction') : 'Add Deduction'; ?> &mdash; <?= get_compnay_title(); ?></title>
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
                        <h1><?= !empty($this->lang->line('label_add_deduction')) ? $this->lang->line('label_add_deduction') : 'Add Deduction'; ?></h1>
                        <div class="section-header-breadcrumb">
                            <div class="btn-group mr-2 no-shadow">
                                <a class="btn btn-primary text-white" href="<?= base_url('payslip') ?>" class="btn"><i class="fas fa-list"></i> <?= !empty($this->lang->line('label_payslip')) ? $this->lang->line('label_payslip') : 'Payslip'; ?></a>
                            </div>
                            <?php if (check_permissions("payslips", "create")) { ?>
                                <i class="btn btn-primary btn-rounded no-shadow" id="modal-add-deduction" data-value="add"><?= !empty($this->lang->line('label_add_deduction')) ? $this->lang->line('label_add_deduction') : 'Add Deduction'; ?></i>
                            <?php } ?>
                        </div>

                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class='col-md-12'>
                                <div class="card">
                                    <div class="card-body">
                                        <table class='table-striped' id='deductions_list' data-toggle="table" data-url="<?= base_url('payslip/get_deductions_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-options='{ "fileName": "Allowances-list" }' data-query-params="queryParams2">
                                            <thead>
                                                <tr>
                                                    <th data-field="id" data-visible="false" data-sortable="true"><?= !empty($this->lang->line('label_id')) ? $this->lang->line('label_id') : 'ID'; ?></th>
                                                    <th data-field="name" data-sortable="true"><?= !empty($this->lang->line('label_name')) ? $this->lang->line('label_name') : 'Name'; ?></th>
                                                    <th data-field="amount" data-sortable="true"><?= !empty($this->lang->line('label_amount')) ? $this->lang->line('label_amount') : 'Amount'; ?></th>
                                                    <th data-field="percentage" data-sortable="true"><?= !empty($this->lang->line('label_percentage')) ? $this->lang->line('label_percentage') : 'Percentage'; ?></th>
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
            <?php if (check_permissions("payslips", "create")) { ?>
            <?= form_open('payslip/create_deduction', 'id="modal-add-deduction-part"', 'class="modal-part"'); ?>
            <div id="modal-deduction-title" class="d-none"><?= !empty($this->lang->line('label_add_deduction')) ? $this->lang->line('label_add_deduction') : 'Add Deduction'; ?></div>
            <div id="modal-footer-add-title" class="d-none"><?= !empty($this->lang->line('label_add')) ? $this->lang->line('label_add') : 'Add'; ?></div>
            <input type="hidden" name="is_reload" id="is_reload" value="0">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_deduction')) ? $this->lang->line('label_deduction') : 'Deduction'; ?></label><span class="asterisk"> *</span>
                        <div class="input-group">
                            <?= form_input(['name' => 'deduction_name', 'placeholder' => !empty($this->lang->line('label_deduction')) ? $this->lang->line('label_deduction') : 'deduction', 'class' => 'form-control']) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_education_type')) ? $this->lang->line('label_education_type') : 'Deducation Type'; ?></label><span class="asterisk"> *</span>
                        <select class="form-control deducation_data" name="deduction_type" id="deduction_type">
                            <option value=""><?= !empty($this->lang->line('label_choose_deduction_type')) ? $this->lang->line('label_choose_deduction_type') : 'Choose Deducation Type'; ?>...</option>
                            <option value="amount"><?= !empty($this->lang->line('label_amount')) ? $this->lang->line('label_amount') : 'Amount'; ?></option>
                            <option value="percentage"><?= !empty($this->lang->line('label_percentage')) ? $this->lang->line('label_percentage') : 'percentage'; ?></option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12 deducation_data_amount d-none">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_amount')) ? $this->lang->line('label_amount') : 'Amount'; ?></label><span class="asterisk"> *</span>
                        <div class="input-group">
                            <?= form_input(['type' => 'number', 'name' => 'deduction_amount', 'placeholder' => !empty($this->lang->line('label_amount')) ? $this->lang->line('label_amount') : 'Amount', 'min' => 0, 'class' => 'form-control']) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 deducation_data_percentage d-none">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_percentage')) ? $this->lang->line('label_percentage') : 'percentage'; ?></label><span class="asterisk"> *</span>
                        <div class="input-group">
                            <?= form_input(['type' => 'number', 'name' => 'deduction_percentage', 'placeholder' => !empty($this->lang->line('label_percentage')) ? $this->lang->line('label_percentage') : 'percentage', 'min' => 0, 'max' => 100, 'class' => 'form-control']) ?>
                        </div>
                    </div>
                </div>
            </div>
            </form>
            <?php } ?>
            <div class="modal-edit-deduction"></div>
            <?= form_open('payslip/edit_deduction', 'id="modal-edit-deduction-part"', 'class="modal-part"'); ?>
            <div id="modal-title" class="d-none"><?= !empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit'; ?><?= !empty($this->lang->line('label_deduction')) ? ' ' . $this->lang->line('label_deduction') : ' Deduction'; ?></div>
            <div id="modal-footer-edit-title" class="d-none"><?= !empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit'; ?></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_name')) ? $this->lang->line('label_name') : 'Name'; ?></label><span class="asterisk">*</span>
                        <div class="input-group">
                            <input name="id" type="hidden" id="update_id" value="">
                            <?= form_input(['name' => 'deduction_name', 'placeholder' => !empty($this->lang->line('label_name')) ? $this->lang->line('label_name') : 'Name', 'class' => 'form-control', 'id' => 'update_name']) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_education_type')) ? $this->lang->line('label_education_type') : 'Deducation Type'; ?></label><span class="asterisk"> *</span>
                        <select class="form-control deducation_data" name="deduction_type" id="update_deduction_type">
                            <option value=""><?= !empty($this->lang->line('label_choose_deduction_type')) ? $this->lang->line('label_choose_deduction_type') : 'Choose Deducation Type'; ?>...</option>
                            <option value="amount"><?= !empty($this->lang->line('label_amount')) ? $this->lang->line('label_amount') : 'Amount'; ?></option>
                            <option value="percentage"><?= !empty($this->lang->line('label_percentage')) ? $this->lang->line('label_percentage') : 'percentage'; ?></option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12 deducation_data_amount d-none">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_amount')) ? $this->lang->line('label_amount') : 'Amount'; ?></label><span class="asterisk"> *</span>
                        <div class="input-group">
                            <?= form_input(['type' => 'number', 'name' => 'deduction_amount', 'placeholder' => !empty($this->lang->line('label_amount')) ? $this->lang->line('label_amount') : 'Amount', 'class' => 'form-control', 'min' => 0, 'id' => 'update_amount']) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 deducation_data_percentage d-none">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_percentage')) ? $this->lang->line('label_percentage') : 'percentage'; ?></label><span class="asterisk"> *</span>
                        <div class="input-group">
                            <?= form_input(['type' => 'number', 'name' => 'deduction_percentage', 'placeholder' => !empty($this->lang->line('label_percentage')) ? $this->lang->line('label_percentage') : 'percentage', 'min' => 0, 'max' => 100, 'class' => 'form-control', 'id' => 'update_percentage']) ?>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>

        <?php
        require_once(APPPATH . 'views/include-js.php');
        ?>
        <script src="<?= base_url('assets/js/page/components-payslip.js'); ?>"></script>
</body>

</html>