<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= !empty($this->lang->line('label_edit_payslip')) ? $this->lang->line('label_edit_payslip') : 'Edit Payslip'; ?> &mdash; <?= get_compnay_title(); ?></title>
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
                        <h1><?= !empty($this->lang->line('label_edit_payslip')) ? $this->lang->line('label_edit_payslip') : 'Edit Payslip'; ?></h1>
                        <div class="section-header-breadcrumb">
                            <?php if (check_permissions("payslips", "create")) { ?>
                                <div class="btn-group mr-2 no-shadow">
                                    <a class="btn btn-primary text-white" href="<?= base_url('payslip/allowances') ?>" class="btn"><i class="fas fa-list"></i> <?= !empty($this->lang->line('label_add_allowance')) ? $this->lang->line('label_add_allowance') : 'Add Allowance'; ?></a>
                                </div>
                            <?php } ?>
                            <?php if (check_permissions("payslips", "create")) { ?>
                                <div class="btn-group mr-2 no-shadow">
                                    <a class="btn btn-primary text-white" href="<?= base_url('payslip/deduction') ?>" class="btn"><i class="fas fa-list"></i> <?= !empty($this->lang->line('label_add_deduction')) ? $this->lang->line('label_add_deduction') : 'Add Deduction'; ?></a>
                                </div>
                            <?php } ?>
                            <div class="btn-group mr-2 no-shadow">
                                <a class="btn btn-primary text-white" href="<?= base_url('payslip') ?>" class="btn"><i class="fas fa-list"></i> <?= !empty($this->lang->line('label_payslip')) ? $this->lang->line('label_payslip') : 'Payslip'; ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="section-body">
                        <div class="row mt-sm-4">
                            <div class='col-md-12'>
                                <div class="card">
                                    <div class="card-body">
                                        <form action="<?= base_url('payslip/edit') ?>" id="edit_payslip_form">
                                            <input type="hidden" name="id" id="payslip_id" value="<?= $payslip['id'] ?>">
                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" class="form-control" value="<?= $this->security->get_csrf_hash(); ?>">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_user_name')) ? $this->lang->line('label_user_name') : 'Username'; ?></label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="user_id" id="user_id" value="<?= $payslip['first_name'] . ' ' . $payslip['last_name'] . ' | ' . $payslip['email'] ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_payslip_month')) ? $this->lang->line('label_payslip_month') : 'Payslip month'; ?></label>
                                                        <div class="input-group">
                                                            <input class="form-control datepicker" type="text" id="payslip_month" name="payslip_month" value="<?= $payslip['payslip_month'] ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_working_days')) ? $this->lang->line('label_working_days') : 'Working Days'; ?></label>
                                                        <div class="input-group">
                                                            <input type="number" class="form-control" id="working_days" name="working_days" onchange="update_total_payslip()" value="<?= $payslip['working_days'] ?>" min="1" max="31" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_loss_of_pay_days')) ? $this->lang->line('label_loss_of_pay_days') : 'loss of pay Days'; ?></label>
                                                        <div class="input-group">
                                                            <input type="number" class="form-control" id="lop_days" name="lop_days" value="<?= $payslip['lop_days'] ?>" onchange="update_total_payslip()" min="0" max="31" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_paid_days')) ? $this->lang->line('label_paid_days') : 'Paid Days'; ?></label>
                                                        <div class="input-group">
                                                            <input type="number" class="form-control" name="paid_days" id="paid_days" onchange="update_total_payslip()" value="<?= $payslip['paid_days'] ?>" min="0" max="31" required readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_basic_salary')) ? $this->lang->line('label_basic_salary') : 'Basic salary'; ?></label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon bootstrap-touchspin-prefix input-group-prepend"><span class="input-group-text"><?= get_currency_symbol(); ?></span></span>
                                                            <input class="form-control" type="number" min="0" id="basic_salary" onchange="update_total_payslip()" name="basic_salary" value="<?= $payslip['basic_salary'] ? $payslip['basic_salary'] : 0 ?>" placeholder=<?= !empty($this->lang->line('label_basic_salary')) ? $this->lang->line('label_basic_salary') : 'Basic salary'; ?>>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_leave_deduction')) ? $this->lang->line('label_leave_deduction') : 'Leave Deduction'; ?></label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon bootstrap-touchspin-prefix input-group-prepend"><span class="input-group-text"><?= get_currency_symbol(); ?></span></span>
                                                            <input class="form-control" type="number" min="0" id="leave_deduction" onchange="update_total_payslip()" name="leave_deduction" value="<?= round($payslip['leave_deduction'], 2) ? round($payslip['leave_deduction'], 2) : 0 ?>" placeholder=<?= !empty($this->lang->line('label_leave_deduction')) ? $this->lang->line('label_leave_deduction') : 'Leave Deduction'; ?> readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_over_time_hours')) ? $this->lang->line('label_over_time_hours') : 'Over time hours'; ?></label>
                                                        <div class="input-group">
                                                            <input class="form-control" type="number" min="0" id="ot_hours" name="ot_hours" value="<?= $payslip['ot_hours'] ?>" onchange="update_total_payslip()" placeholder=<?= !empty($this->lang->line('label_over_time_hours')) ? $this->lang->line('label_over_time_hours') : 'Over time hours'; ?>>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_over_time_rate')) ? $this->lang->line('label_over_time_rate') : 'Over time rate'; ?></label>
                                                        <div class="input-group">
                                                            <input class="form-control" type="number" min="0" id="ot_rate" name="ot_rate" onchange="update_total_payslip()" value="<?= $payslip['ot_rate'] ?>" placeholder=<?= !empty($this->lang->line('label_over_time_rate')) ? $this->lang->line('label_over_time_rate') : 'Over time rate'; ?>>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_over_time_payment')) ? $this->lang->line('label_over_time_payment') : 'Over time payment'; ?></label>
                                                        <div class="input-group">
                                                            <input class="form-control" type="number" min="0" id="ot_payment" name="ot_payment" onchange="update_total_payslip()" value="<?= $payslip['ot_payment'] ? $payslip['ot_payment'] : 0 ?>" placeholder=<?= !empty($this->lang->line('label_over_time_payment')) ? $this->lang->line('label_over_time_payment') : 'Over time payment'; ?> readonly>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_bonus')) ? $this->lang->line('label_bonus') : 'Bonus'; ?></label>
                                                        <div class="input-group">
                                                            <input class="form-control" type="number" min="0" id="bonus" name="bonus" value="<?= $payslip['bonus'] ? $payslip['bonus'] : 0 ?>" onchange="update_total_payslip()" placeholder=<?= !empty($this->lang->line('label_bonus')) ? $this->lang->line('label_bonus') : 'Bonus'; ?>>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_incentives')) ? $this->lang->line('label_incentives') : 'incentives'; ?></label>
                                                        <div class="input-group">
                                                            <input class="form-control" type="number" min="0" id="incentives" onchange="update_total_payslip()" name="incentives" value="<?= $payslip['incentives'] ? $payslip['incentives'] : 0 ?>" placeholder=<?= !empty($this->lang->line('label_incentives')) ? $this->lang->line('label_incentives') : 'incentives'; ?>>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_payment_date')) ? $this->lang->line('label_payment_date') : 'Payment Date'; ?></label>
                                                        <input class="form-control datepicker" type="text" id="payment_date" name="payment_date" value="<?= $payslip['payment_date'] ?>" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="type"><?= !empty($this->lang->line('label_payment_method')) ? $this->lang->line('label_payment_method') : 'Payment Method'; ?></label><span class="asterisk"> *</span>

                                                        <div class="input-group">
                                                            <select class="custom-select select2" id="payment_method" name="payment_method">
                                                                <option value="" selected><?= !empty($this->lang->line('label_choose')) ? $this->lang->line('label_choose') : 'Choose'; ?>...</option>
                                                                <?php
                                                                foreach ($payment_modes as $payment_mode) { ?>
                                                                    <option value="<?= $payment_mode['id'] ?>" <?= ($payslip['payment_method'] == $payment_mode['id']) ? "selected" : "" ?>><?= $payment_mode['title'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></label>
                                                        <div class="selectgroup w-100">
                                                            <label class="selectgroup-item">
                                                                <input type="radio" name="status" id="paid" value="1" class="selectgroup-input" <?= (isset($payslip['status']) && $payslip['status'] == 1) ? "checked" : "" ?>>
                                                                <span class="selectgroup-button"><?= !empty($this->lang->line('label_paid')) ? $this->lang->line('label_paid') : 'Paid'; ?></span>
                                                            </label>
                                                            <label class="selectgroup-item">
                                                                <input type="radio" name="status" id="unpaid" value="0" class="selectgroup-input" <?= (isset($payslip['status']) && $payslip['status'] == 0) ? "checked" : "" ?>>
                                                                <span class="selectgroup-button"><?= !empty($this->lang->line('label_unpaid')) ? $this->lang->line('label_unpaid') : 'Unpaid'; ?></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <select class="custom-select select2" id="allowance_id" onchange="update_total_payslip()" name="allowance_id">
                                                                    <option value="" selected><?= !empty($this->lang->line('label_choose_allowance')) ? $this->lang->line('label_choose_allowance') : 'Choose Allowance'; ?>...</option>
                                                                    <?php
                                                                    foreach ($allowances as $allowance) { ?>
                                                                        <option value="<?= $allowance['id'] ?>"><?= $allowance['name'] ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                <div class="wrapper" id="wrp" style="display: none;">
                                                                    <hr><a href="#" id="modal-add-allowance" style="text-decoration: none;">+ <?= !empty($this->lang->line('label_add_allowance')) ? $this->lang->line('label_add_allowance') : 'Add Allowance'; ?></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="container-fluid">
                                                            <div class="row custom-table-header mb-4">
                                                                <div class="col-md-6 custom-col">
                                                                    <?= !empty($this->lang->line('label_allowance')) ? $this->lang->line('label_allowance') : 'Allowance'; ?>
                                                                </div>
                                                                <div class="col-md-4 custom-col">
                                                                    <?= !empty($this->lang->line('label_amount')) ? $this->lang->line('label_amount') : 'Amount' . '(' . $currency . ')'; ?>
                                                                </div>
                                                                <div class="col-md-2 custom-col">
                                                                    <?= !empty($this->lang->line('label_action')) ? $this->lang->line('label_action') : 'Action'; ?>
                                                                </div>
                                                            </div>
                                                            <div id="allowance_payslip">
                                                                <div class="row mb-2">
                                                                    <div class="col-md-6 custom-col">
                                                                        <input type="text" class="form-control" id="allowance_0_allowance_name" name="allowance_name[]" onchange="update_total_payslip()" placeholder="allowance">
                                                                    </div>
                                                                    <div class="col-md-4 custom-col">
                                                                        <input type="number" class="form-control" amount="amount[]" id="allowance_0_amount" min="0" value="0" onchange="update_total_payslip()" placeholder="<?= get_currency_symbol(); ?>">
                                                                    </div>
                                                                    <div class="col-md-2 custom-col">
                                                                        <a href="#" class="btn btn-icon btn-success add-allowance-payslip" onchange="update_total_payslip()"><i class="fas fa-check"></i></a>
                                                                    </div>
                                                                </div>
                                                                <input type="hidden" name="deleted_allowance_item_ids" id="deleted_allowance_item_ids">
                                                                <?php if (is_array($allowance_items)) :
                                                                    $j = count($allowance_items) > 1 ? count($allowance_items) - 1 : 0;
                                                                    foreach ($allowance_items as $allowance_item) {
                                                                        if ($allowance_item !== null && is_array($allowance_item) && isset($allowance_item['id']) && isset($allowance_item['allowance_name']) && isset($allowance_item['amount']) && isset($allowance_item['payslip_id'])) {
                                                                ?>
                                                                            <div class="row allowance-payslip-data mb-2">
                                                                                <input type="hidden" id="allowance_<?= $j ?>_id" value="<?= $allowance_item['id'] ?>" name="allowance[]">
                                                                                <input type="hidden" name="allowance_item_ids[]" id="allowance_item_<?= $j ?>_id" value="<?= $allowance_item['id'] ?>">
                                                                                <div class="col-md-6 custom-col">
                                                                                    <input type="text" class="form-control" id="allowance_<?= $j ?>_allowance_name" value="<?= $allowance_item['allowance_name'] ?>" name="allowance_name[]" onchange="update_total_payslip()" placeholder="allowance">
                                                                                </div>
                                                                                <div class="col-md-4 custom-col">
                                                                                    <input type="number" class="form-control" id="allowance_<?= $j ?>_amount" min="0" name="amount[]" onchange="update_total_payslip()" value="<?= $allowance_item['amount'] ? $allowance_item['amount'] : 0 ?>" placeholder="<?= get_currency_symbol(); ?>">
                                                                                </div>
                                                                                <div class="col-md-2 custom-col">
                                                                                    <a href="#" class="btn btn-icon btn-danger remove-allowance-payslip" data-count='<?= $j ?>' onchange="update_total_payslip()" data-amount='<?= $allowance_item['amount'] ?>' data-id='<?= $allowance_item['id'] ?>'><i class="fas fa-trash"></i></a>
                                                                                </div>
                                                                            </div>
                                                                <?php
                                                                            $j++;
                                                                        }
                                                                    }
                                                                endif;
                                                                ?>


                                                            </div>
                                                            <hr>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <select class="custom-select select2" id="deduction_id" onchange="update_total_payslip()" name="deduction_id">
                                                                    <option value="" selected><?= !empty($this->lang->line('label_choose_deduction')) ? $this->lang->line('label_choose_deduction') : 'Choose Deduction'; ?>...</option>
                                                                    <?php
                                                                    foreach ($deductions as $deduction) { ?>
                                                                        <option value="<?= $deduction['id'] ?>"><?= $deduction['name'] ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                <div class="wrapper" id="wrp1" style="display: none;">
                                                                    <hr><a href="#" id="modal-add-deduction" style="text-decoration: none;">+ <?= !empty($this->lang->line('label_add_deduction')) ? $this->lang->line('label_add_deduction') : ' Add Deduction'; ?></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="container-fluid">
                                                            <div class="row custom-table-header mb-4">
                                                                <div class="col-md-2 custom-col">
                                                                    <?= !empty($this->lang->line('label_deduction')) ? $this->lang->line('label_deduction') : 'Deduction'; ?>
                                                                </div>
                                                                <div class="col-md-4 custom-col">
                                                                    <?= !empty($this->lang->line('label_deducation_type')) ? $this->lang->line('label_deducation_type') : 'Deducation Type'; ?>
                                                                </div>
                                                                <div class="col-md-2 custom-col">
                                                                    <?= !empty($this->lang->line('label_percentage')) ? $this->lang->line('label_percentage') : 'Percentage'; ?>
                                                                </div>
                                                                <div class="col-md-2 custom-col">
                                                                    <?= !empty($this->lang->line('label_amount')) ? $this->lang->line('label_amount') : 'Amount' . '(' . $currency . ')'; ?>
                                                                </div>
                                                                <div class="col-md-2 custom-col">
                                                                    <?= !empty($this->lang->line('label_action')) ? $this->lang->line('label_action') : 'Action'; ?>
                                                                </div>
                                                            </div>
                                                            <div id="deduction_payslip">
                                                                <div class="row mb-2">
                                                                    <div class="col-md-2 custom-col">
                                                                        <input type="text" class="form-control" onchange="update_total_payslip()" id="deduction_0_deduction_name" name="deduction_name" placeholder="deduction">
                                                                    </div>
                                                                    <div class="col-md-4 custom-col">
                                                                        <select class="form-control" onchange="update_total_payslip()" name="deduction_type" id="deduction_0_deduction_type">
                                                                            <option value="" selected><?= !empty($this->lang->line('label_choose_deduction_type')) ? $this->lang->line('label_choose_deduction_type') : 'Choose Deducation Type'; ?>...</option>
                                                                            <option value="amount"><?= !empty($this->lang->line('label_amount')) ? $this->lang->line('label_amount') : 'Amount'; ?></option>
                                                                            <option value="percentage"><?= !empty($this->lang->line('label_percentage')) ? $this->lang->line('label_percentage') : 'percentage'; ?></option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-2 custom-col">
                                                                        <input type="number" class="form-control" onchange="update_total_payslip()" name="deduction_percentage" id="deduction_0_percentage" min="0" max="100" placeholder="%">
                                                                    </div>
                                                                    <div class="col-md-2 custom-col">
                                                                        <input type="number" class="form-control" onchange="update_total_payslip()" name="deduction_amount" id="deduction_0_amount" min="0" value="0" placeholder="<?= get_currency_symbol(); ?>">
                                                                    </div>
                                                                    <div class="col-md-2 custom-col">
                                                                        <a href="#" class="btn btn-icon btn-success add-deduction-payslip" onchange="update_total_payslip()"><i class="fas fa-check"></i></a>
                                                                    </div>
                                                                </div>
                                                                <input type="hidden" name="deleted_deduction_item_ids" id="deleted_deduction_item_ids">
                                                                <?php
                                                                if (is_array($deductions_items)) {
                                                                    $k = 0;
                                                                    $k = (count($deductions_items) > 1) ? (count($deductions_items)) - 1 : 0;
                                                                    foreach ($deductions_items as $deductions_item) {
                                                                        if (
                                                                            is_array($deductions_item) &&
                                                                            isset($deductions_item['id']) &&
                                                                            isset($deductions_item['deduction_name']) &&
                                                                            isset($deductions_item['amount']) &&
                                                                            isset($deductions_item['payslip_id'])
                                                                        ) {
                                                                ?>
                                                                            <div class="row mb-2 deduction-payslip-data">
                                                                                <input type="hidden" value="<?= $deductions_item['deduction_id'] ?>" name="deduction[]">
                                                                                <input type="hidden" name="deduction_item_ids[]" id="deduction_item_<?= $k ?>_id" value="<?= $deductions_item['id'] ?>">
                                                                                <div class="col-md-2 custom-col">
                                                                                    <input type="text" class="form-control" onchange="update_total_payslip()" name="deduction_name[]" id="deduction_<?= $k ?>_deduction_name" value="<?= $deductions_item['deduction_name'] ?>" placeholder="deduction">
                                                                                </div>
                                                                                <div class="col-md-4 custom-col">
                                                                                    <select class="form-control" id="deduction_type" name="deduction_type[]" onchange="update_total_payslip()" id="deduction_<?= $k ?>_deduction_type">
                                                                                        <option value="" selected><?= !empty($this->lang->line('label_choose_deduction_type')) ? $this->lang->line('label_choose_deduction_type') : 'Choose Deduction Type'; ?>...</option>
                                                                                        <option value="amount" <?= (isset($deductions_items[$k]['deduction_type']) && $deductions_items[$k]['deduction_type'] == 'amount') ? "selected" : "" ?>><?= !empty($this->lang->line('label_amount')) ? $this->lang->line('label_amount') : 'Amount'; ?></option>
                                                                                        <option value="percentage" <?= (isset($deductions_items[$k]['deduction_type']) && $deductions_items[$k]['deduction_type'] == 'percentage') ? "selected" : "" ?>><?= !empty($this->lang->line('label_percentage')) ? $this->lang->line('label_percentage') : 'percentage'; ?></option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-md-2 custom-col">
                                                                                    <input type="number" class="form-control" onchange="update_total_payslip()" name="deduction_percentage[]" id="deduction_<?= $k ?>_percentage" value="<?= $deductions_item['percentage'] ?>" min="0" max="100" placeholder="%">
                                                                                </div>
                                                                                <div class="col-md-2 custom-col">
                                                                                    <input type="number" class="form-control" name="deduction_amount[]" value="<?= $deductions_item['amount'] ? $deductions_item['amount'] : 0 ?>" onchange="update_total_payslip()" id="deduction_<?= $k ?>_amount" min="0" placeholder="<?= get_currency_symbol(); ?>">
                                                                                </div>
                                                                                <div class="col-md-2 custom-col">
                                                                                    <a href="#" class="btn btn-icon btn-danger remove-deduction-payslip" data-count='<?= $k ?>' onchange="update_total_payslip()" data-amount='<?= $deductions_item['amount'] ?>' data-id='<?= $deductions_item['id'] ?>'><i class="fas fa-trash"></i></a>
                                                                                </div>
                                                                            </div>
                                                                <?php
                                                                            $k++;
                                                                        }
                                                                    }
                                                                }
                                                                ?>


                                                            </div>
                                                            <hr>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_total_allowance')) ? $this->lang->line('label_total_allowance') : 'Total Allowance'; ?></label>
                                                        <div class="input-group">
                                                            <input type="number" class="form-control" min="0" name="total_allowance" onchange="update_total_payslip()" value="<?= round($payslip['total_allowance'], 2) ? round($payslip['total_allowance'], 2) : 0 ?>" id="total_allowance" placeholder="0.00" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_total_deduction')) ? $this->lang->line('label_total_deduction') : 'Total Deduction'; ?></label>
                                                        <div class="input-group">
                                                            <input type="number" class="form-control" min="0" name="total_deduction" onchange="update_total_payslip()" value="<?= round($payslip['total_deductions'], 2) ? round($payslip['total_deductions'], 2) : 0 ?>" id="total_deduction" placeholder="0.00" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-7">
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group row align-items-center">
                                                        <label for="site-title" class="form-control-label col-sm-3 text-md-right"><?= !empty($this->lang->line('label_total_earnings')) ? $this->lang->line('label_total_earnings') : 'Total Earnings'; ?></label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <input class="form-control" type="number" min="0" id="total_earnings" value="<?= round($payslip['total_earnings'], 2) ? round($payslip['total_earnings'], 2) : 0 ?>" onchange="update_total_payslip()" name="total_earnings" placeholder="0.00" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row align-items-center">
                                                        <label for="site-title" class="form-control-label col-sm-3 text-md-right"><?= !empty($this->lang->line('label_net_pay')) ? $this->lang->line('label_net_pay') : 'Net pay'; ?></label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <input class="form-control" type="number" min="0" id="net_pay" value="<?= round($payslip['net_pay'], 2) ? round($payslip['net_pay'], 2) : 0 ?>" name="net_pay" onchange="update_total_payslip()" placeholder="0.00" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row text-right">
                                                <div class="card-footer col-md-12">
                                                    <button class="btn btn-primary mb-2" id="submit_button"><?= !empty($this->lang->line('label_submit')) ? $this->lang->line('label_submit') : 'Submit'; ?></button>
                                                    <div id="result" style="display: none;"></div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
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
                                    <option value="amount">Amount</option>
                                    <option value="percentage">Percentage</option>
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
                <?php if (check_permissions("payslips", "create")) { ?>
                    <?= form_open('payslip/create_allowance', 'id="modal-add-allowance-part"', 'class="modal-part"'); ?>
                    <div id="modal-allowance-title" class="d-none"><?= !empty($this->lang->line('label_add_allowance')) ? $this->lang->line('label_add_allowance') : 'Add Allowance'; ?></div>
                    <div id="modal-footer-add-title" class="d-none"><?= !empty($this->lang->line('label_add')) ? $this->lang->line('label_add') : 'Add'; ?></div>
                    <input type="hidden" name="is_reload" id="is_reload" value="0">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?= !empty($this->lang->line('label_allowance')) ? $this->lang->line('label_allowance') : 'Allowance'; ?></label><span class="asterisk"> *</span>
                                <div class="input-group">
                                    <?= form_input(['name' => 'allowance_name', 'placeholder' => !empty($this->lang->line('label_allowance')) ? $this->lang->line('label_allowance') : 'Allowance', 'class' => 'form-control']) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?= !empty($this->lang->line('label_amount')) ? $this->lang->line('label_amount') : 'Amount'; ?></label><span class="asterisk"> *</span>
                                <div class="input-group">
                                    <?= form_input(['type' => 'number', 'name' => 'amount', 'placeholder' => !empty($this->lang->line('label_amount')) ? $this->lang->line('label_amount') : 'Amount', 'min' => 0, 'class' => 'form-control']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                <?php } ?>
            </div>

            <?php include('include-footer.php'); ?>

        </div>
    </div>
    <?php include('include-js.php'); ?>
</body>
<script>
    currency = <?= "'$currency'"; ?>;
    j = <?= count($allowance_items) ?>;
    k = <?= count($deductions_items) ?>;
    payslip_id = <?= $payslip['id']; ?>;
</script>
<script src="<?= base_url('assets/js/page/components-edit-payslip.js'); ?>"></script>

</html>