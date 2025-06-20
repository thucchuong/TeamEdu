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
                            <?php if (check_permissions("payslips", "create")) { ?>

                                <div class="btn-group mr-2 no-shadow">
                                    <a class="btn btn-primary text-white" href="<?= base_url('payslip/create-payslip') ?>" class="btn"><i class="fas fa-plus"></i><?= !empty($this->lang->line('label_create_payslip')) ? $this->lang->line('label_create_payslip') : 'Create Payslip'; ?></a>
                                </div>
                            <?php } ?>

                        </div>

                    </div>
                    <div class="section-body">
                        <input type="hidden" name="status" id="status">
                        <div class="section-body">
                            <div class="row">
                                <div class='col-md-12'>
                                    <div class="card">
                                        <div class="card-body">
                                            <table class='table-striped' id='payslip_list' data-toggle="table" data-url="<?= base_url('payslip/get_payslip_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-options='{
                      "fileName": "payslip-list"
                    }' data-query-params="queryParams">
                                                <thead>
                                                    <tr>
                                                        <th data-field="id" data-sortable="true"><?= !empty($this->lang->line('label_payslip')) ? $this->lang->line('label_payslip') : 'Payslip'; ?></th>
                                                        <th data-field="workspace_id" data-visible="false" data-sortable="true"><?= !empty($this->lang->line('label_workspace_id')) ? $this->lang->line('label_workspace_id') : 'Workspace ID'; ?></th>
                                                        <th data-field="user_id" data-sortable="true" data-visible="false"><?= !empty($this->lang->line('label_user_id')) ? $this->lang->line('label_user_id') : 'User ID'; ?></th>
                                                        <th data-field="username" data-sortable="true"><?= !empty($this->lang->line('label_username')) ? $this->lang->line('label_username') : 'Username'; ?></th>
                                                        <th data-field="payslip_month" data-sortable="true"><?= !empty($this->lang->line('label_payslip_month')) ? $this->lang->line('label_payslip_month') : 'Payslip Month'; ?></th>
                                                        <th data-field="basic_salary" data-sortable="true"><?= !empty($this->lang->line('label_basic_salary')) ? $this->lang->line('label_basic_salary') : 'Basic Salary'; ?></th>
                                                        <th data-field="total_earnings" data-sortable="false"><?= !empty($this->lang->line('label_total_earnings')) ? $this->lang->line('label_total_earnings') : 'Total Earnings'; ?></th>
                                                        <th data-field="total_deductions" data-sortable="false"><?= !empty($this->lang->line('label_total_deductions')) ? $this->lang->line('label_total_deductions') : 'Total Deductions'; ?></th>
                                                        <th data-field="net_pay" data-sortable="false"><?= !empty($this->lang->line('label_net_pay')) ? $this->lang->line('label_net_pay') : 'Net Pay'; ?></th>
                                                        <th data-field="payment_date" data-sortable="false"><?= !empty($this->lang->line('label_payment_date')) ? $this->lang->line('label_payment_date') : 'Payment Date'; ?></th>
                                                        <th data-field="payment_method" data-sortable="false"><?= !empty($this->lang->line('label_payment_method')) ? $this->lang->line('label_payment_method') : 'Payment Method'; ?></th>
                                                        <th data-field="status" data-sortable="false"><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></th>

                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
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
</body>
<script src="<?= base_url('assets/js/page/components-payslip.js'); ?>"></script>

</html>