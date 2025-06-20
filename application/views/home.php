<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= !empty($this->lang->line('label_home')) ? $this->lang->line('label_home') : 'Home'; ?> &mdash; <?= get_compnay_title(); ?></title>
    <?php include('include-css.php'); ?>
    <!-- /END GA -->
</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">

            <?php include('include-header.php'); ?>

            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="row">
                        <?php if (check_permissions("projects", "read")) { ?>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                <div class="card card-statistic-1">
                                    <div class="card-icon bg-mid-dark">
                                        <i class="fas fa-briefcase"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4><?= !empty($this->lang->line('label_total_projects')) ? $this->lang->line('label_total_projects') : 'Total Projects'; ?></h4>
                                        </div>
                                        <div class="card-body">
                                            <?= $total_projects ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (check_permissions("tasks", "read")) { ?>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                <div class="card card-statistic-1">
                                    <div class="card-icon bg-mid-dark">
                                        <i class="fas fa-newspaper"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4><?= !empty($this->lang->line('label_total_tasks')) ? $this->lang->line('label_total_tasks') : 'Total Tasks'; ?></h4>
                                        </div>
                                        <div class="card-body">
                                            <?= $total_tasks ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (check_permissions("users", "read")) { ?>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                <div class="card card-statistic-1">
                                    <div class="card-icon bg-mid-dark">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4><?= !empty($this->lang->line('label_users')) ? $this->lang->line('label_users') : 'Users'; ?></h4>
                                        </div>
                                        <div class="card-body">
                                            <?php echo $total_user; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (check_permissions("notes", "read")) { ?>
                            <?php if (!is_client()) { ?>
                                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                    <div class="card card-statistic-1">
                                        <div class="card-icon bg-mid-dark">
                                            <i class="fas fa-sticky-note"></i>
                                        </div>
                                        <div class="card-wrap">
                                            <div class="card-header">
                                                <h4><?= !empty($this->lang->line('label_clients')) ? $this->lang->line('label_clients') : 'Clients'; ?></h4>
                                            </div>
                                            <div class="card-body">
                                                <?php echo $total_client; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php } else { ?>
                                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                    <div class="card card-statistic-1">
                                        <div class="card-icon bg-mid-dark">
                                            <i class="fas fa-sticky-note"></i>
                                        </div>
                                        <div class="card-wrap">
                                            <div class="card-header">
                                                <h4><?= !empty($this->lang->line('label_notes')) ? $this->lang->line('label_notes') : 'Sticky Notes'; ?></h4>
                                            </div>
                                            <div class="card-body">
                                                <?= $total_notes ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <div class="row">
                        <?php if (check_permissions("projects", "read")) { ?>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4><?= !empty($this->lang->line('label_projects_status')) ? $this->lang->line('label_projects_status') : 'Projects Status'; ?></h4>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="project-status-chart"></canvas>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (check_permissions("tasks", "read")) { ?>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4><?= !empty($this->lang->line('label_task_overview')) ? $this->lang->line('label_task_overview') : 'Tasks Overview'; ?></h4>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="tasks-status-chart"></canvas>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (check_permissions("upcoming_birthdays", "read")) { ?>
                            <div class="col-md-4">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h4 class="d-inline"><?= !empty($this->lang->line('label_upcoming_birthdays')) ? '🎂 ' . $this->lang->line('label_upcoming_birthdays') : '🎂 Upcoming Birthdays'; ?></h4>
                                    </div>
                                    <div class="card-body">
                                        <?php if ($birthdays) { ?>
                                            <div class="owl-carousel owl-theme users-carousel">
                                                <?php foreach ($birthdays as $birthday) :
                                                    $formattedDate = date('j F', strtotime($birthday->date_of_birth));
                                                    $dateTime = new DateTime($birthday->date_of_birth);
                                                    $formattedDate_dob = $dateTime->format('jS F');
                                                    $today = date('j F');
                                                    $today_date = date('Y-m-d');
                                                    $tomorrow = date('j F', strtotime('+1 day'));
                                                    $dayAfterTomorrow = date('j F', strtotime('+2 days'));
                                                ?>
                                                    <div>
                                                        <div class="product-item pb-3">
                                                            <?php if (strcmp($today, $formattedDate) === 0) : ?>
                                                                <div class="text-job text-muted"><b><?= !empty($this->lang->line('label_today')) ? $this->lang->line('label_today') : 'Today'; ?></b></div>
                                                            <?php elseif (strcmp($tomorrow, $formattedDate) === 0) : ?>
                                                                <div class="text-job text-muted"><b><?= !empty($this->lang->line('label_tomorrow')) ? $this->lang->line('label_tomorrow') : 'Tomorrow'; ?></b></div>
                                                            <?php elseif (strcmp($dayAfterTomorrow, $formattedDate) === 0) : ?>
                                                                <div class="text-job text-muted"><b><?= !empty($this->lang->line('label_day_after_tomorrow')) ? $this->lang->line('label_day_after_tomorrow') : ' The Day After Tomorrow'; ?></b></div>
                                                            <?php endif; ?>
                                                            <div class="product-image">
                                                                <?php if (isset($birthday->profile) && !empty($birthday->profile)) { ?>
                                                                    <a href="<?= base_url('users/detail/' . $birthday->id) ?>">
                                                                        <figure class="avatar-md h-100 w-100" data-toggle="tooltip" data-title="<?= $birthday->first_name ?>">
                                                                            <img alt="image" src="<?= base_url('assets/profiles/' . $birthday->profile); ?>" class="img-fluid">
                                                                        </figure>
                                                                    </a>
                                                                <?php } else { ?>
                                                                    <a href="<?= base_url('users/detail/' . $birthday->id) ?>">
                                                                        <figure data-toggle="tooltip" data-title="<?= $birthday->first_name ?>" class="avatar avatar-md h-100 w-100" data-initial="<?= mb_substr($birthday->first_name, 0, 1) . '' . mb_substr($birthday->last_name, 0, 1); ?>">
                                                                        </figure>
                                                                    </a>
                                                                <?php } ?>
                                                            </div>
                                                            <div class="product-details">
                                                                <div class="product-name"><?= $birthday->first_name . ' ' . $birthday->last_name; ?></div>
                                                                <div class="text-muted text-small"><?= $birthday->designation; ?></div>
                                                                <?php if ($formattedDate == $today) : ?>
                                                                    <!-- Display birthday message for today -->
                                                                    <div class="text-job text-muted"><?= !empty($this->lang->line('label_happy_birthday')) ? $this->lang->line('label_happy_birthday') : 'Happy birthday'; ?></div>
                                                                <?php else : ?>
                                                                    <!-- Display formatted date for other days -->
                                                                    <div class="text-job text-muted"><?= $formattedDate_dob; ?></div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                            <?php } else {
                                            echo "<h6 class='text-primary text-center'>" ?><?= !empty($this->lang->line('label_no_upcoming_birthdays')) ?  $this->lang->line('label_no_upcoming_birthdays') : ' No Upcoming Birthdays to display' ?><?php "</h6>";
                                                                                                                                                                                                                                                } ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if (check_permissions("upcoming_work_anniversaries", "read")) { ?>
                            <div class="col-md-4">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h4 class="d-inline"><?= !empty($this->lang->line('label_upcoming_work_anniversaries')) ? '🎉 ' . $this->lang->line('label_upcoming_work_anniversaries') : '🎉 Upcoming Work Anniversaries'; ?></h4>
                                    </div>
                                    <div class="card-body">
                                        <?php if ($work_anniversaries) { ?>
                                            <div class="owl-carousel owl-theme users-carousel1 mr-2">
                                                <?php foreach ($work_anniversaries as $work_anniversary) :
                                                    $formattedDate = date('j F', strtotime($work_anniversary->date_of_joining));
                                                    $today = date('j F');
                                                    $date_of_joining = strtotime($work_anniversary->date_of_joining);
                                                    $today_date = strtotime(date('Y-m-d'));
                                                    $one_month = strtotime('+1 month', $date_of_joining);
                                                    $diff_in_seconds = $today_date - $one_month;
                                                    $diff_in_years = round($diff_in_seconds / (365 * 24 * 60 * 60));
                                                    $year = ordinal($diff_in_years);
                                                    $tomorrow = date('j F', strtotime('+1 day'));
                                                    $dayAfterTomorrow = date('j F', strtotime('+2 days'));
                                                ?>

                                                    <div>
                                                        <div class="product-item pb-3">
                                                            <?php if (strcmp($today, $formattedDate) === 0) : ?>
                                                                <div class="text-job text-muted"><b><?= !empty($this->lang->line('label_today')) ? $this->lang->line('label_today') : 'Today'; ?></b></div>
                                                            <?php elseif (strcmp($tomorrow, $formattedDate) === 0) : ?>
                                                                <div class="text-job text-muted"><b><?= !empty($this->lang->line('label_tomorrow')) ? $this->lang->line('label_tomorrow') : 'Tomorrow'; ?></b></div>
                                                            <?php elseif (strcmp($dayAfterTomorrow, $formattedDate) === 0) : ?>
                                                                <div class="text-job text-muted"><b><?= !empty($this->lang->line('label_day_after_tomorrow')) ? $this->lang->line('label_day_after_tomorrow') : ' The Day After Tomorrow'; ?></b></div>
                                                            <?php endif; ?>

                                                            <?php if (isset($work_anniversary->profile) && !empty($work_anniversary->profile)) : ?>
                                                                <a href="<?= base_url('users/detail/' . $work_anniversary->id) ?>">
                                                                    <figure class="avatar-md h-100 w-100" data-toggle="tooltip" data-title="<?= $work_anniversary->first_name ?>">
                                                                        <img alt="image" src="<?= base_url('assets/profiles/' . $work_anniversary->profile); ?>" class="img-fluid">
                                                                    </figure>
                                                                </a>
                                                            <?php else : ?>

                                                                <a href="<?= base_url('users/detail/' . $work_anniversary->id) ?>">
                                                                    <figure data-toggle="tooltip" data-title="<?= $work_anniversary->first_name ?>" class="avatar avatar-md" data-initial="<?= mb_substr($work_anniversary->first_name, 0, 1) . '' . mb_substr($work_anniversary->last_name, 0, 1); ?>">
                                                                    </figure>
                                                                </a>
                                                            <?php endif; ?>
                                                            <div class="user-details">
                                                                <div class="user-name"><?= $work_anniversary->first_name . ' ' . $work_anniversary->last_name; ?></div>
                                                                <div class="text-job text-muted"><?= $work_anniversary->designation; ?></div>
                                                                <?php if ($formattedDate == $today) : ?>

                                                                    <div class="text-job text-muted"><b><?php echo $year; ?></b> <?= !empty($this->lang->line('label_work_anniversaries')) ? $this->lang->line('label_work_anniversaries') : 'Work Anniversaries'; ?></div>
                                                                <?php else : ?>

                                                                    <div class="text-job text-muted"><?= $formattedDate; ?><b><?php echo ' ' . $year; ?></b> <?= !empty($this->lang->line('label_work_anniversaries')) ? $this->lang->line('label_work_anniversaries') : 'Work Anniversaries'; ?></div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                            <?php } else {
                                            echo "<h6 class='text-primary text-center'>" ?><?= !empty($this->lang->line('label_no_upcoming_work_anniversaries')) ? $this->lang->line('label_no_upcoming_work_anniversaries') : 'No Upcoming Work Anniversaries to display.' ?><?php "</h6>";
                                                                                                                                                                                                                                                                            } ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (check_permissions("members_on_leave", "read")) { ?>
                            <div class="col-md-4">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h4 class="d-inline"><?= !empty($this->lang->line('label_members_on_leave')) ? '🚫 ' . $this->lang->line('label_members_on_leave') : '🚫 Members on Leave'; ?></h4>
                                    </div>
                                    <div class="card-body">
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active show" id="today-tab" data-toggle="tab" href="#today" role="tab" aria-controls="today" aria-selected="false"><?= !empty($this->lang->line('label_today')) ? $this->lang->line('label_today') : 'Today'; ?></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="tomorrow-tab" data-toggle="tab" href="#tomorrow" role="tab" aria-controls="tomorrow" aria-selected="false"><?= !empty($this->lang->line('label_tomorrow')) ? $this->lang->line('label_tomorrow') : 'Tomorrow'; ?></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="after-tomorrow-tab" data-toggle="tab" href="#after-tomorrow" role="tab" aria-controls="after-tomorrow" aria-selected="false"><?= !empty($this->lang->line('label_day_after_tomorrow')) ? $this->lang->line('label_day_after_tomorrow') : ' The Day After Tomorrow'; ?></a>
                                            </li>
                                        </ul>
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade active show" id="today" role="tabpanel" aria-labelledby="today-tab">
                                                <div class="owl-carousel owl-theme users-carousel2 mr-2">
                                                    <div>
                                                        <div class="product-item pb-3">
                                                            <?php
                                                            if ($leave_today->num_rows() > 0) {
                                                                foreach ($leave_today->result() as $row) { ?>
                                                                    <div class="product-image">
                                                                        <?php if (isset($row->profile) && !empty($row->profile)) { ?>
                                                                            <a href="<?= base_url('users/detail/' . $row->id) ?>">
                                                                                <figure class="avatar-md h-100 w-100" data-toggle="tooltip" data-title="<?= $row->first_name ?>">
                                                                                    <img alt="image" src="<?= base_url('assets/profiles/' . $row->profile); ?>" class="img-fluid">
                                                                                </figure>
                                                                            </a>
                                                                        <?php } else { ?>
                                                                            <a href="<?= base_url('users/detail/' . $row->id) ?>">
                                                                                <figure data-toggle="tooltip" data-title="<?= $row->first_name ?>" class="avatar avatar-md " data-initial="<?= mb_substr($row->first_name, 0, 1) . '' . mb_substr($row->last_name, 0, 1); ?>">
                                                                                </figure>
                                                                            </a>
                                                                        <?php } ?>
                                                                    </div>
                                                                    <div class="user-details">
                                                                        <div class="user-name"><?= $row->first_name . ' ' . $row->last_name; ?></div>
                                                                        <div class="text-job text-muted"><?= $row->designation; ?></div>
                                                                    </div>
                                                                <?php }
                                                            } else {
                                                                ?>
                                                                <h6 class="text-primary test-data max-width"><?= !empty($this->lang->line('label_no_member_on_leave')) ? $this->lang->line('label_no_member_on_leave') : 'No Member on leave'; ?></h6>
                                                            <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="tomorrow" role="tabpanel" aria-labelledby="tomorrow-tab">
                                                <div class="owl-carousel owl-theme users-carousel4 mr-2">
                                                    <div>
                                                        <div class="product-item pb-3">
                                                            <?php
                                                            if ($leave_tomorrow->num_rows() > 0) {
                                                                foreach ($leave_tomorrow->result() as $row) {  ?>
                                                                    <div class="product-image">
                                                                        <?php if (isset($row->profile) && !empty($row->profile)) { ?>
                                                                            <a href="<?= base_url('users/detail/' . $row->id) ?>">
                                                                                <figure class="avatar-md h-100 w-100" data-toggle="tooltip" data-title="<?= $row->first_name ?>">
                                                                                    <img alt="image" src="<?= base_url('assets/profiles/' . $row->profile); ?>" class="img-fluid">
                                                                                </figure>
                                                                            </a>
                                                                        <?php } else { ?>
                                                                            <a href="<?= base_url('users/detail/' . $row->id) ?>">
                                                                                <figure data-toggle="tooltip" data-title="<?= $row->first_name ?>" class="avatar avatar-md" data-initial="<?= mb_substr($row->first_name, 0, 1) . '' . mb_substr($row->last_name, 0, 1); ?>">
                                                                                </figure>
                                                                            </a>
                                                                        <?php } ?>
                                                                    </div>
                                                                    <div class="user-details">
                                                                        <div class="user-name"><?= $row->first_name . ' ' . $row->last_name; ?></div>
                                                                        <div class="text-job text-muted"><?= $row->designation; ?></div>
                                                                    </div>
                                                                <?php }
                                                            } else {
                                                                ?>
                                                                <h6 class="text-primary test-data max-width"><?= !empty($this->lang->line('label_no_member_on_leave')) ? $this->lang->line('label_no_member_on_leave') : 'No Member on leave'; ?></h6>
                                                            <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="after-tomorrow" role="tabpanel" aria-labelledby="after-tomorrow-tab">
                                                <div class="owl-carousel owl-theme users-carousel5 mr-2">
                                                    <div>
                                                        <div class="product-item pb-3">
                                                            <?php
                                                            if ($leave_after_tomorrow->num_rows() > 0) {
                                                                foreach ($leave_after_tomorrow->result() as $row) { ?>
                                                                    <div class="product-image">
                                                                        <?php if (isset($row->profile) && !empty($row->profile)) { ?>
                                                                            <a href="<?= base_url('users/detail/' . $row->id) ?>">
                                                                                <figure class="avatar-md h-100 w-100" data-toggle="tooltip" data-title="<?= $row->first_name ?>">
                                                                                    <img alt="image" src="<?= base_url('assets/profiles/' . $row->profile); ?>" class="img-fluid">
                                                                                </figure>
                                                                            </a>
                                                                        <?php } else { ?>
                                                                            <a href="<?= base_url('users/detail/' . $row->id) ?>">
                                                                                <figure data-toggle="tooltip" data-title="<?= $row->first_name ?>" class="avatar avatar-md" data-initial="<?= mb_substr($row->first_name, 0, 1) . '' . mb_substr($row->last_name, 0, 1); ?>">
                                                                                </figure>
                                                                            </a>
                                                                        <?php } ?>
                                                                    </div>
                                                                    <div class="user-details">
                                                                        <div class="user-name"><?= $row->first_name . ' ' . $row->last_name; ?></div>
                                                                        <div class="text-job text-muted"><?= $row->designation; ?></div>
                                                                    </div>
                                                                <?php }
                                                            } else {
                                                                ?>
                                                                <h6 class="text-primary test-data max-width"><?= !empty($this->lang->line('label_no_member_on_leave')) ? $this->lang->line('label_no_member_on_leave') : 'No Member on leave'; ?></h6>
                                                            <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (check_permissions("projects", "read") || check_permissions("tasks", "read")) { ?>
                            <div class="col-lg-8 col-md-4 col-12">
                                <div class="card card-primary">
                                    <div class="card-body">
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active show" id="projects-tab" data-toggle="tab" href="#projects" role="tab" aria-controls="projects" aria-selected="false"><?= !empty($this->lang->line('label_projects')) ? $this->lang->line('label_projects') : 'Projects'; ?></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="tasks-tab" data-toggle="tab" href="#tasks" role="tab" aria-controls="tasks" aria-selected="false"><?= !empty($this->lang->line('label_tasks')) ? $this->lang->line('label_tasks') : 'Tasks'; ?></a>
                                            </li>
                                        </ul>
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade active show" id="projects" role="tabpanel" aria-labelledby="projects-tab">
                                                <div class="card-header">
                                                    <h4 class="d-inline"><?= !empty($this->lang->line('label_most_recently_update')) ? $this->lang->line('label_most_recently_update') : 'Most recently update'; ?></h4>
                                                    <div class="card-header-action">
                                                        <a href="<?= base_url('projects'); ?>" class="btn btn-primary"><?= !empty($this->lang->line('label_view_all')) ? $this->lang->line('label_view_all') : 'View All'; ?></a>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <ul class="list-unstyled list-unstyled-border">
                                                        <?php if (is_array($projects)) :
                                                            $i = 0;
                                                            $i < 5; ?>
                                                            <?php foreach ($projects as $project) :   ?>
                                                                <li class="media">
                                                                    <div class="media-body">
                                                                        <?php
                                                                        if (isset($statuses) && is_array($statuses)) {
                                                                            $project_status = false;

                                                                            foreach ($statuses as $status) {
                                                                                if (isset($projects[$i]['status']) && $projects[$i]['status'] == $status['type']) {
                                                                                    $project_status = true;
                                                                                    $status = '<div class="badge-pill badge-' . $status['text_color'] . ' text-center">' . $status['type'] . '</div>';
                                                                                    break;
                                                                                }
                                                                            }
                                                                            if (!$project_status) {
                                                                        ?>
                                                                                <?php
                                                                                if ($projects[$i]['status'] == "notstarted") {
                                                                                    $status = !empty($this->lang->line('label_notstarted')) ? '<div class="badge-pill badge-info text-center">' . $this->lang->line('label_notstarted') . '</div>' : '<div class="badge-pill badge-info text-center">Not Started</div>';
                                                                                } else if ($projects[$i]['status'] == "ongoing") {
                                                                                    $status = !empty($this->lang->line('label_ongoing')) ? '<div class="badge-pill badge-secondary text-center">' . $this->lang->line('label_ongoing') . '</div>' : '<div class="badge-pill badge-secondary text-center">Ongoing</div>';
                                                                                } else if ($projects[$i]['status'] == "finished") {
                                                                                    $status = !empty($this->lang->line('label_finished')) ? '<div class="badge-pill badge-success text-center">' . $this->lang->line('label_finished') . '</div>' : '<div class="badge-pill badge-success text-center">Finished</div>';
                                                                                } else if ($projects[$i]['status'] == "onhold") {
                                                                                    $status = !empty($this->lang->line('label_onhold')) ? '<div class="badge-pill badge-warning text-center">' . $this->lang->line('label_onhold') . '</div>' : '<div class="badge-pill badge-warning text-center">OnHold</div>';
                                                                                } else if ($projects[$i]['status'] == "cancelled") {
                                                                                    $status = !empty($this->lang->line('label_cancelled')) ? '<div class="badge-pill badge-danger text-center">' . $this->lang->line('label_cancelled') . '</div>' : '<div class="badge-pill badge-danger text-center">Cancelled</div>';
                                                                                }
                                                                                ?>
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>


                                                                        <div class="mb-1 float-right"><?php echo isset($status) ? $status : '' ?></div>
                                                                        <h6 class="media-title"><a href="<?= base_url('projects/details/' . $project['id']); ?>"> <?php echo $projects[$i]['title'] ?> </a></h6>
                                                                        <div class="text-small "><i class="text-muted fas fa-tasks"></i><b class="ml-1"><?php echo $projects[$i]['task_count'] ?></b> <?= !empty($this->lang->line('label_tasks')) ? $this->lang->line('label_tasks') : 'Tasks'; ?>
                                                                            <i class="text-muted fas fa-calendar-alt ml-2"></i> <b><?= !empty($this->lang->line('label_start_date')) ? $this->lang->line('label_start_date') : 'Start Date'; ?>: </b><span class="text-primary"><?php echo date("d-M-Y", strtotime($projects[$i]['start_date'])); ?></span>
                                                                            <b><?= !empty($this->lang->line('label_end_date')) ? $this->lang->line('label_end_date') : 'End Date'; ?>: </b><span class="text-primary"><?php echo date("d-M-Y", strtotime($projects[$i]['end_date'])); ?></span>
                                                                        </div>
                                                                </li>
                                                                <?php $i++; ?>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="tasks" role="tabpanel" aria-labelledby="tasks-tab">
                                                <div class="card-header">
                                                    <h4 class="d-inline"><?= !empty($this->lang->line('label_most_recently_update')) ? $this->lang->line('label_most_recently_update') : 'Most recently update'; ?></h4>
                                                    <div class="card-header-action">
                                                        <a href="<?= base_url('tasks'); ?>" class="btn btn-primary"><?= !empty($this->lang->line('label_view_all')) ? $this->lang->line('label_view_all') : 'View All'; ?></a>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <ul class="list-unstyled list-unstyled-border">
                                                        <?php if (is_array($tasks)) : $i = 0; ?>
                                                            <?php foreach ($tasks as $task) :   ?>
                                                                <li class="media">
                                                                    <?php if (isset($tasks[$i]['profile']) && !empty($tasks[$i]['profile'])) { ?>
                                                                        <a href="<?= base_url('users/detail/' . $tasks[$i]['id']) ?>">
                                                                            <figure class="avatar avatar-md " data-toggle="tooltip" data-title="<?= $tasks[$i]['first_name'] ?>">
                                                                                <img alt="image" src="<?= base_url('assets/profiles/' . $tasks[$i]['profile']); ?>" class="rounded-circle">
                                                                            </figure>
                                                                        </a>
                                                                    <?php } else { ?>
                                                                        <a href="<?= base_url('users/detail/' . $tasks[$i]['id']) ?>">
                                                                            <figure data-toggle="tooltip" data-title="<?= isset($tasks[$i]['first_name']) ? $tasks[$i]['first_name'] : '' ?>" class="avatar avatar-md" data-initial="<?= (isset($tasks[$i]['first_name']) ? (string)mb_substr($tasks[$i]['first_name'], 0, 1) : '') . (isset($tasks[$i]['last_name']) ? (string)mb_substr($tasks[$i]['last_name'], 0, 1) : ''); ?>">
                                                                            </figure>
                                                                        </a>
                                                                    <?php } ?>
                                                                    <div class="media-body ml-2">
                                                                        <?php
                                                                        if (isset($statuses) && is_array($statuses)) {
                                                                            $task_status = false;

                                                                            foreach ($statuses as $status) {
                                                                                if (isset($tasks[$i]['status']) && $tasks[$i]['status'] == $status['type']) {
                                                                                    $task_status = true;
                                                                                    $status = '<div class="badge-pill badge-' . $status['text_color'] . ' text-center">' . $status['type'] . '</div>';
                                                                                    break;
                                                                                }
                                                                            }
                                                                            if (!$task_status) {
                                                                                $status = '';
                                                                        ?>
                                                                                <?php if ($tasks[$i]['status'] == "todo") {
                                                                                    $status = !empty($this->lang->line('label_todo')) ? '<div class="badge-pill badge-info text-center">' . $this->lang->line('label_todo') . '</div>' : '<div class="badge-pill badge-info text-center">Todo</div>';;
                                                                                } else if ($tasks[$i]['status'] == "inprogress") {
                                                                                    $status = !empty($this->lang->line('label_in_progress')) ? '<div class="badge-pill badge-secondary text-center">' . $this->lang->line('label_in_progress') . '</div>' : '<div class="badge-pill badge-secondary text-center">In Progress</div>';
                                                                                } else if ($tasks[$i]['status'] == "review") {
                                                                                    $status = !empty($this->lang->line('label_review')) ? '<div class="badge-pill badge-warning text-center">' . $this->lang->line('label_review') . '</div>' : '<div class="badge-pill badge-warning text-center">Review</div>';
                                                                                } else if ($tasks[$i]['status'] == "done") {
                                                                                    $status = !empty($this->lang->line('label_done')) ? '<div class="badge-pill badge-success text-center">' . $this->lang->line('label_done') . '</div>' : '<div class="badge-pill badge-success text-center">Done</div>';
                                                                                }  ?>
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>



                                                                        <div class="mb-1 float-right"><?php echo $status ?></div>
                                                                        <h6 class="media-title"><a href="<?= base_url('projects/tasks/' . $tasks[$i]['project_id']); ?>"><?php echo $tasks[$i]['title'] ?> </a><b>[ <?php echo $tasks[$i]['project_title'] ?>]</b></h6>
                                                                        <div class="text-small text-muted"><?= $tasks[$i]['first_name'] . ' ' . $tasks[$i]['last_name']; ?> <div class="bullet"></div><span class="text-primary"><?php echo date("d-M-Y", strtotime($tasks[$i]['updated_at'])); ?></span></div>
                                                                    </div>
                                                                </li>
                                                                <?php $i++; ?>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (check_permissions("todos", "read")) { ?>
                            <div class="col-lg-4 col-md-4 col-12">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h4 class="d-inline"><?= !empty($this->lang->line('label_todos')) ? $this->lang->line('label_todos') : 'Todos'; ?></h4>
                                        <div class="card-header-action">
                                            <a href="<?= base_url('todo'); ?>" class="btn btn-primary"><?= !empty($this->lang->line('label_view_all')) ? $this->lang->line('label_view_all') : 'View All'; ?></a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <form action="<?= base_url('todo/add_todo_list') ?>" id="add_todo">

                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name();
                                                                        ?>" class="form-control" value="<?= $this->security->get_csrf_hash(); ?>">
                                            <h4 class="card-title"><?= !empty($this->lang->line('label_todos')) ? $this->lang->line('label_todos') : 'Todos'; ?></h4>
                                            <div class="add-items checkbox">
                                                <?php if ((check_permissions("todos", "create")) || (check_permissions("todos", "update"))) { ?>
                                                    <div class="row">
                                                        <div class="col-md-9">
                                                            <input type="hidden" name="id" id="todo_id">
                                                            <input type="text" class="form-control todo-list-input" placeholder="<?= !empty($this->lang->line('label_add_todos')) ? $this->lang->line('label_add_todos') : 'Add Todo list'; ?>" name="description" id="todo_text" value="">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <button id="add" type="submit" class="add btn btn-primary font-weight-bold todo-list-add btn-lg"><?= !empty($this->lang->line('label_add')) ? $this->lang->line('label_add') : 'Add'; ?></button>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <div id="tolist" class="tolist mt-5">

                                                <?php
                                                $todos = json_decode(strval($todos), true);
                                                if (!empty($todos['rows'])) :
                                                ?>

                                                    <div class="sort ">
                                                        <?php foreach ($todos['rows'] as $list) : ?>

                                                            <div class='form-check list-wrapper' draggable="true" id=" <?= $list['id'] ?>">
                                                                <div class="row grabber">
                                                                    <div class="col-md-9">
                                                                        <div class="row h5 align-items-center">
                                                                            <div class="dragger todo-dragger"></div>
                                                                            <input class='checkbox check_test ml-3' name="<?= $list['id'] ?>" type='checkbox' <?= (isset($list['status']) && $list['status'] == '1') ? 'Checked' : '' ?> onclick='update_status(this)' id="<?= $list['id'] ?>" position="<?= $list['position'] ?>">
                                                                            <div class="d-block">
                                                                                <label for="<?= $list['id'] ?>">
                                                                                    <?php $status = (isset($list['status']) && $list['status'] == '1') ? '1' : '' ?>
                                                                                    <p class="ml-3 mb-0" id="desc-<?= $list['id'] ?>">
                                                                                        <?= ($status == 1) ? "<s class='text-primary'>" . $list['description'] . "</s>" : $list['description']; ?>
                                                                                    </p>
                                                                                </label>
                                                                                <div class="text-small text-primary">
                                                                                    <i class="text-muted fas fa-calendar-alt ml-2"></i> <?= date('d-m-Y', strtotime($list['created_at'])) ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <hr>
                                                                    </div>
                                                                    <div class="col-md-3 mt-3">
                                                                        <?php if (check_permissions("todos", "update")) { ?>
                                                                            <div onclick='edit_task(this)' data-description="<?= $list['description']  ?>" id="<?= $list['id'] ?>" class="btn btn-primary btn-sm">
                                                                                <i class='fa fa-pen text-white'></i>
                                                                            </div>
                                                                        <?php } ?>
                                                                        <?php if (check_permissions("todos", "delete")) { ?>
                                                                            <div onclick='remove(this)' id="<?= $list['id'] ?>" class="btn btn-danger btn-sm">
                                                                                <i class='fa fa-trash  text-white'></i>
                                                                            </div>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>

                                                <?php else :  ?>
                                                    <div class="text-center empty-todos" data-type="empty-todos">
                                                        <h5 class="text-primary"><i class="fa fa-list"></i> <?= !empty($this->lang->line('label_no_todo_list')) ? $this->lang->line('label_no_todo_list') : 'No Todos created yet! Create your first Todo now'; ?></h5>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (check_permissions("tasks", "read")) { ?>
                            <div class='col-md-12'>
                                <div class="card">
                                    <div class="card-header">
                                        <h4><?= !empty($this->lang->line('label_task_insights')) ? $this->lang->line('label_task_insights') : 'Tasks Insights'; ?></h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-md-3">
                                                <!-- <label>Project Name</label> -->
                                                <input name="projects_name" id="projects_name" type="text" class="form-control" placeholder="<?= !empty($this->lang->line('label_project_name')) ? $this->lang->line('label_project_name') : 'Project Name'; ?>">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <!-- <label>Default Select</label> -->
                                                <select id="tasks_status" name="tasks_status" class="form-control">
                                                    <option value=""><?= !empty($this->lang->line('label_select_status')) ? $this->lang->line('label_select_status') : 'Select Status'; ?></option>
                                                    <?php
                                                    foreach ($statuses_task as $status) { ?>
                                                        <option value="<?= $status['status'] ?>"><?= $status['status'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <!-- <label>Default Select</label> -->
                                                <input placeholder="<?= !empty($this->lang->line('label_tasks_due_dates_between')) ? $this->lang->line('label_tasks_due_dates_between') : 'Tasks Due Dates Between'; ?>" id="tasks_between" name="tasks_between" type="text" class="form-control" autocomplete="off">
                                                <input id="tasks_start_date" name="tasks_start_date" type="hidden">
                                                <input id="tasks_end_date" name="tasks_end_date" type="hidden">

                                            </div>
                                            <div class="form-group col-md-2">
                                                <i class="btn btn-primary btn-rounded no-shadow" id="fillter-tasks"><?= !empty($this->lang->line('label_filter')) ? $this->lang->line('label_filter') : 'Filter'; ?></i>
                                            </div>
                                        </div>
                                        <table class='table-striped' id='tasks_list' data-toggle="table" data-url="<?= base_url('home/get_tasks_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-options='{
                      "fileName": "tasks-list",
                      "ignoreColumn": ["state"] 
                    }' data-query-params="queryParams">
                                            <thead>
                                                <tr>
                                                    <th data-field="id" data-visible="false" data-sortable="true"><?= !empty($this->lang->line('label_id')) ? $this->lang->line('label_id') : 'ID'; ?></th>
                                                    <th data-field="title" data-sortable="true"><?= !empty($this->lang->line('label_tasks')) ? $this->lang->line('label_tasks') : 'Tasks'; ?></th>
                                                    <th data-field="project_id" data-visible='false' data-sortable="true"><?= !empty($this->lang->line('label_id')) ? $this->lang->line('label_id') : 'Project ID'; ?></th>
                                                    <th data-field="project_title" data-sortable="true"><?= !empty($this->lang->line('label_projects')) ? $this->lang->line('label_projects') : 'Project'; ?></th>

                                                    <th data-field="priority" data-sortable="true"><?= !empty($this->lang->line('label_priority')) ? $this->lang->line('label_priority') : 'Priority'; ?></th>
                                                    <th data-field="status" data-sortable="true"><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></th>
                                                    <th data-field="start_date" data-sortable="true"><?= !empty($this->lang->line('label_start_date')) ? $this->lang->line('label_start_date') : 'Start Date'; ?></th>
                                                    <th data-field="due_date" data-sortable="true"><?= !empty($this->lang->line('label_due_date')) ? $this->lang->line('label_due_date') : 'Due Date'; ?></th>

                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                <?php } ?>
                                </div>
                            </div>
                    </div>
                </section>
            </div>

            <?php include('include-footer.php'); ?>

        </div>
    </div>

    <script>
        label_projects_status = "<?= !empty($this->lang->line('label_projects_status')) ? $this->lang->line('label_projects_status') : 'Projects Status'; ?>";
        label_tasks_status = "<?= !empty($this->lang->line('label_tasks_status')) ? $this->lang->line('label_tasks_status') : 'Tasks Status'; ?>";

        home_workspace_id = "<?= $this->session->userdata('workspace_id') ?>";
        home_user_id = "<?= $this->session->userdata('user_id') ?>";
        home_is_super_admin = "<?= $is_admin ?>";
    </script>

    <?php include('include-js.php'); ?>
    <script src="<?= base_url('assets/js/page/home.js'); ?>"></script>

</body>

</html>