<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <?php require_once(APPPATH . 'views/include-css.php'); ?>
</head>

<body>


    <div id="floating_chat_view" class="my-0 h-100">

        <div class="section-header mb-1">
            <div>
                <h1 id='modal-edit-group'></h1>
            </div>

            <span id='modal-edit-group'></span>
            <span id='modal-info-group'></span>
        </div>
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
        <?php $data_chat = get_system_settings('web_fcm_settings');
        $chat_theme = get_chat_theme();

        $data_chat = $data_chat;
        if (empty($data_chat['messagingSenderId']) || empty($data_chat['projectId']) || empty($data_chat['apiKey'] || empty($data_chat['appId']))) { ?>
            <div class="row alert alert-danger justify-content-center"><?= !empty($this->lang->line('label_to_use_chat_system_you_have_to_setup_web_fcm_settings_first')) ? $this->lang->line('label_to_use_chat_system_you_have_to_setup_web_fcm_settings_first') : ' To use chat system you have to setup web FCM settings first.'; ?> <a href="<?= base_url('settings'); ?>"> <?= !empty($this->lang->line('label_go_to_settings')) ? $this->lang->line('label_go_to_settings') : 'Go to Settings'; ?></a> </div>
        <?php } ?>

        <div class="row alert alert-danger text-center d-none" id="noti_permission"><?= !empty($this->lang->line('label_please_enable_desktop_notifications_we_need_your_permission_to')) ? $this->lang->line('label_please_enable_desktop_notifications_we_need_your_permission_to') : 'Please enable desktop notifications. We need your permission to '; ?><a href="#" id="noti_permission_href"><b><?= !empty($this->lang->line('label_enable_desktop_notifications_click_here')) ? $this->lang->line('label_enable_desktop_notifications_click_here') : 'enable desktop notifications (Click Here)'; ?></b></a>.
        </div>


        <div class="h-100">

            <div class="col-lg-2 floating-chat-users" style="padding: 0px;">
                <div class="card chat-theme-light chat-scroll chat-min">
                    <select id="chat_user" name="select_user_id[]" class="chat_user form-control select2 mb-4" data-placeholder=" Type to search and select users" onload="multiselect()">
                        <option value="">Type to search and select user</option>
                        <?php foreach ($all_user as $all_users) {
                        ?>
                            <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                        <?php
                        } ?>
                    </select>
                    <div id="add-scroll-js ">
                        <div class="card-header chat-card-header text-color mt-4">
                            <h4>Personal Chat</h4>
                        </div>
                        <div class="chat-card-body">
                            <ul class="list-unstyled list-unstyled-border chat-list-unstyled-border">
                                <?php if (!empty($users)) {

                                    foreach ($users as $user) {
                                        if ($user['id'] == $_SESSION['user_id']) {
                                ?>
                                            <li class="media">
                                                <div class="media-body">
                                                    <div class="chat-person" data-picture="" data-type="person" data-id="<?= $user['id'] ?>"><i class="<?= ($user['is_online'] == 1) ? 'fa fa-circle text-success' : 'fa fa-circle'; ?> "></i> <?= $user['first_name'] ?> <?= $user['last_name'] ?> (You)</div>
                                                </div>
                                            </li>
                                    <?php }
                                    }
                                } else { ?>
                                    <p class="card-body p-0 px-5 text-muted">It seems there are no chats available at the moment</p>
                                <?php } ?>


                                <?php if (!empty($users)) {
                                    foreach ($users as $user) {
                                        if (isset($user['id']) && !empty($user['id']) && $user['id'] != '' && $user['id'] != $_SESSION['user_id']) { ?>
                                            <li class="media">
                                                <div class="media-body">
                                                    <div data-unread_msg="<?= $user['unread_msg'] ?>" class="chat-person <?= ($user['unread_msg'] > 0) ? 'new-msg-rcv' : ''; ?>" data-picture="<?= $user['picture'] ?>" data-type="person" data-id="<?= $user['id'] ?>"><i class="<?= ($user['is_online'] == 1) ? 'fa fa-circle text-success' : 'fa fa-circle'; ?> "></i> <?= $user['first_name'] ?> <?= $user['last_name'] ?>
                                                        <?= ($user['unread_msg'] > 0) ? (($user['unread_msg'] > 9) ? '<div class="badge-chat">9 +</div>' : '<div class="badge-chat">' . $user['unread_msg'] . '</div>') : ''; ?>
                                                    </div>
                                                </div>
                                            </li>
                                <?php }
                                    }
                                } ?>
                            </ul>
                        </div>

                        <div class="card-header chat-card-header text-color">

                            <h4><?= !empty($this->lang->line('label_group_chat')) ? $this->lang->line('label_group_chat') : 'Group Chat'; ?> <?php if (check_permissions("chat", "create")) { ?><a href="#" class="modal-add-group"><i class="fas fa-plus-circle"></i></a><?php } ?></h4>
                        </div>
                        <div class="chat-card-body">
                            <ul class="list-unstyled list-unstyled-border chat-list-unstyled-border">

                                <?php if (!empty($groups)) {

                                    foreach ($groups as $group) {
                                        // print_r($group);
                                ?>

                                        <li class="media">
                                            <div class="media-body">
                                                <div class="chat-person <?= ($group['is_read'] == 1) ? 'new-msg-rcv' : ''; ?>" data-id="<?= $group['group_id'] ?>" data-type="group" data-not_in_group="false"># <?= $group['title'] ?></div>
                                            </div>
                                        </li>

                                <?php }
                                } ?>

                                <?php if (!empty($groups) && $is_admin == true) {

                                    foreach ($not_in_groups as $not_in_group) {
                                        // print_r($not_in_group);
                                ?>

                                        <li class="media">
                                            <div class="media-body">
                                                <div class="chat-person" data-id="<?= $not_in_group['id'] ?>" data-type="group" data-not_in_group="true"># <?= $not_in_group['title'] ?></div>
                                            </div>
                                        </li>

                                <?php }
                                } ?>

                            </ul>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-8 col-lg-9" id="chat_area_wait">
            </div>

            <div class="col-lg-6 d-none floating-chat-area" style="padding: 0px;" id="chat_area">
                <div class="card chat-box chat-theme-light chat-min " id="mychatbox2">
                    <div class="align-items-center card-header chat-card-header d-flex">
                        <a href="#" class="btn button floating-chat-back-btn"><i class="fa fa-arrow-left"></i></a>
                        <div class="mr-3" id="chat-avtar-main">#</div>
                        <div class="media-body">
                            <div class="mt-0 mb-1 font-weight-bold text-color" id="chat_title"></div>
                            <div class="text-small font-600-bold" id="chat_online_status"></div>
                        </div>

                    </div>
                    <div id="chat-box-content" class="chat-bg card-body chat-scroll chat-content">
                        <div class="chat_loader">Loading...</div>
                    </div>
                    <div class="card-body d-none" id="chat-dropbox">
                        <div id='myAlbum' class="dropzone"></div>
                        <div class="text-center mt-3">
                            <button class="btn btn-danger shadow-none" onclick="closeDropZone();"><?= !empty($this->lang->line('label_close')) ? $this->lang->line('label_close') : 'Close'; ?>
                            </button>
                        </div>
                    </div>
                    <div class="form-control theme-inputs d-none" id="chat-input-textarea-result"></div>

                    <div class="card-footer chat-form">
                        <form id="chat-form2" autocomplete="off">
                            <input type="hidden" id="opposite_user_id" name="opposite_user_id" value="">
                            <input type="hidden" id="my_user_id" name="my_user_id" value="<?= $_SESSION['user_id'] ?>" data-picture="<?= isset($member['picture']) ? $member['picture'] : '' ?>">
                            <input type="hidden" id="chat_type" name="chat_type" value="">
                            <textarea class="form-control theme-inputs" id="chat-input-textarea" rows="1" name="chat-input-textarea"></textarea>
                            <a Class="chat-preview-btn d-none" id="chat-preview-btn"><?= !empty($this->lang->line('label_preview')) ? $this->lang->line('label_preview') : 'Preview'; ?></a>

                            <a class="bg-success go-to-bottom-btn">
                                <i class="fas fa-arrow-down"></i>
                            </a>

                            <button class="btn btn-danger">
                                <i class="far fa-paper-plane"></i>
                            </button>

                            <button class="btn-file btn btn-primary" onclick="showDropZone();">
                                <i class="fas fa-paperclip"></i>
                            </button>

                        </form>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
    </div>

    <form action="<?= base_url('chat/create_group'); ?>" class="modal-part modal-add-group-part">
        <div id="modal-title-group" class="d-none"><?= !empty($this->lang->line('label_create_group')) ? $this->lang->line('label_create_group') : 'Create Group'; ?></div>
        <div id="modal-footer-add-title" class="d-none"><?= !empty($this->lang->line('label_add')) ? $this->lang->line('label_add') : 'Add'; ?></div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label><?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?></label>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="title" name="title">
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label><?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?></label>
                    <div class="input-group">
                        <textarea type="textarea" class="form-control" placeholder="description" name="description"></textarea>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label><?= !empty($this->lang->line('label_select_users')) ? $this->lang->line('label_select_users') : 'Select Users'; ?></label>
                    <select class="form-control select2" multiple="" name="users[]" id="users">
                        <?php foreach ($all_user as $all_users) {
                            if ($all_users->id != $user->id && !is_client($all_users->id)) { ?>
                                <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                        <?php }
                        } ?>
                    </select>
                </div>
            </div>

        </div>
    </form>

    <form action="<?= base_url('chat/edit_group'); ?>" class="modal-part" id="modal-edit-group-part">
        <div id="modal-title-edit-group" class="d-none"><?= !empty($this->lang->line('label_edit_group')) ? $this->lang->line('label_edit_group') : 'Edit Group'; ?></div>
        <div id="modal-footer-edit-title" class="d-none"><?= !empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit'; ?></div>
        <div id="modal-footer-delete-title" class="d-none"><?= !empty($this->lang->line('label_delete')) ? $this->lang->line('label_delete') : 'Delete'; ?></div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label><?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?></label>
                    <div class="input-group">
                        <input type="hidden" name="update_id" id="update_id">
                        <input type="text" class="form-control" placeholder="title" name="title" id="update_title">
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label><?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?></label>
                    <div class="input-group">
                        <textarea type="textarea" class="form-control" placeholder="description" name="description" id="update_description"></textarea>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label><?= !empty($this->lang->line('label_select_users')) ? $this->lang->line('label_select_users') : 'Select Users'; ?></label>
                    <select class="form-control select2" multiple="" name="users[]" id="update_users">
                        <?php foreach ($all_user as $all_users) {
                            if ($all_users->id != $user->id && !is_client($all_users->id)) { ?>

                                <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                        <?php }
                        } ?>
                    </select>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label><?= !empty($this->lang->line('label_select_admins')) ? $this->lang->line('label_select_admins') : 'Select Admins'; ?> (Leave empty if don't want to make admin)</label>
                    <select class="form-control select2" multiple="" name="admins[]" id="update_admins">
                        <?php foreach ($all_user as $all_users) {
                            if ($all_users->id != $user->id && !is_client($all_users->id)) { ?>

                                <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                        <?php }
                        } ?>
                    </select>
                </div>
            </div>
        </div>
    </form>

    <form action="<?= base_url('chat/edit_group'); ?>" class="modal-part" id="modal-info-group-part">
        <div id="modal-title" class="d-none"><?= !empty($this->lang->line('label_edit_group')) ? $this->lang->line('label_edit_group') : 'Edit group'; ?></div>
        <div id="modal-footer-edit-title" class="d-none"><?= !empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit'; ?></div>
        <div id="modal-footer-delete-title" class="d-none"><?= !empty($this->lang->line('label_delete')) ? $this->lang->line('label_delete') : 'Delete'; ?></div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label><?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?></label>
                    <div class="input-group">
                        <input type="hidden" name="update_id" id="update_id_info">
                        <input type="text" class="form-control" placeholder="title" name="title" id="update_title_info" disabled>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label><?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?></label>
                    <div class="input-group">
                        <textarea type="textarea" class="form-control" placeholder="description" name="description" id="update_description_info" disabled></textarea>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label><?= !empty($this->lang->line('label_users')) ? $this->lang->line('label_users') : 'Users'; ?></label>
                    <select class="form-control select2" multiple="" name="users[]" id="update_users_info" disabled>
                        <?php foreach ($all_user as $all_users) {
                            if ($all_users->id != $user->id  && !is_client($all_users->id)) { ?>

                                <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                            <?php } else { ?>
                                <option value="<?= $all_users->id ?>">You</option>
                        <?php }
                        } ?>
                    </select>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label><?= !empty($this->lang->line('label_group_admins')) ? $this->lang->line('label_group_admins') : 'Group Admins'; ?></label>
                    <select class="form-control select2" multiple="" name="admins[]" id="update_admins_info" disabled>

                        <?php foreach ($all_user as $all_users) {
                            if ($all_users->id != $user->id  && !is_client($all_users->id)) { ?>

                                <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                            <?php } else { ?>
                                <option value="<?= $all_users->id ?>">You</option>
                        <?php }
                        } ?>

                    </select>
                </div>
            </div>

        </div>
    </form>

    <!--end row-->
    <script>
        base_url = "<?php echo base_url(); ?>";
        role = "<?= $this->session->userdata('role'); ?>";
        csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
            csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    </script>

    <?php include('include-js.php'); ?>

    <script type="text/javascript">
        dictDefaultMessage = "<?= !empty($this->lang->line('label_drop_files_here_to_upload')) ? $this->lang->line('label_drop_files_here_to_upload') : 'Drop Files Here To Upload'; ?>";
    </script>

    <script src="<?= base_url('assets/modules/ion.sound.min.js'); ?>"></script>

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/firebase-messaging-sw.js')
                .then(function(registration) {
                    console.log('Service Worker registered with scope:', registration.scope);
                })
                .catch(function(error) {
                    console.error('Service Worker registration failed:', error);
                });
        }
    </script>
    <script src="<?= base_url('assets/js/page/components-chat-box.js'); ?>"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js"></script>


    <!-- chat -->
</body>


</html>