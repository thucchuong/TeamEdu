<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title><?= !empty($this->lang->line('label_tasks_report')) ? $this->lang->line('label_tasks_report') : 'Tasks Report'; ?> &mdash; <?= get_compnay_title(); ?></title>

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
            <h1> <?= !empty($this->lang->line('label_tasks_report')) ? $this->lang->line('label_tasks_report') : 'Tasks Report'; ?></h1>
          </div>

          <div class="section-body">
            <div class="row">
              <div class="col-md-6">
                <div class="card">
                  <div class="card-header">
                    <h4><?= !empty($this->lang->line('label_task_status')) ? $this->lang->line('label_task_status') : 'Tasks Status'; ?></h4>
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
                    <canvas id="tasks-status-chart"></canvas>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card">
                  <div class="card-header">
                    <h4><?= !empty($this->lang->line('label_task_start_month_year')) ? $this->lang->line('label_task_start_month_year') : 'Tasks Started in Month Year'; ?></h4>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <input type="month" class="form-control col-md-3" id="monthPicker" name="month" required>
                      <?php if (!is_client()) { ?>
                      <div class="form-group col-md-3">
                        <select class="form-control" name="user_id" id="user_id">
                          <option value=""><?= !empty($this->lang->line('label_select_users')) ? $this->lang->line('label_select_users') : 'Select Users'; ?></option>
                          <?php foreach ($all_user as $all_users) { ?>
                            <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <?php } ?>
                      <div class="col-md-2">
                        <button type="button" class="btn btn-primary" id="applyFilter"><?= !empty($this->lang->line('label_filter')) ? $this->lang->line('label_filter') : 'Filter'; ?></button>
                      </div>
                    </div>
                    <canvas id="task-start-month-chart" height="100"></canvas>
                  </div>

                </div>
              </div>
              <div class="col-md-6">
                <div class="card">
                  <div class="card-header">
                    <h4><?= !empty($this->lang->line('label_task_end_month_year')) ? $this->lang->line('label_task_end_month_year') . ' ' : 'Tasks Ended in Month Year'; ?></h4>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <input type="month" class="form-control col-md-3" id="monthPicker1" name="month" required>
                      <?php if (!is_client()) { ?>
                      <div class="form-group col-md-3">
                        <select class="form-control" name="user_id" id="user_id1">
                          <option value=""><?= !empty($this->lang->line('label_select_users')) ? $this->lang->line('label_select_users') : 'Select Users'; ?></option>
                          <?php foreach ($all_user as $all_users) { ?>
                            <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <?php } ?>
                      <div class="col-md-2">
                        <button type="button" class="btn btn-primary" id="endFilter"><?= !empty($this->lang->line('label_filter')) ? $this->lang->line('label_filter') : 'Filter'; ?></button>
                      </div>
                    </div>
                    <canvas id="task-end-month-chart" height="100"></canvas>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </section>
      </div>

      <!--forms code goes here-->
    </div>
    <?php include('include-footer.php'); ?>
  </div>
  </div>

  <?php include('include-js.php'); ?>
  <script>
    label_tasks_status = "<?= !empty($this->lang->line('label_tasks_status')) ? $this->lang->line('label_tasks_status') : 'Tasks Status'; ?>";
    label_task_start_month_year = "<?= !empty($this->lang->line('label_task_start_month_year')) ? $this->lang->line('label_task_start_month_year') : 'Tasks Started in month Year'; ?>";
    label_task_end_month_year = "<?= !empty($this->lang->line('label_task_end_month_year')) ? $this->lang->line('label_task_end_month_year') : 'Tasks Ended in month Year'; ?>";

    home_workspace_id = "<?= $this->session->userdata('workspace_id') ?>";
  </script>
  <script>
    var yearSelect = document.getElementById('yearPicker');
    var currentYear = new Date().getFullYear();
    var startYear = 2000;
    var endYear = 2099;

    for (var year = startYear; year <= endYear; year++) {
      var option = document.createElement('option');
      option.value = year;
      option.text = year;

      // Set the 'selected' attribute if the year is the current year
      if (year === currentYear) {
        option.selected = true;
      }

      yearSelect.appendChild(option);
    }
  </script>
  <script src="<?= base_url('assets/js/page/components-tasks-report.js'); ?>"></script>
</body>

</html>