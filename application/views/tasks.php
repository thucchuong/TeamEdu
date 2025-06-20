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
                        <h1> <a href="<?= base_url('/projects/details/' . $current_project['id']); ?>"> <?= $current_project['title'] ?></a> <?= !empty($this->lang->line('label_tasks')) ? $this->lang->line('label_tasks') : 'Tasks'; ?></h1>
                        <div class="section-header-breadcrumb">
                            <?php if (check_permissions("tasks", "create")) { ?>
                                <i class="btn btn-primary btn-rounded no-shadow" id="modal-add-task"><?= !empty($this->lang->line('label_create_task')) ? $this->lang->line('label_create_task') : 'Create Task'; ?></i>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="section-body">
                        <div class="row">
                            <div class="modal-edit-task "></div>
                            <div class="modal-add-task-details"></div>

                            <div class="col-12">
                                <?php if (isset($statuses) && is_array($statuses)) { ?>
                                    <div class="board" data-plugin="dragula" data-containers="[<?php
                                                                                                $statusList = [];
                                                                                                foreach ($statuses as $status) {
                                                                                                    $status = str_replace(' ', '-', $status["status"]);
                                                                                                    $statusList[] = '&quot;task-list-' . $status . '&quot;';
                                                                                                }
                                                                                                echo implode(",", $statusList);
                                                                                                ?>]">
                                        <?php foreach ($statuses as $status) {
                                            $status_title = str_replace(' ', '-', $status["status"]);
                                            $task_status = false;
                                        ?>
                                            <div class="tasks animated" data-sr-id="<?= $i ?>">
                                                <div class="mt-0 task-header text-uppercase"><?= !empty($this->lang->line('label_' . $status['status'])) ? $this->lang->line('label_' . $status['status']) : $status['status']; ?> (<span class="count"><?= $status['count'] ? $status['count'] : 0; ?></span>)</div>
                                                <div id="task-list-<?= $status_title ?>" data-status="<?= $status['status'] ?>" class="task-list-items">

                                                    <?php foreach ($tasks as $task) {
                                                        if (($task['status'] == $status['status']) && (in_array($user->id, $admin_ids) || in_array($user->id, explode(",", $task['user_id'])) || is_member() || is_client())
                                                        ) {
                                                    ?>
                                                            <div class="card mb-0" id="<?= $task['id'] ?>">
                                                                <div class="card-body p-3">
                                                                    <div class="card-header-action float-right">
                                                                        <div class="dropdown card-widgets">
                                                                            <?php if ((check_permissions("tasks", "update")) || (check_permissions("tasks", "create")) || (check_permissions("tasks", "delete"))) { ?>
                                                                                <a href="#" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-cog"></i></a>
                                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                                    <?php if (check_permissions("tasks", "update")) { ?>
                                                                                        <a class="dropdown-item has-icon modal-edit-task-ajax" data-id="<?= $task['id'] ?>" href="#"><i class="fas fa-pencil-alt"></i> <?= !empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit'; ?></a>
                                                                                    <?php } ?>
                                                                                    <?php if (check_permissions("tasks", "create")) { ?>
                                                                                        <a class="dropdown-item has-icon modal-duplicate-task-ajax" data-id="<?= $task['id'] ?>" href="#"><i class="fas fa-copy"></i> <?= !empty($this->lang->line('label_duplicate')) ? $this->lang->line('label_duplicate') : 'Duplicate'; ?></a>
                                                                                    <?php } ?>
                                                                                    <?php if (check_permissions("tasks", "delete")) { ?>
                                                                                        <a class="dropdown-item has-icon delete-task-alert" data-task_id="<?= $task['id'] ?>" data-project_id="<?= $current_project['id'] ?>" href="<?= base_url('projects/delete_task/' . $task['id'] . '/' . $current_project['id']); ?>"><i class="far fa-trash-alt"></i> <?= !empty($this->lang->line('label_delete')) ? $this->lang->line('label_delete') : 'Delete'; ?></a>
                                                                                    <?php } ?>
                                                                                </div>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                    <div>
                                                                        <a href="#" data-id="<?= $task['id'] ?>" class="text-body modal-add-task-details-ajax"><?= $task['title'] ?></a>
                                                                    </div>
                                                                    <span class="badge badge-<?= $task['class'] ?> projects-badge"><?= $task['priority'] ?></span>
                                                                    <p class="mt-2 mb-2">
                                                                        <span class="text-nowrap d-inline-block">
                                                                            <i class="fas fa-comments text-muted"></i>
                                                                            <b><?= $task['comment_count'] ?></b> <?= !empty($this->lang->line('label_comments')) ? $this->lang->line('label_comments') : 'Comments'; ?>
                                                                        </span>
                                                                    </p>
                                                                    <small class="float-right text-muted mt-2"><?= $task['due_date'] ?></small>

                                                                    <?php $i = 0;
                                                                    $j = 0;
                                                                    foreach ($task['task_users'] as $task_user) {
                                                                        if ($i < 2) {
                                                                            if (isset($task_user['profile']) && !empty($task_user['profile'])) {
                                                                    ?>
                                                                                <figure class="avatar mr-2 avatar-sm" data-toggle="tooltip" data-title="<?= $task_user['first_name'] ?>">
                                                                                    <img alt="image" src="<?= base_url('assets/profiles/' . $task_user['profile']); ?>" class="rounded-circle">
                                                                                </figure>
                                                                            <?php } else { ?>
                                                                                <figure data-toggle="tooltip" data-title="<?= $task_user['first_name'] ?>" class="avatar mr-2 avatar-sm" data-initial="<?= mb_substr($task_user['first_name'], 0, 1) . '' . mb_substr($task_user['last_name'], 0, 1); ?>">
                                                                                </figure>
                                                                            <?php }
                                                                        } else {
                                                                            if ($j == 0) {
                                                                            ?>
                                                                                <figure data-toggle="tooltip" data-title="More" class="avatar mr-2 avatar-sm" data-initial="+">
                                                                                </figure>
                                                                    <?php $j++;
                                                                            }
                                                                        }
                                                                        $i++;
                                                                    } ?>

                                                                </div>
                                                            </div>
                                                    <?php }
                                                    } ?>

                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php

                                } ?>
                            </div>

                        </div>
                    </div>
                </section>
            </div>
            <?php if (check_permissions("tasks", "create")) { ?>
                <form action="<?= base_url('projects/create_task/' . $current_project['id']); ?>" method="post" class="modal-part" id="modal-add-task-part">
                    <div id="modal-title" class="d-none"><?= !empty($this->lang->line('label_create_task')) ? $this->lang->line('label_create_task') : 'Create task'; ?></div>
                    <div id="modal-footer-add-title" class="d-none"><?= !empty($this->lang->line('label_add')) ? $this->lang->line('label_add') : 'Add'; ?></div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="<?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?>" name="title">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="milestone_id"><?= !empty($this->lang->line('label_milestone')) ? $this->lang->line('label_milestone') : 'Milestone'; ?></label>
                                <select id="milestone_id" name="milestone_id" class="form-control">
                                    <option value="" selected><?= !empty($this->lang->line('label_select_milestone')) ? $this->lang->line('label_select_milestone') : 'Select Milestone'; ?></option>
                                    <?php foreach ($milestones as $milestone) { ?>
                                        <option value="<?= $milestone['id'] ?>"><?= $milestone['title'] ?></option>
                                    <?php } ?>

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
                                        foreach ($statuses as $status) {  ?>
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
                                    <textarea type="textarea" class="form-control" placeholder="<?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?>" name="description"></textarea>
                                </div>
                            </div>
                        </div>

                        <?php if (!is_client()) { ?>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><?= !empty($this->lang->line('label_assign_to')) ? $this->lang->line('label_assign_to') : 'Assign To'; ?></label>
                                    <select class="form-control select2" multiple="" name="user_id[]" id="user_id">
                                        <?php foreach ($all_user as $all_users) { ?>
                                            <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                                        <?php } ?>
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
            <form action="<?= base_url('projects/edit_task'); ?>" method="post" class="modal-part" id="modal-edit-task-part">
                <div id="modal-edit-task-title" class="d-none"><?= !empty($this->lang->line('label_edit_task')) ? $this->lang->line('label_edit_task') : 'Edit Task'; ?></div>
                <div id="modal_footer_edit_title" class="d-none"><?= !empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit'; ?></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?></label>
                            <div class="input-group">
                                <input type="hidden" name="update_id" id="update_id">
                                <input type="text" class="form-control" placeholder="<?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?>" name="title" id="update_title">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="milestone_id"><?= !empty($this->lang->line('label_milestone')) ? $this->lang->line('label_milestone') : 'Milestone'; ?></label>
                            <select id="update_milestone_id" name="milestone_id" class="form-control">
                                <option value="" selected><?= !empty($this->lang->line('label_select_milestone')) ? $this->lang->line('label_select_milestone') : 'Select Milestone'; ?></option>
                                <?php foreach ($milestones as $milestone) { ?>
                                    <option value="<?= $milestone['id'] ?>"><?= $milestone['title'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="budget"><?= !empty($this->lang->line('label_priority')) ? $this->lang->line('label_priority') : 'Priority'; ?></label>
                            <select id="update_priority" name="priority" class="form-control">
                                <option value="low"><?= !empty($this->lang->line('label_low')) ? $this->lang->line('label_low') : 'Low'; ?></option>
                                <option value="medium"><?= !empty($this->lang->line('label_medium')) ? $this->lang->line('label_medium') : 'Medium'; ?></option>
                                <option value="high"><?= !empty($this->lang->line('label_high')) ? $this->lang->line('label_high') : 'High'; ?></option>
                            </select>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status"><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></label><span class="asterisk"> *</span>
                            <div class="input-group">
                                <select class="custom-select select2" id="update_status" name="status">
                                    <option value="" selected><?= !empty($this->lang->line('label_choose_statuses')) ? $this->lang->line('label_choose_statuses') : 'Choose Statuses'; ?>...</option>
                                    <?php
                                    foreach ($statuses as $status) { ?>
                                        <option value="<?= $status['status'] ?>"><?= $status['status'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?></label>
                            <div class="input-group">
                                <textarea type="textarea" class="form-control" placeholder="<?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?>" name="description" id="update_description"></textarea>
                            </div>
                        </div>
                    </div>
                    <?php if (!is_client()) { ?>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?= !empty($this->lang->line('label_assign_to')) ? $this->lang->line('label_assign_to') : 'Assign To'; ?></label>
                                <select class="form-control select2" multiple="" name="user_id[]" id="update_user_id">
                                    <?php foreach ($all_user as $all_users) { ?>
                                        <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_date"><?= !empty($this->lang->line('label_start_date')) ? $this->lang->line('label_start_date') : 'Start Date'; ?></label>
                            <input class="form-control datepicker" type="text" id="update_start_date" name="start_date" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_date"><?= !empty($this->lang->line('label_due_date')) ? $this->lang->line('label_due_date') : 'Due Date'; ?></label>
                            <input class="form-control datepicker" type="text" id="update_due_date" name="due_date" autocomplete="off">
                        </div>
                    </div>
                </div>
            </form>

            <form action="<?= base_url('projects/add_task_details'); ?>" enctype="multipart/form-data" class="modal-part" id="modal-add-task-details-part">
                <div id="modal-footer-submit-title" class="d-none"><?= !empty($this->lang->line('label_submit')) ? $this->lang->line('label_submit') : 'Submit'; ?></div>
                <input type="hidden" name="workspace_id" id="workspace_id_details">
                <input type="hidden" name="project_id" id="project_id_details">
                <input type="hidden" name="task_id" class="task_id_details activity_log_list">
                <input type="hidden" name="user_id" id="user_id_details">
                <div class="p-2">
                    <div class="row">
                        <div class="col-md-12 font-weight-bold">
                            <?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?>
                        </div>
                        <div class="col-md-12" id="task_details_description">

                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <div class="font-weight-bold"><?= !empty($this->lang->line('label_create_date')) ? $this->lang->line('label_create_date') : 'Create Date'; ?></div>
                            <p class="mt-1" id="task_details_date_created"></p>
                        </div>
                        <div class="col-md-3">
                            <div class="font-weight-bold"><?= !empty($this->lang->line('label_start_date')) ? $this->lang->line('label_start_date') : 'Start Date'; ?></div>
                            <p class="mt-1" id="task_details_start_date"></p>
                        </div>
                        <div class="col-md-3">
                            <div class="font-weight-bold"><?= !empty($this->lang->line('label_due_date')) ? $this->lang->line('label_due_date') : 'Due Date'; ?></div>
                            <p class="mt-1" id="task_details_due_date"></p>
                        </div>
                        <div class="col-md-3">
                            <div class="font-weight-bold"><?= !empty($this->lang->line('label_assign_to')) ? $this->lang->line('label_assign_to') : 'Assign To'; ?></div>
                            <span id="asigned_to_name"></span>

                        </div>
                        <div class="col-md-3">
                            <div class="font-weight-bold"><?= !empty($this->lang->line('label_milestone')) ? $this->lang->line('label_milestone') : 'Milestone'; ?></div>
                            <p class="mt-1" id="task_details_milestone"> </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card col-md-12">
                            <div class="card-body">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="comments-tab" data-toggle="tab" href="#comments" role="tab" aria-controls="comments" aria-selected="true"><?= !empty($this->lang->line('label_comments')) ? $this->lang->line('label_comments') : 'Comments'; ?></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="files-tab" data-toggle="tab" href="#files" role="tab" aria-controls="files" aria-selected="false"><?= !empty($this->lang->line('label_files')) ? $this->lang->line('label_files') : 'Files'; ?></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-id="<?= $task['id'] ?>" id="activity-tab" data-toggle="tab" href="#activity" role="tab" aria-controls="activity" aria-selected="false"><?= !empty($this->lang->line('label_activity')) ? $this->lang->line('label_activity') : 'Activity'; ?></a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="comments" role="tabpanel" aria-labelledby="comments-tab">
                                        <textarea class="form-control form-control-light mb-2" name="comment" placeholder="Write message" id="example-textarea" rows="3" required=""></textarea>
                                        <div class="card-body">
                                            <ul class="list-unstyled list-unstyled-border" id="comments_list">

                                            </ul>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="files-tab">
                                        <input type="file" class="form-control mb-2" name="file" id="file">
                                        <span id="project_media_list">

                                        </span>
                                    </div>
                                    <div class="tab-pane fade" id="activity" role="tabpanel" aria-labelledby="activity-tab">
                                        <div class="col-md-12">
                                            <table class='table-striped activity_log_list' id="xyz" data-id="<?= $task['id'] ?>" data-toggle="table" data-url="<?= base_url('projects/get_task_activity_logs_list/') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-options='{
                                            "fileName": "activity-list",
                                            "ignoreColumn": ["state"] 
                                            }' data-query-params="queryParams1">
                                                <thead>
                                                    <tr>
                                                        <th data-field="id" data-sortable="true" data-visible="false"><?= !empty($this->lang->line('label_id')) ? $this->lang->line('label_id') : 'ID'; ?></th>
                                                        <th data-field="workspace_id" data-sortable="true" data-visible="false"><?= !empty($this->lang->line('label_workspace_id')) ? $this->lang->line('label_workspace_id') : 'Workspace ID'; ?></th>
                                                        <th data-field="user_id" data-sortable="true" data-visible="false"><?= !empty($this->lang->line('label_user_id')) ? $this->lang->line('label_user_id') : 'User ID'; ?></th>
                                                        <th data-field="user_name" data-sortable="true" data-visible="true"><?= !empty($this->lang->line('label_user_name')) ? $this->lang->line('label_user_name') : 'User Name'; ?></th>
                                                        <th data-field="activity" data-sortable="true"><?= !empty($this->lang->line('label_activity')) ? $this->lang->line('label_activity') : 'Activity'; ?></th>
                                                        <th data-field="type" data-sortable="true"><?= !empty($this->lang->line('label_type')) ? $this->lang->line('label_type') : 'Type'; ?></th>
                                                        <th data-field="project_id" data-sortable="true" data-visible="false"><?= !empty($this->lang->line('label_project_id')) ? $this->lang->line('label_project_id') : 'Project ID'; ?></th>
                                                        <th data-field="project_title" data-sortable="true" data-visible="true"><?= !empty($this->lang->line('label_project_title')) ? $this->lang->line('label_project_title') : 'Project'; ?></th>
                                                        <th data-field="task_id" data-sortable="true" data-visible="false"><?= !empty($this->lang->line('label_task_id')) ? $this->lang->line('label_task_id') : 'Task ID'; ?></th>
                                                        <th data-field="task_title" data-sortable="true" data-visible="true"><?= !empty($this->lang->line('label_task_title')) ? $this->lang->line('label_task_title') : 'Task'; ?></th>
                                                        <th data-field="comment_id" data-sortable="true" data-visible="false"><?= !empty($this->lang->line('label_comment_id')) ? $this->lang->line('label_comment_id') : 'Comment ID'; ?></th>
                                                        <th data-field="comment" data-sortable="true" data-visible="true"><?= !empty($this->lang->line('label_comment')) ? $this->lang->line('label_comment') : 'Comment'; ?></th>
                                                        <th data-field="file_id" data-sortable="true" data-visible="false"><?= !empty($this->lang->line('label_file_id')) ? $this->lang->line('label_file_id') : 'File ID'; ?></th>
                                                        <th data-field="file_name" data-sortable="true" data-visible="true"><?= !empty($this->lang->line('label_file')) ? $this->lang->line('label_file') : 'File'; ?></th>
                                                        <th data-field="milestone_id" data-sortable="true" data-visible="false"><?= !empty($this->lang->line('label_milestone_id')) ? $this->lang->line('label_milestone_id') : 'Milestone ID'; ?></th>
                                                        <th data-field="milestone" data-sortable="true" data-visible="true"><?= !empty($this->lang->line('label_milestone')) ? $this->lang->line('label_milestone') : 'Milestone'; ?></th>
                                                        <th data-field="date_created" data-sortable="true"><?= !empty($this->lang->line('label_date_created')) ? $this->lang->line('label_date_created') : 'Date Time'; ?></th>
                                                        <th data-field="action" data-sortable="false"><?= !empty($this->lang->line('label_action')) ? $this->lang->line('label_action') : 'Action'; ?></th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <?php if (check_permissions("statuses", "create")) { ?>
                <?= form_open('Statuses/statuses_create', 'id="modal-add-statuses-part"', 'class="modal-part"'); ?>
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
            <?php include('include-js.php'); ?>
            <!-- Page Specific JS File -->
            <script src="<?= base_url('assets/js/page/components-user.js'); ?>"></script>
            <script src="<?= base_url('assets/js/page/tasks.js'); ?>"></script>
            <script src="<?= base_url('assets/js/page/components-statuses.js'); ?>"></script>

</body>

</html>