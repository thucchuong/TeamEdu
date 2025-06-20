<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= !empty($this->lang->line('label_languages')) ? $this->lang->line('label_languages') : 'Languages'; ?> &mdash; <?= get_compnay_title(); ?></title>
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
                        <h1><?= !empty($this->lang->line('label_languages')) ? $this->lang->line('label_languages') : 'Languages'; ?>
                        </h1>
                        <div class="section-header-breadcrumb">
                            <i class="btn btn-primary btn-rounded no-shadow" id="modal-add-language"><?= !empty($this->lang->line('label_create_new')) ? $this->lang->line('label_create_new') : 'Create New'; ?></i>
                        </div>
                    </div>
                    <div class="section-body">
                        <div id="output-status"></div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h4><?= !empty($this->lang->line('label_jump_to')) ? $this->lang->line('label_jump_to') : 'Jump To'; ?></h4>
                                    </div>
                                    <div class="card-body">
                                        <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                                            <?php
                                            $languages =  get_languages();
                                            foreach ($languages as $lang) {
                                                if ($lang['language'] == $active_tab_lang) { ?>
                                                    <li class="nav-item">
                                                        <a class="nav-link active" href='<?= base_url("languages/change/" . $lang['language']); ?>'><?= ucfirst($lang['language']); ?></a>
                                                    </li>
                                                <?php } else { ?>
                                                    <li class="nav-item">
                                                        <a class="nav-link" href='<?= base_url("languages/change/" . $lang['language']); ?>'><?= ucfirst($lang['language']); ?></a>
                                                    </li>
                                            <?php }
                                            } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="tab-content no-padding" id="myTab2Content">
                                    <div class="tab-pane fade show active" id="languages-settings" role="tabpanel" aria-labelledby="languages-tab4">
                                        <form action="<?= base_url('languages/save_languages'); ?>" id="languages-setting-form">
                                            <div class="card" id="languages-settings-card">
                                                <div class="card-header">
                                                    <h4>Labels</h4>
                                                    <div class="card-header-action float-right">
                                                        <div class="card-widgets form-check">
                                                            <input class="form-check-input" name="is_rtl" type="checkbox" id="is_rtlCheck1" <?= !empty($rtl) ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="is_rtlCheck1">
                                                                Enable RTL
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label>Dashboard</label>
                                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" class="form-control" value="<?= $this->security->get_csrf_hash(); ?>">
                                                            <input type="hidden" name="language" value='<?= $this->lang->line('label_language') ?>'>
                                                            <input type="text" name="dashboard" value='<?= $this->lang->line('label_dashboard') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Projects</label>
                                                            <input type="text" name="projects" value='<?= $this->lang->line('label_projects') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Users</label>
                                                            <input type="text" name="users" value='<?= $this->lang->line('label_users') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Chat</label>
                                                            <input type="text" name="chat" value='<?= $this->lang->line('label_chat') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Notes</label>
                                                            <input type="text" name="notes" value='<?= $this->lang->line('label_notes') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Note</label>
                                                            <input type="text" name="note" value='<?= $this->lang->line('label_note') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Settings</label>
                                                            <input type="text" name="settings" value='<?= $this->lang->line('label_settings') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Notifications</label>
                                                            <input type="text" name="notifications" value='<?= $this->lang->line('label_notifications') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Calendar</label>
                                                            <input type="text" name="calendar" value='<?= $this->lang->line('label_calendar') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Activity Logs</label>
                                                            <input type="text" name="activity_logs" value='<?= $this->lang->line('label_activity_logs') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Attachments</label>
                                                            <input type="text" name="attachments" value='<?= $this->lang->line('label_attachments') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Send Mail</label>
                                                            <input type="text" name="send_mail" value='<?= $this->lang->line('label_send_mail') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Active</label>
                                                            <input type="text" name="active" value='<?= $this->lang->line('label_active') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Deactive</label>
                                                            <input type="text" name="deactive" value='<?= $this->lang->line('label_deactive') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Events</label>
                                                            <input type="text" name="events" value='<?= $this->lang->line('label_events') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Announcements</label>
                                                            <input type="text" name="announcements" value='<?= $this->lang->line('label_announcements') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Total Projects</label>
                                                            <input type="text" name="total_projects" value='<?= $this->lang->line('label_total_projects') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Total Tasks</label>
                                                            <input type="text" name="total_tasks" value='<?= $this->lang->line('label_total_tasks') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Personal note</label>
                                                            <input type="text" name="personal_note" value='<?= $this->lang->line('label_personal_note') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Project Status</label>
                                                            <input type="text" name="project_status" value='<?= $this->lang->line('label_project_status') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Task Overview</label>
                                                            <input type="text" name="task_overview" value='<?= $this->lang->line('label_task_overview') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Task Insights</label>
                                                            <input type="text" name="task_insights" value='<?= $this->lang->line('label_task_insights') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Ongoing</label>
                                                            <input type="text" name="ongoing" value='<?= $this->lang->line('label_ongoing') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Finished</label>
                                                            <input type="text" name="finished" value='<?= $this->lang->line('label_finished') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Onhold</label>
                                                            <input type="text" name="onhold" value='<?= $this->lang->line('label_onhold') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Todos</label>
                                                            <input type="text" name="todos" value='<?= $this->lang->line('label_todos') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Todo</label>
                                                            <input type="text" name="todo" value='<?= $this->lang->line('label_todo') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>In Progress</label>
                                                            <input type="text" name="in_progress" value='<?= $this->lang->line('label_in_progress') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Review</label>
                                                            <input type="text" name="review" value='<?= $this->lang->line('label_review') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Retry</label>
                                                            <input type="text" name="retry" value='<?= $this->lang->line('label_retry') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>No attachments found</label>
                                                            <input type="text" name="no_attachments_found" value='<?= $this->lang->line('label_no_attachments_found') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Mail Details</label>
                                                            <input type="text" name="mail_details" value='<?= $this->lang->line('label_mail_details') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Expired</label>
                                                            <input type="text" name="expired" value='<?= $this->lang->line('label_expired') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Due</label>
                                                            <input type="text" name="due" value='<?= $this->lang->line('label_due') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Done</label>
                                                            <input type="text" name="done" value='<?= $this->lang->line('label_done') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Expiry date</label>
                                                            <input type="text" name="expiry_date" value='<?= $this->lang->line('label_expiry_date') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Choose Item</label>
                                                            <input type="text" name="choose_item" value='<?= $this->lang->line('label_choose_item') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Create Payment</label>
                                                            <input type="text" name="create_payment" value='<?= $this->lang->line('label_create_payment') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Add Item</label>
                                                            <input type="text" name="add_item" value='<?= $this->lang->line('label_add_item') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Edit Note</label>
                                                            <input type="text" name="edit_note" value='<?= $this->lang->line('label_edit_note') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Edit Projects</label>
                                                            <input type="text" name="edit_projects" value='<?= $this->lang->line('label_edit_projects') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Edit Task</label>
                                                            <input type="text" name="edit_task" value='<?= $this->lang->line('label_edit_task') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Add Tax</label>
                                                            <input type="text" name="add_tax" value='<?= $this->lang->line('label_add_tax') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Select Tax</label>
                                                            <input type="text" name="select_tax" value='<?= $this->lang->line('label_select_tax') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Address</label>
                                                            <input type="text" name="address" value='<?= $this->lang->line('label_address') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>City</label>
                                                            <input type="text" name="city" value='<?= $this->lang->line('label_city') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>State</label>
                                                            <input type="text" name="state" value='<?= $this->lang->line('label_state') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Country</label>
                                                            <input type="text" name="country" value='<?= $this->lang->line('label_country') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Username</label>
                                                            <input type="text" name="user_name" value='<?= $this->lang->line('label_user_name') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Expense Type</label>
                                                            <input type="text" name="expense_type" value='<?= $this->lang->line('label_expense_type') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Expense Date</label>
                                                            <input type="text" name="expense_date" value='<?= $this->lang->line('label_expense_date') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Zip Code</label>
                                                            <input type="text" name="zip_code" value='<?= $this->lang->line('label_zip_code') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>No task assigned</label>
                                                            <input type="text" name="no_task_assigned" value='<?= $this->lang->line('label_no_task_assigned') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Owner</label>
                                                            <input type="text" name="owner" value='<?= $this->lang->line('label_owner') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Product Service</label>
                                                            <input type="text" name="product_service" value='<?= $this->lang->line('label_product_service') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Create New Workspace</label>
                                                            <input type="text" name="create_new_workspace" value='<?= $this->lang->line('label_create_new_workspace') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Remove Me From Workspace</label>
                                                            <input type="text" name="remove_me_from_workspace" value='<?= $this->lang->line('label_remove_me_from_workspace') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Profile</label>
                                                            <input type="text" name="profile" value='<?= $this->lang->line('label_profile') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Quantity</label>
                                                            <input type="text" name="quantity" value='<?= $this->lang->line('label_quantity') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Logout</label>
                                                            <input type="text" name="logout" value='<?= $this->lang->line('label_logout') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>All</label>
                                                            <input type="text" name="all" value='<?= $this->lang->line('label_all') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Tasks</label>
                                                            <input type="text" name="tasks" value='<?= $this->lang->line('label_tasks') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Comments</label>
                                                            <input type="text" name="comments" value='<?= $this->lang->line('label_comments') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Details</label>
                                                            <input type="text" name="details" value='<?= $this->lang->line('label_details') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Declined</label>
                                                            <input type="text" name="declined" value='<?= $this->lang->line('label_declined') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Delete</label>
                                                            <input type="text" name="delete" value='<?= $this->lang->line('label_delete') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Create Project</label>
                                                            <input type="text" name="create_project" value='<?= $this->lang->line('label_create_project') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Start Date</label>
                                                            <input type="text" name="start_date" value='<?= $this->lang->line('label_start_date') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>End Date</label>
                                                            <input type="text" name="end_date" value='<?= $this->lang->line('label_end_date') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Budget</label>
                                                            <input type="text" name="budget" value='<?= $this->lang->line('label_budget') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Days Left</label>
                                                            <input type="text" name="days_left" value='<?= $this->lang->line('label_days_left') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Upload Files</label>
                                                            <input type="text" name="upload_files" value='<?= $this->lang->line('label_upload_files') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Drop Files Here To Upload</label>
                                                            <input type="text" name="drop_files_here_to_upload" value='<?= $this->lang->line('label_drop_files_here_to_upload') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Drop File Here To Upload</label>
                                                            <input type="text" name="drop_file_here_to_upload" value='<?= $this->lang->line('label_drop_file_here_to_upload') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Uploaded Files</label>
                                                            <input type="text" name="uploaded_files" value='<?= $this->lang->line('label_uploaded_files') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Preview</label>
                                                            <input type="text" name="preview" value='<?= $this->lang->line('label_preview') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Name</label>
                                                            <input type="text" name="name" value='<?= $this->lang->line('label_name') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Size</label>
                                                            <input type="text" name="size" value='<?= $this->lang->line('label_size') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Action</label>
                                                            <input type="text" name="action" value='<?= $this->lang->line('label_action') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Milestones</label>
                                                            <input type="text" name="milestones" value='<?= $this->lang->line('label_milestones') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Billing Details</label>
                                                            <input type="text" name="billing_details" value='<?= $this->lang->line('label_billing_details') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Accepted</label>
                                                            <input type="text" name="accepted" value='<?= $this->lang->line('label_accepted') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Title</label>
                                                            <input type="text" name="title" value='<?= $this->lang->line('label_title') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Status</label>
                                                            <input type="text" name="status" value='<?= $this->lang->line('label_status') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Summary</label>
                                                            <input type="text" name="summary" value='<?= $this->lang->line('label_summary') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Cost</label>
                                                            <input type="text" name="cost" value='<?= $this->lang->line('label_cost') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Create</label>
                                                            <input type="text" name="create" value='<?= $this->lang->line('label_create') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Incomplete</label>
                                                            <input type="text" name="incomplete" value='<?= $this->lang->line('label_incomplete') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Complete</label>
                                                            <input type="text" name="complete" value='<?= $this->lang->line('label_complete') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Description</label>
                                                            <input type="text" name="description" value='<?= $this->lang->line('label_description') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Update</label>
                                                            <input type="text" name="update" value='<?= $this->lang->line('label_update') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Edit</label>
                                                            <input type="text" name="edit" value='<?= $this->lang->line('label_edit') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Project Tasks</label>
                                                            <input type="text" name="project_tasks" value='<?= $this->lang->line('label_project_tasks') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Create Task</label>
                                                            <input type="text" name="create_task" value='<?= $this->lang->line('label_create_task') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Milestone</label>
                                                            <input type="text" name="milestone" value='<?= $this->lang->line('label_milestone') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Add Milestone</label>
                                                            <input type="text" name="add_milestone" value='<?= $this->lang->line('label_add_milestone') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Edit Milestone</label>
                                                            <input type="text" name="edit_milestone" value='<?= $this->lang->line('label_edit_milestone') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Priority</label>
                                                            <input type="text" name="priority" value='<?= $this->lang->line('label_priority') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Price</label>
                                                            <input type="text" name="price" value='<?= $this->lang->line('label_price') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Low</label>
                                                            <input type="text" name="low" value='<?= $this->lang->line('label_low') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Medium</label>
                                                            <input type="text" name="medium" value='<?= $this->lang->line('label_medium') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>High</label>
                                                            <input type="text" name="high" value='<?= $this->lang->line('label_high') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Message</label>
                                                            <input type="text" name="message" value='<?= $this->lang->line('label_message') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Type your Message</label>
                                                            <input type="text" name="type_your_message" value='<?= $this->lang->line('label_type_your_message') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Assign To</label>
                                                            <input type="text" name="assign_to" value='<?= $this->lang->line('label_assign_to') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Due Date</label>
                                                            <input type="text" name="due_date" value='<?= $this->lang->line('label_due_date') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>From Date</label>
                                                            <input type="text" name="from_date" value='<?= $this->lang->line('label_from_date') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Date</label>
                                                            <input type="text" name="date" value='<?= $this->lang->line('label_date') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Files</label>
                                                            <input type="text" name="files" value='<?= $this->lang->line('label_files') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Submit</label>
                                                            <input type="text" name="submit" value='<?= $this->lang->line('label_submit') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Admin</label>
                                                            <input type="text" name="admin" value='<?= $this->lang->line('label_admin') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Background color</label>
                                                            <input type="text" name="background_color" value='<?= $this->lang->line('label_background_color') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Text color</label>
                                                            <input type="text" name="text_color" value='<?= $this->lang->line('label_text_color') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Is Public</label>
                                                            <input type="text" name="is_public" value='<?= $this->lang->line('label_is_public') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Smtp Mail</label>
                                                            <input type="text" name="smtp_mail" value='<?= $this->lang->line('label_smtp_mail') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Team Member</label>
                                                            <input type="text" name="team_member" value='<?= $this->lang->line('label_team_member') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Contact</label>
                                                            <input type="text" name="contact" value='<?= $this->lang->line('label_contact') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Add User</label>
                                                            <input type="text" name="add_user" value='<?= $this->lang->line('label_add_user') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Add Unit</label>
                                                            <input type="text" name="add_unit" value='<?= $this->lang->line('label_add_unit') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Edit Unit</label>
                                                            <input type="text" name="edit_unit" value='<?= $this->lang->line('label_edit_unit') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Make Admin</label>
                                                            <input type="text" name="make_admin" value='<?= $this->lang->line('label_make_admin') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Delete From Workspace</label>
                                                            <input type="text" name="delete_from_workspace" value='<?= $this->lang->line('label_delete_from_workspace') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Email</label>
                                                            <input type="text" name="email" value='<?= $this->lang->line('label_email') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>First Name</label>
                                                            <input type="text" name="first_name" value='<?= $this->lang->line('label_first_name') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Last Name</label>
                                                            <input type="text" name="last_name" value='<?= $this->lang->line('label_last_name') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Password</label>
                                                            <input type="text" name="password" value='<?= $this->lang->line('label_password') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Confirm Password</label>
                                                            <input type="text" name="confirm_password" value='<?= $this->lang->line('label_confirm_password') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Add</label>
                                                            <input type="text" name="add" value='<?= $this->lang->line('label_add') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Chat Box</label>
                                                            <input type="text" name="chat_box" value='<?= $this->lang->line('label_chat_box') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Personal Chat</label>
                                                            <input type="text" name="personal_chat" value='<?= $this->lang->line('label_personal_chat') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Group Chat</label>
                                                            <input type="text" name="group_chat" value='<?= $this->lang->line('label_group_chat') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Close</label>
                                                            <input type="text" name="close" value='<?= $this->lang->line('label_close') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Search</label>
                                                            <input type="text" name="search" value='<?= $this->lang->line('label_search') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Search All</label>
                                                            <input type="text" name="search_all" value='<?= $this->lang->line('label_search_all') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Create Group</label>
                                                            <input type="text" name="create_group" value='<?= $this->lang->line('label_create_group') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Edit Group</label>
                                                            <input type="text" name="edit_group" value='<?= $this->lang->line('label_edit_group') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Select Users</label>
                                                            <input type="text" name="select_users" value='<?= $this->lang->line('label_select_users') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Color</label>
                                                            <input type="text" name="color" value='<?= $this->lang->line('label_color') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>OK</label>
                                                            <input type="text" name="ok" value='<?= $this->lang->line('label_ok') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Cancel</label>
                                                            <input type="text" name="cancel" value='<?= $this->lang->line('label_cancel') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Your Data is safe</label>
                                                            <input type="text" name="your_data_is_safe" value='<?= $this->lang->line('label_your_data_is_safe') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Add Note</label>
                                                            <input type="text" name="add_note" value='<?= $this->lang->line('label_add_note') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Create Note</label>
                                                            <input type="text" name="create_note" value='<?= $this->lang->line('label_create_note') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Info</label>
                                                            <input type="text" name="info" value='<?= $this->lang->line('label_info') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Warning</label>
                                                            <input type="text" name="warning" value='<?= $this->lang->line('label_warning') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Danger</label>
                                                            <input type="text" name="danger" value='<?= $this->lang->line('label_danger') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>General</label>
                                                            <input type="text" name="general" value='<?= $this->lang->line('label_general') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>System</label>
                                                            <input type="text" name="system" value='<?= $this->lang->line('label_system') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Change Settings</label>
                                                            <input type="text" name="change_settings" value='<?= $this->lang->line('label_change_settings') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>General Settings</label>
                                                            <input type="text" name="general_settings" value='<?= $this->lang->line('label_general_settings') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Company Title</label>
                                                            <input type="text" name="company_title" value='<?= $this->lang->line('label_company_title') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Full Logo</label>
                                                            <input type="text" name="full_logo" value='<?= $this->lang->line('label_full_logo') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Half Logo</label>
                                                            <input type="text" name="half_logo" value='<?= $this->lang->line('label_half_logo') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Favicon</label>
                                                            <input type="text" name="favicon" value='<?= $this->lang->line('label_favicon') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Failed</label>
                                                            <input type="text" name="failed" value='<?= $this->lang->line('label_failed') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Chat Theme</label>
                                                            <input type="text" name="chat_theme" value='<?= $this->lang->line('label_chat_theme') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Google Analytics Code</label>
                                                            <input type="text" name="google_analytics_code" value='<?= $this->lang->line('label_google_analytics_code') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Save Changes</label>
                                                            <input type="text" name="save_changes" value='<?= $this->lang->line('label_save_changes') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Email Settings</label>
                                                            <input type="text" name="email_settings" value='<?= $this->lang->line('label_email_settings') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>SMTP Host</label>
                                                            <input type="text" name="smtp_host" value='<?= $this->lang->line('label_smtp_host') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>SMTP Port</label>
                                                            <input type="text" name="smtp_port" value='<?= $this->lang->line('label_smtp_port') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Email Content Type</label>
                                                            <input type="text" name="email_content_type" value='<?= $this->lang->line('label_email_content_type') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>SMTP Encryption</label>
                                                            <input type="text" name="smtp_encryption" value='<?= $this->lang->line('label_smtp_encryption') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Web API Key</label>
                                                            <input type="text" name="web_api_key" value='<?= $this->lang->line('label_web_api_key') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Project ID</label>
                                                            <input type="text" name="project_id" value='<?= $this->lang->line('label_project_id') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Sender ID</label>
                                                            <input type="text" name="sender_id" value='<?= $this->lang->line('label_sender_id') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Sent</label>
                                                            <input type="text" name="sent" value='<?= $this->lang->line('label_sent') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Create & Customize</label>
                                                            <input type="text" name="create_and_customize" value='<?= $this->lang->line('label_create_and_customize') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Hi</label>
                                                            <input type="text" name="hi" value='<?= $this->lang->line('label_hi') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Jump To</label>
                                                            <input type="text" name="jump_to" value='<?= $this->lang->line('label_jump_to') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Project Details</label>
                                                            <input type="text" name="project_details" value='<?= $this->lang->line('label_project_details') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Project Milestone</label>
                                                            <input type="text" name="project_milestone" value='<?= $this->lang->line('label_project_milestone') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Project File</label>
                                                            <input type="text" name="project_file" value='<?= $this->lang->line('label_project_file') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Task Status</label>
                                                            <input type="text" name="task_status" value='<?= $this->lang->line('label_task_status') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Create Date</label>
                                                            <input type="text" name="create_date" value='<?= $this->lang->line('label_create_date') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Make Team Member</label>
                                                            <input type="text" name="make_team_member" value='<?= $this->lang->line('label_make_team_member') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Select Admins</label>
                                                            <input type="text" name="select_admins" value='<?= $this->lang->line('label_select_admins') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Group Admins</label>
                                                            <input type="text" name="group_admins" value='<?= $this->lang->line('label_group_admins') ?>' class="form-control">
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <label>Search Result</label>
                                                            <input type="text" name="search_result" value='<?= $this->lang->line('label_search_result') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Choose File</label>
                                                            <input type="text" name="choose_file" value='<?= $this->lang->line('label_choose_file') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>System Settings</label>
                                                            <input type="text" name="system_settings" value='<?= $this->lang->line('label_system_settings') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Languages</label>
                                                            <input type="text" name="languages" value='<?= $this->lang->line('label_languages') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Create New</label>
                                                            <input type="text" name="create_new" value='<?= $this->lang->line('label_create_new') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Timezone</label>
                                                            <input type="text" name="timezone" value='<?= $this->lang->line('label_timezone') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Time Tracker</label>
                                                            <input type="text" name="time_tracker" value='<?= $this->lang->line('label_time_tracker') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Add Time Sheet</label>
                                                            <input type="text" name="add_time_sheet" value='<?= $this->lang->line('label_add_time_sheet') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Edit Time Sheet</label>
                                                            <input type="text" name="edit_time_sheet" value='<?= $this->lang->line('label_edit_time_sheet') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>App URL</label>
                                                            <input type="text" name="app_url" value='<?= $this->lang->line('label_app_url') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Date Created</label>
                                                            <input type="text" name="date_created" value='<?= $this->lang->line('label_date_created') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Ends On</label>
                                                            <input type="text" name="ends_on" value='<?= $this->lang->line('label_ends_on') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Starts On</label>
                                                            <input type="text" name="starts_on" value='<?= $this->lang->line('label_starts_on') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Slug</label>
                                                            <input type="text" name="slug" value='<?= $this->lang->line('label_slug') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Created by</label>
                                                            <input type="text" name="created_by" value='<?= $this->lang->line('label_created_by') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Workspace Id</label>
                                                            <input type="text" name="workspace_id" value='<?= $this->lang->line('label_workspace_id') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Announcement</label>
                                                            <input type="text" name="announcement" value='<?= $this->lang->line('label_announcement') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Announcement Details</label>
                                                            <input type="text" name="announcement_details" value='<?= $this->lang->line('label_announcement_details') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Create Announcement</label>
                                                            <input type="text" name="create_announcement" value='<?= $this->lang->line('label_create_announcement') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Pinned Announcement</label>
                                                            <input type="text" name="pinned_announcement" value='<?= $this->lang->line('label_pinned_announcement') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Unpinned Announcement</label>
                                                            <input type="text" name="unpinned_announcement" value='<?= $this->lang->line('label_unpinned_announcement') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Filter</label>
                                                            <input type="text" name="filter" value='<?= $this->lang->line('label_filter') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Mark All As Read</label>
                                                            <input type="text" name="mark_all_as_read" value='<?= $this->lang->line('label_mark_all_as_read') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Create Event</label>
                                                            <input type="text" name="create_event" value='<?= $this->lang->line('label_create_event') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>No Unread Notifications Found</label>
                                                            <input type="text" name="no_unread_notifications_found" value='<?= $this->lang->line('label_no_unread_notifications_found') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Edit Meeting</label>
                                                            <input type="text" name="edit_meeting" value='<?= $this->lang->line('label_edit_meeting') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>View All</label>
                                                            <input type="text" name="view_all" value='<?= $this->lang->line('label_view_all') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Notification Details</label>
                                                            <input type="text" name="notification_details" value='<?= $this->lang->line('label_notification_details') ?>' class="form-control">
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <label>Currency</label>
                                                            <input type="text" name="currency" value='<?= $this->lang->line('label_currency') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Currency Symbol</label>
                                                            <input type="text" name="currency_symbol" value='<?= $this->lang->line('label_currency_symbol') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Currency Shortcode</label>
                                                            <input type="text" name="currency_shortcode" value='<?= $this->lang->line('label_currency_shortcode') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Currency Full Form</label>
                                                            <input type="text" name="currency_full_form" value='<?= $this->lang->line('label_currency_full_form') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Edit Workspace</label>
                                                            <input type="text" name="edit_workspace" value='<?= $this->lang->line('label_edit_workspace') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Assigned Date</label>
                                                            <input type="text" name="assigned_date" value='<?= $this->lang->line('label_assigned_date') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Assigned</label>
                                                            <input type="text" name="assigned" value='<?= $this->lang->line('label_assigned') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Edit Lead</label>
                                                            <input type="text" name="edit_lead" value='<?= $this->lang->line('label_edit_lead') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Project Name</label>
                                                            <input type="text" name="project_name" value='<?= $this->lang->line('label_project_name') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Select Status</label>
                                                            <input type="text" name="select_status" value='<?= $this->lang->line('label_select_status') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Tasks Due Dates Between</label>
                                                            <input type="text" name="tasks_due_dates_between" value='<?= $this->lang->line('label_tasks_due_dates_between') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>ID</label>
                                                            <input type="text" name="id" value='<?= $this->lang->line('label_id') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Leave Requests</label>
                                                            <input type="text" name="leave_requests" value='<?= $this->lang->line('label_leave_requests') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Leaves</label>
                                                            <input type="text" name="leaves" value='<?= $this->lang->line('label_leaves') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Edit Leave</label>
                                                            <input type="text" name="edit_leave" value='<?= $this->lang->line('label_edit_leave') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Request Leave</label>
                                                            <input type="text" name="request_leave" value='<?= $this->lang->line('label_request_leave') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Rate</label>
                                                            <input type="text" name="rate" value='<?= $this->lang->line('label_rate') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Leave Duration</label>
                                                            <input type="text" name="leave_duration" value='<?= $this->lang->line('label_leave_duration') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Reason</label>
                                                            <input type="text" name="reason" value='<?= $this->lang->line('label_reason') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Action By</label>
                                                            <input type="text" name="action_by" value='<?= $this->lang->line('label_action_by') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Grid View</label>
                                                            <input type="text" name="grid_view" value='<?= $this->lang->line('label_grid_view') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>List View</label>
                                                            <input type="text" name="list_view" value='<?= $this->lang->line('label_list_view') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Role</label>
                                                            <input type="text" name="role" value='<?= $this->lang->line('label_role') ?>' class="form-control">
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <label>Hide Budget/Costs From Users?</label>
                                                            <input type="text" name="hide_budget_costs_from_users" value='<?= $this->lang->line('label_hide_budget_costs_from_users') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Version</label>
                                                            <input type="text" name="version" value='<?= $this->lang->line('label_version') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Leave Date From To</label>
                                                            <input type="text" name="leave_date_from_to" value='<?= $this->lang->line('label_leave_date_from_to') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>No. of Days</label>
                                                            <input type="text" name="no_of_days" value='<?= $this->lang->line('label_no_of_days') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Assign users to manage leave requests</label>
                                                            <input type="text" name="assign_users_to_manage_leave_requests" value='<?= $this->lang->line('label_assign_users_to_manage_leave_requests') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Approve</label>
                                                            <input type="text" name="approve" value='<?= $this->lang->line('label_approve') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Disapprove</label>
                                                            <input type="text" name="disapprove" value='<?= $this->lang->line('label_disapprove') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Approved</label>
                                                            <input type="text" name="approved" value='<?= $this->lang->line('label_approved') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Disapproved</label>
                                                            <input type="text" name="disapproved" value='<?= $this->lang->line('label_disapproved') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Under Review</label>
                                                            <input type="text" name="under_review" value='<?= $this->lang->line('label_under_review') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Reason for leave?</label>
                                                            <input type="text" name="reason_for_leave" value='<?= $this->lang->line('label_reason_for_leave') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Select Milestone</label>
                                                            <input type="text" name="select_milestone" value='<?= $this->lang->line('label_select_milestone') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Not Started</label>
                                                            <input type="text" name="notstarted" value='<?= $this->lang->line('label_notstarted') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Cancelled</label>
                                                            <input type="text" name="cancelled" value='<?= $this->lang->line('label_cancelled') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Clients</label>
                                                            <input type="text" name="clients" value='<?= $this->lang->line('label_clients') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Client</label>
                                                            <input type="text" name="client" value='<?= $this->lang->line('label_client') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Add Client</label>
                                                            <input type="text" name="add_client" value='<?= $this->lang->line('label_add_client') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Expense Types</label>
                                                            <input type="text" name="expense_types" value='<?= $this->lang->line('label_expense_types') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Select Clients</label>
                                                            <input type="text" name="select_clients" value='<?= $this->lang->line('label_select_clients') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Select Client</label>
                                                            <input type="text" name="select_client" value='<?= $this->lang->line('label_select_client') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Draft</label>
                                                            <input type="text" name="draft" value='<?= $this->lang->line('label_draft') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Create Lead</label>
                                                            <input type="text" name="create_lead" value='<?= $this->lang->line('label_create_lead') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Add Estimate</label>
                                                            <input type="text" name="add_estimate" value='<?= $this->lang->line('label_add_estimate') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Fully Paid</label>
                                                            <input type="text" name="fully_paid" value='<?= $this->lang->line('label_fully_paid') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Partially Paid</label>
                                                            <input type="text" name="partially_paid" value='<?= $this->lang->line('label_partially_paid') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Edit Invoice</label>
                                                            <input type="text" name="edit_invoice" value='<?= $this->lang->line('label_edit_invoice') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>View Invoice</label>
                                                            <input type="text" name="view_invoice" value='<?= $this->lang->line('label_view_invoice') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Add Invoice</label>
                                                            <input type="text" name="add_invoice" value='<?= $this->lang->line('label_add_invoice') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>System Fonts</label>
                                                            <input type="text" name="system_fonts" value='<?= $this->lang->line('label_system_fonts') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Manage</label>
                                                            <input type="text" name="manage" value='<?= $this->lang->line('label_manage') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Manage Workspaces</label>
                                                            <input type="text" name="manage_workspaces" value='<?= $this->lang->line('label_manage_workspaces') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Workspaces</label>
                                                            <input type="text" name="workspaces" value='<?= $this->lang->line('label_workspaces') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Company</label>
                                                            <input type="text" name="company" value='<?= $this->lang->line('label_company') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Phone</label>
                                                            <input type="text" name="phone" value='<?= $this->lang->line('label_phone') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Font Format</label>
                                                            <input type="text" name="font_format" value='<?= $this->lang->line('label_font_format') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Leads</label>
                                                            <input type="text" name="leads" value='<?= $this->lang->line('label_leads') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Email Templates</label>
                                                            <input type="text" name="email_templates" value='<?= $this->lang->line('label_email_templates') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Email Templates Type</label>
                                                            <input type="text" name="email_templates_type" value='<?= $this->lang->line('label_email_templates_type') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>User Group permissions</label>
                                                            <input type="text" name="user_group_permissions" value='<?= $this->lang->line('label_user_group_permissions') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Member group permissions</label>
                                                            <input type="text" name="member_group_permissions" value='<?= $this->lang->line('label_member_group_permissions') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Client group permissions</label>
                                                            <input type="text" name="client_group_permissions" value='<?= $this->lang->line('label_client_group_permissions') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Projects List</label>
                                                            <input type="text" name="projects_list" value='<?= $this->lang->line('label_projects_list') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Projects Calendar</label>
                                                            <input type="text" name="projects_calender" value='<?= $this->lang->line('label_projects_calender') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Finance</label>
                                                            <input type="text" name="finance" value='<?= $this->lang->line('label_finance') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Estimates</label>
                                                            <input type="text" name="estimates" value='<?= $this->lang->line('label_estimates') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Estimate date</label>
                                                            <input type="text" name="estimate_date" value='<?= $this->lang->line('label_estimate_date') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Estimate no</label>
                                                            <input type="text" name="estimate_no" value='<?= $this->lang->line('label_estimate_no') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Estimate</label>
                                                            <input type="text" name="estimate" value='<?= $this->lang->line('label_estimate') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Invoices</label>
                                                            <input type="text" name="invoices" value='<?= $this->lang->line('label_invoices') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Invoice Date</label>
                                                            <input type="text" name="invoice_date" value='<?= $this->lang->line('label_invoice_date') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Invoice Summary</label>
                                                            <input type="text" name="invoice_summary" value='<?= $this->lang->line('label_invoice_summary') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Payment Summary</label>
                                                            <input type="text" name="payment_summary" value='<?= $this->lang->line('label_payment_summary') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Payment Modes</label>
                                                            <input type="text" name="payment_modes" value='<?= $this->lang->line('label_payment_modes') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Add Payment</label>
                                                            <input type="text" name="add_payment" value='<?= $this->lang->line('label_add_payment') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Add Payment Mode</label>
                                                            <input type="text" name="add_payment_mode" value='<?= $this->lang->line('label_add_payment_mode') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Edit Payment Mode</label>
                                                            <input type="text" name="edit_payment_mode" value='<?= $this->lang->line('label_edit_payment_mode') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Edit Payment</label>
                                                            <input type="text" name="edit_payment" value='<?= $this->lang->line('label_edit_payment') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Invoice Id</label>
                                                            <input type="text" name="invoice_id" value='<?= $this->lang->line('label_invoice_id') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Invoice</label>
                                                            <input type="text" name="invoice" value='<?= $this->lang->line('label_invoice') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Invoice No</label>
                                                            <input type="text" name="invoice_no" value='<?= $this->lang->line('label_invoice_no') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Not Assigned</label>
                                                            <input type="text" name="not_assigned" value='<?= $this->lang->line('label_not_assigned') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Payment Mode Id</label>
                                                            <input type="text" name="payment_mode_id" value='<?= $this->lang->line('label_payment_mode_id') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Client Id</label>
                                                            <input type="text" name="client_id" value='<?= $this->lang->line('label_client_id') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Client name</label>
                                                            <input type="text" name="client_name" value='<?= $this->lang->line('label_client_name') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Amount</label>
                                                            <input type="text" name="amount" value='<?= $this->lang->line('label_amount') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>No Payment Found</label>
                                                            <input type="text" name="no_payment_found" value='<?= $this->lang->line('label_no_payment_found') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Add Expense Type</label>
                                                            <input type="text" name="add_expense_type" value='<?= $this->lang->line('label_add_expense_type') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Add Expense</label>
                                                            <input type="text" name="add_expense" value='<?= $this->lang->line('label_add_expense') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Edit Expense</label>
                                                            <input type="text" name="edit_expense" value='<?= $this->lang->line('label_edit_expense') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Edit Expense Type</label>
                                                            <input type="text" name="edit_expense_type" value='<?= $this->lang->line('label_edit_expense_type') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Choose</label>
                                                            <input type="text" name="choose" value='<?= $this->lang->line('label_choose') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Percentage</label>
                                                            <input type="text" name="percentage" value='<?= $this->lang->line('label_percentage') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Sub Total</label>
                                                            <input type="text" name="subtotal" value='<?= $this->lang->line('label_subtotal') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Select User</label>
                                                            <input type="text" name="select_user" value='<?= $this->lang->line('label_select_user') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Select Invoice</label>
                                                            <input type="text" name="select_invoice" value='<?= $this->lang->line('label_select_invoice') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Payment Mode</label>
                                                            <input type="text" name="payment_mode" value='<?= $this->lang->line('label_payment_mode') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Payment Date</label>
                                                            <input type="text" name="payment_date" value='<?= $this->lang->line('label_payment_date') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Subject</label>
                                                            <input type="text" name="subject" value='<?= $this->lang->line('label_subject') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Total</label>
                                                            <input type="text" name="total" value='<?= $this->lang->line('label_total') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Final Total</label>
                                                            <input type="text" name="final_total" value='<?= $this->lang->line('label_final_total') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Date sent</label>
                                                            <input type="text" name="date_sent" value='<?= $this->lang->line('label_date_sent') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>To</label>
                                                            <input type="text" name="to" value='<?= $this->lang->line('label_to') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Total Tax</label>
                                                            <input type="text" name="total_tax" value='<?= $this->lang->line('label_total_tax') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Personal Note</label>
                                                            <input type="text" name="personal_note" value='<?= $this->lang->line('label_personal_note') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Add new type</label>
                                                            <input type="text" name="add_new_type" value='<?= $this->lang->line('label_add_new_type') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>User Id</label>
                                                            <input type="text" name="user_id" value='<?= $this->lang->line('label_user_id') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Expenses</label>
                                                            <input type="text" name="expenses" value='<?= $this->lang->line('label_expenses') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Items</label>
                                                            <input type="text" name="items" value='<?= $this->lang->line('label_items') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Edit Item</label>
                                                            <input type="text" name="edit_item" value='<?= $this->lang->line('label_edit_item') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Payments</label>
                                                            <input type="text" name="payments" value='<?= $this->lang->line('label_payments') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Taxes</label>
                                                            <input type="text" name="taxes" value='<?= $this->lang->line('label_taxes') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Tax</label>
                                                            <input type="text" name="tax" value='<?= $this->lang->line('label_tax') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Units</label>
                                                            <input type="text" name="units" value='<?= $this->lang->line('label_units') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Unit</label>
                                                            <input type="text" name="unit" value='<?= $this->lang->line('label_unit') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Unit Id</label>
                                                            <input type="text" name="unit_id" value='<?= $this->lang->line('label_unit_id') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Most Recently Update</label>
                                                            <input type="text" name="most_recently_update" value='<?= $this->lang->line('label_most_recently_update') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Least Recently Update</label>
                                                            <input type="text" name="least_recently_update" value='<?= $this->lang->line('label_least_recently_update') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Newest First</label>
                                                            <input type="text" name="newest_first" value='<?= $this->lang->line('label_newest_first') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Oldest First</label>
                                                            <input type="text" name="oldest_first" value='<?= $this->lang->line('label_oldest_first') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Meetings</label>
                                                            <input type="text" name="meetings" value='<?= $this->lang->line('label_meetings') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Create Meeting</label>
                                                            <input type="text" name="create_meeting" value='<?= $this->lang->line('label_create_meeting') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Save as Draft</label>
                                                            <input type="text" name="save_as_draft" value='<?= $this->lang->line('label_save_as_draft') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Drop files here and click button below to proceed</label>
                                                            <input type="text" name="drop_files_here_and_click_button_below_to_proceed" value='<?= $this->lang->line('label_drop_files_here_and_click_button_below_to_proceed') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Leads due dates between</label>
                                                            <input type="text" name="leads_due_dates_between" value='<?= $this->lang->line('label_leads_due_dates_between') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>New</label>
                                                            <input type="text" name="new" value='<?= $this->lang->line('label_new') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Qualified</label>
                                                            <input type="text" name="qualified" value='<?= $this->lang->line('label_qualified') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Discussion</label>
                                                            <input type="text" name="discussion" value='<?= $this->lang->line('label_discussion') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Won</label>
                                                            <input type="text" name="won" value='<?= $this->lang->line('label_won') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Lost</label>
                                                            <input type="text" name="lost" value='<?= $this->lang->line('label_lost') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Created</label>
                                                            <input type="text" name="created" value='<?= $this->lang->line('label_created') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Updated</label>
                                                            <input type="text" name="updated" value='<?= $this->lang->line('label_updated') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Deleted</label>
                                                            <input type="text" name="deleted" value='<?= $this->lang->line('label_deleted') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Uploaded</label>
                                                            <input type="text" name="uploaded" value='<?= $this->lang->line('label_uploaded') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Add Language</label>
                                                            <input type="text" name="add_language" value='<?= $this->lang->line('label_add_language') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Language Name</label>
                                                            <input type="text" name="language_name" value='<?= $this->lang->line('label_language_name') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Language Code</label>
                                                            <input type="text" name="language_code" value='<?= $this->lang->line('label_language_code') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Search Message</label>
                                                            <input type="text" name="search_msg" value='<?= $this->lang->line('label_search_msg') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Create Contracts</label>
                                                            <input type="text" name="create_contracts" value='<?= $this->lang->line('label_create_contracts') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Contracts Type</label>
                                                            <input type="text" name="contracts_type" value='<?= $this->lang->line('label_contract_type') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Value</label>
                                                            <input type="text" name="value" value='<?= $this->lang->line('label_value') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Signed Status</label>
                                                            <input type="text" name="signed_status" value='<?= $this->lang->line('label_signed_status') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Service Provider</label>
                                                            <input type="text" name="service_provider" value='<?= $this->lang->line('label_service_provider') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Prepared By</label>
                                                            <input type="text" name="prepared_by" value='<?= $this->lang->line('label_prepared_by') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Contracts</label>
                                                            <input type="text" name="Delete signature" value='<?= $this->lang->line('label_delete_signature') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Create Signature</label>
                                                            <input type="text" name="create_signature" value='<?= $this->lang->line('label_create_signature') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Contract Id</label>
                                                            <input type="text" name="contract_id" value='<?= $this->lang->line('label_contract_id') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Unsigned</label>
                                                            <input type="text" name="unsigned" value='<?= $this->lang->line('label_unsigned') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Signature</label>
                                                            <input type="text" name="signature" value='<?= $this->lang->line('label_signature') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Contracts Signature</label>
                                                            <input type="text" name="contracts_sign" value='<?= $this->lang->line('label_contracts_sign') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Contracts</label>
                                                            <input type="text" name="contracts" value='<?= $this->lang->line('label_contracts') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Add Contracts Type</label>
                                                            <input type="text" name="add_contracts_type" value='<?= $this->lang->line('label_add_contracts_type') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Knowledgebase</label>
                                                            <input type="text" name="knowledgebase" value='<?= $this->lang->line('label_knowledgebase') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Article Groups</label>
                                                            <input type="text" name="article_groups" value='<?= $this->lang->line('label_article_groups') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Article</label>
                                                            <input type="text" name="article" value='<?= $this->lang->line('label_article') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Add Article</label>
                                                            <input type="text" name="add_article" value='<?= $this->lang->line('label_add_article') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Select Group</label>
                                                            <input type="text" name="select_group" value='<?= $this->lang->line('label_select_group') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Add Group</label>
                                                            <input type="text" name="add_group" value='<?= $this->lang->line('label_add_group') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Article Description</label>
                                                            <input type="text" name="article_description" value='<?= $this->lang->line('label_article_description') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Add Article Group</label>
                                                            <input type="text" name="add_article_group" value='<?= $this->lang->line('label_add_article_group') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Edit Article Group</label>
                                                            <input type="text" name="edit_article_group" value='<?= $this->lang->line('label_edit_article_group') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Search Knowledgebase Article</label>
                                                            <input type="text" name="search_knowledgebase_article" value='<?= $this->lang->line('label_search_knowledgebase_article') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Search Article</label>
                                                            <input type="text" name="search_article" value='<?= $this->lang->line('label_search_article') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Payslip</label>
                                                            <input type="text" name="payslip" value='<?= $this->lang->line('label_payslip') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Create Payslip</label>
                                                            <input type="text" name="add_payslip" value='<?= $this->lang->line('label_add_payslip') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Payslip Month</label>
                                                            <input type="text" name="payslip_month" value='<?= $this->lang->line('label_payslip_month') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Working Days</label>
                                                            <input type="text" name="working_days" value='<?= $this->lang->line('label_working_days') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Loss of pay Days</label>
                                                            <input type="text" name="loss_of_pay_days" value='<?= $this->lang->line('label_loss_of_pay_days') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Paid Days</label>
                                                            <input type="text" name="paid_days" value='<?= $this->lang->line('label_paid_days') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Basic Salary</label>
                                                            <input type="text" name="basic_salary" value='<?= $this->lang->line('label_basic_salary') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Leave Deduction</label>
                                                            <input type="text" name="leave_deduction" value='<?= $this->lang->line('label_leave_deduction') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Over time hours</label>
                                                            <input type="text" name="label_over_time_hours" value='<?= $this->lang->line('label_over_time_hours') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Over Time rate</label>
                                                            <input type="text" name="over_time_rate" value='<?= $this->lang->line('label_over_time_rate') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Over time payment</label>
                                                            <input type="text" name="over_time_payment" value='<?= $this->lang->line('label_over_time_payment') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Bonus</label>
                                                            <input type="text" name="bonus" value='<?= $this->lang->line('label_bonus') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Paid</label>
                                                            <input type="text" name="paid" value='<?= $this->lang->line('label_paid') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Unaid</label>
                                                            <input type="text" name="unpaid" value='<?= $this->lang->line('label_unpaid') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Incentives</label>
                                                            <input type="text" name="incentives" value='<?= $this->lang->line('label_incentives') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Add Allowance</label>
                                                            <input type="text" name="add_allowance" value='<?= $this->lang->line('label_add_allowance') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Allowance</label>
                                                            <input type="text" name="allowance" value='<?= $this->lang->line('label_allowance') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Choose Deduction</label>
                                                            <input type="text" name="choose_deduction" value='<?= $this->lang->line('label_choose_deduction') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Deduction</label>
                                                            <input type="text" name="deduction" value='<?= $this->lang->line('label_deduction') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Add Deduction</label>
                                                            <input type="text" name="add_deduction" value='<?= $this->lang->line('label_add_deduction') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Deduction Type</label>
                                                            <input type="text" name="deduction_type" value='<?= $this->lang->line('label_deduction_type') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Total Allowance</label>
                                                            <input type="text" name="total_allowance" value='<?= $this->lang->line('label_total_allowance') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Total Deduction</label>
                                                            <input type="text" name="total_deduction" value='<?= $this->lang->line('label_total_deduction') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Total Earnings</label>
                                                            <input type="text" name="total_earnings" value='<?= $this->lang->line('label_total_earnings') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Net Pay</label>
                                                            <input type="text" name="net_pay" value='<?= $this->lang->line('label_net_pay') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Edit payslip</label>
                                                            <input type="text" name="edit_payslip" value='<?= $this->lang->line('label_edit_payslip') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Date of Birth</label>
                                                            <input type="text" name="date_of_birth" value='<?= $this->lang->line('label_date_of_birth') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Date of Joining</label>
                                                            <input type="text" name="date_of_joining" value='<?= $this->lang->line('label_date_of_joining') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Gender</label>
                                                            <input type="text" name="gender" value='<?= $this->lang->line('label_gender') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Male</label>
                                                            <input type="text" name="male" value='<?= $this->lang->line('label_male') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Female</label>
                                                            <input type="text" name="female" value='<?= $this->lang->line('label_female') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Designation</label>
                                                            <input type="text" name="designation" value='<?= $this->lang->line('label_designation') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Upcoming Birthdays</label>
                                                            <input type="text" name="upcoming_birthdays" value='<?= $this->lang->line('label_upcoming_birthdays') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Upcoming Work Anniversaries</label>
                                                            <input type="text" name="upcoming_work_anniversaries" value='<?= $this->lang->line('label_upcoming_work_anniversaries') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Work Anniversaries</label>
                                                            <input type="text" name="work_anniversaries" value='<?= $this->lang->line('label_work_anniversaries') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Happy birthday</label>
                                                            <input type="text" name="happy_birthday" value='<?= $this->lang->line('label_happy_birthday') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Today birthday</label>
                                                            <input type="text" name="today_birthday" value='<?= $this->lang->line('label_today_birthday') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Members on Leave</label>
                                                            <input type="text" name="members_on_leave" value='<?= $this->lang->line('label_members_on_leave') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Start Time</label>
                                                            <input type="text" name="start_time" value='<?= $this->lang->line('label_start_time') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>End Time</label>
                                                            <input type="text" name="end_time" value='<?= $this->lang->line('label_end_time') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>No Todos created</label>
                                                            <input type="text" name="no_todo_list" value='<?= $this->lang->line('label_no_todo_list') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Created Date</label>
                                                            <input type="text" name="created_date" value='<?= $this->lang->line('label_created_date') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Creates Meeting Notes</label>
                                                            <input type="text" name="creates_meeting_notes" value='<?= $this->lang->line('label_creates_meeting_notes') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Days till DeadLine</label>
                                                            <input type="text" name="days_till_deadLine" value='<?= $this->lang->line('label_days_till_deadLine') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Day(s) deadline missed</label>
                                                            <input type="text" name="days_deadline_missed" value='<?= $this->lang->line('label_days_deadline_missed') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Day(s) left</label>
                                                            <input type="text" name="days_left" value='<?= $this->lang->line('label_days_left') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Day(s) left</label>
                                                            <input type="text" name="days_left" value='<?= $this->lang->line('label_days_left') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Make Super Admin</label>
                                                            <input type="text" name="make_super_admin" value='<?= $this->lang->line('label_make_super_admin') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>No files uploaded yet!</label>
                                                            <input type="text" name="no_files_uploaded" value='<?= $this->lang->line('label_no_files_uploaded') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Select Activity</label>
                                                            <input type="text" name="select_activity" value='<?= $this->lang->line('label_select_activity') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Select Type</label>
                                                            <input type="text" name="select_type" value='<?= $this->lang->line('label_select_type') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Add Todos List</label>
                                                            <input type="text" name="add_todos" value='<?= $this->lang->line('label_add_todos') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Article Due Dates Between</label>
                                                            <input type="text" name="article_due_dates_between" value='<?= $this->lang->line('label_article_due_dates_between') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Group Name</label>
                                                            <input type="text" name="group_name" value='<?= $this->lang->line('label_group_name') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Showing</label>
                                                            <input type="text" name="showing" value='<?= $this->lang->line('label_showing') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>From</label>
                                                            <input type="text" name="from" value='<?= $this->lang->line('label_from') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Remaining Days</label>
                                                            <input type="text" name="remaining_days" value='<?= $this->lang->line('label_remaining_days') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>No priority selected</label>
                                                            <input type="text" name="no_priority" value='<?= $this->lang->line('label_no_priority') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Missed sinces</label>
                                                            <input type="text" name="missed_sinces" value='<?= $this->lang->line('label_missed_sinces') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Add Task</label>
                                                            <input type="text" name="add_task" value='<?= $this->lang->line('label_add_task') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Select Project</label>
                                                            <input type="text" name="select_project" value='<?= $this->lang->line('label_select_project') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Add Event</label>
                                                            <input type="text" name="add_event" value='<?= $this->lang->line('label_add_event') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Activity</label>
                                                            <input type="text" name="activity" value='<?= $this->lang->line('label_activity') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>User Type</label>
                                                            <input type="text" name="user_type" value='<?= $this->lang->line('label_user_type') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Time Tracker Dates Between Range</label>
                                                            <input type="text" name="time_tracker_dates_between" value='<?= $this->lang->line('label_time_tracker_dates_between') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>No Members on Leave</label>
                                                            <input type="text" name="no_members_on_leave" value='<?= $this->lang->line('label_no_members_on_leave') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Report</label>
                                                            <input type="text" name="report" value='<?= $this->lang->line('label_report') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Contracts Report</label>
                                                            <input type="text" name="contracts_report" value='<?= $this->lang->line('label_contracts_report') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Invoices Report</label>
                                                            <input type="text" name="invoices_report" value='<?= $this->lang->line('label_invoices_report') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Leads Report</label>
                                                            <input type="text" name="leads_report" value='<?= $this->lang->line('label_leads_report') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Projects Report</label>
                                                            <input type="text" name="projects_report" value='<?= $this->lang->line('label_projects_report') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Tasks Report</label>
                                                            <input type="text" name="tasks_report" value='<?= $this->lang->line('label_tasks_report') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Income Invoices</label>
                                                            <input type="text" name="income_invoices" value='<?= $this->lang->line('label_income_invoices') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Leads Assigned in Month Year</label>
                                                            <input type="text" name="lead_assigned_month_year" value='<?= $this->lang->line('label_lead_assigned_month_year') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Lead start month</label>
                                                            <input type="text" name="leads_start_month" value='<?= $this->lang->line('label_leads_start_month') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Projects Status</label>
                                                            <input type="text" name="projects_status" value='<?= $this->lang->line('label_projects_status') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Projects Priority</label>
                                                            <input type="text" name="projects_priority" value='<?= $this->lang->line('label_projects_priority') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Projects Started in Month Year</label>
                                                            <input type="text" name="project_start_month_year" value='<?= $this->lang->line('label_project_start_month_year') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Projects Ended in Month Year</label>
                                                            <input type="text" name="_project_end_month_year" value='<?= $this->lang->line('label_project_end_month_year') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Tasks Started in month Year</label>
                                                            <input type="text" name="task_start_month_year" value='<?= $this->lang->line('label_task_start_month_year') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Tasks Ended in month Year</label>
                                                            <input type="text" name="task_end_month_year" value='<?= $this->lang->line('label_task_end_month_year') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Income invoices</label>
                                                            <input type="text" name="income_invoices" value='<?= $this->lang->line('label_income_invoices') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Choose Invoice Status</label>
                                                            <input type="text" name="choose_invoice_status" value='<?= $this->lang->line('label_choose_invoice_status') ?>' class="form-control">
                                                        </div>
                                                        <!-- <div class="form-group col-md-6">
                                                            <label>Screenshot</label>
                                                            <input type="text" name="screenshot" value='<?= $this->lang->line('label_screenshot') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Start Screenshot</label>
                                                            <input type="text" name="start_screenshot" value='<?= $this->lang->line('label_start_screenshot') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Stop Screenshot</label>
                                                            <input type="text" name="stop_screenshot" value='<?= $this->lang->line('label_stop_screenshot') ?>' class="form-control">
                                                        </div> -->
                                                        <div class="form-group col-md-6">
                                                            <label>Type</label>
                                                            <input type="text" name="type" value='<?= $this->lang->line('label_type') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Select</label>
                                                            <input type="text" name="select" value='<?= $this->lang->line('label_select') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Bulk Upload sample file</label>
                                                            <input type="text" name="bulk_upload_sample_file" value='<?= $this->lang->line('label_bulk_upload_sample_file') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Bulk Update sample file</label>
                                                            <input type="text" name="bulk_update_sample_file" value='<?= $this->lang->line('label_bulk_update_sample_file') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Bulk Upload Instructions</label>
                                                            <input type="text" name="bulk_upload_instructions" value='<?= $this->lang->line('label_bulk_upload_instructions') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Bulk Update Instructions</label>
                                                            <input type="text" name="bulk_update_instructions" value='<?= $this->lang->line('label_bulk_update_instructions') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Bulk Download Data</label>
                                                            <input type="text" name="bulk_download_data" value='<?= $this->lang->line('label_bulk_download_data') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>No Member on leave</label>
                                                            <input type="text" name="no_member_on_leave" value='<?= $this->lang->line('label_no_member_on_leave') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>The Day After Tomorrow</label>
                                                            <input type="text" name="day_after_tomorrow" value='<?= $this->lang->line('label_day_after_tomorrow') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Tomorrow</label>
                                                            <input type="text" name="tomorrow" value='<?= $this->lang->line('label_tomorrow') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Today</label>
                                                            <input type="text" name="today" value='<?= $this->lang->line('label_today') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>No Upcoming Work Anniversaries to display.</label>
                                                            <input type="text" name="no_upcoming_work_anniversaries" value='<?= $this->lang->line('label_no_upcoming_work_anniversaries') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>No Upcoming Birthdays to display.</label>
                                                            <input type="text" name="no_upcoming_birthdays" value='<?= $this->lang->line('label_no_upcoming_birthdays') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Favorite Projects</label>
                                                            <input type="text" name="favorite_projects" value='<?= $this->lang->line('label_favorite_projects') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Calender View</label>
                                                            <input type="text" name="calender_view" value='<?= $this->lang->line('label_calender_view') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Gantt chart</label>
                                                            <input type="text" name="gantt_chart" value='<?= $this->lang->line('label_gantt_chart') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Select Priority</label>
                                                            <input type="text" name="select_priority" value='<?= $this->lang->line('label_select_priority') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Low Priority</label>
                                                            <input type="text" name="low_priority" value='<?= $this->lang->line('label_low_priority') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Medium Priority</label>
                                                            <input type="text" name="medium_priority" value='<?= $this->lang->line('label_medium_priority') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>High Priority</label>
                                                            <input type="text" name="high_priority" value='<?= $this->lang->line('label_high_priority') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Username</label>
                                                            <input type="text" name="username" value='<?= $this->lang->line('label_username') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Gross Salary</label>
                                                            <input type="text" name="gross_salary" value='<?= $this->lang->line('label_gross_salary') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Lop Days</label>
                                                            <input type="text" name="lop_days" value='<?= $this->lang->line('label_lop_days') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Earnings</label>
                                                            <input type="text" name="earnings" value='<?= $this->lang->line('label_earnings') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Total Deductions</label>
                                                            <input type="text" name="total_deductions" value='<?= $this->lang->line('label_total_deductions') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Other Earnings</label>
                                                            <input type="text" name="other_earnings" value='<?= $this->lang->line('label_other_earnings') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Copyright</label>
                                                            <input type="text" name="copyright" value='<?= $this->lang->line('label_copyright') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Design & Developed By</label>
                                                            <input type="text" name="design_developed_by" value='<?= $this->lang->line('label_design_developed_by') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Payslip Sign</label>
                                                            <input type="text" name="payslip_sign" value='<?= $this->lang->line('label_payslip_sign') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Today's Total Time</label>
                                                            <input type="text" name="todays_total_time" value='<?= $this->lang->line('label_todays_total_time') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Week's Total Time</label>
                                                            <input type="text" name="weeks_total_time" value='<?= $this->lang->line('label_weeks_total_time') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Month's Total Time</label>
                                                            <input type="text" name="months_total_time" value='<?= $this->lang->line('label_months_total_time') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Year's Total Time</label>
                                                            <input type="text" name="years_total_time" value='<?= $this->lang->line('label_years_total_time') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Home</label>
                                                            <input type="text" name="home" value='<?= $this->lang->line('label_home') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Basic</label>
                                                            <input type="text" name="basic" value='<?= $this->lang->line('label_basic') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Forgot Password</label>
                                                            <input type="text" name="forgot_password" value='<?= $this->lang->line('label_forgot_password') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Confirm New Password</label>
                                                            <input type="text" name="confirm_new_password" value='<?= $this->lang->line('label_confirm_new_password') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>No Project Found!</label>
                                                            <input type="text" name="no_project_found" value='<?= $this->lang->line('label_no_project_found') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>No Notes Found!</label>
                                                            <input type="text" name="no_notes_found" value='<?= $this->lang->line('label_no_notes_found') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Upcoming</label>
                                                            <input type="text" name="upcoming" value='<?= $this->lang->line('label_upcoming') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Other</label>
                                                            <input type="text" name="other" value='<?= $this->lang->line('label_other') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Print</label>
                                                            <input type="text" name="print" value='<?= $this->lang->line('label_print') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Change Password</label>
                                                            <input type="text" name="change_password" value='<?= $this->lang->line('label_change_password') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Enter new password.</label>
                                                            <input type="text" name="enter_new_password" value='<?= $this->lang->line('label_enter_new_password') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Auto Update System</label>
                                                            <input type="text" name="auto_update_system" value='<?= $this->lang->line('label_auto_update_system') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Updater</label>
                                                            <input type="text" name="updater" value='<?= $this->lang->line('label_updater') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Updater</label>
                                                            <input type="text" name="updater_note" value='<?= $this->lang->line('label_updater_note') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>From</label>
                                                            <input type="text" name="from" value='<?= $this->lang->line('label_from') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Update Now</label>
                                                            <input type="text" name="update_now" value='<?= $this->lang->line('label_update_now') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Go to Settings</label>
                                                            <input type="text" name="go_to_settings" value='<?= $this->lang->line('label_go_to_settings') ?>' class="form-control">
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <label>Join group</label>
                                                            <input type="text" name="join_group" value='<?= $this->lang->line('label_join_group') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Module/Permissions</label>
                                                            <input type="text" name="module_permissions" value='<?= $this->lang->line('label_module_permissions') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label>This is an Automatic Updater helps you update your App From</label>
                                                            <input type="text" name="automatic_updater" value='<?= $this->lang->line('label_automatic_updater') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label>Upload update.zip and click update now it will automatically update your system...</label>
                                                            <input type="text" name="automatic_unzip" value='<?= $this->lang->line('label_automatic_unzip') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label>Payment slip for the month of</label>
                                                            <input type="text" name="payslip_slip_for_the_month_of" value='<?= $this->lang->line('label_payslip_slip_for_the_month_of') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label>Read and follow instructions carefully while preparing data</label>
                                                            <input type="text" name="read_and_follow_instructions_carefully_while_preparing_data" value='<?= $this->lang->line('label_read_and_follow_instructions_carefully_while_preparing_data') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label>Download and save the sample file to reduce errors</label>
                                                            <input type="text" name="download_and_save_the_sample_file_to_reduce_errors" value='<?= $this->lang->line('label_download_and_save_the_sample_file_to_reduce_errors') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label>For adding bulk products file should be .csv format</label>
                                                            <input type="text" name="for_adding_bulk_products_file_should_be_csv_format" value='<?= $this->lang->line('label_for_adding_bulk_products_file_should_be_csv_format') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label>Make sure you entered valid data as per instructions before proceed</label>
                                                            <input type="text" name="make_sure_you_entered_valid_data_as_per_instructions_before_proceed" value='<?= $this->lang->line('label_make_sure_you_entered_valid_data_as_per_instructions_before_proceed') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label>To use chat system you have to setup web FCM settings first.</label>
                                                            <input type="text" name="to_use_chat_system_you_have_to_setup_web_fcm_settings_first" value='<?= $this->lang->line('label_to_use_chat_system_you_have_to_setup_web_fcm_settings_first') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label>Please enable desktop notifications. We need your permission to </label>
                                                            <input type="text" name="please_enable_desktop_notifications_we_need_your_permission_to" value='<?= $this->lang->line('label_please_enable_desktop_notifications_we_need_your_permission_to') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label>enable desktop notifications (Click Here)</label>
                                                            <input type="text" name="enable_desktop_notifications_click_here" value='<?= $this->lang->line('label_enable_desktop_notifications_click_here') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label>You are not in this group</label>
                                                            <input type="text" name="you_are_not_in_this_group" value='<?= $this->lang->line('label_you_are_not_in_this_group') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label>Join group and access all the details.</label>
                                                            <input type="text" name="join_group_and_access_all_the_details" value='<?= $this->lang->line('label_join_group_and_access_all_the_details') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label>(If User Already Exits In Other Workspace Than Select Email)</label>
                                                            <input type="text" name="if_user_already_exits_in_other_workspace_than_select_email" value='<?= $this->lang->line('label_if_user_already_exits_in_other_workspace_than_select_email') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label>We will send a link to reset your password</label>
                                                            <input type="text" name="we_will_send_a_link_to_reset_your_password" value='<?= $this->lang->line('label_we_will_send_a_link_to_reset_your_password') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label>Admin always have all the permission. Here you can set permissions for members and clients.</label>
                                                            <input type="text" name="admin_always_have_all_the_permission_here_you_can_set_permissions_for_members_and_clients" value='<?= $this->lang->line('label_admin_always_have_all_the_permission_here_you_can_set_permissions_for_members_and_clients') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label>General settings such as, site title, site description, address and so on.</label>
                                                            <input type="text" name="general_settings_such_as_site_title_site_description_address_and_so_on" value='<?= $this->lang->line('label_general_settings_such_as_site_title_site_description_address_and_so_on') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label>Email SMTP settings, notifications and others related to email.</label>
                                                            <input type="text" name="email_smtp_settings_notifications_and_others_related_to_email" value='<?= $this->lang->line('label_email_smtp_settings_notifications_and_others_related_to_email') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label>FCM and other important settings</label>
                                                            <input type="text" name="fcm_and_other_important_settings" value='<?= $this->lang->line('label_fcm_and_other_important_settings') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label>General settings such as, company title, company logo and so on.</label>
                                                            <input type="text" name="general_settings_intro" value='<?= $this->lang->line('label_general_settings_intro') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label>The image must have a maximum size of 1MB</label>
                                                            <input type="text" name="image_size_intro" value='<?= $this->lang->line('label_image_size_intro') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label>Set below crone jobs on your cPanel.</label>
                                                            <input type="text" name="crone_jobs_cpanel" value='<?= $this->lang->line('label_crone_jobs_cpanel') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label>Set it for once in a day.</label>
                                                            <input type="text" name="set_once_day" value='<?= $this->lang->line('label_set_once_day') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label>FCM and other important settings.</label>
                                                            <input type="text" name="system_settings_intro" value='<?= $this->lang->line('label_system_settings_intro') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label>Email Settings intro</label>
                                                            <input type="text" name="email_settings_intro" value='<?= $this->lang->line('label_email_settings_intro') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label>Email Intro</label>
                                                            <input type="text" name="email_intro" value='<?= $this->lang->line('label_email_intro') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label>Password Intro</label>
                                                            <input type="text" name="password_intro" value='<?= $this->lang->line('label_password_intro') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label>Smtp Host Intro</label>
                                                            <input type="text" name="smtp_host_intro" value='<?= $this->lang->line('label_smtp_host_intro') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label>SMTP port Intro</label>
                                                            <input type="text" name="smtp_port_intro" value='<?= $this->lang->line('label_smtp_port_intro') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label>Text-plain or HTML content chooser.</label>
                                                            <input type="text" name="email_content_type_intro" value='<?= $this->lang->line('label_email_content_type_intro') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label>Smtp Encryption Intro</label>
                                                            <input type="text" name="smtp_encryption_intro" value='<?= $this->lang->line('label_smtp_encryption_intro') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label>FCM Server Key</label>
                                                            <input type="text" name="fcm_server_key" value='<?= $this->lang->line('label_fcm_server_key') ?>' class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>System Regsitration</label>
                                                            <input type="text" name="system_regsitration" value='<?= $this->lang->line('label_system_regsitrations') ?>' class="form-control">
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="card-footer bg-whitesmoke text-md-right">
                                                    <button class="btn btn-primary" id="languages-save-btn">Save Changes</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <?php $data = get_system_settings('email');
                                    $dataemail = $data;
                                    ?>
                                    <div class="tab-pane fade" id="email-settings" role="tabpanel" aria-labelledby="email-tab4">
                                        <form action="<?= base_url('settings/save_settings'); ?>" id="email-setting-form">
                                            <div class="card" id="email-settings-card">
                                                <div class="card-header">
                                                    <h4>Email Settings</h4>
                                                </div>
                                                <div class="card-body">
                                                    <p class="text-muted">Email SMTP settings, notifications and others related to email.</p>
                                                    <div class="form-group row align-items-center">
                                                        <label for="email" class="form-control-label col-sm-3 text-md-right">Email</label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <input type="hidden" name="setting_type" class="form-control" value="email">
                                                            <input type="text" name="email" class="form-control" id="email" value="<?= !empty($dataemail->email) ? $dataemail->email : '' ?>">
                                                            <div class="form-text text-muted">This is the email address that the contact and report emails will be sent to, aswell as being the from address in signup and notification emails.</div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row align-items-center">
                                                        <label for="password" class="form-control-label col-sm-3 text-md-right">Password</label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <input type="text" name="password" class="form-control" id="password" value="<?= !empty($dataemail->password) ? $dataemail->password : '' ?>">
                                                            <div class="form-text text-muted">Password of above given email.</div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row align-items-center">
                                                        <label for="smtp_host" class="form-control-label col-sm-3 text-md-right">SMTP Host</label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <input type="text" name="smtp_host" class="form-control" id="smtp_host" value="<?= !empty($dataemail->smtp_host) ? $dataemail->smtp_host : '' ?>">
                                                            <div class="form-text text-muted">This is the host address for your smtp server, this is only needed if you are using SMTP as the Email Send Type.</div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row align-items-center">
                                                        <label for="smtp_port" class="form-control-label col-sm-3 text-md-right">SMTP Hort</label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <input type="text" name="smtp_port" class="form-control" id="smtp_port" value="<?= !empty($dataemail->smtp_port) ? $dataemail->smtp_port : '' ?>">
                                                            <div class="form-text text-muted">SMTP port this will provide your service provider.</div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="form-control-label col-sm-3 mt-3 text-md-right">E-mail Content Type</label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <select class="form-control" name="mail_content_type" id="mail_content_type">
                                                                <?php
                                                                if (!empty($dataemail->mail_content_type)) {

                                                                    if ($dataemail->mail_content_type == 'text') { ?>
                                                                        <option value="text" selected>Text</option>
                                                                        <option value="html">HTML</option>
                                                                    <?php } else { ?>
                                                                        <option value="text">Text</option>
                                                                        <option value="html" selected>HTML</option>
                                                                    <?php }
                                                                } else { ?>
                                                                    <option value="text" selected>Text</option>
                                                                    <option value="html">HTML</option>
                                                                <?php } ?>
                                                            </select>
                                                            <div class="form-text text-muted">Text-plain or HTML content chooser.</div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="form-control-label col-sm-3 mt-3 text-md-right">SMTP Encryption</label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <select class="form-control" name="smtp_encryption" id="smtp_encryption">
                                                                <?php
                                                                if (!empty($dataemail->smtp_encryption)) {

                                                                    if ($dataemail->smtp_encryption == 'ssl') { ?>
                                                                        <option value="off">off</option>
                                                                        <option value="ssl" selected>SSL</option>
                                                                        <option value="tls">TLS</option>
                                                                    <?php } elseif ($dataemail->smtp_encryption == 'tls') { ?>
                                                                        <option value="off">off</option>
                                                                        <option value="ssl">SSL</option>
                                                                        <option value="tls" selected>TLS</option>
                                                                    <?php } else {  ?>
                                                                        <option value="off" selected>off</option>
                                                                        <option value="ssl">SSL</option>
                                                                        <option value="tls">TLS</option>
                                                                    <?php   }
                                                                } else { ?>
                                                                    <option value="off" selected>off</option>
                                                                    <option value="ssl">SSL</option>
                                                                    <option value="tls">TLS</option>
                                                                <?php } ?>
                                                            </select>
                                                            <div class="form-text text-muted">If your e-mail service provider supported secure connections, you can choose security method on list.</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer bg-whitesmoke text-md-right">
                                                    <button class="btn btn-primary" id="eamil-save-btn">Save Changes</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <?php $data = get_system_settings('web_fcm_settings');
                                    $datasystem = $data;
                                    ?>
                                    <div class="tab-pane fade" id="system-settings" role="tabpanel" aria-labelledby="system-tab4">
                                        <form action="<?= base_url('settings/save_settings'); ?>" id="system-setting-form">
                                            <div class="card" id="system-settings-card">
                                                <div class="card-header">
                                                    <h4>System Settings</h4>
                                                </div>
                                                <div class="card-body">
                                                    <p class="text-muted">FCM and other important settings.</p>
                                                    <div class="form-group row align-items-center">
                                                        <label for="apiKey" class="form-control-label col-sm-3 text-md-right">Web API Key</label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <input type="hidden" name="setting_type" class="form-control" value="system">
                                                            <input type="text" name="apiKey" class="form-control" id="apiKey" value="<?= !empty($datasystem->apiKey) ? $datasystem->apiKey : '' ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row align-items-center">
                                                        <label for="projectId" class="form-control-label col-sm-3 text-md-right">Project ID</label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <input type="text" name="projectId" class="form-control" id="projectId" value="<?= !empty($datasystem->projectId) ? $datasystem->projectId : '' ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row align-items-center">
                                                        <label for="messagingSenderId" class="form-control-label col-sm-3 text-md-right">Sender ID</label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <input type="text" name="messagingSenderId" class="form-control" id="messagingSenderId" value="<?= !empty($datasystem->messagingSenderId) ? $datasystem->messagingSenderId : '' ?>">
                                                        </div>
                                                    </div>

                                                    <div class="form-group row align-items-center">
                                                        <label for="appId" class="form-control-label col-sm-3 text-md-right">appId</label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <input type="text" name="appId" class="form-control" id="appId" value="<?= !empty($datasystem->appId) ? $datasystem->appId : '' ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer bg-whitesmoke text-md-right">
                                                    <button class="btn btn-primary" id="system-save-btn">Save Changes</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <?= form_open('languages/create', 'id="modal-add-language-part"', 'class="modal-part"'); ?>
            <div id="modal-title" class="d-none"><?= !empty($this->lang->line('label_add_language')) ? $this->lang->line('label_add_language') : 'Add Language'; ?></div>
            <div id="modal-footer-add-title" class="d-none"><?= !empty($this->lang->line('label_add')) ? $this->lang->line('label_add') : 'Add'; ?></div>
            <div class="form-group">
                <label><?= !empty($this->lang->line('label_language_name')) ? $this->lang->line('label_language_name') : 'Add'; ?></label>
                <div class="input-group">
                    <?= form_input(['name' => 'language', 'placeholder' => 'For Ex: English', 'class' => 'form-control']) ?>
                </div>
            </div>
            <div class="form-group">
                <label><?= !empty($this->lang->line('label_language_code')) ? $this->lang->line('label_language_code') : 'Add'; ?></label>
                <div class="input-group">
                    <?= form_input(['name' => 'code', 'placeholder' => 'For Ex: en', 'class' => 'form-control']) ?>
                </div>
            </div>
            <div class="form-check">
                <input class="form-check-input" name="is_rtl" type="checkbox" id="defaultCheck1">
                <label class="form-check-label" for="defaultCheck1">
                    Enable RTL
                </label>
            </div>
            </form>
            <?php include('include-footer.php'); ?>
        </div>
    </div>
    <?php include('include-js.php'); ?>
    <!-- Page Specific JS File -->
    <script src="<?= base_url('assets/js/page/features-setting-detail.js'); ?>"></script>
</body>

</html>