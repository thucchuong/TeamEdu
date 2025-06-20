<?php //$current_version = get_current_version(); 
?>

<?php
if (!$this->ion_auth->logged_in() || !isset($_SESSION['role'])) {
    redirect('auth', 'refresh');
}
?>

<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <!-- <li>
                <span class="badge badge-success">v <?= (isset($current_version) && !empty($current_version)) ? $current_version : '1.0' ?></span>
            </li> -->
            <?php if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) { ?>
                <li class="ml-2">
                    <span class="badge badge-danger">Demo mode</span>
                </li>
            <?php } ?>
            <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
        </ul>
        <div class="search-element">
            <input class="form-control" id="search-results" type="search" placeholder="Search" aria-label="Search" data-width="250">
            <button class="btn" type="submit"><i class="fas fa-search"></i></button>
            <div class="search-backdrop"></div>
            <div class="search-result">
                <div class="search-results chat-scroll" style="max-height: 350px;">
                    <div class="search-header">Result</div>
                </div>
            </div>
        </div>
    </form>

    <ul class="navbar-nav navbar-right">
        <?php
        if (!empty($workspace)) {
            if (is_admin()) {
        ?>
                <li>
                    <a href="<?= base_url('send-mail'); ?>" title="Send Email" class="nav-link nav-link-lg" aria-expanded="true">
                        <i class="fa fa-envelope"></i>
                    </a>
                </li>
            <?php } ?>
            <?php if (check_permissions("announcements", "read")) { ?>
                <li>
                    <a href="<?= base_url('announcements'); ?>" title="Announcements" class="nav-link nav-link-lg" aria-expanded="true">
                        <i class="fa fa-bullhorn"></i>
                    </a>
                </li>
            <?php } ?>
            <?php $beep = !empty($notifications) ? 'beep' : ''; ?>
            <li class="dropdown dropdown-list-toggle">
                <a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg <?= $beep; ?>"><i class="far fa-bell"></i></a>
                <div class="dropdown-menu dropdown-list dropdown-menu-right">
                    <div class="dropdown-header">
                        <?= !empty($this->lang->line('label_notifications')) ? $this->lang->line('label_notifications') : 'Notifications'; ?>
                        <?php if (!empty($notifications)) { ?>
                            <div class="float-right">
                                <a href="#" class="mark-all-as-read-alert" data-user-id=<?= $this->session->userdata('user_id'); ?>><?= !empty($this->lang->line('label_mark_all_as_read')) ? $this->lang->line('label_mark_all_as_read') : 'Mark all as read'; ?></a>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="dropdown-list-content dropdown-list-icons">
                        <?php if (!empty($notifications)) {
                            foreach ($notifications as $notification) {
                                $notification_url = '#';
                                switch ($notification['type']) {
                                    case 'event':
                                        $notification_url = base_url('calendar/' . $notification['type_id']);
                                        break;
                                    case 'announcement':
                                        $notification_url = base_url('announcements/details/' . $notification['type_id']);
                                        break;
                                    case 'project':
                                        $notification_url = base_url('projects/details/' . $notification['type_id']);
                                        break;
                                    case 'task':
                                        $notification_url = base_url('projects/tasks/' . $notification['type_id']);
                                        break;
                                    default:
                                        $notification_url = '#';
                                        break;
                                } ?>
                                <a href="<?= $notification_url; ?> " class="dropdown-item notification">
                                    <div class="dropdown-item-icon bg-info text-white">
                                        <i class="fas fa-bell"></i>
                                    </div>
                                    <div class="dropdown-item-desc">
                                        <?= $notification['title']; ?>
                                        <div class="time"><?= date("d-M-Y H:i:s", strtotime($notification['date_created'])); ?></div>
                                    </div>
                                </a>
                            <?php }
                        } else { ?>
                            <div class="dropdown-footer text-center">
                                <p>
                                    <?= !empty($this->lang->line('label_no_unread_notifications_found')) ? $this->lang->line('label_no_unread_notifications_found') : 'No Unread Notifications Found!!'; ?>
                                </p>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="dropdown-footer text-center">
                        <a href="<?= base_url('notifications') ?>">
                            <?= !empty($this->lang->line('label_view_all')) ? $this->lang->line('label_view_all') : 'View All'; ?>
                            <i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </li>
        <?php } ?>
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <?php if (isset($user->profile) && !empty($user->profile)) { ?>
                    <img alt="image" src="<?= base_url('assets/profiles/' . $user->profile); ?>" class="rounded-circle mr-1">
                <?php } else { ?>
                    <figure class="avatar mr-1 avatar-sm" data-initial="<?= mb_substr($user->first_name, 0, 1) . '' . mb_substr($user->last_name, 0, 1); ?>"></figure>
                <?php } ?>
                <div class="d-sm-none d-lg-inline-block"><?= !empty($this->lang->line('label_hi')) ? $this->lang->line('label_hi') : 'Hi'; ?>👋, <?= $user->first_name ?></div>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-width">
                <!--workspace code goes here-->
                <a href="<?= base_url('profile'); ?>" class="dropdown-item has-icon">
                    <i class="far fa-user"></i>
                    <?= !empty($this->lang->line('label_profile')) ? $this->lang->line('label_profile') : 'Profile'; ?>
                </a>
                <div class="dropdown-divider"></div>
                <a href="<?= base_url('auth/logout'); ?>" class="dropdown-item has-icon text-danger">
                    <i class="fas fa-sign-out-alt"></i>
                    <?= !empty($this->lang->line('label_logout')) ? $this->lang->line('label_logout') : 'Logout'; ?>
                </a>
            </div>
        </li>
    </ul>
</nav>
<?php
$response = get_system_settings('general');
?>
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="<?= base_url('home'); ?>">
                <img alt="Task Hub" src="<?= !empty($response['full_logo']) ? base_url('assets/icons/' . $response['full_logo']) : base_url('assets/icons/logo.png'); ?>" width="200px">
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="<?= base_url('home'); ?>">
                <img alt="Task Hub" src="<?= !empty($response['half_logo']) ? base_url('assets/icons/' . $response['half_logo']) : base_url('assets/icons/logo-half.png'); ?>" width="40px">
            </a>
        </div>
        <ul class="sidebar-menu">
            <li data-toggle="dropdown" class="p-2">
                <a class="workspace-btn nav-link dropdown-toggle" href="#"><i class="fas fa-check"></i> <span>
                        <?php
                        if (!empty($workspace)) {
                            $workspace_id = $this->session->userdata('workspace_id');
                            foreach ($workspace as $row) {
                                if ($row->id == $workspace_id) {
                                    echo $row->title;
                                }
                            }
                        } else {
                            echo 'No Workspace Found.';
                        } ?>
                    </span>
                </a>
            </li>
            <div class="dropdown-menu">
                <?php
                if (!empty($workspace)) {
                    $workspace_id = $this->session->userdata('workspace_id');
                    $workspace_count = count($workspace);
                    if ($workspace_count > 5) {
                        $workspace_count = 5;
                    }
                    for ($i = 0; $i < $workspace_count; $i++) {
                        $row = $workspace[$i]; ?>
                        <a href="<?= base_url('workspace/change/' . $row->id); ?>" class="dropdown-item has-icon">
                            <?php if ($row->id == $workspace_id) { ?>
                                <i class="fas fa-check"></i>
                            <?php } ?>
                            <?= $row->title ?>
                            <?php if ($row->created_by == $user->id) { ?>
                                <span class="badge badge-info projects-badge">
                                    <?= !empty($this->lang->line('label_owner')) ? $this->lang->line('label_owner') : 'Owner'; ?>
                                </span>
                            <?php } ?>
                        </a>
                <?php
                    }
                } else {
                    echo '<a href="#" class="dropdown-item has-icon">No Workspace Found.</a>';
                }
                ?>
                <div class="dropdown-divider"></div>
                <a href="<?= base_url('workspace/manage-workspaces') ?>" class="dropdown-item has-icon">
                    <i class="fas fa-chart-bar"></i>
                    <?= !empty($this->lang->line('label_manage_workspaces')) ? $this->lang->line('label_manage_workspaces') . ' ' : 'Manage Workspaces '; ?><div class="badge badge-info projects-badge"><?php echo workspace_count() ?></div>
                </a>
                <?php if (is_admin()) { ?>
                    <a href="#" id="modal-add-workspace" class="dropdown-item has-icon">
                        <i class="fas fa-plus"></i><?= !empty($this->lang->line('label_create_new_workspace')) ? $this->lang->line('label_create_new_workspace') : 'Create New Workspace'; ?>
                    </a>
                    <?php if (!empty($workspace)) { ?>
                        <a href="#" data-id="<?= $workspace_id ?>" class="dropdown-item has-icon modal-edit-workspace-ajax">
                            <i class="fas fa-edit"></i>
                            <?= !empty($this->lang->line('label_edit_workspace')) ? $this->lang->line('label_edit_workspace') : 'Edit Workspace'; ?>
                        </a>
                <?php }
                } ?>
                <?php if (!empty($this->session->has_userdata('workspace_id'))) { ?>
                    <a href="<?= base_url('users/remove-user-from-workspace/' . $user->id); ?>" class="dropdown-item has-icon">
                        <i class="fas fa-times"></i>
                        <?= !empty($this->lang->line('label_remove_me_from_workspace')) ? $this->lang->line('label_remove_me_from_workspace') : 'Remove Me From Workspace'; ?>
                    </a>
                <?php } ?>
            </div>
            <li <?= (current_url() == base_url('home')) ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url('home'); ?>"><i class="fas fa-fire text-danger"></i> <span> <?= !empty($this->lang->line('label_dashboard')) ? $this->lang->line('label_dashboard') : 'Dashboard'; ?> </span></a></li>
            <?php if (check_permissions("todos", "read")) { ?>
                <li <?= (current_url() == base_url('todo')) ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url('todo'); ?>"><i class="fas fa-bars"></i> <span> <?= !empty($this->lang->line('label_todos')) ? $this->lang->line('label_todos') : 'Todos'; ?> </span></a></li>
            <?php } ?>
            <?php if (check_permissions("statuses", "read")) { ?>
                <li <?= (current_url() == base_url('statuses')) ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url('statuses'); ?>"><i class="fas fa-file-contract"></i> <span>
                            <?= !empty($this->lang->line('label_statuses')) ? $this->lang->line('label_statuses') : 'Statuses'; ?>
                        </span></a>
                </li>
            <?php } ?>
            <?php if (!empty($this->session->has_userdata('workspace_id'))) { ?>
                <?php if (check_permissions("projects", "read")) {
                ?>
                    <li class="dropdown <?= (current_url() == base_url('projects') ||
                                            current_url() == base_url('projects/calendar') ||
                                            current_url() == base_url('projects/favorite_projects') ||
                                            current_url() == base_url('projects/lists') ||
                                            current_url() == base_url('gantt_chart') ||
                                            current_url() == base_url('bulk-projects')) ? ' active' : ''; ?>">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="far fa-file-alt text-info"></i><span><?= !empty($this->lang->line('label_projects')) ? $this->lang->line('label_projects') : 'Projects'; ?></span></a>
                        <ul class="dropdown-menu">
                            <li <?= (current_url() == base_url('projects') || $this->uri->segment(2) == 'projects') ? 'class="active"' : ''; ?>>
                                <a class="nav-link" href="<?= base_url('projects'); ?>"><i class="far fa-file-alt text-success"></i>
                                    <span> <?= !empty($this->lang->line('label_projects')) ? $this->lang->line('label_projects') : 'Projects'; ?> </span></a>
                            </li>
                            <li <?= (current_url() == base_url('projects/favorite_projects') || $this->uri->segment(2) == 'projects/favorite_projects') ? 'class="active"' : ''; ?>>
                                <a class="nav-link" href="<?= base_url('projects/favorite_projects'); ?>"><i class="fas fa-star text-golden"></i>
                                    <span> <?= !empty($this->lang->line('label_favorite_projects')) ? $this->lang->line('label_favorite_projects') : 'Favorite Projects'; ?> </span></a>
                            </li>
                            <li <?= (current_url() == base_url('projects/calendar') || $this->uri->segment(2) == 'projects/calendar') ? 'class="active"' : ''; ?>>
                                <a class="nav-link" href="<?= base_url('projects/calendar'); ?>"><i class="fas fa-calendar-alt text-info"></i>
                                    <span> <?= !empty($this->lang->line('label_calender_view')) ? $this->lang->line('label_calender_view') : 'Calender View'; ?> </span>
                                </a>
                            </li>
                            <li <?= (current_url() == base_url('/gantt_chart') || $this->uri->segment(2) == 'gantt_chart') ? 'class="active"' : ''; ?>>
                                <a class="nav-link" href="<?= base_url('/gantt_chart'); ?>"> <i class="fas fa-layer-group text-warning"></i>
                                    <span> <?= !empty($this->lang->line('label_gantt_chart')) ? $this->lang->line('label_gantt_chart') : 'Gantt chart'; ?> </span>
                                </a>
                            </li>
                            <li <?= (current_url() == base_url('projects/bulk_project_upload') || $this->uri->segment(2) == 'projects/bulk_project_upload') ? 'class="active"' : ''; ?>>
                                <a class="nav-link" href="<?= base_url('projects/bulk_project_upload'); ?>"> <i class="fas fa-upload text-danger"></i>
                                    <span> <?= !empty($this->lang->line('label_bulk_upload')) ? $this->lang->line('label_bulk_upload') : 'Bulk Upload'; ?> </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php } ?>
                <?php if (check_permissions("tasks", "read")) { ?>
                    <li class="dropdown <?= (current_url() == base_url('tasks') || current_url() == base_url('projects/bulk_task_upload')) ? ' active' : ''; ?>">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="far fa-newspaper text-warning"></i><span><?= !empty($this->lang->line('label_tasks')) ? $this->lang->line('label_tasks') : 'Tasks'; ?></span></a>
                        <ul class="dropdown-menu">
                            <li <?= (current_url() == base_url('tasks') || $this->uri->segment(2) == 'tasks') ? 'class="active"' : ''; ?>>
                                <a class="nav-link" href="<?= base_url('tasks'); ?>"><i class="far fa-newspaper text-success"></i>
                                    <span> <?= !empty($this->lang->line('label_tasks')) ? $this->lang->line('label_tasks') : 'Tasks'; ?> </span></a>
                            </li>
                            <li <?= (current_url() == base_url('projects/bulk_task_upload') || $this->uri->segment(2) == 'projects/bulk_task_upload') ? 'class="active"' : ''; ?>>
                                <a class="nav-link" href="<?= base_url('projects/bulk_task_upload'); ?>"> <i class="fas fa-upload text-danger"></i>
                                    <span> <?= !empty($this->lang->line('label_bulk_upload')) ? $this->lang->line('label_bulk_upload') : 'Bulk Upload'; ?> </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php } ?>

                <?php if (check_permissions("leads", "read")) { ?>
                    <li <?= (current_url() == base_url('leads')) ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url('leads'); ?>"><i class="fas fa-tty text-danger"></i> <span>
                                <?= !empty($this->lang->line('label_leads')) ? $this->lang->line('label_leads') : 'Leads'; ?>
                            </span></a>
                    </li>
                <?php } ?>
                <!-- <li <?= (current_url() == base_url('screenshot')) ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url('screenshot'); ?>"><i class="fas fa-phone text-danger"></i> <span>
                                <?= !empty($this->lang->line('label_screenshot')) ? $this->lang->line('label_screenshot') : 'screenshot'; ?>
                            </span></a>
                    </li> -->
                <?php if (check_permissions("payslips", "read")) { ?>
                    <li <?= (current_url() == base_url('payslip') || current_url() == base_url('payslip/create-payslip')) ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url('payslip'); ?>"><i class="fas fa-receipt text-success"></i> <span>
                                <?= !empty($this->lang->line('label_payslip')) ? $this->lang->line('label_payslip') : 'Payslip'; ?>
                            </span></a>
                    </li>
                <?php } ?>
                <?php if (check_permissions("meetings", "read")) { ?>
                    <li <?= (current_url() == base_url('meetings')) ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url('meetings'); ?>"><i class="fas fa-handshake text-info"></i> <span><?= !empty($this->lang->line('label_meetings')) ? $this->lang->line('label_meetings') : 'Meetings'; ?></span></a></li>
                <?php } ?>
                <?php if (check_permissions("calendar", "read")) { ?>
                    <li <?= (current_url() == base_url('calendar')) ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url('calendar'); ?>"><i class="fas fa-calendar text-danger"></i> <span>
                                <?= !empty($this->lang->line('label_calendar')) ? $this->lang->line('label_calendar') : 'Calendar'; ?>
                            </span></a>
                    </li>
                <?php } ?>
                <?php if (check_permissions("chat", "read")) { ?>
                    <li <?= (current_url() == base_url('chat')) ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url('chat'); ?>"><i class="fas fa-comments text-success"></i>
                            <?php $unread_msg = get_chat_count();
                            if ($unread_msg > 0 && current_url() != base_url('chat')) { ?>
                                <span class="font-weight-bold" title="<?= $unread_msg ?> unread message(s) "><?= !empty($this->lang->line('label_chat')) ? $this->lang->line('label_chat') : 'Chat'; ?> <i class="fas fa-circle text-danger fa-xs"></i>
                                </span>
                            <?php } else { ?>
                                <span><?= !empty($this->lang->line('label_chat')) ? $this->lang->line('label_chat') : 'Chat'; ?></span>
                            <?php } ?>
                        </a>
                    </li>
                <?php } ?>
                <?php if (is_admin()) { ?>
                    <li class="dropdown <?= (current_url() == base_url('expenses') || current_url() == base_url('expenses/expense-types') || current_url() == base_url('estimates') || current_url() == base_url('estimates/create-estimate') || current_url() == base_url('taxes') || current_url() == base_url('items') || current_url() == base_url('units') || current_url() == base_url('invoices') || current_url() == base_url('payments') || current_url() == base_url('payments/payment-modes') || current_url() == base_url('invoices/create-invoice') || $this->uri->segment(2) == 'view-estimate' || $this->uri->segment(2) == 'invoice' || $this->uri->segment(2) == 'edit-estimate') || $this->uri->segment(2) == 'view-invoice' || $this->uri->segment(2) == 'edit-invoice' || $this->uri->segment(2) == 'estimate' ? ' active' : ''; ?>">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa fa-credit-card"></i> <span><?= !empty($this->lang->line('label_finance')) ? $this->lang->line('label_finance') : 'Finance'; ?></span></a>
                        <ul class="dropdown-menu" style="display: none;">
                            <li <?= (current_url() == base_url('expenses') || current_url() == base_url('expenses/expense-types')) ? 'class="active"' : ''; ?>>
                                <a class="nav-link" href="<?= base_url('expenses'); ?>">
                                    <span>
                                        <?= !empty($this->lang->line('label_expenses')) ? $this->lang->line('label_expenses') : 'Expenses'; ?>
                                    </span>
                                </a>
                            </li>

                            <li <?= (current_url() == base_url('estimates') || current_url() == base_url('estimates/create-estimate') || $this->uri->segment(2) == 'view-estimate' || $this->uri->segment(2) == 'edit-estimate' || $this->uri->segment(2) == 'estimate') ? 'class="active"' : ''; ?>>
                                <a class="nav-link" href="<?= base_url('estimates'); ?>">
                                    <span>
                                        <?= !empty($this->lang->line('label_estimates')) ? $this->lang->line('label_estimates') : 'Estimates'; ?>
                                    </span>
                                </a>
                            </li>

                            <li <?= (current_url() == base_url('invoices') || $this->uri->segment(2) == 'invoice' || current_url() == base_url('invoices/create-invoice') || $this->uri->segment(2) == 'view-invoice' || $this->uri->segment(2) == 'edit-invoice') ? 'class="active"' : ''; ?>>
                                <a class="nav-link" href="<?= base_url('invoices'); ?>">
                                    <span>
                                        <?= !empty($this->lang->line('label_invoices')) ? $this->lang->line('label_invoices') : 'Invoices'; ?>
                                    </span>
                                </a>
                            </li>
                            <li <?= (current_url() == base_url('items')) ? 'class="active"' : ''; ?>>
                                <a class="nav-link" href="<?= base_url('items'); ?>">
                                    <span>
                                        <?= !empty($this->lang->line('label_items')) ? $this->lang->line('label_items') : 'Items'; ?>
                                    </span>
                                </a>
                            </li>
                            <li <?= (current_url() == base_url('payments') || current_url() == base_url('payments/payment-modes')) ? 'class="active"' : ''; ?>>
                                <a class="nav-link" href="<?= base_url('payments'); ?>">
                                    <span>
                                        <?= !empty($this->lang->line('label_payments')) ? $this->lang->line('label_payments') : 'Payments'; ?>
                                    </span>
                                </a>
                            </li>
                            <li <?= (current_url() == base_url('taxes')) ? 'class="active"' : ''; ?>>
                                <a class="nav-link" href="<?= base_url('taxes'); ?>">
                                    <span>
                                        <?= !empty($this->lang->line('label_taxes')) ? $this->lang->line('label_taxes') : 'Taxes'; ?>
                                    </span>
                                </a>
                            </li>

                            <li <?= (current_url() == base_url('units')) ? 'class="active"' : ''; ?>>
                                <a class="nav-link" href="<?= base_url('units'); ?>">
                                    <span>
                                        <?= !empty($this->lang->line('label_units')) ? $this->lang->line('label_units') : 'Units'; ?>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php } ?>
                <?php if (check_permissions("users", "read")) { ?>
                    <li <?= (current_url() == base_url('users')) ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url('users'); ?>"><i class="fas fa-user text-warning"></i> <span>
                                <?= !empty($this->lang->line('label_users')) ? $this->lang->line('label_users') : 'Users'; ?>
                            </span></a>
                    </li>
                <?php } ?>
                <?php if (check_permissions("clients", "read")) { ?>
                    <li <?= (current_url() == base_url('clients')) ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url('clients'); ?>"><i class="fas fa-users text-info"></i> <span>
                                <?= !empty($this->lang->line('label_clients')) ? $this->lang->line('label_clients') : 'Clients'; ?>
                            </span></a>
                    </li>
                <?php } ?>
                <?php if (check_permissions("knowledgebase", "read")) { ?>
                    <li <?= (current_url() == base_url('knowledgebase') || current_url() == base_url('knowledgebase/add-article') || current_url() == base_url('knowledgebase/view-article') || current_url() == base_url('knowledgebase/edit-article') || current_url() == base_url('knowledgebase/article_groups')) ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url('knowledgebase'); ?>"> <i class="fas fa-book-open text-danger"></i>
                            <span>
                                <?= !empty($this->lang->line('label_knowledgebase')) ? $this->lang->line('label_knowledgebase') : 'Knowledgebase'; ?>
                            </span>
                        </a>
                    </li>
                <?php } ?>
                <?php if (check_permissions("contracts", "read")) { ?>
                    <li <?= (current_url() == base_url('contracts') || current_url() == base_url('contracts/contracts-type')) ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url('contracts'); ?>"><i class="fas fa-file-contract"></i> <span>
                                <?= !empty($this->lang->line('label_contracts')) ? $this->lang->line('label_contracts') : 'Contracts'; ?>
                            </span></a>
                    </li>
                <?php } ?>
                <?php if (is_admin()) { ?>
                    <li <?= (current_url() == base_url('activity-logs')) ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url('activity-logs'); ?>"><i class="fas fa-chart-line text-warning"></i><span>
                                <?= !empty($this->lang->line('label_activity_logs')) ? $this->lang->line('label_activity_logs') : 'Activity Logs'; ?>
                            </span></a>
                    </li>
                <?php } ?>
                <?php if (check_permissions("leave_requests", "read")) { ?>
                    <li <?= (current_url() == base_url('leaves')) ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url('leaves'); ?>"><i class="fas fa-check text-success"></i> <span>
                                <?= !empty($this->lang->line('label_leave_requests')) ? $this->lang->line('label_leave_requests') : 'Leave Requests'; ?>
                            </span></a>
                    </li>
                <?php } ?>
                <?php if (check_permissions("time_tracker", "read")) { ?>
                    <li <?= (current_url() == base_url('time_tracker')) ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url('time_tracker'); ?>"><i class="fas fa-clock text-warning"></i> <span>
                                <?= !empty($this->lang->line('label_time_tracker')) ? $this->lang->line('label_time_tracker') : 'Time Tracker'; ?>
                            </span></a>
                    </li>
                <?php } ?>
                <?php if (check_permissions("notes", "read")) { ?>
                    <li <?= (current_url() == base_url('notes')) ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url('notes'); ?>"><i class="fas fa-clipboard-list text-danger"></i> <span>
                                <?= !empty($this->lang->line('label_notes')) ? $this->lang->line('label_notes') : 'Notes'; ?>
                            </span></a>
                    </li>
                <?php } ?>
            <?php } ?>
            <?php if (check_permissions("projects", "read") || check_permissions("tasks", "read") || check_permissions("leads", "read") || check_permissions("invoices", "read")) {
            ?>
                <li class="dropdown <?= (current_url() == base_url('report/project_report') || current_url() == base_url('report/tasks_report') || current_url() == base_url('report/leads_report') || current_url() == base_url('report/invoices_report')) ? ' active' : ''; ?>">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-money-bill-alt text-info"></i> <span><?= !empty($this->lang->line('label_report')) ? $this->lang->line('label_report') : 'Report'; ?></span></a>
                    <ul class="dropdown-menu">
                        <?php if (check_permissions("projects", "read")) { ?>
                            <li <?= (current_url() == base_url('report/project_report')) ? 'class="active"' : ''; ?>>
                                <a class="nav-link" href="<?= base_url('report/project_report'); ?>">
                                    <span>
                                        <?= !empty($this->lang->line('label_projects_report')) ? $this->lang->line('label_projects_report') : 'Projects Report'; ?>
                                    </span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if (check_permissions("tasks", "read")) { ?>
                            <li <?= (current_url() == base_url('report/tasks_report')) ? 'class="active"' : ''; ?>>
                                <a class="nav-link" href="<?= base_url('report/tasks_report'); ?>">
                                    <span>
                                        <?= !empty($this->lang->line('label_tasks_report')) ? $this->lang->line('label_tasks_report') : 'Tasks Report'; ?>
                                    </span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if (check_permissions("leads", "read")) { ?>
                            <li <?= (current_url() == base_url('report/leads_report')) ? 'class="active"' : ''; ?>>
                                <a class="nav-link" href="<?= base_url('report/leads_report'); ?>">
                                    <span>
                                        <?= !empty($this->lang->line('label_leads_report')) ? $this->lang->line('label_leads_report') : 'Leads Report'; ?>
                                    </span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if (check_permissions("invoices", "read")) { ?>
                            <li <?= (current_url() == base_url('report/invoices_report')) ? 'class="active"' : ''; ?>>
                                <a class="nav-link" href="<?= base_url('report/invoices_report'); ?>">
                                    <span>
                                        <?= !empty($this->lang->line('label_invoices_report')) ? $this->lang->line('label_invoices_report') : 'Invoices Report'; ?>
                                    </span>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php }
            ?>
            <?php if (is_admin()) { ?>
                <li <?= (current_url() == base_url('purchase_code')) ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url('purchase_code'); ?>"><i class="far fa-check-circle text-success"></i><span>
                            <?= !empty($this->lang->line('label_system_regsitrations')) ? $this->lang->line('label_system_regsitrations') : 'System Regsitration'; ?>
                        </span></a>
                </li>
            <?php } ?>
            <?php if (is_admin()) { ?>
                <li class="dropdown <?= (current_url() == base_url('settings/setting-detail') || current_url() == base_url('email-templates') || current_url() == base_url('permissions'))  ? ' active' : ''; ?>">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-cog"></i> <span><?= !empty($this->lang->line('label_settings')) ? $this->lang->line('label_settings') : 'Settings'; ?></span></a>
                    <ul class="dropdown-menu" style="display: block;">
                        <li <?= (current_url() == base_url('settings/setting-detail') || current_url() == base_url('settings/setting-detail')) ? 'class="active"' : ''; ?>>
                            <a class="nav-link" href="<?= base_url('settings/setting-detail'); ?>">
                                <span>
                                    <?= !empty($this->lang->line('label_settings')) ? $this->lang->line('label_settings') : 'Settings'; ?>
                                </span>
                            </a>
                        </li>
                        <li <?= (current_url() == base_url('email-templates') || current_url() == base_url('email-templates')) ? 'class="active"' : ''; ?>>
                            <a class="nav-link" href="<?= base_url('email-templates'); ?>">
                                <span>
                                    <?= !empty($this->lang->line('label_email_templates')) ? $this->lang->line('label_email_templates') : 'Email Templates'; ?>
                                </span>
                            </a>
                        </li>
                        <li <?= (current_url() == base_url('permissions') || current_url() == base_url('permissions')) ? 'class="active"' : ''; ?>>
                            <a class="nav-link" href="<?= base_url('permissions'); ?>">
                                <span>
                                    <?= !empty($this->lang->line('label_user_group_permissions')) ? $this->lang->line('label_user_group_permissions') : 'User Permissions'; ?>
                                </span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-header">-</li>
            <?php } ?>
            <li class="language-btn dropup">
                <a data-toggle="dropdown" class="nav-link dropdown-toggle" href="#"><i class="fas fa-language"></i>
                    <span><?= isset($user->lang) ? ucfirst($user->lang) : ' '; ?></span>
                </a>
                <div class="dropdown-menu">
                    <?php
                    $languages =  get_languages();
                    if (!empty($languages)) {
                        foreach ($languages as $lang) { ?>
                            <a href='<?= base_url("languageswitch/switchlang/" . $lang['language']); ?>' class="dropdown-item has-icon"><?= (($lang['language'] == $user->lang) ? '<i class="fas fa-check"></i>' : ''); ?> <?= ucfirst($lang['language']); ?> </a>
                        <?php }
                    }
                    if (is_admin()) {
                        ?>
                        <div class="dropdown-divider"></div>
                        <a href='<?= base_url("languages/change/" . $user->lang); ?>' class="dropdown-item has-icon"> <?= !empty($this->lang->line('label_create_and_customize')) ? $this->lang->line('label_create_and_customize') : 'Create & Customize'; ?> </a>
                    <?php } ?>

                </div>
            </li>
        </ul>
    </aside>
    <?php if (check_permissions("time_tracker", "create")) { ?>
        <div class="timer" id="timer" onclick="open_timer_section()">
            <div class="internal-section rounded-circle border shadow-sm p-1 mb-2 bg-white rounded" id="modal" data-toggle="modal" data-target="#exampleModalLong">
                <img alt="image" class="timer-img" id="timer-image" width="10%" height="10%" src="<?= base_url('assets/img/94150-clock.png'); ?>" />
            </div>
        </div>
    <?php } ?>
    <?php if (check_permissions("chat", "read")) { ?>
        <div class="align-items-center border chat-internal-section d-flex justify-content-center mb-2 p-1 rounded rounded-circle shadow-sm" id="modal">

            <div id="chat-button" class="text-white"><i class="far fa-comments"></i></div>
            <!-- Floating chat iframe -->
            <iframe src="<?= base_url('chat/floating_chat') ?>" id="chat-iframe" style="display: none; position: absolute; bottom: 65px; right: -44px; width: 450px; height: 600px; border: none;z-index:999;"></iframe>
        </div>
    <?php } ?>
</div>
</div>
<!-- modal for timer -->
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Timer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
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
                <div class="stopwatch">
                    <div class="stopwatch_time">
                        <input type="text" name="hour" id="hour" value="00" class="form-control stopwatch_time_input" readonly>
                        <div class="stopwatch_time_lable">Hours</div>
                    </div>
                    <div class="stopwatch_time">
                        <input type="text" name="minute" id="minute" value="00" class="form-control stopwatch_time_input" readonly>
                        <div class="stopwatch_time_lable">Minutes</div>
                    </div>
                    <div class="stopwatch_time">
                        <input type="text" name="second" id="second" value="00" class="form-control stopwatch_time_input" readonly>
                        <div class="stopwatch_time_lable">Second</div>
                    </div>
                </div>
                <div class="selectgroup selectgroup-pills d-flex justify-content-around mt-3">
                    <label class="selectgroup-item">
                        <span class="selectgroup-button selectgroup-button-icon" title="Start" id="start" onclick="startTimer()"><i class="fas fa-play"></i></span>
                    </label>
                    <label class="selectgroup-item">
                        <span class="selectgroup-button selectgroup-button-icon" title="Stop" id="end" onclick="stopTimer()"><i class="fas fa-stop"></i></span>
                    </label>
                    <label class="selectgroup-item">
                        <span class="selectgroup-button selectgroup-button-icon" title="Pause" id="pause" onclick="pauseTimer()"><i class="fas fa-pause"></i></span>
                    </label>
                </div>
                <div class="form-group mb-0">
                    <label class="label"><?= !empty($this->lang->line('label_message')) ? $this->lang->line('label_message') : 'Message'; ?>:</label>
                    <textarea class="form-control" id="message" placeholder="Enter Your Message" name="message" required></textarea>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <a href="<?= base_url('time_tracker'); ?>" class="btn btn-primary"><i class="fas fa-regular fa-clock"></i> View all Timesheets</a>

            </div>
        </div>
    </div>
</div>

