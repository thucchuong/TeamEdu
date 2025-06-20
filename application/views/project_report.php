<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= !empty($this->lang->line('label_projects_report')) ? $this->lang->line('label_projects_report') : 'Projects Report'; ?> &mdash; <?= get_compnay_title(); ?></title>

    <?php include('include-css.php'); ?>
</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <!-- navbar -->
            <?php include('include-header.php'); ?>
            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1><?= !empty($this->lang->line('label_projects_report')) ? $this->lang->line('label_projects_report') : 'Projects Report'; ?></h1>
                    </div>
                    <div class="card col-md-12">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4><?= !empty($this->lang->line('label_projects_status')) ? $this->lang->line('label_projects_status') : 'Projects Status'; ?></h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <select id="yearPicker" class="form-control"></select>
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-primary" id="statusFilter"><?= !empty($this->lang->line('label_filter')) ? $this->lang->line('label_filter') : 'Filter'; ?></button>
                                                </div>
                                            </div>
                                            <canvas id="project-status-chart" height="150"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4><?= !empty($this->lang->line('label_projects_priority')) ? $this->lang->line('label_projects_priority') : 'Projects Priority'; ?></h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <select id="yearPicker1" class="form-control"></select>
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-primary" id="priorityFilter"><?= !empty($this->lang->line('label_filter')) ? $this->lang->line('label_filter') : 'Filter'; ?></button>
                                                </div>
                                            </div>
                                            <canvas id="project-priority-chart" height="150"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4><?= !empty($this->lang->line('label_project_start_month_year')) ? $this->lang->line('label_project_start_month_year') . ' ' : 'Projects Started in Month Year'; ?></h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <input type="month" class="form-control col-md-3" id="monthPicker" name="month" required>
                                                <?php if (!is_client()) { ?>
                                                    <div class="form-group col-md-3">
                                                        <select id="client_id" name="client_id" class="form-control">
                                                            <option value=""><?= !empty($this->lang->line('label_select_clients')) ? $this->lang->line('label_select_clients') : 'Select Clients'; ?></option>
                                                            <?php foreach ($all_user as $all_users) {
                                                                if (is_client($all_users->id)) { ?>
                                                                    <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                                                            <?php }
                                                            } ?>
                                                        </select>
                                                    </div>
                                                <?php } ?>
                                                <div class="form-group col-md-3">
                                                    <select class="form-control" name="user_id" id="user_id">
                                                        <option value=""><?= !empty($this->lang->line('label_select_users')) ? $this->lang->line('label_select_users') : 'Select Users'; ?></option>
                                                        <?php foreach ($all_user as $all_users) {
                                                            if (!is_client($all_users->id)) { ?>
                                                                <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                                                        <?php }
                                                        } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-primary" id="applyFilter"><?= !empty($this->lang->line('label_filter')) ? $this->lang->line('label_filter') : 'Filter'; ?></button>
                                                </div>
                                            </div>
                                            <canvas id="project-start-month-chart" height="100"></canvas>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4><?= !empty($this->lang->line('label_project_end_month_year')) ? $this->lang->line('label_project_end_month_year') . ' ' : 'Projects Ended in Month Year'; ?></h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <input type="month" class="form-control col-md-3" id="monthPicker1" name="month" required>
                                                <?php if (!is_client()) { ?>
                                                    <div class="form-group col-md-3">
                                                        <select id="client_id1" name="client_id" class="form-control">
                                                            <option value=""><?= !empty($this->lang->line('label_select_clients')) ? $this->lang->line('label_select_clients') : 'Select Clients'; ?></option>
                                                            <?php foreach ($all_user as $all_users) {
                                                                if (is_client($all_users->id)) { ?>
                                                                    <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                                                            <?php }
                                                            } ?>
                                                        </select>
                                                    </div>
                                                <?php } ?>
                                                <div class="form-group col-md-3">
                                                    <select class="form-control" name="user_id" id="user_id1">
                                                        <option value=""><?= !empty($this->lang->line('label_select_users')) ? $this->lang->line('label_select_users') : 'Select Users'; ?></option>
                                                        <?php foreach ($all_user as $all_users) {
                                                            if (!is_client($all_users->id)) { ?>
                                                                <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                                                        <?php }
                                                        } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-primary" id="endFilter"><?= !empty($this->lang->line('label_filter')) ? $this->lang->line('label_filter') : 'Filter'; ?></button>
                                                </div>
                                            </div>
                                            <canvas id="project-end-month-chart" height="100"></canvas>
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
    <script>
        label_projects_status = "<?= !empty($this->lang->line('label_projects_status')) ? $this->lang->line('label_projects_status') : 'Projects Status'; ?>";
        label_projects_priority = "<?= !empty($this->lang->line('label_projects_priority')) ? $this->lang->line('label_projects_priority') : 'Projects priority'; ?>";
        label_project_start_month_year = "<?= !empty($this->lang->line('label_project_start_month_year')) ? $this->lang->line('label_project_start_month_year') : 'Projects start month'; ?>";
        label_project_end_month_year = "<?= !empty($this->lang->line('label_project_end_month_year')) ? $this->lang->line('label_project_end_month_year') : 'Projects end month'; ?>";
        home_workspace_id = "<?= $this->session->userdata('workspace_id') ?>";
    </script>

    <?php include('include-js.php'); ?>
    <!-- Page Specific JS File -->
    <script src="<?= base_url('assets/js/page/components-project-report.js'); ?>"></script>

</body>

</html>