<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= !empty($this->lang->line('label_projects')) ? $this->lang->line('label_projects') : 'Projects'; ?> &mdash; <?= get_compnay_title(); ?></title>
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
                        <h1><?= !empty($this->lang->line('label_projects')) ? $this->lang->line('label_projects') : 'Projects'; ?></h1>
                        <div class="section-header-breadcrumb overflow">

                            <div class="btn-group mr-2 no-shadow">
                                <a class="btn btn-primary text-white"><i class="fas fa-th-large"></i> <?= !empty($this->lang->line('label_grid_view')) ? $this->lang->line('label_grid_view') : 'Grid View'; ?></a>
                                <a href="<?= base_url('projects/lists') ?>" class="btn"><i class="fas fa-list"></i> <?= !empty($this->lang->line('label_list_view')) ? $this->lang->line('label_list_view') : 'List View'; ?></a>
                            </div>
                            <?php if (check_permissions("projects", "create")) { ?>
                                <i class="btn btn-primary btn-rounded no-shadow" id="modal-add-project"><?= !empty($this->lang->line('label_create_project')) ? $this->lang->line('label_create_project') : 'Create Project'; ?></i>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="section-body">
                        <div class="row align-items-center justify-content-between mx-auto">
                            <div><?= !empty($this->lang->line('label_showing')) ? $this->lang->line('label_showing') : 'Showing '; ?> <?= $page + 1 ?> to <?= (($page + $per_page) < $total_projects) ? $page + $per_page : $total_projects ?> <?= !empty($this->lang->line('label_projects')) ? $this->lang->line('label_projects') : 'Projects '; ?> <?= !empty($this->lang->line('label_from')) ? $this->lang->line('label_from') : 'From '; ?> <?= $total_projects ?></div>
                            <div class="text-sm-right status-filter overflow d-flex">
                                <div class="btn-group mb-3">
                                    <a href="<?= base_url('projects'); ?>" class="btn btn-light <?= $this->input->get('filter') == '' ? 'bg-primary' : '' ?>" data-status="All"><?= !empty($this->lang->line('label_all')) ? $this->lang->line('label_all') : 'All'; ?></a>
                                </div>
                                <div class="btn-group mb-3 ml-1">
                                    <?php
                                    if (isset($statuses_project) && is_array($statuses_project)) {
                                        $project_status = false;
                                        foreach ($statuses_project as $status) {
                                            if ($status['status']) {
                                                $project_status = true;
                                    ?>
                                                <a href="<?= base_url('projects?filter=' . $status['status']); ?>" class="btn btn-light <?= $this->input->get('filter') ==  $status['status'] ? 'bg-primary' : '' ?>" data-status=<?= $status['status'] ?>><?= !empty($this->lang->line('label_' . $status['status'])) ? $this->lang->line('label_' . $status['status']) : $status['status']; ?></a>
                                        <?php
                                            }
                                        }
                                        ?>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group text-sm-right">
                                <select id="project_sort_by" class="form-control">
                                    <option value="newest-desc" <?= ($this->input->get('sort') == "newest-desc") ? 'selected' : '' ?>><?= !empty($this->lang->line('label_newest_first')) ? $this->lang->line('label_newest_first') : 'Newest First'; ?></option>
                                    <option value="oldest-asc" <?= ($this->input->get('sort') == "oldest-asc") ? 'selected' : '' ?>><?= !empty($this->lang->line('label_oldest_first')) ? $this->lang->line('label_oldest_first') : 'Oldest First'; ?></option>
                                    <option value="recently-asc" <?= ($this->input->get('sort') == "recently-asc") ? 'selected' : '' ?>><?= !empty($this->lang->line('label_most_recently_update')) ? $this->lang->line('label_most_recently_update') : 'Most Recently Update'; ?></option>
                                    <option value="recently-desc" <?= ($this->input->get('sort') == "recently-desc") ? 'selected' : '' ?>><?= !empty($this->lang->line('label_least_recently_update')) ? $this->lang->line('label_least_recently_update') : 'Least Recently Update'; ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="modal-edit-project"></div>
                            <?php if (!empty($projects)) {
                                foreach ($projects as $project) { ?>

                                    <div class="col-md-6">
                                        <div class="card author-box card-<?= $project['class'] ?>">
                                            <div class="card-body">
                                                <?php //if (in_array($user->id, $admin_ids)) { 
                                                ?>
                                                <div class="card-header-action float-right">
                                                    <div class="row">
                                                        <?php if ((check_permissions("tasks", "create")) || (check_permissions("projects", "update")) || (check_permissions("projects", "create")) || (check_permissions("projects", "delete"))) { ?>
                                                            <div class="col-6">
                                                                <div class="dropdown card-widgets">
                                                                    <a href="#" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-cog"></i></a>
                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                        <?php if (check_permissions("tasks", "create")) { ?>
                                                                            <a class="dropdown-item has-icon modal-add-task-ajax" href="#" data-toggle="modal" onclick="handleAddTaskClick(this)" data-target="#modal-add-project-task" data-id="<?= $project['id'] ?>">
                                                                                <i class="fas fa-plus"></i> <?= !empty($this->lang->line('label_create_task')) ? $this->lang->line('label_create_task') : 'Create Task'; ?>
                                                                            </a>
                                                                        <?php } ?>
                                                                        <?php if (check_permissions("projects", "update")) { ?>
                                                                            <a class="dropdown-item has-icon modal-edit-project-ajax" href="#" data-id="<?= $project['id'] ?>"><i class="fas fa-pencil-alt"></i> <?= !empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit'; ?></a>
                                                                        <?php } ?>
                                                                        <?php if (check_permissions("projects", "create")) { ?>
                                                                            <a class="dropdown-item has-icon modal-duplicate-project-ajax" href="#" data-id="<?= $project['id'] ?>"><i class="fas fa-copy"></i> <?= !empty($this->lang->line('label_duplicate')) ? $this->lang->line('label_duplicate') : 'Duplicate'; ?></a>
                                                                        <?php } ?>
                                                                        <?php if (check_permissions("projects", "delete")) { ?>
                                                                            <a class="dropdown-item has-icon delete-project-alert" href="<?= base_url('projects/delete/' . $project['id']); ?>" data-project_id="<?= $project['id'] ?>"><i class="far fa-trash-alt"></i> <?= !empty($this->lang->line('label_delete')) ? $this->lang->line('label_delete') : 'Delete'; ?></a>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                        <?php if (check_permissions("projects", "update")) { ?>
                                                            <div class="col-3">
                                                                <div class="card-widgets mt-2">
                                                                    <?php if ($project['is_favorite'] == 1) : ?>
                                                                        <i class='fas fa-star text-golden' id="<?= $project['id'] ?>" onclick="un_fav(<?= $project['id'] ?>)" style="font-size: 16px;" title="Removed from favorite"></i>
                                                                    <?php else : ?>
                                                                        <i class='far fa-star text-golden ' id="<?= $project['id'] ?>" onclick="fav(<?= $project['id'] ?>)" style="font-size: 16px;" title="Add to favorite"></i>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="author-box-name">
                                                    <a href="<?= base_url('projects/details/' . $project['id']); ?>"><?= $project['title'] ?></a>
                                                </div>
                                                <?php
                                                if (isset($statuses) && is_array($statuses)) {
                                                    $project_status = false;
                                                    foreach ($statuses as $status) {

                                                        if (isset($project['status']) && $project['status'] == $status['type']) {
                                                            $project_status = true;
                                                ?>
                                                            <div class="author-box-job">
                                                                <div class="badge badge-<?= $status['text_color'] ?> projects-badge"><?= !empty($this->lang->line('label_' . $status['type'])) ? $this->lang->line('label_' . $status['type']) : $status['type']; ?></div>
                                                            </div>
                                                        <?php
                                                            break;
                                                        }
                                                    }
                                                    if (!$project_status) {
                                                        ?>
                                                        <div class="author-box-job">
                                                            <div class="badge badge-<?= $project['class'] ?> projects-badge"><?= !empty($this->lang->line('label_' . $project['status'])) ? $this->lang->line('label_' . $project['status']) : $project['status']; ?></div>
                                                        </div>
                                                <?php
                                                    }
                                                }
                                                ?>
                                                <div class="author-box-description">
                                                    <p><?= $project['description'] ?></p>
                                                </div>
                                                <div class="mb-2 mt-3">
                                                    <span class="pr-2 mb-2 d-inline-block">
                                                        <i class="text-muted fas fa-tasks"></i>
                                                        <b><?= $project['task_count'] ?></b> <a href="<?= base_url('projects/tasks/' . $project['id']); ?>"><?= !empty($this->lang->line('label_tasks')) ? $this->lang->line('label_tasks') : 'Tasks'; ?></a>
                                                    </span>
                                                    <span class="mb-2 d-inline-block">
                                                        <i class="text-muted fas fa-comments"></i>
                                                        <b><?= $project['comment_count'] ?></b> <a href="<?= base_url('projects/tasks/' . $project['id']); ?>"><?= !empty($this->lang->line('label_comments')) ? $this->lang->line('label_comments') : 'Comments'; ?></a>
                                                    </span>
                                                    <div class="w-100 d-sm-none"></div>
                                                    <div class="float-right mt-sm-0 mt-3">
                                                        <a href="<?= base_url('projects/details/' . $project['id']); ?>" class="btn btn-sm btn-primary no-shadow"><?= !empty($this->lang->line('label_details')) ? $this->lang->line('label_details') : 'Details'; ?> <i class="fas fa-chevron-right"></i></a>
                                                    </div>
                                                </div>
                                                <div class="mb-2 mt-3">
                                                    <span class="pr-2 mb-2 d-inline-block">
                                                        <i class="text-muted fas fa-calendar-alt"></i> <b><?= !empty($this->lang->line('label_start_date')) ? $this->lang->line('label_start_date') : 'Start Date'; ?>: </b><span class="text-primary"><?php echo date("d-M-Y", strtotime($project['start_date'])); ?></span>
                                                    </span>
                                                    <span class="mb-2 d-inline-block">
                                                        <b><?= !empty($this->lang->line('label_end_date')) ? $this->lang->line('label_end_date') : 'End Date'; ?>: </b><span class="text-primary"><?php echo date("d-M-Y", strtotime($project['end_date'])); ?></span>
                                                    </span>
                                                    <div class="w-100 d-sm-none"></div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-4"><span> <?= !empty($this->lang->line('label_priority')) ? $this->lang->line('label_priority') : 'Priority'; ?></span></div>
                                                    <div class="col-md-8"> <?php if ($project['priority'] == "high") : ?>
                                                            <span class="badge badge-danger">
                                                                <?= !empty($this->lang->line('label_' . $project['priority'])) ? $this->lang->line('label_' . $project['priority']) : $project['priority']; ?>
                                                            </span>
                                                        <?php elseif ($project['priority'] == "medium") : ?>
                                                            <span class="badge badge-warning">
                                                                <?= !empty($this->lang->line('label_' . $project['priority'])) ? $this->lang->line('label_' . $project['priority']) : $project['priority']; ?>
                                                            </span>
                                                        <?php elseif ($project['priority'] == "low") : ?>
                                                            <span class="badge badge-primary">
                                                                <?= !empty($this->lang->line('label_' . $project['priority'])) ? $this->lang->line('label_' . $project['priority']) : $project['priority']; ?>
                                                            </span>
                                                        <?php else : ?>
                                                            <span class="badge badge-secondary">
                                                                <?= 'No Priority Given' ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="mb-2">
                                                    <?php
                                                    $date1 = new DateTime("now");
                                                    $date2 = new DateTime($project['end_date']);
                                                    $interval = $date2->diff($date1);
                                                    $start = strtotime($project['start_date']);
                                                    $end = strtotime($project['end_date']);
                                                    $remaining_days = ($end - $start) / 60 / 60 / 24;
                                                    $current_date =  date('Y-m-d');
                                                    ?>
                                                    <!-- <?= $current_date, $end ?> -->
                                                    <?php foreach ($statuses_project as $status) {
                                                        if (($project['status']) == $status['status']) { ?>
                                                            <div class="row">
                                                                <div class="col-4"><span><?= !empty($this->lang->line('label_days_till_deadLine')) ? $this->lang->line('label_days_till_deadLine') : 'Days till DeadLine'; ?></span></div>
                                                                <div class="col-8">
                                                                    <?php if ($current_date > $project['end_date']) : ?>
                                                                        <span class="badge-pill badge-danger"> <?= $interval->days ?> <?= !empty($this->lang->line('label_days_deadline_missed')) ? $this->lang->line('label_days_deadline_missed') : 'Day(s) deadline missed'; ?> </span>
                                                                    <?php elseif ($remaining_days <= 5) : ?>
                                                                        <span class="badge-pill badge-warning"> <?= $remaining_days ?> <b><?= !empty($this->lang->line('label_left')) ? $this->lang->line('label_left') : 'Day(s) left'; ?></b> </span>
                                                                    <?php elseif ($remaining_days <= 15) : ?>
                                                                        <span class="badge-pill badge-primary"> <?= $remaining_days ?> <b><?= !empty($this->lang->line('label_left')) ? $this->lang->line('label_left') : 'Day(s) left'; ?></b> </span>
                                                                    <?php elseif ($remaining_days <= 20) : ?>
                                                                        <span class="badge-pill badge-info"> <?= $remaining_days ?> <b><?= !empty($this->lang->line('label_left')) ? $this->lang->line('label_left') : 'Day(s) left'; ?></b> </span>
                                                                    <?php else : ?>
                                                                        <span class="badge-pill badge-secondary"> <?= $remaining_days ?> <b><?= !empty($this->lang->line('label_left')) ? $this->lang->line('label_left') : 'Day(s) left'; ?></b> </span>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                    <?php }
                                                    } ?>
                                                    <!-- echo ($end - $start) / 60 / 60 / 24; -->
                                                </div>
                                                <div class="row">
                                                    <?php if (!empty($project['projects_clients'])) { ?>
                                                        <div class="col-md-6">
                                                            <h6><?= !empty($this->lang->line('label_clients')) ? $this->lang->line('label_clients') : 'Clients'; ?></h6>
                                                            <?php foreach ($project['projects_clients'] as $projects_clients) {

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
                                                        <?php foreach ($project['projects_users'] as $projects_users) {

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
                                            <div class="card-footer pt-0">
                                                <h6><?= !empty($this->lang->line('label_task_insights')) ? $this->lang->line('label_task_insights') : 'Tasks Insights'; ?></h6>
                                                <div class="progress">
                                                    <?php if (isset($project['project_progress']) && !empty($project['project_progress'])) {
                                                        foreach ($project['project_progress'] as $progress) { ?>
                                                            <div title="<?= !empty($this->lang->line('label_' . $progress['status'])) ? $this->lang->line('label_' . $progress['status']) : $progress['status']; ?> (<?= $progress['percentage'] ?>%)" class="progress-bar progress-bar-striped bg-<?= $progress_bar_classes[$progress['status']] ?>" role="progressbar" data-width="<?= $progress['percentage'] ?>%" aria-valuenow="<?= $progress['percentage'] ?>" aria-valuemin="0" aria-valuemax="100"> <?= !empty($this->lang->line('label_' . $progress['status'])) ? $this->lang->line('label_' . $progress['status']) : ucwords($progress['status']); ?> (<?= $progress['percentage'] ?>%)</div>
                                                        <?php }
                                                    } else { ?>
                                                        <div class="progress-bar progress-bar-striped bg-dark" role="progressbar" data-width="100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"><?= !empty($this->lang->line('label_no_task_assigned')) ? $this->lang->line('label_no_task_assigned') : 'No tasks assigned'; ?></div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php }
                            } else {  ?>
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4><?= !empty($this->lang->line('label_no_project_found')) ? $this->lang->line('label_no_project_found') : 'No Project Found!'; ?> </h4>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="col-md-12">
                                <?php echo $links; ?>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <?php if (check_permissions("projects", "create")) { ?>
                <form action="<?= base_url('projects/create'); ?>" method="post" class="modal-part" id="modal-add-project-part">
                    <div id="modal-title" class="d-none"><?= !empty($this->lang->line('label_create_project')) ? $this->lang->line('label_create_project') : 'Create Project'; ?></div>
                    <div id="modal-footer-add-title" class="d-none"><?= !empty($this->lang->line('label_add')) ? $this->lang->line('label_add') : 'Add'; ?></div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder=<?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?> name="title">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status"><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></label><span class="asterisk"> *</span>
                                <div class="input-group">
                                    <select class="custom-select select2" id="statuses_project_id" name="status">
                                        <option value="" selected><?= !empty($this->lang->line('label_choose_statuses')) ? $this->lang->line('label_choose_statuses') : 'Choose Statuses'; ?>...</option>
                                        <?php
                                        foreach ($statuses_project as $status) { ?>
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
                                <label for="budget"><?= !empty($this->lang->line('label_budget')) ? $this->lang->line('label_budget') : 'Budget'; ?></label>
                                <div class="input-group">
                                    <span class="input-group-addon bootstrap-touchspin-prefix input-group-prepend"><span class="input-group-text"><?= get_currency_symbol(); ?></span></span>
                                    <input class="form-control" type="number" min="0" id="budget" name="budget" value="150" placeholder=<?= !empty($this->lang->line('label_budget')) ? $this->lang->line('label_budget') : 'Budget'; ?>>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start_date"><?= !empty($this->lang->line('label_start_date')) ? $this->lang->line('label_start_date') : 'Start Date'; ?></label>
                                <input class="form-control datepicker" type="text" id="start_date" name="start_date" value="" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_date"><?= !empty($this->lang->line('label_end_date')) ? $this->lang->line('label_end_date') : 'End Date'; ?></label>
                                <input class="form-control datepicker" type="text" id="end_date" name="end_date" value="" autocomplete="off">
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

                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="form-group">
                                    <?= !empty($this->lang->line('label_priority')) ? $this->lang->line('label_priority') : 'Priority'; ?>
                                    <select id="priority" class="form-control" name="priority">
                                        <option value="default"><?= !empty($this->lang->line('label_select_priority')) ? $this->lang->line('label_select_priority') : 'Select Priority'; ?></option>
                                        <option value="low"><?= !empty($this->lang->line('label_low_priority')) ? $this->lang->line('label_low_priority') : 'Low Priority'; ?></option>
                                        <option value="medium"><?= !empty($this->lang->line('label_medium_priority')) ? $this->lang->line('label_medium_priority') : 'Medium Priority'; ?></option>
                                        <option value="high"><?= !empty($this->lang->line('label_high_priority')) ? $this->lang->line('label_high_priority') : 'High Priority'; ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?= !empty($this->lang->line('label_select_users')) ? $this->lang->line('label_select_users') : 'Select Users'; ?></label>
                                <select class="form-control select2" multiple="" name="users[]" id="users">
                                    <?php foreach ($all_user as $all_users) {
                                        if (!is_client($all_users->id)) { ?>
                                            <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="clients"><?= !empty($this->lang->line('label_select_clients')) ? $this->lang->line('label_select_clients') : 'Select Clients'; ?></label>
                                <select id="clients" name="clients[]" class="form-control select2" multiple="">
                                    <?php
                                    if (is_client($client_ids['id'])) {
                                        foreach ($client_ids as $client_id) {
                                    ?>
                                            <option value="<?= $client_id['id'] ?>">
                                                <?= $client_id['first_name'] ?> <?= $client_id['last_name'] ?>
                                            </option>
                                            <?php
                                        }
                                    } else {
                                        foreach ($all_user as $all_users) {
                                            if (is_client($all_users->id)) {
                                            ?>
                                                <option value="<?= $all_users->id ?>">
                                                    <?= $all_users->first_name ?> <?= $all_users->last_name ?>
                                                </option>
                                    <?php
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                    </div>
                </form>
            <?php } ?>
            <form action="<?= base_url('projects/edit'); ?>" method="post" class="modal-part" id="modal-edit-project-part">
                <div id="modal-edit-project-title" class="d-none"><?= !empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit'; ?><?= !empty($this->lang->line('label_projects')) ? '  ' . $this->lang->line('label_projects') : '  Projects '; ?></div>
                <div id="modal-footer-edit-title" class="d-none"><?= !empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit'; ?></div>
                <div class="row">
                    <div class="col-md-12">
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
                            <label for="status"><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></label><span class="asterisk"> *</span>
                            <div class="input-group">
                                <select class="custom-select select2" id="update_status" name="status">
                                    <option value="" selected><?= !empty($this->lang->line('label_choose_statuses')) ? $this->lang->line('label_choose_statuses') : 'Choose Statuses'; ?>...</option>
                                    <?php
                                    foreach ($statuses_project as $status) { ?>
                                        <option value="<?= $status['status'] ?>"><?= $status['status'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="budget"><?= !empty($this->lang->line('label_budget')) ? $this->lang->line('label_budget') : 'Budget'; ?></label>
                            <div class="input-group">
                                <span class="input-group-addon bootstrap-touchspin-prefix input-group-prepend"><span class="input-group-text"><?= get_currency_symbol(); ?></span></span>
                                <input class="form-control" type="number" min="0" id="update_budget" name="budget" value="150" placeholder=<?= !empty($this->lang->line('label_budget')) ? $this->lang->line('label_budget') : 'Budget'; ?>>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="start_date"><?= !empty($this->lang->line('label_start_date')) ? $this->lang->line('label_start_date') : 'Start Date'; ?></label>
                            <input class="form-control datepicker" type="text" id="update_start_date" name="start_date" autocomplete="off">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_date"><?= !empty($this->lang->line('label_end_date')) ? $this->lang->line('label_end_date') : 'End Date'; ?></label>
                            <input class="form-control datepicker" type="text" id="update_end_date" name="end_date" autocomplete="off">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?></label>
                            <div class="input-group">
                                <textarea type="textarea" class="form-control" placeholder=<?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?> name="description" id="update_description"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="form-group">
                                <?= !empty($this->lang->line('label_priority')) ? $this->lang->line('label_priority') : 'Priority'; ?>
                                <select id="update_priority" class="form-control" name="priority">
                                    <option value="default"><?= !empty($this->lang->line('label_select_priority')) ? $this->lang->line('label_select_priority') : 'Select Priority'; ?></option>
                                    <option value="low"><?= !empty($this->lang->line('label_low_priority')) ? $this->lang->line('label_low_priority') : 'Low Priority'; ?></option>
                                    <option value="medium"><?= !empty($this->lang->line('label_medium_priority')) ? $this->lang->line('label_medium_priority') : 'Medium Priority'; ?></option>
                                    <option value="high"><?= !empty($this->lang->line('label_high_priority')) ? $this->lang->line('label_high_priority') : 'High Priority'; ?></option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_select_users')) ? $this->lang->line('label_select_users') : 'Select Users'; ?> (Make Sure You Don't Remove Yourself From Project)</label>
                            <select class="form-control select2" multiple="" name="users[]" id="update_users">
                                <?php foreach ($all_user as $all_users) {
                                    if (!is_client($all_users->id)) { ?>
                                        <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="clients"><?= !empty($this->lang->line('label_select_clients')) ? $this->lang->line('label_select_clients') : 'Select Clients'; ?></label>
                            <select id="update_clients" name="clients[]" class="form-control select2" multiple="">
                                <?php
                                if (is_client($client_ids['id'])) {
                                    foreach ($client_ids as $client_id) {
                                ?>
                                        <option value="<?= $client_id['id'] ?>">
                                            <?= $client_id['first_name'] ?> <?= $client_id['last_name'] ?>
                                        </option>
                                        <?php
                                    }
                                } else {
                                    foreach ($all_user as $all_users) {
                                        if (is_client($all_users->id)) {
                                        ?>
                                            <option value="<?= $all_users->id ?>">
                                                <?= $all_users->first_name ?> <?= $all_users->last_name ?>
                                            </option>
                                <?php
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                </div>
            </form>

            <div class="modal fade" id="modal-add-project-task" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"><?= !empty($this->lang->line('label_add_task')) ? $this->lang->line('label_add_task') : 'Add Task'; ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="<?= base_url('tasks/create_task'); ?>" method="post" id="add_project_task">
                                <div class="row">
                                    <input type="hidden" name="project_id" id="task-track-id">
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
                                            <label for="priority"><?= !empty($this->lang->line('label_priority')) ? $this->lang->line('label_priority') : 'Priority'; ?></label>
                                            <select id="priority" name="priority" class="form-control">
                                                <option value="low"><?= !empty($this->lang->line('label_low')) ? $this->lang->line('label_low') : 'Low'; ?></option>
                                                <option value="medium"><?= !empty($this->lang->line('label_medium')) ? $this->lang->line('label_medium') : 'Medium'; ?></option>
                                                <option value="high"><?= !empty($this->lang->line('label_high')) ? $this->lang->line('label_high') : 'High'; ?></option>
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
                                                    foreach ($statuses_task as $status) { ?>
                                                        <option value="<?= $status['status'] ?>"><?= $status['status'] ?></option>
                                                    <?php } ?>
                                                </select>
                                                <div class="wrapper" id="wrp" style="display: none;">
                                                    <hr><a href="#" id="modal-add-statuses"> + <?= !empty($this->lang->line('label_add_statuses')) ? $this->lang->line('label_add_statuses') : 'Add Statuses'; ?></a>
                                                </div>
                                            </div>
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
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" id="submit_button"><?= !empty($this->lang->line('label_submit')) ? $this->lang->line('label_submit') : 'Submit'; ?></button>
                        </div>
                        </form>

                    </div>
                </div>
            </div>
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
            <?php include('include-footer.php'); ?>

        </div>
    </div>

    <?php include('include-js.php'); ?>

    <!-- Page Specific JS File -->

    <script src="<?= base_url('assets/js/page/components-user.js'); ?>"></script>
    <script src="<?= base_url('assets/js/page/components-statuses.js'); ?>"></script>

</body>

</html>