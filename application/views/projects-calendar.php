<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= !empty($this->lang->line('label_project_calendar')) ? $this->lang->line('label_project_calendar') : 'Projects-Calendar'; ?> &mdash; <?= get_compnay_title(); ?></title>
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
                        <h1><?= !empty($this->lang->line('label_project_calendar')) ? $this->lang->line('label_project_calendar') : 'Projects-Calendar'; ?></h1>
                        <div class="section-header-breadcrumb">
                            <?php if (check_permissions("projects", "create")) { ?>
                                <i class="btn btn-primary btn-rounded no-shadow" id="modal-add-project"><?= !empty($this->lang->line('label_create_project')) ? $this->lang->line('label_create_project') : 'Create Project'; ?></i>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card p-4">
                            <div class="section-body">

                                <div id="calendar"></div>
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
            <div class="modal-edit-project"></div>
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
    <?php
    $evnts = [];
    $temp = [];
    foreach ($projects as $project) {
        $evnts['id'] = $project['id'];
        $evnts['title'] = $project['title'];
        $evnts['start'] = $project['start_date'];
        $evnts['end'] = $project['end_date'];
        $evnts['classNames'] = 'projects';
        $evnts['backgroundColor'] = 'blue';
        $evnts['borderColor'] = 'blue';
        $evnts['textColor'] = 'white';
        array_push($temp, $evnts);
    }
    ?>
    <script>
        var evnts = <?= json_encode($temp) ?>;
    </script>
    <?php include('include-js.php'); ?>
    <script src="<?= base_url('assets/js/page/components-project-calendar.js'); ?>"></script>
    <script src="<?= base_url('assets/js/page/components-statuses.js'); ?>"></script>
</body>

</html>