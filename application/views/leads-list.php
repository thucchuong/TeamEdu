<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title><?= !empty($this->lang->line('label_leads')) ? $this->lang->line('label_leads') : 'Leads'; ?> &mdash; <?= get_compnay_title(); ?></title>

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
            <h1><?= !empty($this->lang->line('label_leads')) ? $this->lang->line('label_leads') : 'Leads'; ?></h1>
            <div class="section-header-breadcrumb">
              <div class="btn-group mr-2 no-shadow d-block">
                <a href="<?= base_url('leads') ?>" class="btn btn-primary text-white"><i class="fas fa-th-large"></i> <?= !empty($this->lang->line('label_grid_view')) ? $this->lang->line('label_grid_view') : 'Grid View'; ?></a>
                <a href="<?= base_url('leads/lists') ?>" class="btn"><i class="fas fa-list"></i> <?= !empty($this->lang->line('label_list_view')) ? $this->lang->line('label_list_view') : 'List View'; ?></a>
                <?php if (check_permissions("leads", "create")) { ?>
                  <i class="btn btn-primary btn-rounded no-shadow" id="modal-add-lead"><?= !empty($this->lang->line('label_create_leads')) ? $this->lang->line('label_create_lead') : 'Create Lead'; ?></i>
                <?php } ?>
              </div>
            </div>
          </div>
          <div class="section-body">
            <div class="row">
              <div class='col-md-12'>
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="form-group col-md-3">
                        <select name="status" id="leads_status" class="form-control">
                          <option value=""><?= !empty($this->lang->line('label_select_status')) ? $this->lang->line('label_select_status') : 'Select Status'; ?></option>
                          <option value="new"><?= !empty($this->lang->line('label_new')) ? $this->lang->line('label_new') : 'New'; ?></option>
                          <option value="qualified"><?= !empty($this->lang->line('label_qualified')) ? $this->lang->line('label_qualified') : 'Qualified'; ?></option>
                          <option value="discussion"><?= !empty($this->lang->line('label_discussion')) ? $this->lang->line('label_discussion') : 'Discussion'; ?></option>
                          <option value="won"><?= !empty($this->lang->line('label_won')) ? $this->lang->line('label_won') : 'Won'; ?></option>
                          <option value="lost"><?= !empty($this->lang->line('label_lost')) ? $this->lang->line('label_lost') : 'Lost'; ?></option>
                        </select>
                      </div>
                      <div class="form-group col-md-4">
                        <input placeholder="<?= !empty($this->lang->line('label_leads_due_dates_between')) ? $this->lang->line('label_leads_due_dates_between') : 'Leads Due Dates Between'; ?>" id="leads_between" name="leads_between" type="text" class="form-control" autocomplete="off">
                        <input id="leads_start_date" name="leads_start_date" type="hidden">
                        <input id="leads_end_date" name="leads_end_date" type="hidden">
                      </div>
                      <?php if (!is_client()) { ?>
                        <div class="form-group col-md-3">
                          <select class="form-control select2" name="user_ids" id="user_id">
                            <option value=""><?= !empty($this->lang->line('label_select_users')) ? $this->lang->line('label_select_users') : 'Select Users'; ?></option>
                            <?php foreach ($all_user as $all_users) {
                            ?>
                              <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                            <?php
                            } ?>
                          </select>
                        </div>
                      <?php
                      } ?>
                      <div class="form-group col-md-2">
                        <i class="btn btn-primary btn-rounded no-shadow" id="fillter-leads"><?= !empty($this->lang->line('label_filter')) ? $this->lang->line('label_filter') : 'Filter'; ?></i>
                      </div>
                    </div>
                    <table class='table-striped' id='leads_list' data-toggle="table" data-url="<?= base_url('leads/get_leads_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                      "fileName": "Leads-list"
                    }' data-query-params="queryParams">
                      <thead>
                        <tr>
                          <th data-field="id" data-sortable="true"><?= !empty($this->lang->line('label_id')) ? $this->lang->line('label_id') : 'ID'; ?></th>
                          <th data-field="title" data-sortable="true"><?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'title'; ?></th>
                          <th data-field="description" data-sortable="true" data-visible="false"><?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?></th>
                          <th data-field="email" data-sortable="true"><?= !empty($this->lang->line('label_email')) ? $this->lang->line('label_email') : 'email'; ?></th>
                          <th data-field="user_ids" data-sortable="false"><?= !empty($this->lang->line('label_assigned')) ? $this->lang->line('label_assigned') : 'Assigned'; ?></th>
                          <th data-field="phone" data-sortable="true"><?= !empty($this->lang->line('label_phone')) ? $this->lang->line('label_phone') : 'Phone'; ?></th>
                          <th data-field="status" data-sortable="false"><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></th>
                          <th data-field="assigned_date" data-sortable="false"><?= !empty($this->lang->line('label_assigned_date')) ? $this->lang->line('label_assigned_date') : 'Assigned Date'; ?></th>
                          <?php if ((check_permissions("leads", "update")) || (check_permissions("clients", "create")) || (check_permissions("leads", "create")) || (check_permissions("leads", "delete"))) { ?>
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
      <?php if (check_permissions("leads", "create")) { ?>
        <?= form_open(base_url('leads/create'), 'id="modal-add-lead-part"', 'class="modal-part"'); ?>
        <div id="modal-title" class="d-none"><?= !empty($this->lang->line('label_create_lead')) ? $this->lang->line('label_create_lead') : 'Create Lead'; ?></div>
        <div id="modal-footer-add-title" class="d-none"><?= !empty($this->lang->line('label_add')) ? $this->lang->line('label_add') : 'Add'; ?></div>
        <div class="row">
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
              <label><?= !empty($this->lang->line('label_email')) ? $this->lang->line('label_email') : 'Email'; ?></label>
              <div class="input-group">
                <input type="hidden" name="group_id" value="3">
                <input type="email" class="demo-default" placeholder=<?= !empty($this->lang->line('label_email')) ? $this->lang->line('label_email') : 'Email'; ?> name="email" id="email">
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label><?= !empty($this->lang->line('label_phone')) ? $this->lang->line('label_phone') : 'Phone'; ?></label>
              <div class="input-group">
                <input type="number" class="form-control" placeholder=<?= !empty($this->lang->line('label_phone')) ? $this->lang->line('label_phone') : 'Phone'; ?> name="phone">
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></label>
              <select name="status" class="form-control">
                <option value="new"><?= !empty($this->lang->line('label_new')) ? $this->lang->line('label_new') : 'New'; ?></option>
                <option value="qualified"><?= !empty($this->lang->line('label_qualified')) ? $this->lang->line('label_qualified') : 'Qualified'; ?></option>
                <option value="discussion"><?= !empty($this->lang->line('label_discussion')) ? $this->lang->line('label_discussion') : 'Discussion'; ?></option>
                <option value="won"><?= !empty($this->lang->line('label_won')) ? $this->lang->line('label_won') : 'Won'; ?></option>
                <option value="lost"><?= !empty($this->lang->line('label_lost')) ? $this->lang->line('label_lost') : 'Lost'; ?></option>
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

          <div class="col-md-6">
            <div class="form-group">
              <label><?= !empty($this->lang->line('label_assigned_date')) ? $this->lang->line('label_assigned_date') : 'Assigned Date'; ?></label>
              <div class="input-group">
                <input type="datetime-local" class="form-control" placeholder=<?= !empty($this->lang->line('label_assigned_date')) ? $this->lang->line('label_assigned_date') : 'Assigned Date'; ?> name="assigned_date">
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label><?= !empty($this->lang->line('label_select_assigned_users')) ? $this->lang->line('label_select_assigned_users') : 'Assigned'; ?></label>
              <select class="form-control select2" multipl="" name="users[]" id="users">
                <?php foreach ($all_user as $all_users) { ?>
                  <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
        </form>
      <?php } ?>

      <div class="modal-edit-leads"></div>

      <form action="<?= base_url('leads/edit'); ?>" method="post" class="modal-part" id="modal-edit-leads-part">
        <div id="modal-title" class="d-none"><?= !empty($this->lang->line('label_edit_lead')) ? $this->lang->line('label_edit_lead') : 'Edit Lead'; ?></div>
        <div id="modal-footer-edit-title" class="d-none"><?= !empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit'; ?></div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label><?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?></label>
              <div class="input-group">
                <input type="hidden" name="update_id" id="update_id">
                <input type="text" class="form-control" placeholder=<?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?> name="title" id="update_title">
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label><?= !empty($this->lang->line('label_email')) ? $this->lang->line('label_email') : 'Email'; ?></label>
              <div class="input-group">
                <input type="email" class="form-control" placeholder=<?= !empty($this->lang->line('label_email')) ? $this->lang->line('label_email') : 'Email'; ?> name="email" id="update_email" readonly>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label><?= !empty($this->lang->line('label_phone')) ? $this->lang->line('label_phone') : 'Phone'; ?></label>
              <div class="input-group">
                <input type="number" class="form-control" placeholder=<?= !empty($this->lang->line('label_phone')) ? $this->lang->line('label_phone') : 'Phone'; ?> name="phone" id="update_phone">
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="status"><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></label>
              <select name="status" class="form-control" id="update_status">
                <option value="new"><?= !empty($this->lang->line('label_new')) ? $this->lang->line('label_new') : 'New'; ?></option>
                <option value="qualified"><?= !empty($this->lang->line('label_qualified')) ? $this->lang->line('label_qualified') : 'Qualified'; ?></option>
                <option value="discussion"><?= !empty($this->lang->line('label_discussion')) ? $this->lang->line('label_discussion') : 'Discussion'; ?></option>
                <option value="won"><?= !empty($this->lang->line('label_won')) ? $this->lang->line('label_won') : 'Won'; ?></option>
                <option value="lost"><?= !empty($this->lang->line('label_lost')) ? $this->lang->line('label_lost') : 'Lost'; ?></option>
              </select>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label><?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?></label>
              <div class="input-group">
                <textarea type="textarea" id="update_description" class="form-control" placeholder=<?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?> name="description"></textarea>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label><?= !empty($this->lang->line('label_assigned_date')) ? $this->lang->line('label_assigned_date') : 'Assigned Date'; ?></label>
              <div class="input-group">
                <input type="datetime-local" id="update_assigned_date" class="form-control" placeholder=<?= !empty($this->lang->line('label_assigned_date')) ? $this->lang->line('label_assigned_date') : 'Assigned Date'; ?> name="assigned_date">
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label><?= !empty($this->lang->line('label_select_assigned_users')) ? $this->lang->line('label_select_assigned_users') : 'Assigned'; ?></label>
              <select class="form-control select2" multiple="" name="users[]" id="update_users">
                <?php foreach ($all_user as $all_users) { ?>
                  <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
    </div>
    </form>
    <div class="modal-edit-client-leads"></div>
    <?= form_open('auth/create_user', 'id="modal-edit-client-leads-part"', 'class="modal-part"'); ?>
    <input type="hidden" name="user_type" value="client">
    <input type="hidden" name="update_id" id="update_id">
    <input type="hidden" name="admin_id" value="<?= $this->session->userdata('user_id') ?>">
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label><?= !empty($this->lang->line('label_email')) ? $this->lang->line('label_email') : 'Email'; ?></label>
          <div class="input-group">
            <input type="email" name="email" class="form-control update_email" readonly>
          </div>
        </div>
      </div>
      <div class="col-md-6" id="first_name">
        <div class="form-group">
          <label><?= !empty($this->lang->line('label_first_name')) ? $this->lang->line('label_first_name') : 'First Name'; ?></label>
          <div class="input-group">
            <?= form_input(['name' => 'first_name', 'placeholder' => !empty($this->lang->line('label_first_name')) ? $this->lang->line('label_first_name') : 'First Name', 'class' => 'form-control']) ?>
          </div>
        </div>
      </div>
      <div class="col-md-6" id="last_name">
        <div class="form-group">
          <label><?= !empty($this->lang->line('label_last_name')) ? $this->lang->line('label_last_name') : 'Last Name'; ?></label>
          <div class="input-group">
            <?= form_input(['name' => 'last_name', 'placeholder' => !empty($this->lang->line('label_last_name')) ? $this->lang->line('label_last_name') : 'Last Name', 'class' => 'form-control']) ?>
          </div>
        </div>
      </div>

      <div class="col-md-6" id="company">
        <div class="form-group">
          <label><?= !empty($this->lang->line('label_company')) ? $this->lang->line('label_company') : 'Company'; ?></label>
          <div class="input-group">
            <?= form_input(['name' => 'company', 'placeholder' => !empty($this->lang->line('label_company')) ? $this->lang->line('label_company') : 'Company', 'class' => 'form-control update_title']) ?>
          </div>
        </div>
      </div>

      <div class="col-md-6" id="phone">
        <div class="form-group">
          <label><?= !empty($this->lang->line('label_phone')) ? $this->lang->line('label_phone') : 'Phone'; ?></label>
          <div class="input-group">
            <?= form_input(['name' => 'phone', 'type' => 'number', 'placeholder' => !empty($this->lang->line('label_phone')) ? $this->lang->line('label_phone') : 'Phone', 'class' => 'form-control update_phone']) ?>
          </div>
        </div>
      </div>

      <div class="col-md-6" id="date_of_birth">
        <div class="form-group">
          <label><?= !empty($this->lang->line('label_date_of_birth')) ? $this->lang->line('label_date_of_birth') : 'Date of Birth'; ?></label>
          <div class="input-group">
            <?= form_input(['name' => 'date_of_birth', 'type' => 'text', 'placeholder' => !empty($this->lang->line('label_date_of_birth')) ? $this->lang->line('label_date_of_birth') : 'Date of Birth', 'class' => 'form-control datepicker']) ?>
          </div>
        </div>
      </div>

      <div class="col-md-6" id="date_of_joining">
        <div class="form-group">
          <label><?= !empty($this->lang->line('label_date_of_joining')) ? $this->lang->line('label_date_of_joining') : 'Date of Joining'; ?></label>
          <div class="input-group">
            <?= form_input(['name' => 'date_of_joining', 'type' => 'text', 'placeholder' => !empty($this->lang->line('label_date_of_joining')) ? $this->lang->line('label_date_of_joining') : 'Date of Joining', 'class' => 'form-control datepicker']) ?>
          </div>
        </div>
      </div>

      <div class="col-md-6" id="gender">
        <div class="form-group">
          <label class="form-control-label col-md-12"><?= !empty($this->lang->line('label_gender')) ? $this->lang->line('label_gender') : 'Gender'; ?></label>

          <input id="male" name="gender" type="radio" class="" value="0" <?php echo $this->form_validation->set_radio('gender', 0); ?> />
          <label for="male" class=""><?= !empty($this->lang->line('label_male')) ? $this->lang->line('label_male') : 'Male'; ?></label>
          <input id="female" name="gender" type="radio" class="" value="1" <?php echo $this->form_validation->set_radio('gender', 1); ?> />
          <label for="female" class=""><?= !empty($this->lang->line('label_female')) ? $this->lang->line('label_female') : 'Female'; ?></label>

        </div>
      </div>

      <div class="col-md-6" id="designation">
        <div class="form-group">
          <label><?= !empty($this->lang->line('label_designation')) ? $this->lang->line('label_designation') : 'Designation'; ?></label>
          <div class="input-group">
            <?= form_input(['name' => 'designation', 'type' => 'text', 'placeholder' => !empty($this->lang->line('label_designation')) ? $this->lang->line('label_designation') : 'Designation', 'class' => 'form-control']) ?>
          </div>
        </div>
      </div>

      <div class="col-md-6" id="password">
        <div class="form-group">
          <label><?= !empty($this->lang->line('label_password')) ? $this->lang->line('label_password') : 'Password'; ?></label>
          <div class="input-group">
            <?= form_input(['name' => 'password', 'placeholder' => !empty($this->lang->line('label_password')) ? $this->lang->line('label_password') : 'Password', 'class' => 'form-control']) ?>
          </div>
        </div>
      </div>
      <div class="col-md-6" id="password_confirm">
        <div class="form-group">
          <label><?= !empty($this->lang->line('label_confirm_password')) ? $this->lang->line('label_confirm_password') : 'Confirm Password'; ?></label>
          <div class="input-group">
            <?= form_input(['name' => 'password_confirm', 'placeholder' => !empty($this->lang->line('label_confirm_password')) ? $this->lang->line('label_confirm_password') : 'Confirm Password', 'class' => 'form-control']) ?>
          </div>
        </div>
      </div>
      <div class="col-md-12" id="address">
        <div class="form-group">
          <label for="address"><?= !empty($this->lang->line('label_address')) ? $this->lang->line('label_address') : 'Address'; ?></label>
          <textarea type="textarea" class="form-control" placeholder=<?= !empty($this->lang->line('label_address')) ? $this->lang->line('label_address') : "Address"?> name="address" id="address"></textarea>
        </div>
      </div>
      <div class="col-md-6" id="city">
        <div class="form-group">
          <label><?= !empty($this->lang->line('label_city')) ? $this->lang->line('label_city') : 'City'; ?></label>
          <div class="input-group">
            <?= form_input(['name' => 'city', 'placeholder' => !empty($this->lang->line('label_city')) ? $this->lang->line('label_city') : 'City', 'class' => 'form-control']) ?>
          </div>
        </div>
      </div>
      <div class="col-md-6" id="state">
        <div class="form-group">
          <label><?= !empty($this->lang->line('label_state')) ? $this->lang->line('label_state') : 'State'; ?></label>
          <div class="input-group">
            <?= form_input(['name' => 'state', 'placeholder' => !empty($this->lang->line('label_state')) ? $this->lang->line('label_state') : 'State', 'class' => 'form-control']) ?>
          </div>
        </div>
      </div>
      <div class="col-md-6" id="zip_code">
        <div class="form-group">
          <label><?= !empty($this->lang->line('label_zip_code')) ? $this->lang->line('label_zip_code') : 'Zip Code'; ?></label>
          <div class="input-group">
            <?= form_input(['name' => 'zip_code', 'placeholder' => !empty($this->lang->line('label_zip_code')) ? $this->lang->line('label_zip_code') : 'Zip Code', 'class' => 'form-control', 'type' => 'number']) ?>
          </div>
        </div>
      </div>
      <div class="col-md-6" id="country">
        <div class="form-group">
          <label><?= !empty($this->lang->line('label_country')) ? $this->lang->line('label_country') : 'Country'; ?></label>
          <div class="input-group">
            <?= form_input(['name' => 'country', 'placeholder' => !empty($this->lang->line('label_country')) ? $this->lang->line('label_country') : 'Country', 'class' => 'form-control']) ?>
          </div>
        </div>
      </div>
    </div>
    </form>
    </section>
  </div>

  <!--forms code goes here-->

  <?php include('include-footer.php'); ?>
  </div>
  </div>

  <?php include('include-js.php'); ?>
  <script src="<?= base_url('assets/js/page/components-leads.js'); ?>"></script>

</body>

</html>