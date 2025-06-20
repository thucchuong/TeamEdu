<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= !empty($this->lang->line('label_project_details')) ? $this->lang->line('label_project_details') : 'Project Details'; ?> &mdash; <?= get_compnay_title(); ?></title>
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
                        <h1><?= !empty($this->lang->line('label_project_details')) ? $this->lang->line('label_project_details') : 'Project Details'; ?></h1>
                        <div class="section-header-breadcrumb">
                            <?php if (check_permissions("tasks", "create")) { ?>
                                <a href="<?= base_url('projects/tasks/' . $projects_data['id']); ?>" class="btn btn-primary btn-rounded no-shadow"><?= !empty($this->lang->line('label_project_tasks')) ? $this->lang->line('label_project_tasks') : 'Project Tasks'; ?></a>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="section-body">
                    <input type="hidden" id="project_id_data" value="<?= $projects_data['id'] ?>">
                        <div class="row">
                            <div class="modal-edit-milestone"></div>
                            <?php
                            // print_r($files); 
                            $now = date('Y-m-d'); // or your date as well
                            $your_date = strtotime($projects_data['end_date']);
                            $curdate = strtotime($now);
                            $mydate = $your_date;
                            if ($curdate > $mydate) {
                                $dayleft = 0;
                            } else {
                                $dayleft = $your_date - $curdate;
                                $dayleft = round($dayleft / (60 * 60 * 24));
                            }
                            ?>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h4><?= !empty($this->lang->line('label_task_overview')) ? $this->lang->line('label_task_overview') : 'Tasks Overview'; ?></h4>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="task-area-chart" height="290"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card author-box card-<?= $projects_data['class']; ?>">
                                    <div class="card-body">
                                        <div class="author-box-name" style="flex-basis: auto;">
                                            <h4><?= $projects_data['title']; ?>
                                                <div class="text-right btn">
                                                  
                                                    <?php if ($projects_data['is_favourite'] == 1) : ?>
                                                        
                                                        <i class='fas fa-star text-golden' id="<?= $projects_data['id'] ?>" onclick="un_fav(<?= $projects_data['id'] ?>)" style="font-size: 1.2rem;" title="Removed to favorite"></i>
                                                    <?php else : ?>
                                                        <i class='far fa-star text-golden ' id="<?= $projects_data['id'] ?>" onclick="fav(<?= $projects_data['id'] ?>)" style="font-size: 1.2rem;" title="Removed from favorite"></i>
                                                    <?php endif; ?>
                                                </div>
                                            </h4>
                                        </div>
                                        <div class="author-box-job">
                                            <div class="badge badge-<?= $projects_data['class']; ?> projects-badge"><?= !empty($this->lang->line('label_' . $project['status'])) ? $this->lang->line('label_' . $project['status']) : $project['status']; ?></div>
                                            <div class="float-right mt-sm-1">
                                                <b><?= !empty($this->lang->line('label_start_date')) ? $this->lang->line('label_start_date') : 'Start Date'; ?>: </b><?= $projects_data['start_date']; ?> <b><?= !empty($this->lang->line('label_end_date')) ? $this->lang->line('label_end_date') : 'End Date'; ?>: </b><?= $projects_data['end_date']; ?>
                                            </div>
                                        </div>
                                        <div class="author-box-description">
                                            <p><?= $projects_data['description']; ?></p>
                                        </div>

                                        <div class="row">
                                            <?php if (!empty($projects_data['projects_clients'])) { ?>
                                                <div class="col-md-6">
                                                    <h6><?= !empty($this->lang->line('label_clients')) ? $this->lang->line('label_clients') : 'Clients'; ?></h6>
                                                    <?php foreach ($projects_data['projects_clients'] as $projects_clients) {

                                                        if (isset($projects_clients['profile']) && !empty($projects_clients['profile'])) { ?>
                                                            <a href="<?= base_url('users/detail/' . $projects_clients['id']) ?>">
                                                                <figure class="avatar avatar-md" data-toggle="tooltip" data-title="<?= $projects_clients['first_name'] ?>">
                                                                    <img alt="image" src="<?= base_url('assets/profiles/' . $projects_clients['profile']); ?>" class="rounded-circle">
                                                                </figure>
                                                            </a>
                                                        <?php } else { ?>
                                                            <a href="<?= base_url('users/detail/' . $projects_clients['id']) ?>">
                                                                <figure data-toggle="tooltip" data-title="<?= $projects_clients['first_name'] ?>" class="avatar avatar-md" data-initial="<?= mb_substr($projects_clients['first_name'], 0, 1) . '' . mb_substr($projects_clients['last_name'], 0, 1); ?>">
                                                                </figure>
                                                            </a>
                                                    <?php }
                                                    } ?>
                                                </div>
                                            <?php } ?>
                                            <div class="col-md-6">
                                                <h6 class="mt-1"><?= !empty($this->lang->line('label_users')) ? $this->lang->line('label_users') : 'Users'; ?></h6>
                                                <?php foreach ($projects_data['projects_users'] as $projects_users) {
                                                    if (isset($projects_users['profile']) && !empty($projects_users['profile'])) { ?>
                                                        <a href="<?= base_url('users/detail/' . $projects_users['id']) ?>">
                                                            <figure class="avatar avatar-md" data-toggle="tooltip" data-title="<?= $projects_users['first_name'] ?>">
                                                                <img alt="image" src="<?= base_url('assets/profiles/' . $projects_users['profile']); ?>" class="rounded-circle">
                                                            </figure>
                                                        </a>
                                                    <?php } else { ?>
                                                        <a href="<?= base_url('users/detail/' . $projects_users['id']) ?>">
                                                            <figure data-toggle="tooltip" data-title="<?= $projects_users['first_name'] ?>" class="avatar avatar-md" data-initial="<?= mb_substr($projects_users['first_name'], 0, 1) . '' . mb_substr($projects_users['last_name'], 0, 1); ?>">
                                                            </figure>
                                                        </a>
                                                <?php }
                                                } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <?php if (!hide_budget()) { ?>
                                        <div class="col-md-6">
                                            <div class="card card-statistic-2">
                                                <div class="card-icon bg-mid-dark m-3">
                                                    <i class="fas"><?= get_currency_symbol(); ?></i>
                                                </div>
                                                <div class="card-wrap">
                                                    <div class="card-header">
                                                        <h4><?= !empty($this->lang->line('label_budget')) ? $this->lang->line('label_budget') : 'Budget'; ?></h4>
                                                    </div>
                                                    <div class="card-body">
                                                        <?= $projects_data['budget']; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="col-md-6">
                                        <div class="card card-statistic-2">
                                            <div class="card-icon bg-mid-dark m-3">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                            <div class="card-wrap">
                                                <div class="card-header">
                                                    <h4><?= !empty($this->lang->line('label_days_left')) ? $this->lang->line('label_days_left') : 'Days Left'; ?></h4>
                                                </div>
                                                <div class="card-body">
                                                    <?= $dayleft ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card card-statistic-2">
                                            <div class="card-icon bg-mid-dark m-3">
                                                <i class="fas fa-tasks"></i>
                                            </div>
                                            <div class="card-wrap">
                                                <div class="card-header">
                                                    <h4><?= !empty($this->lang->line('label_total_tasks')) ? $this->lang->line('label_total_tasks') : 'Total Tasks'; ?></h4>
                                                </div>
                                                <div class="card-body">
                                                    <a href="<?= base_url('projects/tasks/' . $projects_data['id']); ?>"><?= $projects_data['task_count']; ?></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card card-statistic-2">
                                            <div class="card-icon bg-mid-dark m-3">
                                                <i class="fas fa-comments"></i>
                                            </div>
                                            <div class="card-wrap">
                                                <div class="card-header">
                                                    <h4><?= !empty($this->lang->line('label_comments')) ? $this->lang->line('label_comments') : 'Comments'; ?></h4>
                                                </div>
                                                <div class="card-body">
                                                    <a href="<?= base_url('projects/tasks/' . $projects_data['id']); ?>"><?= $projects_data['comment_count']; ?></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card author-box">
                                    <div class="card-body">
                                        <div class="author-box-name mb-4">
                                            <?= !empty($this->lang->line('label_upload_files')) ? $this->lang->line('label_upload_files') : 'Upload Files'; ?>
                                        </div>
                                        <input type="hidden" id="workspace_id" value="<?= $projects_data['workspace_id'] ?>">
                                        <input type="hidden" id="project_id" value="<?= $projects_data['id'] ?>">
                                        <div class="dropzone dz-clickable" id="project-files-dropzone">
                                            <div class="dz-default dz-message">
                                                <span>
                                                    <?= !empty($this->lang->line('label_drop_files_here_to_upload')) ? $this->lang->line('label_drop_files_here_to_upload') : 'Drop files here to upload'; ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>
                                            <?= !empty($this->lang->line('label_uploaded_files')) ? $this->lang->line('label_uploaded_files') : 'Uploaded Files'; ?>
                                        </h4>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive table-invoice">
                                            <table class="table table-striped">
                                                <tr>
                                                    <th><?= !empty($this->lang->line('label_preview')) ? $this->lang->line('label_preview') : 'Preview'; ?></th>
                                                    <th><?= !empty($this->lang->line('label_name')) ? $this->lang->line('label_name') : 'Name'; ?></th>
                                                    <th><?= !empty($this->lang->line('label_size')) ? $this->lang->line('label_size') : 'Size'; ?></th>
                                                    <th><?= !empty($this->lang->line('label_action')) ? $this->lang->line('label_action') : 'Action'; ?></th>
                                                </tr>
                                                <?php if (empty($files)) { ?>
                                                    <tr class="text-center">
                                                        <td colspan="4"><b><?= !empty($this->lang->line('label_no_files_uploaded')) ? $this->lang->line('label_no_files_uploaded') : 'No files uploaded yet!'; ?></b></td>
                                                    </tr>
                                                    <?php } else {
                                                    foreach ($files as $file) { ?>
                                                        <tr>
                                                            <td><?= $file['file_extension'] ?></td>
                                                            <td><?= $file['original_file_name'] ?></td>
                                                            <td class="font-weight-600"><?= $file['file_size'] ?></td>
                                                            <td>
                                                                <a download="<?= $file['original_file_name'] ?>" href="<?= base_url('assets/project/' . $file['file_name']); ?>" class="btn btn-primary btn-action mt-1 "><i class="fas fa-download"></i></a>
                                                                <a class="btn btn-danger btn-action mt-1 delete-file-alert" data-file_id="<?= $file['id'] ?>"><i class="fas fa-trash"></i></a>
                                                            </td>
                                                        </tr>
                                                <?php }
                                                } ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4><?= !empty($this->lang->line('label_milestones')) ? $this->lang->line('label_milestones') : 'Milestones'; ?></h4>
                                        <?php if (check_permissions("milestone", "create")) { ?>
                                            <div class="card-header-action">
                                                <?php if (!hide_budget()) { ?>
                                                    <a href="#" class="btn btn-primary" id="modal-add-milestone"><?= !empty($this->lang->line('label_create')) ? $this->lang->line('label_create') : 'Create'; ?></a>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive table-invoice">
                                            <table class="table table-striped">
                                                <tr>
                                                    <th><?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?></th>
                                                    <th><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></th>
                                                    <th><?= !empty($this->lang->line('label_summary')) ? $this->lang->line('label_summary') : 'Summary'; ?></th>
                                                    <?php if (!hide_budget()) { ?>
                                                        <th><?= !empty($this->lang->line('label_cost')) ? $this->lang->line('label_cost') : 'Cost'; ?></th>
                                                        <?php if ((check_permissions("milestone", "update")) || (check_permissions("milestone", "delete"))) { ?>
                                                            <th><?= !empty($this->lang->line('label_action')) ? $this->lang->line('label_action') : 'Action'; ?></th>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </tr>
                                                <?php if (empty($milestones)) { ?>
                                                    <tr class="text-center">
                                                        <td colspan="5"><b>No milstone created yet!</b></td>
                                                    </tr>
                                                <?php }
                                                foreach ($milestones as $milestone) { ?>
                                                    <tr>
                                                        <td><?= $milestone['title'] ?></td>
                                                        <td>
                                                            <div class="badge badge-<?= $milestone['class'] ?> projects-badge"><?= $milestone['status'] ?></div>
                                                        </td>
                                                        <td><?= $milestone['description'] ?></td>
                                                        <?php if (!hide_budget()) { ?>
                                                            <td class="font-weight-600"><?= get_currency_symbol(); ?> <?= $milestone['cost'] ?></td>
                                                            <td>
                                                                <a class="btn btn-primary btn-action mt-1 modal-edit-milestone-ajax" data-id="<?= $milestone['id'] ?>"><i class="fas fa-pencil-alt"></i></a>
                                                                <a class="btn btn-danger btn-action mt-1 delete-milestone-alert" data-milestone_id="<?= $milestone['id'] ?>" data-project_id="<?= $projects_data['id'] ?>" href="<?= base_url('projects/delete_milestone/' . $milestone['id'] . '/' . $projects_data['id']); ?>"><i class="fas fa-trash"></i></a>
                                                            </td>
                                                        <?php } ?>
                                                    </tr>
                                                <?php } ?>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3><?= !empty($this->lang->line('label_activity')) ? $this->lang->line('label_activity') : 'Activity'; ?></h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-md-3">
                                                <!-- <label>Default Select</label> -->
                                                <select id="activity" name="activity" class="form-control">
                                                    <option value="">Select Activity</option>
                                                    <option value="Created"><?= !empty($this->lang->line('label_created')) ? $this->lang->line('label_created') : 'Created'; ?></option>
                                                    <option value="Updated"><?= !empty($this->lang->line('label_updated')) ? $this->lang->line('label_updated') : 'Updated'; ?></option>
                                                    <option value="Deleted"><?= !empty($this->lang->line('label_deleted')) ? $this->lang->line('label_deleted') : 'Deleted'; ?></option>
                                                    <option value="Uploaded"><?= !empty($this->lang->line('label_uploaded')) ? $this->lang->line('label_uploaded') : 'Uploaded'; ?></option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <i class="btn btn-primary btn-rounded no-shadow" id="filter-activity"><?= !empty($this->lang->line('label_filter')) ? $this->lang->line('label_filter') : 'Filter'; ?></i>
                                            </div>
                                        </div>
                                        <table class='table-striped' id='activity_log_list' data-toggle="table" data-url="<?= base_url('projects/get_activity_logs_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-options='{
                                            "fileName": "activity-list",
                                            "ignoreColumn": ["state"] 
                                            }' data-query-params="queryParams">
                                            <thead>
                                                <tr>
                                                    <input type="hidden" id="project_id" value="<?= $projects_data['id'] ?>">
                                                    <th data-field="id" data-sortable="true" data-visible="false"><?= !empty($this->lang->line('label_id')) ? $this->lang->line('label_id') : 'ID'; ?></th>
                                                    <th data-field="workspace_id" data-sortable="true" data-visible="false"><?= !empty($this->lang->line('label_workspace_id')) ? $this->lang->line('label_workspace_id') : 'Workspace ID'; ?></th>
                                                    <th data-field="user_id" data-sortable="true" data-visible="false"><?= !empty($this->lang->line('label_user_id')) ? $this->lang->line('label_user_id') : 'User ID'; ?></th>
                                                    <th data-field="user_name" data-sortable="true" data-visible="true"><?= !empty($this->lang->line('label_user_name')) ? $this->lang->line('label_user_name') : 'User Name'; ?></th>
                                                    <th data-field="activity" data-sortable="true"><?= !empty($this->lang->line('label_activity')) ? $this->lang->line('label_activity') : 'Activity'; ?></th>
                                                    <th data-field="type" data-sortable="true"><?= !empty($this->lang->line('label_type')) ? $this->lang->line('label_type') : 'Type'; ?></th>
                                                    <th data-field="project_id" data-sortable="true" data-visible="false"><?= !empty($this->lang->line('label_project_id')) ? $this->lang->line('label_project_id') : 'Project ID'; ?></th>
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
                </section>
            </div>

            <form action="<?= base_url('projects/create_milestone/' . $projects_data['id']); ?>" method="post" class="modal-part" id="modal-add-milestone-part">
                <div id="modal-title" class="d-none"><?= !empty($this->lang->line('label_add_milestone')) ? $this->lang->line('label_add_milestone') : 'Add Milestone'; ?></div>
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status"><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></label>
                                    <select id="status" name="status" class="form-control">
                                        <option value="incomplete"><?= !empty($this->lang->line('label_incomplete')) ? $this->lang->line('label_incomplete') : 'Incomplete'; ?></option>
                                        <option value="complete"><?= !empty($this->lang->line('label_complete')) ? $this->lang->line('label_complete') : 'Complete'; ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="budget"><?= !empty($this->lang->line('label_cost')) ? $this->lang->line('label_cost') : 'Cost'; ?></label>
                                    <div class="input-group">
                                        <span class="input-group-addon bootstrap-touchspin-prefix input-group-prepend"><span class="input-group-text"><?= get_currency_symbol(); ?></span></span>
                                        <input class="form-control" type="number" min="0" id="cost" name="cost" value="">
                                    </div>
                                </div>
                            </div>
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
                </div>
            </form>

            <form action="<?= base_url('projects/edit_milestone'); ?>" method="post" class="modal-part" id="modal-edit-milestone-part">
                <div id="modal-title" class="d-none"><?= !empty($this->lang->line('label_edit_milestone')) ? $this->lang->line('label_edit_milestone') : 'Edit Milestone'; ?></div>
                <div id="modal-footer-edit-title" class="d-none"><?= !empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit'; ?></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?></label>
                            <div class="input-group">
                                <input type="hidden" name="update_id" id="update_id">
                                <input type="text" class="form-control" placeholder=<?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?> id="update_title" name="title">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status"><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></label>
                                    <select id="update_status" name="status" class="form-control">
                                        <option value="incomplete"><?= !empty($this->lang->line('label_incomplete')) ? $this->lang->line('label_incomplete') : 'Incomplete'; ?></option>
                                        <option value="complete"><?= !empty($this->lang->line('label_complete')) ? $this->lang->line('label_complete') : 'Complete'; ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="budget">Cost</label>
                                    <div class="input-group">
                                        <span class="input-group-addon bootstrap-touchspin-prefix input-group-prepend"><span class="input-group-text"><?= get_currency_symbol(); ?></span></span>
                                        <input class="form-control" type="number" min="0" id="update_cost" name="cost" value="150" placeholder="Project Budget">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?></label>
                            <div class="input-group">
                                <textarea type="textarea" class="form-control" placeholder=<?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?> id="update_description" name="description"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <?php include('include-footer.php'); ?>

        </div>
    </div>

    <script>
        label_todo = "<?= !empty($this->lang->line('label_todo')) ? $this->lang->line('label_todo') : 'Todo'; ?>";
        label_in_progress = "<?= !empty($this->lang->line('label_in_progress')) ? $this->lang->line('label_in_progress') : 'In Progress'; ?>";
        label_review = "<?= !empty($this->lang->line('label_review')) ? $this->lang->line('label_review') : 'Review'; ?>";
        label_done = "<?= !empty($this->lang->line('label_done')) ? $this->lang->line('label_done') : 'Done'; ?>";
        label_tasks = "<?= !empty($this->lang->line('label_tasks')) ? $this->lang->line('label_tasks') : 'Tasks'; ?>";

        todo_task = "<?= $todo_task ?>";
        inprogress_task = "<?= $inprogress_task ?>";
        review_task = "<?= $review_task ?>";
        done_task = "<?= $done_task ?>";
        dictDefaultMessage = "<?= !empty($this->lang->line('label_drop_files_here_to_upload')) ? $this->lang->line('label_drop_files_here_to_upload') : 'Drop Files Here To Upload'; ?>";
        project_id = <?= $projects_data['id'] ?>
    </script>

    <?php include('include-js.php'); ?>
    <!-- Page Specific JS File -->
    <script src="<?= base_url('assets/js/page/components-user.js'); ?>"></script>
    <script src="<?= base_url('assets/js/page/project-details.js'); ?>"></script>

</body>

</html>