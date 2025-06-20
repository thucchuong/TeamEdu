<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title><?= !empty($this->lang->line('label_tasks')) ? $this->lang->line('label_tasks') : 'Tasks'; ?> &mdash; <?= get_compnay_title(); ?></title>
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
            <h1> <?= !empty($this->lang->line('label_tasks')) ? $this->lang->line('label_tasks') : 'Tasks'; ?></h1>
            <div class="section-header-breadcrumb">
              <?php if (check_permissions("tasks", "create")) { ?>
                <i class="btn btn-primary btn-rounded no-shadow" id="modal-add-task-list"><?= !empty($this->lang->line('label_create_task')) ? $this->lang->line('label_create_task') : 'Create Task'; ?></i>
              <?php } ?>
            </div>
          </div>

          <div class="section-body">
            <div class="row">
              <div class="modal-edit-task "></div>
              <div class="modal-add-task-details"></div>

              <div class='col-md-12'>
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="form-group col-md-4">
                        <input name="projects_name" id="projects_name" type="text" class="form-control" placeholder="<?= !empty($this->lang->line('label_project_name')) ? $this->lang->line('label_project_name') : 'Project Name'; ?>">
                      </div>
                      <div class="form-group col-md-4">

                        <select id="tasks_status" name="tasks_status" class="form-control">
                          <option value=""><?= !empty($this->lang->line('label_select_status')) ? $this->lang->line('label_select_status') : 'Select Status'; ?></option>
                          <?php
                          foreach ($statuses as $status) { ?>
                            <option value="<?= $status['status'] ?>"><?= $status['status'] ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="form-group col-md-4">
                        <input placeholder="<?= !empty($this->lang->line('label_tasks_due_dates_between')) ? $this->lang->line('label_tasks_due_dates_between') : 'Tasks Due Dates Between'; ?>" id="tasks_between" name="tasks_between" type="text" class="form-control" autocomplete="off">
                        <input id="tasks_start_date" name="tasks_start_date" type="hidden">
                        <input id="tasks_end_date" name="tasks_end_date" type="hidden">
                      </div>
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
                      <?php if (!is_member()) { ?>
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
                      <?php } ?>
                      <div class="form-group col-md-2">
                        <i class="btn btn-primary btn-rounded no-shadow" id="fillter-tasks"><?= !empty($this->lang->line('label_filter')) ? $this->lang->line('label_filter') : 'Filter'; ?></i>
                      </div>
                    </div>
                    <table class='table-striped' id='tasks_list' data-toggle="table" data-url="<?= base_url('home/get_tasks_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                      "fileName": "tasks-list",
                      "ignoreColumn": ["state"] 
                    }' data-query-params="queryParams2">
                      <thead>
                        <tr>
                          <th data-field="id" data-visible='false' data-sortable="true"><?= !empty($this->lang->line('label_id')) ? $this->lang->line('label_id') : 'ID'; ?></th>
                          <th data-field="title" data-sortable="true"><?= !empty($this->lang->line('label_tasks')) ? $this->lang->line('label_tasks') : 'Tasks'; ?></th>
                          <th data-field="project_id" data-visible='false' data-sortable="true"><?= !empty($this->lang->line('label_id')) ? $this->lang->line('label_id') : 'Project ID'; ?></th>
                          <th data-field="project_title" data-sortable="true"><?= !empty($this->lang->line('label_projects')) ? $this->lang->line('label_projects') : 'Project'; ?></th>
                          <th data-field="projects_userss" data-sortable="false"><?= !empty($this->lang->line('label_users')) ? $this->lang->line('label_users') : 'Users'; ?></th>
                          <th data-field="projects_clientss" data-sortable="false"><?= !empty($this->lang->line('label_clients')) ? $this->lang->line('label_clients') : 'Clients'; ?></th>
                          <th data-field="description" data-visible='false' data-sortable="true"><?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?></th>
                          <th data-field="priority" data-visible='false' data-sortable="true"><?= !empty($this->lang->line('label_priority')) ? $this->lang->line('label_priority') : 'Priority'; ?></th>
                          <th data-field="status" data-sortable="true"><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></th>
                          <th data-field="start_date" data-visible='false' data-sortable="true"><?= !empty($this->lang->line('label_start_date')) ? $this->lang->line('label_start_date') : 'Start Date'; ?></th>
                          <th data-field="due_date" data-visible='false' data-sortable="true"><?= !empty($this->lang->line('label_due_date')) ? $this->lang->line('label_due_date') : 'Due Date'; ?></th>
                          <?php if (check_permissions("tasks", "read")) { ?>
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
      <?php if (check_permissions("tasks", "create")) { ?>
        <?= form_open('tasks/create_task', 'id="modal-add-task-list-part"', 'class="modal-part"'); ?>
        <div id="modal-title" class="d-none"><?= !empty($this->lang->line('label_add_task')) ? $this->lang->line('label_add_task') : 'Add Task'; ?></div>
        <div id="modal-footer-add-title" class="d-none"><?= !empty($this->lang->line('label_add')) ? $this->lang->line('label_add') : 'Add'; ?></div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label><?= !empty($this->lang->line('label_select_project')) ? $this->lang->line('label_select_project') : 'Select Project'; ?></label>
              <select class="form-control select2" name="project_id" id="project_id_data" onchange="get_milestone_and_user_data()">
                <option value="" selected>Choose...</option>
                <?php foreach ($projects as $project) {
                ?>
                  <option value="<?= $project['id'] ?>"><?= $project['title'] ?></option>
                <?php
                } ?>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label><?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?></label>
              <div class="input-group">
                <input type="text" class="form-control" placeholder=<?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?> name="title">
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="milestone_id"><?= !empty($this->lang->line('label_milestone')) ? $this->lang->line('label_milestone') : 'Milestone'; ?></label>
              <select name='milestone_id' id='milestone_id' class='selectric form-control'>
                <option value=""><?= !empty($this->lang->line('label_select_milestone')) ? $this->lang->line('label_select_milestone') : 'Select Milestone'; ?></option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="status"><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></label><span class="asterisk"> *</span>
              <div class="input-group">
                <select class="custom-select select2" id="statuses_task_id" name="status">
                  <option value="" selected><?= !empty($this->lang->line('label_choose_statuses')) ? $this->lang->line('label_choose_statuses') : 'Choose Statuses'; ?>...</option>
                  <?php
                  foreach ($statuses as $status) { ?>
                    <option value="<?= $status['status'] ?>"><?= $status['status'] ?></option>
                  <?php } ?>
                </select>
                <div class="wrapper" id="wrp" style="display: none;">
                  <hr><a href="#" id="modal-add-statuses"> + <?= !empty($this->lang->line('label_add_statuses')) ? $this->lang->line('label_add_statuses') : 'Add Statuses'; ?></a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="priority"><?= !empty($this->lang->line('label_priority')) ? $this->lang->line('label_priority') : 'Priority'; ?></label>
              <select id="priority" name="priority" class="form-control">
                <option value="low"><?= !empty($this->lang->line('label_low')) ? $this->lang->line('label_low') : 'Low'; ?></option>
                <option value="medium"><?= !empty($this->lang->line('label_medium')) ? $this->lang->line('label_medium') : 'Medium'; ?></option>
                <option value="high"><?= !empty($this->lang->line('label_high')) ? $this->lang->line('label_high') : 'High'; ?></option>
              </select>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label><?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?></label>
              <div class="input-group">
                <textarea type="textarea" class="form-control" placeholder=<?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?> name="description"></textarea>
              </div>
            </div>
          </div>
          <?php if (!is_client()) { ?>
            <div class="col-md-12">
              <div class="form-group">
                <label><?= !empty($this->lang->line('label_assign_to')) ? $this->lang->line('label_assign_to') : 'Assign To'; ?></label>
                <select class="form-control select2" multiple="" name="user_id[]" id="update_user_id">
                  <option value="">Select User</option>
                </select>
              </div>
            </div>
          <?php } ?>
          <div class="col-md-6">
            <div class="form-group">
              <label for="start_date"><?= !empty($this->lang->line('label_start_date')) ? $this->lang->line('label_start_date') : 'Start Date'; ?></label>
              <input class="form-control datepicker" type="text" id="start_date" name="start_date" value="" autocomplete="off">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="end_date"><?= !empty($this->lang->line('label_due_date')) ? $this->lang->line('label_due_date') : 'Due Date'; ?></label>
              <input class="form-control datepicker" type="text" id="due_date" name="due_date" value="" autocomplete="off">
            </div>
          </div>
        </div>
        </form>
      <?php } ?>
      <?php if (check_permissions("statuses", "create")) { ?>
        <?= form_open('Statuses/statuses_create', 'id="modal-add-statuses-part" class="modal-part"'); ?>
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
      <?php include('include-footer.php'); ?>
    </div>
  </div>

  <?php include('include-js.php'); ?>
  <script src="<?= base_url('assets/js/page/tasks.js'); ?>"></script>
  <script src="<?= base_url('assets/js/page/components-user.js'); ?>"></script>
  <script src="<?= base_url('assets/js/page/components-statuses.js'); ?>"></script>
</body>

</html>