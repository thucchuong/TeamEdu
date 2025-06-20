<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= !empty($this->lang->line('label_time_tracker')) ? $this->lang->line('label_time_tracker') : 'Time Tracker'; ?> &mdash; <?= get_compnay_title(); ?></title>
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
                        <h1><?= !empty($this->lang->line('label_time_tracker')) ? $this->lang->line('label_time_tracker') : 'Time Tracker'; ?></h1>
                        <div class="section-header-breadcrumb">
                            <?php if (check_permissions("time_tracker", "create")) { ?>
                                <i class="btn btn-primary btn-rounded no-shadow" id="modal-add-time-tracker"><?= !empty($this->lang->line('label_add_time_sheet')) ? $this->lang->line('label_add_time_sheet') : 'Add Time Sheet'; ?></i>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="section-body">
                    <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                    <div class="card card-statistic-2">
                                        <div class="bg-success card-icon shadow-success">
                                            <i class="fa-chart-bar fas"></i>
                                        </div>
                                        <div class="card-wrap">
                                            <div class="card-header">
                                                <h4><?= !empty($this->lang->line('label_todays_total_time')) ? $this->lang->line('label_todays_total_time') : "Today's Total Time"; ?></h4>
                                            </div>
                                            <div class="card-body">
                                                <?= $today_sheet ? $today_sheet: '00:00:00' ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                    <div class="card card-statistic-2">
                                        <div class="bg-danger card-icon shadow-danger">
                                            <i class="fa-chart-area fas"></i>
                                        </div>
                                        <div class="card-wrap">
                                            <div class="card-header">
                                                <h4><?= !empty($this->lang->line('label_weeks_total_time')) ? $this->lang->line('label_weeks_total_time') : "Week's Total Time"; ?></h4>
                                            </div>
                                            <div class="card-body">
                                                <?= $weekly_sheet ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                    <div class="card card-statistic-2">
                                        <div class="bg-secondary card-icon shadow-secondary">
                                            <i class="fa-chart-line fas"></i>
                                        </div>
                                        <div class="card-wrap">
                                            <div class="card-header">
                                                <h4><?= !empty($this->lang->line('label_months_total_time')) ? $this->lang->line('label_months_total_time') : "Month's Total Time"; ?></h4>
                                            </div>
                                            <div class="card-body">
                                                <?= $monthly_sheet ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                    <div class="card card-statistic-2">
                                        <div class="bg-info card-icon shadow-info">
                                            <i class="fa-chart-pie fas"></i>
                                        </div>
                                        <div class="card-wrap">
                                            <div class="card-header">
                                                <h4><?= !empty($this->lang->line('label_years_total_time')) ? $this->lang->line('label_years_total_time') : "Year's Total Time"; ?></h4>
                                            </div>
                                            <div class="card-body">
                                                <?= $yearly_sheet ?></div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <input placeholder="<?= !empty($this->lang->line('label_time_tracker_dates_between')) ? $this->lang->line('label_time_tracker_dates_between') : 'Time Tracker Dates Between Range'; ?>" id="time_traker_between" name="time_traker_between" type="text" class="form-control" autocomplete="off">
                                        <input id="start_date" name="start_date" type="hidden">
                                        <input id="end_date" name="end_date" type="hidden">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <select class="form-control" name="user_id" id="user_id">
                                            <option value=""><?= !empty($this->lang->line('label_select_users')) ? $this->lang->line('label_select_users') : 'Select Users'; ?></option>
                                            <?php foreach ($all_user as $all_users) {
                                                if (is_client($all_users->id)) { ?>
                                                    <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <i class="btn btn-primary btn-rounded no-shadow" id="fillter-time-tracker"><?= !empty($this->lang->line('label_filter')) ? $this->lang->line('label_filter') : 'Filter'; ?></i>
                                    </div>
                                </div>
                                <table class='table-striped' id='time_tracker_list' data-toggle="table" data-url="<?= base_url('time_tracker/get_time_tracker_sheet_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                      "fileName": "time-tracker-list",
                    }' data-query-params="time_tracker_query_params">
                                    <thead>
                                        <tr>
                                            <th data-field="id" data-visible="false" data-sortable="true"><?= !empty($this->lang->line('label_id')) ? $this->lang->line('label_id') : 'ID'; ?></th>

                                            <th data-field="username" data-sortable="true"><?= !empty($this->lang->line('label_users')) ? $this->lang->line('label_users') : 'Users'; ?></th>

                                            <th data-field="start_time" data-sortable="true"><?= !empty($this->lang->line('label_start_time')) ? $this->lang->line('label_start_time') : 'Start Time'; ?></th>

                                            <th data-field="end_time" data-sortable="true"><?= !empty($this->lang->line('label_end_time')) ? $this->lang->line('label_end_time') : 'End Time'; ?></th>

                                            <th data-field="duration" data-sortable="true"><?= !empty($this->lang->line('label_duration')) ? $this->lang->line('label_duration') : 'Duration'; ?></th>

                                            <th data-field="message" data-sortable="true"><?= !empty($this->lang->line('label_message')) ? $this->lang->line('label_message') : 'Message'; ?></th>

                                            <th data-field="project_id" data-sortable="true"><?= !empty($this->lang->line('label_project')) ? $this->lang->line('label_project') : 'project'; ?></th>

                                            <th data-field="date" data-sortable="true"><?= !empty($this->lang->line('label_date')) ? $this->lang->line('label_date') : 'Date'; ?></th>

                                            <!-- <?php if ($this->ion_auth->is_admin() || is_workspace_admin($this->session->userdata('user_id'), $this->session->userdata('workspace_id'))) { ?>
                                            <th data-field="active" data-sortable="false"><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></th>
                                        <?php } ?> -->

                                            <th data-field="action" data-sortable="false"><?= !empty($this->lang->line('label_action')) ? $this->lang->line('label_action') : 'Action'; ?></th>

                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                </section>
            </div>
            <?php if (check_permissions("time_tracker", "create")) { ?>
            <?= form_open('time_tracker/create', 'id="modal-add-time-tracker-part"', 'class="modal-part"'); ?>
            <div id="modal-title" class="d-none"><?= !empty($this->lang->line('label_add_time_sheet')) ? $this->lang->line('label_add_time_sheet') : 'Add Time Sheet'; ?></div>
            <div id="modal-footer-add-title" class="d-none"><?= !empty($this->lang->line('label_add')) ? $this->lang->line('label_add') : 'Add'; ?></div>
            <div class="form-group">
                <select class="form-control select2" name="project_id" id="project_id">
                    <option value="" selected><?= !empty($this->lang->line('label_select_project')) ? $this->lang->line('label_select_project') : 'Select Project'; ?></option>
                    <?php foreach ($projects as $project) {
                    ?>
                        <option value="<?= $project['id'] ?>"><?= $project['title'] ?></option>
                    <?php
                    } ?>
                </select>
            </div>
            <div class="form-group">
                <label><?= !empty($this->lang->line('label_start_date')) ? $this->lang->line('label_start_date') : 'Start date'; ?></label>
                <div class="input-group">
                    <input class="form-control" type="time" id="start_date" name="start_date" autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <label><?= !empty($this->lang->line('label_end_date')) ? $this->lang->line('label_end_date') : 'End date'; ?></label>
                <div class="input-group">
                    <input class="form-control" type="time" id="end_date" name="end_date" autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <label class="label"><?= !empty($this->lang->line('label_message')) ? $this->lang->line('label_message') : 'Message'; ?>:</label>
                <textarea class="form-control" id="message" placeholder="Enter Your Message" name="message" required></textarea>
            </div>
            <div class="form-group">
                <label for="date"><?= !empty($this->lang->line('label_date')) ? $this->lang->line('label_date') : 'Date'; ?></label>
                <input class="form-control datetimepicker" type="text" id="date" name="date" value="" autocomplete="off">
            </div>
            </form>
            <?php } ?>
            <div class="modal-edit-time-tracker"></div>
            <?= form_open('time_tracker/edit', 'id="modal-edit-time-tracker-part"', 'class="modal-part"'); ?>
            <div id="modal-edit-time-tracker-title" class="d-none"><?= !empty($this->lang->line('label_edit_time_sheet')) ? $this->lang->line('label_edit_time_sheet') : 'Edit Time Sheet'; ?></div>
            <div id="modal-footer-edit-title" class="d-none"><?= !empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit'; ?></div>

            <input name="update_id" type="hidden" id="update_id" value="">
            <div class="form-group">
                <select class="form-control select2" name="project_id" id="update_project_id">
                    <option value="" selected><?= !empty($this->lang->line('label_select_project')) ? $this->lang->line('label_select_project') : 'Select Project'; ?></option>
                    <?php foreach ($projects as $project) {
                    ?>
                        <option value="<?= $project['id'] ?>"><?= $project['title'] ?></option>
                    <?php
                    } ?>
                </select>
            </div>
            <div class="form-group">
                <select class="form-control select2" name="task_id" id="update_task_id">
                    <option value="" selected><?= !empty($this->lang->line('label_select_task')) ? $this->lang->line('label_select_task') : 'Select task'; ?></option>
                    <?php foreach ($tasks as $task) {
                    ?>
                        <option value="<?= $task['id'] ?>"><?= $task['title'] ?></option>
                    <?php
                    } ?>
                </select>
            </div>
            <div class="form-group">
                <label><?= !empty($this->lang->line('label_start_date')) ? $this->lang->line('label_start_date') : 'Start date'; ?></label>
                <div class="input-group">
                    <input class="form-control" type="time" id="update_start_date" name="start_date" value="00:00:00" autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <label><?= !empty($this->lang->line('label_end_date')) ? $this->lang->line('label_end_date') : 'End date'; ?></label>
                <div class="input-group">
                    <input class="form-control" type="time" id="update_end_date" value="00:00:00" name="end_date" autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <label class="label"><?= !empty($this->lang->line('label_message')) ? $this->lang->line('label_message') : 'Message'; ?>:</label>
                <textarea class="form-control" id="update_message" placeholder="Enter Your Message" name="message" required></textarea>
            </div>
            <div class="form-group">
                <label for="date"><?= !empty($this->lang->line('label_date')) ? $this->lang->line('label_date') : 'Date'; ?></label>
                <input class="form-control datetimepicker" type="text" id="update_date" name="date" value="" autocomplete="off">
            </div>
            </form>
            <?php include('include-footer.php'); ?>
        </div>
    </div>



    <?php include('include-js.php'); ?>

    <!-- Page Specific JS File -->
    <script src="assets/js/page/components-user.js"></script>

</body>

</html>