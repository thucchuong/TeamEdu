<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= !empty($this->lang->line('label_invoices_report')) ? $this->lang->line('label_invoices_report') : 'Invoices report'; ?> &mdash;<?= get_compnay_title(); ?></title>

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
                        <h1><?= !empty($this->lang->line('label_invoices_report')) ? $this->lang->line('label_invoices_report') : 'Invoices report'; ?></h1>
                    </div>

                    <div class="section-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4><?= !empty($this->lang->line('label_income_invoices')) ? $this->lang->line('label_income_invoices') : 'Income Invoices'; ?></h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <input type="month" class="form-control col-md-3" id="monthPicker" name="month" required>
                                            <div class="form-group col-md-3">
                                                <select class="form-control" name="client_id" id="client_id">
                                                    <option value=""><?= !empty($this->lang->line('label_select_clients')) ? $this->lang->line('label_select_clients') : 'Select Clients'; ?></option>
                                                    <?php foreach ($all_user as $all_users) {
                                                        if (is_client($all_users->id)) { ?>
                                                            <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                                                    <?php }
                                                    } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-primary" id="applyFilter"><?= !empty($this->lang->line('label_filter')) ? $this->lang->line('label_filter') : 'Filter'; ?></button>
                                            </div>
                                        </div>
                                        <canvas id="income-invoices-chart" height="100"></canvas>
                                    </div>

                                </div>
                            </div>
                        </div>
                </section>
            </div>

            </section>
        </div>

        <?php include('include-footer.php'); ?>
    </div>
    </div>

    <?php include('include-js.php'); ?>
    <script>
        label_income_invoices = "<?= !empty($this->lang->line('label_income_invoices')) ? $this->lang->line('label_income_invoices') : 'Income invoices'; ?>";
        label_fully_paid = "<?= !empty($this->lang->line('label_fully_paid')) ? $this->lang->line('label_fully_paid') : 'Fully paid'; ?>";
        label_partially_paid = "<?= !empty($this->lang->line('label_partially_paid')) ? $this->lang->line('label_partially_paid') : 'Partially paid'; ?>";
        label_draft = "<?= !empty($this->lang->line('label_draft')) ? $this->lang->line('label_draft') : 'Draft'; ?>";
        label_cancelled = "<?= !empty($this->lang->line('label_cancelled')) ? $this->lang->line('label_cancelled') : 'Cancelled'; ?>";
        label_due = "<?= !empty($this->lang->line('label_due')) ? $this->lang->line('label_due') : 'Due'; ?>";
        home_workspace_id = "<?= $this->session->userdata('workspace_id') ?>";
    </script>
    <script src="<?= base_url('assets/js/page/components-invoice-report.js'); ?>"></script>

</html>