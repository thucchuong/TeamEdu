<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= !empty($this->lang->line('label_payslip')) ? $this->lang->line('label_payslip') : 'Payslip'; ?> &mdash; <?= get_compnay_title(); ?></title>
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
                        <h1><?= !empty($this->lang->line('label_payslip')) ? $this->lang->line('label_payslip') : 'Payslip'; ?></h1>
                        <div class="section-header-breadcrumb">
                            <div class="btn-group mr-2 no-shadow">
                                <a class="btn btn-primary text-white" href="<?= base_url('payslip') ?>" class="btn"><i class="fas fa-list"></i> <?= !empty($this->lang->line('label_payslip')) ? $this->lang->line('label_payslips') : 'Payslip'; ?></a>
                            </div>
                        </div>
                        <?php if (check_permissions("payslips", "create")) { ?>
                            <a class="btn btn-primary text-white" href="<?= base_url('payslip/create-payslip') ?>" class="btn"><i class="fas fa-plus"></i><?= !empty($this->lang->line('label_create_payslip')) ? $this->lang->line('label_create_payslip') : 'Create Payslip'; ?></a>
                        <?php } ?>
                    </div>
                    <div class="section-body">
                        <div class="payslip">
                            <div class="payslip-print w-100" id='section-to-print'>
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <div class="text-center lh-1 mb-4">
                                            <?php $full_logo = get_compnay_logo(); ?>
                                            <img class="mb-2" alt="Task Hub" src="<?= !empty($full_logo) ? base_url('assets/icons/' . $full_logo) : base_url('assets/icons/logo.png'); ?>" width="200px">
                                            <div class="mb-1"><b><?= get_compnay_title(); ?></b></div>
                                            <span class="fw-normal"><?= !empty($this->lang->line('label_payslip_slip_for_the_month_of')) ? $this->lang->line('label_payslip_slip_for_the_month_of') : 'Payment slip for the month of'; ?> <b><?= date('F Y', strtotime($payslip['payslip_month'])); ?></b></span>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div> <b><?= !empty($this->lang->line('label_username')) ? $this->lang->line('label_username') : 'Username'; ?> : </b> <small class="ms-3"><?= $payslip['first_name'] . ' ' . $payslip['last_name'] ?></small> </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div> <b><?= !empty($this->lang->line('label_paid_days')) ? $this->lang->line('label_paid_days') : 'Paid Days'; ?> : </b> <small class="ms-3"><?= $payslip['paid_days'] ?></small> </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div> <b><?= !empty($this->lang->line('label_email')) ? $this->lang->line('label_email') : 'Email'; ?> : </b> <small class="ms-3"><?= $payslip['email'] ?></small> </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div> <b><?= !empty($this->lang->line('label_lop_days')) ? $this->lang->line('label_lop_days') : 'Lop Days'; ?> : </b> <small class="ms-3"><?= $payslip['lop_days'] ?></small> </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <table class="mt-4 table table-bordered responsive-table">
                                                <thead class="bg-dark text-white">
                                                    <tr>
                                                        <th scope="col" class="text-black-50 bg-secondary"><?= !empty($this->lang->line('label_earnings')) ? $this->lang->line('label_earnings') : 'Earnings'; ?></th>
                                                        <th scope="col" class="text-black-50 bg-secondary"><?= !empty($this->lang->line('label_amount')) ? $this->lang->line('label_amount') : 'Amount'; ?></th>
                                                        <th scope="col" class="text-black-50 bg-secondary"><?= !empty($this->lang->line('label_deduction')) ? $this->lang->line('label_deduction') : 'Deduction'; ?></th>
                                                        <th scope="col" class="text-black-50 bg-secondary"><?= !empty($this->lang->line('label_amount')) ? $this->lang->line('label_amount') : 'Amount'; ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="2" class="align-baseline">
                                                            <div>
                                                                <div class="d-flex d-flex justify-content-between mt-3">
                                                                    <h6 scope="row"><?= !empty($this->lang->line('label_basic')) ? $this->lang->line('label_basic') : 'Basic'; ?></h6>
                                                                    <p><?= get_currency_symbol() . number_format($payslip['basic_salary']) ?></p>
                                                                </div>
                                                                <?php
                                                                $i = 0;
                                                                foreach ($allowance_items as $allowance_item) {
                                                                ?>
                                                                    <div class="d-flex d-flex justify-content-between">
                                                                        <h6 scope="row"><?= $allowance_item['allowance_name'] ?></h6>
                                                                        <p><?= get_currency_symbol() . number_format($allowance_item['amount']) ?></p>
                                                                    </div>
                                                                <?php
                                                                    $i++;
                                                                } ?>
                                                            </div>
                                                        </td>
                                                        <td colspan="2" class="align-baseline">
                                                            <?php
                                                            $i = 0;
                                                            foreach ($deductions_items as $deductions_item) {
                                                            ?>
                                                                <div class="d-flex d-flex justify-content-between mt-3">
                                                                    <h6 scope="row"><?= $deductions_item['deduction_name'] ?></h6>
                                                                    <p><?= get_currency_symbol() . number_format($deductions_item['amount']) ?></p>
                                                                </div>
                                                            <?php
                                                                $i++;
                                                            } ?>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="2">
                                                            <div class="d-flex d-flex justify-content-between mt-3">
                                                                <h6 scope="row"><?= !empty($this->lang->line('label_gross_salary')) ? $this->lang->line('label_gross_salary') : 'Gross Salary'; ?></h6>
                                                                <p><?php echo get_currency_symbol() . number_format($gross_salary = $payslip['basic_salary'] + $payslip['total_allowance'], 2);
                                                                    ?></p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row" colspan="2"><?= !empty($this->lang->line('label_other_earnings')) ? $this->lang->line('label_other_earnings') : 'Other Earnings'; ?></th>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <div class="d-flex d-flex justify-content-between mt-3">
                                                                <h6 scope="row"><?= !empty($this->lang->line('label_bonus')) ? $this->lang->line('label_bonus') : 'Bonus'; ?></h6>
                                                                <p><?= get_currency_symbol() . number_format($payslip['bonus'])
                                                                    ?></p>
                                                            </div>
                                                            <div class="d-flex d-flex justify-content-between mt-3">
                                                                <h6 scope="row"><?= !empty($this->lang->line('label_incentives')) ? $this->lang->line('label_incentives') : 'incentives'; ?></h6>
                                                                <p><?= get_currency_symbol() . number_format($payslip['incentives'])
                                                                    ?></p>
                                                            </div>
                                                            <div class="d-flex d-flex justify-content-between mt-3">
                                                                <h6 scope="row"><?= !empty($this->lang->line('label_over_time_rate')) ? $this->lang->line('label_over_time_rate') : 'Over time rate'; ?></h6>
                                                                <p><?= get_currency_symbol() . number_format($payslip['ot_payment'])
                                                                    ?></p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row"><?= !empty($this->lang->line('label_total_earnings')) ? $this->lang->line('label_total_earnings') : 'Total Earnings'; ?></th>
                                                        <td class="text-right"><?= get_currency_symbol() . number_format($payslip['total_earnings'])  ?></td>
                                                        <th scope="row"><?= !empty($this->lang->line('label_total_deductions')) ? $this->lang->line('label_total_deductions') : 'Total Deductions'; ?></th>
                                                        <td class="text-right"><?= get_currency_symbol() . number_format($payslip['total_deductions'])  ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row"><?= !empty($this->lang->line('label_net_pay')) ? $this->lang->line('label_net_pay') : 'Net pay'; ?>(<?php
                                                                                                                                                                            if (isset($payslip['status']) && $payslip['status'] == 1) {
                                                                                                                                                                                $status = '<b class="text-primary"">' . (!empty($this->lang->line('label_paid')) ? $this->lang->line('label_paid') : 'Paid') . '</b>';
                                                                                                                                                                            } else {
                                                                                                                                                                                $status = '<b class="text-danger">' . (!empty($this->lang->line('label_unpaid')) ? $this->lang->line('label_unpaid') : 'Unpaid') . '</b>';
                                                                                                                                                                            }
                                                                                                                                                                            echo $status;
                                                                                                                                                                            ?>) </th>
                                                        <th class="text-right" scope="row"><?= get_currency_symbol() . number_format($payslip['net_pay'])  ?></th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-lg-6 text-left">
                                        <div class="text-xl-left mt-5">
                                            <h6><?= !empty($this->lang->line('label_signature')) ? $this->lang->line('label_signature') : 'signature'; ?></h6>
                                            <?php
                                            if (isset($payslip['signature']) && !empty($payslip['signature'])) { ?>
                                                <img alt="Signature" src="<?= base_url('assets/sign/' . $payslip['signature']); ?>" height="100px" width="200px"><br>
                                                <?php if (is_admin() || is_member()) {
                                                    if (check_permissions("payslips", "delete")) { ?>
                                                        <button type="button" id="section-not-to-print" class="btn btn-outline-primary delete-payslips-sign-alert" data-toggle="modal" data-id="<?= $payslip['id'] ?>">
                                                            <?= !empty($this->lang->line('label_delete_signature')) ? $this->lang->line('label_delete_signature') : 'Delete signature'; ?>
                                                        </button>
                                                    <?php }
                                                }
                                            } else {
                                                if (is_admin() || is_member()) {
                                                    if (check_permissions("payslips", "create")) { ?>
                                                        <button type="button" id="section-not-to-print" class="btn btn-outline-primary sign-data mt-1" data-toggle="modal" data-id="<?= $payslip['id'] ?>" data-target=".edit-payslips-sign-modal"><?= !empty($this->lang->line('label_create_signature')) ? $this->lang->line('label_create_signature') : 'Create Signature'; ?></button><br>
                                            <?php }
                                                }
                                            } ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 text-right">
                                        <div class="text-xl-right" id="section-not-to-print">
                                            <button class="btn btn-warning btn-icon icon-left" onclick="window.print()"><i class="fas fa-print"></i> <?= !empty($this->lang->line('label_print')) ? $this->lang->line('label_print') : 'Print'; ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            </section>
        </div>
        <div class="modal fade edit-payslips-sign-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><?= !empty($this->lang->line('label_payslip_sign')) ? $this->lang->line('label_payslip_sign') : ' Payslip Sign'; ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action='<?= base_url('payslip/create_payslips_sign') ?>' method="post" enctype="multipart/form-data" id="sign_form">
                            <input name="id" type="hidden" id="update_id" value="<?= $payslip['id']; ?>">
                            <div class="form-group col-12">
                                <label><?= !empty($this->lang->line('label_signature')) ? $this->lang->line('label_signature') : 'Signature'; ?></label>
                            </div>
                            <div class="form-group col-12">
                                <canvas id="signature-pad1" height="181" style="touch-action: none; user-select: none; border:1px solid #6c757d !important;" width="720"></canvas>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSaveSign" class="btn btn-primary"><?= !empty($this->lang->line('label_save')) ? $this->lang->line('label_save') : 'Save'; ?></button>
                    </div>
                </div>
            </div>
        </div>
        <?php include('include-footer.php'); ?>
    </div>
    </div>
    <?php include('include-js.php'); ?>
</body>
<script>
    payslip_id = <?= $payslip['id']; ?>
</script>
<script src="<?= base_url('assets/js/page/components-payslip.js'); ?>"></script>

</html>