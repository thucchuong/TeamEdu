<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= !empty($this->lang->line('label_gantt_chart')) ? $this->lang->line('label_gantt_chart') : 'Gantt Chart'; ?> &mdash; <?= get_compnay_title(); ?></title>
    <?php include('include-css.php'); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/frappe-gantt/0.6.1/frappe-gantt.css" integrity="sha512-57KPd8WI3U+HC1LxsxWPL2NKbW82g0BH+0PuktNNSgY1E50mnIc0F0cmWxdnvrWx09l8+PU2Kj+Vz33I+0WApw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <?php include('include-header.php'); ?>

            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1><?= !empty($this->lang->line('label_gantt_chart')) ? $this->lang->line('label_gantt_chart') : 'Gantt Chart'; ?></h1>
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <!-- <div class="form-group col-md-6">
                                <select class="form-control select2 project_filter" name="project_id" id="project_id">
                                    <option value="" selected>Choose...</option>
                                    <?php foreach ($projects_data as $project) {
                                    ?>
                                        <option value="<?= $project['id'] ?>"><?= $project['title'] ?></option>
                                    <?php
                                    } ?>
                                </select>
                            </div> -->

                            <!-- <div class="form-group col-md-4">
                                <select id="tasks_status" name="tasks_status" class="form-control select2 project_filter">
                                    <option value=""><?= !empty($this->lang->line('label_select_status')) ? $this->lang->line('label_select_status') : 'Select Status'; ?></option>
                                    <option value="done"><?= !empty($this->lang->line('label_done')) ? $this->lang->line('label_done') : 'Done'; ?></option>
                                    <option value="todo"><?= !empty($this->lang->line('label_todo')) ? $this->lang->line('label_todo') : 'Todo'; ?></option>
                                    <option value="inprogress"><?= !empty($this->lang->line('label_in_progress')) ? $this->lang->line('label_in_progress') : 'In Progress'; ?></option>
                                    <option value="review"><?= !empty($this->lang->line('label_review')) ? $this->lang->line('label_review') : 'Review'; ?></option>
                                </select>
                            </div> -->

                            <div class="form-group col-md-6">
                                <select class="form-control select2 gantt_filter">
                                    <option value="Month">Month</option>
                                    <option value="Quarter Day">Quarter Day</option>
                                    <option value="Half Day">Half Day</option>
                                    <option value="Day">Day</option>
                                    <option value="Week">Week</option>
                                </select>
                            </div>
                            <!-- <div class="form-group col-md-6">
                                <select class="form-control select2 project_filter" name="user_id" id="user_id">
                                    <option value=""><?= !empty($this->lang->line('label_select_users')) ? $this->lang->line('label_select_users') : 'Select Users'; ?></option>
                                    <?php foreach ($all_user as $all_users) {
                                        if (!is_client($all_users->id)) { ?>
                                            <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <select id="client_id" name="client_id" class="form-control select2 project_filter">
                                    <option value=""><?= !empty($this->lang->line('label_select_clients')) ? $this->lang->line('label_select_clients') : 'Select Clients'; ?></option>
                                    <?php foreach ($all_user as $all_users) {
                                        if (is_client($all_users->id)) { ?>
                                            <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div> -->
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-primary">
                                    <div class="card-body" id="gantt">
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
    <?php include('include-js.php'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/frappe-gantt/0.6.1/frappe-gantt.min.js" integrity="sha512-HyGTvFEibBWxuZkDsE2wmy0VQ0JRirYgGieHp0pUmmwyrcFkAbn55kZrSXzCgKga04SIti5jZQVjbTSzFpzMlg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="<?= base_url('assets/js/page/components-gantt-chart.js'); ?>"></script>
</body>

</html>