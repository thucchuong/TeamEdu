<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= !empty($this->lang->line('label_meetings')) ? $this->lang->line('label_meetings') : 'Meetings'; ?> &mdash; <?= get_admin_company_title($this->data['admin_id']); ?></title>

    <?php require_once(APPPATH . 'views/include-css.php'); ?>
</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">

            <?php require_once(APPPATH . '/views/include-header.php'); ?>

            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1><?= !empty($this->lang->line('label_meetings')) ? $this->lang->line('label_meetings') : 'Meetings'; ?></h1>
                        <div class="section-header-breadcrumb">
                            <?php if (check_permissions("meetings", "create")) { ?>
                                <i class="btn btn-primary btn-rounded no-shadow" id="modal-add-meeting" data-value="add"><?= !empty($this->lang->line('label_create_meeting')) ? $this->lang->line('label_create_meeting') : 'Create Meeting'; ?></i>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="section-body">
                        <div class="row">
                            <div class='col-md-12'>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                <input placeholder="Meeting Dates Between Range" id="meetings_between" name="meetings_between" type="text" class="form-control" autocomplete="off">
                                                <input id="start_date" name="start_date" type="hidden">
                                                <input id="end_date" name="end_date" type="hidden">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <i class="btn btn-primary btn-rounded no-shadow" id="fillter-meetings">Filtter</i>
                                            </div>
                                        </div>
                                        <table class='table-striped' id='meeting_list' data-toggle="table" data-url="<?= base_url('meetings/get_meetings_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-options='{
                      "fileName": "meetings-list"
                    }' data-query-params="queryParams">
                                            <thead>
                                                <tr>
                                                    <th data-field="id" data-visible="false" data-sortable="true"><?= !empty($this->lang->line('label_id')) ? $this->lang->line('label_id') : 'ID'; ?></th>
                                                    <th data-field="workspace_id" data-visible="false" data-sortable="true"><?= !empty($this->lang->line('label_workspace_id')) ? $this->lang->line('label_workspace_id') : 'Workspace ID'; ?></th>
                                                    <th data-field="title" data-sortable="true"><?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?></th>
                                                    <th data-field="created_by" data-sortable="true"><?= !empty($this->lang->line('label_created_by')) ? $this->lang->line('label_created_by') : 'Created By'; ?></th>
                                                    <th data-field="slug" data-visible="false" data-sortable="true"><?= !empty($this->lang->line('label_slug')) ? $this->lang->line('label_slug') : 'Slug'; ?></th>
                                                    <th data-field="users" data-visible="true" data-sortable="false"><?= !empty($this->lang->line('label_users')) ? $this->lang->line('label_users') : 'Users'; ?></th>
                                                    <th data-field="clients" data-visible="true" data-sortable="false"><?= !empty($this->lang->line('label_clients')) ? $this->lang->line('label_clients') : 'Clients'; ?></th>
                                                    <th data-field="type" data-visible="true" data-sortable="false"><?= !empty($this->lang->line('label_type')) ? $this->lang->line('label_type') : 'Type'; ?></th>
                                                    <th data-field="platform" data-visible="true" data-sortable="false"><?= !empty($this->lang->line('label_platform')) ? $this->lang->line('label_platform') : 'Platform'; ?></th>
                                                    <th data-field="link" data-visible="true" data-sortable="false"><?= !empty($this->lang->line('label_link')) ? $this->lang->line('label_link') : 'Link'; ?></th>
                                                    <th data-field="venue" data-visible="true" data-sortable="false"><?= !empty($this->lang->line('label_venue')) ? $this->lang->line('label_venue') : 'Venue'; ?></th>
                                                    <th data-field="start_date" data-visible="true"><?= !empty($this->lang->line('label_starts_on')) ? $this->lang->line('label_starts_on') : 'Starts On'; ?></th>
                                                    <th data-field="end_date" data-visible="true"><?= !empty($this->lang->line('label_ends_on')) ? $this->lang->line('label_ends_on') : 'Ends On'; ?></th>
                                                    <th data-field="status" data-visible="true"><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></th>
                                                    <th data-field="created_on" data-sortable="false" data-visible="false"><?= !empty($this->lang->line('label_date_created')) ? $this->lang->line('label_date_created') : 'Date Created'; ?></th>
                                                    <?php if ((check_permissions("meetings", "update")) || (check_permissions("meetings", "delete"))) { ?>
                                                        <th data-field="action" data-sortable="false"><?= !empty($this->lang->line('label_action')) ? $this->lang->line('label_action') : 'Action'; ?></th>
                                                    <?php } ?>

                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="meeting_id" id="meeting_id">
                        <input type="hidden" name="type" id="type">
                        <div class="modal-meeting-users"></div>
                        <div class="row" id="modal-meeting-users-part">
                            <div class='col-md-12'>
                                <div class="card">
                                    <div class="card-body">
                                        <table class='table-striped' id='meeting_users_list' data-toggle="table" data-url="<?= base_url('meetings/get_meeting_participants_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="false" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="first_name" data-sort-order="asc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-options='{
                      "fileName": "users-list",
                      "ignoreColumn": ["state"] 
                    }' data-query-params="queryParams1">
                                            <thead>
                                                <tr>
                                                    <th data-field="id" data-visible="false" data-sortable="true"><?= !empty($this->lang->line('label_id')) ? $this->lang->line('label_id') : 'ID'; ?></th>

                                                    <th data-field="first_name" data-sortable="true"><?= !empty($this->lang->line('label_users')) ? $this->lang->line('label_users') : 'Users'; ?></th>

                                                    <th data-field="role" data-sortable="false"><?= !empty($this->lang->line('label_role')) ? $this->lang->line('label_role') : 'Role'; ?></th>
                                                    <th data-field="assigned" data-sortable="false"><?= !empty($this->lang->line('label_assigned')) ? $this->lang->line('label_assigned') : 'Assigned'; ?></th>
                                                    <?php if ($this->ion_auth->is_admin()) { ?>
                                                        <th data-field="active" data-sortable="false"><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></th>
                                                    <?php } ?>

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
                        <div class="modal-meeting-clients"></div>
                        <div class="row" id="modal-meeting-clients-part">
                            <div class='col-md-12'>
                                <div class="card">
                                    <div class="card-body">
                                        <table class='table-striped' id='meeting_clients_list' data-toggle="table" data-url="<?= base_url('meetings/get_meeting_participants_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="false" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="first_name" data-sort-order="asc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-options='{
                      "fileName": "users-list",
                      "ignoreColumn": ["state"] 
                    }' data-query-params="queryParams1">
                                            <thead>
                                                <tr>
                                                    <th data-field="id" data-visible="false" data-sortable="true"><?= !empty($this->lang->line('label_id')) ? $this->lang->line('label_id') : 'ID'; ?></th>

                                                    <th data-field="first_name" data-sortable="true"><?= !empty($this->lang->line('label_users')) ? $this->lang->line('label_users') : 'Users'; ?></th>

                                                    <th data-field="role" data-sortable="false"><?= !empty($this->lang->line('label_role')) ? $this->lang->line('label_role') : 'Role'; ?></th>
                                                    <th data-field="assigned" data-sortable="false"><?= !empty($this->lang->line('label_assigned')) ? $this->lang->line('label_assigned') : 'Assigned'; ?></th>
                                                    <?php if ($this->ion_auth->is_admin()) { ?>
                                                        <th data-field="active" data-sortable="false"><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></th>
                                                    <?php } ?>

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
            <div class="modal-edit-meeting"></div>
            <?php if (check_permissions("meetings", "create")) { ?>
                <form action="<?= base_url('meetings/create'); ?>" method="post" class="modal-part" id="modal-add-meeting-part">
                    <div id="modal-title" class="d-none"><?= !empty($this->lang->line('label_create_meeting')) ? $this->lang->line('label_create_meeting') : 'Create Meeting'; ?></div>
                    <div id="modal-footer-add-title" class="d-none"><?= !empty($this->lang->line('label_add')) ? $this->lang->line('label_add') : 'Add'; ?></div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="<?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?>" name="title">
                                </div>
                            </div>
                        </div>
                        <?php
                        $type = ['physical', 'virtual'];
                        $platform = ['inbuilt', 'zoom', 'google_meet', 'microsoft_teams'];
                        ?>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="type"><?= !empty($this->lang->line('label_type')) ? $this->lang->line('label_type') : 'Type'; ?></label>
                                <span class="asterisk">*</span>
                                <select name="type" class="form-control type">
                                    <option value=" "><?= !empty($this->lang->line('label_select_type')) ? $this->lang->line('label_select_type') : 'Select Types'; ?></option>
                                    <?php foreach ($type as $row) : ?>
                                        <option value="<?= $row ?>" <?= (isset($fetched_data[0]['id']) &&  $fetched_data[0]['type'] == $row) ? "Selected" : "" ?>><?= ucwords(str_replace('_', ' ', $row)) ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group physical <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'physical') ? '' : 'd-none' ?>">
                                <label for="type"><?= !empty($this->lang->line('label_venue')) ? $this->lang->line('label_venue') : 'Venue'; ?></label>
                                <textarea class="form-control" name="venue" id="" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group virtual <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'virtual') ? '' : 'd-none' ?>">
                                <label for="platform"><?= !empty($this->lang->line('label_platform')) ? $this->lang->line('label_platform') : 'Platform'; ?></label>
                                <select name="platform" class="form-control platform">
                                    <option value=" "><?= !empty($this->lang->line('label_select_type')) ? $this->lang->line('label_select_type') : 'Select Types'; ?></option>
                                    <?php foreach ($platform as $row) : ?>
                                        <option value="<?= $row ?>" <?= (isset($fetched_data[0]['id']) &&  $fetched_data[0]['platform'] == $row) ? "Selected" : "" ?>><?= ucwords(str_replace('_', ' ', $row)) ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group zoom  <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'zoom') ? '' : 'd-none' ?>">
                                <label for="link"><?= !empty($this->lang->line('label_link')) ? $this->lang->line('label_link') : 'Link'; ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="link" name="link1">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group google_meet  <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'google_meet') ? '' : 'd-none' ?>">
                                <label for="link"><?= !empty($this->lang->line('label_link')) ? $this->lang->line('label_link') : 'Link'; ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="link" name="link2">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group microsoft_teams  <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'microsoft_teams') ? '' : 'd-none' ?>">
                                <label for="link"><?= !empty($this->lang->line('label_link')) ? $this->lang->line('label_link') : 'Link'; ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="link" name="link3">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start_date"><?= !empty($this->lang->line('label_start_date')) ? $this->lang->line('label_start_date') : 'Start Date'; ?></label>
                                <input class="form-control datetimepicker" type="text" id="start_date" name="start_date" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_date"><?= !empty($this->lang->line('label_end_date')) ? $this->lang->line('label_end_date') : 'End Date'; ?></label>
                                <input class="form-control datetimepicker" type="text" id="end_date" name="end_date" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?= !empty($this->lang->line('label_select_users')) ? $this->lang->line('label_select_users') : 'Select Users'; ?></label>
                                <select class="form-control select2" multiple="" name="users[]">
                                    <?php foreach ($all_user as $all_users) {
                                        if (!is_client($all_users->id) && $all_users->id != $user_id) { ?>
                                            <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="clients"><?= !empty($this->lang->line('label_select_clients')) ? $this->lang->line('label_select_clients') : 'Select Clients'; ?></label>
                                <select name="clients[]" class="form-control select2" multiple="">
                                    <?php foreach ($all_user as $all_users) {
                                        if (is_client($all_users->id) && $all_users->id != $user_id) { ?>
                                            <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                            <b>* <?= !empty($this->lang->line('label_creates_meeting_notes')) ? $this->lang->line('label_creates_meeting_notes') : 'Person who creates meeting will be participant automatically'; ?></b>
                        </div>
                    </div>
                </form>
            <?php } ?>
            <form action="<?= base_url('meetings/edit'); ?>" method="post" class="modal-part" id="modal-edit-meeting-part">
                <div id="modal-edit-meeting-title" class="d-none"><?= !empty($this->lang->line('label_edit_meeting')) ? $this->lang->line('label_edit_meeting') : 'Edit Meeting'; ?></div>
                <div id="modal_footer_edit_title" class="d-none"><?= !empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit'; ?></div>
                <input type="hidden" name="update_id" id="update_id">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?></label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder=<?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?> name="title" id="update_title">
                            </div>
                        </div>
                    </div>
                    <?php
                    $type = ['physical', 'virtual'];
                    $platform = ['inbuilt', 'zoom', 'google_meet', 'microsoft_teams'];
                    ?>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="type"><?= !empty($this->lang->line('label_type')) ? $this->lang->line('label_type') : 'Type'; ?></label>
                            <span class="asterisk">*</span>
                            <select name="type" id="update_type" class="form-control type">
                                <option value=" "><?= !empty($this->lang->line('label_select_type')) ? $this->lang->line('label_select_type') : 'Select Types'; ?></option>
                                <?php foreach ($type as $row) : ?>
                                    <option value="<?= $row ?>" <?= (isset($fetched_data[0]['id']) &&  $fetched_data[0]['type'] == $row) ? "Selected" : "" ?>><?= ucwords(str_replace('_', ' ', $row)) ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div id="update_type" class="form-group physical <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'physical') ? '' : 'd-none' ?>">
                            <label for="type"><?= !empty($this->lang->line('label_venue')) ? $this->lang->line('label_venue') : 'Venue'; ?></label>
                            <textarea class="form-control" name="venue" id="update_venue" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div id="update_type" class="form-group virtual <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'virtual') ? '' : 'd-none' ?>">
                            <label for="platform"><?= !empty($this->lang->line('label_platform')) ? $this->lang->line('label_platform') : 'Platform'; ?></label>
                            <select id="update_platform" name="platform" class="form-control platform">
                                <option value=" "><?= !empty($this->lang->line('label_select_type')) ? $this->lang->line('label_select_type') : 'Select Types'; ?></option>
                                <?php foreach ($platform as $row) : ?>
                                    <option value="<?= $row ?>" <?= (isset($fetched_data[0]['id']) &&  $fetched_data[0]['platform'] == $row) ? "Selected" : "" ?>><?= ucwords(str_replace('_', ' ', $row)) ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div id="update_platform" class="form-group zoom  <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'zoom') ? '' : 'd-none' ?>">
                            <label for="link"><?= !empty($this->lang->line('label_link')) ? $this->lang->line('label_link') : 'Link'; ?></label>
                            <div class="input-group">
                                <input type="text" id="update_link" class="form-control" placeholder="link" name="link1">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div id="update_platform" class="form-group google_meet  <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'google_meet') ? '' : 'd-none' ?>">
                            <label for="link"><?= !empty($this->lang->line('label_link')) ? $this->lang->line('label_link') : 'Link'; ?></label>
                            <div class="input-group">
                                <input type="text" id="update_link" class="form-control" placeholder="link" name="link2">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div id="update_platform" class="form-group microsoft_teams  <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'microsoft_teams') ? '' : 'd-none' ?>">
                            <label for="link"><?= !empty($this->lang->line('label_link')) ? $this->lang->line('label_link') : 'Link'; ?></label>
                            <div class="input-group">
                                <input type="text" id="update_link" class="form-control" placeholder="link" name="link3">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="start_date"><?= !empty($this->lang->line('label_start_date')) ? $this->lang->line('label_start_date') : 'Start Date'; ?></label>
                            <input class="form-control datetimepicker" type="text" name="start_date" id="update_start_date" autocomplete="off">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_date"><?= !empty($this->lang->line('label_end_date')) ? $this->lang->line('label_end_date') : 'End Date'; ?></label>
                            <input class="form-control datetimepicker" type="text" name="end_date" id="update_end_date" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_select_users')) ? $this->lang->line('label_select_users') : 'Select Users'; ?></label>
                            <select class="form-control select2" multiple="" name="users[]" id="update_users">
                                <?php foreach ($all_user as $all_users) {
                                    if (!is_client($all_users->id) && $all_users->id != $user_id) { ?>
                                        <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="clients"><?= !empty($this->lang->line('label_select_clients')) ? $this->lang->line('label_select_clients') : 'Select Clients'; ?></label>
                            <select name="clients[]" id="update_clients" class="form-control select2" multiple="">
                                <?php foreach ($all_user as $all_users) {
                                    if (is_client($all_users->id) && $all_users->id != $user_id) { ?>
                                        <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                        <b>* <?= !empty($this->lang->line('label_creates_meeting_notes')) ? $this->lang->line('label_creates_meeting_notes') : 'Person who creates meeting will be participant automatically'; ?></b>
                    </div>

                </div>
            </form>

            <?php require_once(APPPATH . 'views/include-footer.php'); ?>

        </div>
    </div>

    <?php require_once(APPPATH . 'views/include-js.php'); ?>
    <script src="<?= base_url('assets/js/page/components-meetings.js'); ?>"></script>
</body>

</html>