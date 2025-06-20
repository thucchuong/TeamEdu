<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= !empty($this->lang->line('label_add_user')) ? $this->lang->line('label_add_user') : 'Add User'; ?> &mdash; <?= get_compnay_title(); ?></title>
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
                        <h1><?= $this->lang->line('label_users') ?></h1>
                        <div class="section-header-breadcrumb">
                            <?php if (check_permissions("users", "create")) { ?>
                                <i class="btn btn-primary btn-rounded no-shadow" id="modal-add-user"><?= !empty($this->lang->line('label_add_user')) ? $this->lang->line('label_add_user') : 'Add User'; ?></i>
                            <?php } ?>
                        </div>

                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class="modal-edit-user"></div>
                            <div class='col-md-12'>
                                <div class="card">
                                    <div class="card-body">

                                        <table class='table-striped' id='users_list' data-toggle="table" data-url="<?= base_url('users/get_users_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="first_name" data-sort-order="asc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-options='{
                      "fileName": "users-list",
                      "ignoreColumn": ["state"] 
                    }' data-query-params="queryParams">
                                            <thead>
                                                <tr>
                                                    <th data-field="id" data-visible="false" data-sortable="true"><?= !empty($this->lang->line('label_id')) ? $this->lang->line('label_id') : 'ID'; ?></th>

                                                    <th data-field="first_name" data-sortable="true"><?= !empty($this->lang->line('label_users')) ? $this->lang->line('label_users') : 'Users'; ?></th>

                                                    <th data-field="role" data-sortable="false"><?= !empty($this->lang->line('label_role')) ? $this->lang->line('label_role') : 'Role'; ?></th>
                                                    <th data-field="assigned" data-sortable="false"><?= !empty($this->lang->line('label_assigned')) ? $this->lang->line('label_assigned') : 'Assigned'; ?></th>
                                                    <?php //if ($this->ion_auth->is_admin()) { 
                                                    ?>
                                                    <th data-field="active" data-sortable="false"><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></th>
                                                    <?php //} 
                                                    ?>

                                                    <?php //if ($this->ion_auth->is_admin()) { 
                                                    ?>
                                                    <th data-field="action" data-sortable="false"><?= !empty($this->lang->line('label_action')) ? $this->lang->line('label_action') : 'Action'; ?></th>
                                                    <?php //} 
                                                    ?>

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

            <?php
            $user_permissions = $client_permissions_data = "";

            $actions = ['create', 'read', 'update', 'delete'];
            $total_actions = count($actions);

            // /* reading member's permissions from database */
            $user_permissions = (!empty($modules[0]['member_permissions'])) ? json_decode($modules[0]['member_permissions'], 1) : [];
            $client_permissions_data = (!empty($modules[0]['client_permissions'])) ? json_decode($modules[0]['client_permissions'], 1) : [];

            ?>

            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">
                                <?= !empty($this->lang->line('label_user_specific_permissions')) ? $this->lang->line('label_user_specific_permissions') : 'User specific Permissions'; ?>
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="<?= base_url('users/set_user_permission') ?>" id="user_permission_module">

                                <input type="number" id="id" name="id" hidden readonly>
                                <div class="row">
                                    <div class="col">
                                        <table class="table table-light">
                                            <thead>
                                                <tr>
                                                    <th scope="col">
                                                        <?= !empty($this->lang->line('label_module_permissions')) ? $this->lang->line('label_module_permissions') : 'Module/Permissions'; ?></th>
                                                    <th scope="col"><?= !empty($this->lang->line('label_create')) ? $this->lang->line('label_create') : 'Create'; ?></th>
                                                    <th scope="col"><?= !empty($this->lang->line('label_read')) ? $this->lang->line('label_read') : 'Read'; ?></th>
                                                    <th scope="col"><?= !empty($this->lang->line('label_update')) ? $this->lang->line('label_update') : 'Update'; ?>Update</th>
                                                    <th scope="col"><?= !empty($this->lang->line('label_delete')) ? $this->lang->line('label_delete') : 'Delete'; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($system_modules as $module => $member_permissions) : ?>
                                                    <tr>
                                                        <td class="col-sm-4 text-left">
                                                            <?= ucfirst(str_replace("_", " ", $module)); ?>
                                                        </td>
                                                        <?php for ($i = 0; $i < $total_actions; $i++) {

                                                            $checked = (isset($user_permissions[$module]) && array_key_exists($actions[$i], $user_permissions[$module]) && ($user_permissions[$module][$actions[$i]] == "on" || $user_permissions[$module][$actions[$i]] == 1)) ? "checked" : "23";

                                                            if (array_search($actions[$i], $system_modules[$module]) !== false) { ?>
                                                                <td class="col-sm-2 text-center">
                                                                    <input type="checkbox" name="<?= 'permissions[' . $module . '][' . $actions[$i] . ']' ?>">
                                                                </td>
                                                            <?php } else { ?>
                                                                <td class="col-sm-2 text-center">
                                                                    -</td>
                                                        <?php }
                                                        } ?>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="submit_button_update" class="btn btn-primary"><?= !empty($this->lang->line('label_save')) ? $this->lang->line('label_save') : 'Save'; ?></button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php if (check_permissions("users", "create")) { ?>
                <?= form_open('auth/create_user', 'id="modal-add-user-part"', 'class="modal-part"'); ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_email')) ? $this->lang->line('label_email') : 'Email'; ?> <?= !empty($this->lang->line('label_if_user_already_exits_in_other_workspace_than_select_email')) ? $this->lang->line('label_if_user_already_exits_in_other_workspace_than_select_email') : '(If User Already Exits In Other Workspace Than Select Email)'; ?></label>
                            <div class="input-group">
                                <?= form_input(['name' => 'email', 'class' => 'demo-default', 'id' => 'email']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" id="first_name">
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_first_name')) ? $this->lang->line('label_first_name') : 'First Name'; ?></label>
                            <div class="input-group">
                                <?= form_input(['name' => 'first_name', 'placeholder' => !empty($this->lang->line('label_first_name')) ? $this->lang->line('label_first_name') : 'First Name', 'class' => 'form-control']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" id="last_name">
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_last_name')) ? $this->lang->line('label_last_name') : 'Last Name'; ?></label>
                            <div class="input-group">
                                <?= form_input(['name' => 'last_name', 'placeholder' => !empty($this->lang->line('label_last_name')) ? $this->lang->line('label_last_name') : 'Last Name', 'class' => 'form-control']) ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" id="password">
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_password')) ? $this->lang->line('label_password') : 'Password'; ?></label>
                            <div class="input-group">
                                <?= form_input(['name' => 'password', 'placeholder' => !empty($this->lang->line('label_password')) ? $this->lang->line('label_password') : 'Password', 'class' => 'form-control']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" id="password_confirm">
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_confirm_password')) ? $this->lang->line('label_confirm_password') : 'Confirm Password'; ?></label>
                            <div class="input-group">
                                <?= form_input(['name' => 'password_confirm', 'placeholder' => !empty($this->lang->line('label_confirm_password')) ? $this->lang->line('label_confirm_password') : 'Confirm Password', 'class' => 'form-control']) ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6" id="date_of_birth">
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_date_of_birth')) ? $this->lang->line('label_date_of_birth') : 'Date of Birth'; ?></label>
                            <div class="input-group">
                                <?= form_input(['name' => 'date_of_birth', 'type' => 'text', 'placeholder' => !empty($this->lang->line('label_date_of_birth')) ? $this->lang->line('label_date_of_birth') : 'Date of Birth', 'class' => 'form-control datepicker']) ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6" id="date_of_joining">
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_date_of_joining')) ? $this->lang->line('label_date_of_joining') : 'Date of Joining'; ?></label>
                            <div class="input-group">
                                <?= form_input(['name' => 'date_of_joining', 'type' => 'text', 'placeholder' => !empty($this->lang->line('label_date_of_joining')) ? $this->lang->line('label_date_of_joining') : 'Date of Joining', 'class' => 'form-control datepicker']) ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6" id="gender">
                        <div class="form-group">
                            <label class="form-control-label col-md-12"><?= !empty($this->lang->line('label_gender')) ? $this->lang->line('label_gender') : 'Gender'; ?></label>

                            <input id="male" name="gender" type="radio" class="" value="male" <?php echo $this->form_validation->set_radio('gender', 0); ?> />
                            <label for="male" class=""><?= !empty($this->lang->line('label_male')) ? $this->lang->line('label_male') : 'Male'; ?></label>
                            <input id="female" name="gender" type="radio" class="" value="female" <?php echo $this->form_validation->set_radio('gender', 1); ?> />
                            <label for="female" class=""><?= !empty($this->lang->line('label_female')) ? $this->lang->line('label_female') : 'Female'; ?></label>

                        </div>
                    </div>

                    <div class="col-md-6" id="designation">
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_designation')) ? $this->lang->line('label_designation') : 'Designation'; ?></label>
                            <div class="input-group">
                                <?= form_input(['name' => 'designation', 'type' => 'text', 'placeholder' => !empty($this->lang->line('label_designation')) ? $this->lang->line('label_designation') : 'Designation', 'class' => 'form-control']) ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6" id="phone">
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_phone')) ? $this->lang->line('label_phone') : 'Phone'; ?></label>
                            <div class="input-group">
                                <?= form_input(['name' => 'phone', 'type' => 'number', 'placeholder' => !empty($this->lang->line('label_phone')) ? $this->lang->line('label_phone') : 'Phone', 'class' => 'form-control']) ?>
                            </div>
                        </div>
                    </div>

                </div>
                </form>
            <?php } ?>
            <?= form_open('auth/edit_user', 'id="modal-edit-user-part"', 'class="modal-part"'); ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_first_name')) ? $this->lang->line('label_first_name') : 'First Name'; ?></label>
                        <div class="input-group">
                            <input name="id" type="hidden" id="id" value="">
                            <?= form_input(['name' => 'first_name', 'placeholder' => !empty($this->lang->line('label_first_name')) ? $this->lang->line('label_first_name') : 'First Name', 'class' => 'form-control']) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_last_name')) ? $this->lang->line('label_last_name') : 'Last Name'; ?></label>
                        <div class="input-group">
                            <?= form_input(['name' => 'last_name', 'placeholder' => !empty($this->lang->line('label_last_name')) ? $this->lang->line('label_last_name') : 'Last Name', 'class' => 'form-control']) ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_password')) ? $this->lang->line('label_password') : 'Password'; ?></label>
                        <div class="input-group">
                            <?= form_input(['name' => 'password', 'placeholder' => !empty($this->lang->line('label_password')) ? $this->lang->line('label_password') : 'Password', 'class' => 'form-control']) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_confirm_password')) ? $this->lang->line('label_confirm_password') : 'Confirm Password'; ?></label>
                        <div class="input-group">
                            <?= form_input(['name' => 'password_confirm', 'placeholder' => !empty($this->lang->line('label_confirm_password')) ? $this->lang->line('label_confirm_password') : 'Confirm Password', 'class' => 'form-control']) ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-6" id="date_of_birth">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_date_of_birth')) ? $this->lang->line('label_date_of_birth') : 'Date of Birth'; ?></label>
                        <div class="input-group">
                            <?= form_input(['name' => 'date_of_birth', 'type' => 'text', 'placeholder' => !empty($this->lang->line('label_date_of_birth')) ? $this->lang->line('label_date_of_birth') : 'Date of Birth', 'class' => 'form-control datepicker']) ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-6" id="date_of_joining">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_date_of_joining')) ? $this->lang->line('label_date_of_joining') : 'Date of Joining'; ?></label>
                        <div class="input-group">
                            <?= form_input(['name' => 'date_of_joining', 'type' => 'text', 'placeholder' => !empty($this->lang->line('label_date_of_joining')) ? $this->lang->line('label_date_of_joining') : 'Date of Joining', 'class' => 'form-control datepicker']) ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-6" id="gender">
                    <div class="form-group">
                        <label class="form-control-label col-md-12"><?= !empty($this->lang->line('label_gender')) ? $this->lang->line('label_gender') : 'Gender'; ?></label>

                        <input id="male" name="gender" type="radio" class="" value="0" <?php echo $this->form_validation->set_radio('gender', 0); ?> />
                        <label for="male" class=""><?= !empty($this->lang->line('label_male')) ? $this->lang->line('label_male') : 'Male'; ?></label>
                        <input id="female" name="gender" type="radio" class="" value="1" <?php echo $this->form_validation->set_radio('gender', 1); ?> />
                        <label for="female" class=""><?= !empty($this->lang->line('label_female')) ? $this->lang->line('label_female') : 'Female'; ?></label>

                    </div>
                </div>

                <div class="col-md-6" id="designation">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_designation')) ? $this->lang->line('label_designation') : 'Designation'; ?></label>
                        <div class="input-group">
                            <?= form_input(['name' => 'designation', 'type' => 'text', 'placeholder' => !empty($this->lang->line('label_designation')) ? $this->lang->line('label_designation') : 'Designation', 'class' => 'form-control']) ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-6" id="phone">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_phone')) ? $this->lang->line('label_phone') : 'Phone'; ?></label>
                        <div class="input-group">
                            <?= form_input(['name' => 'phone', 'type' => 'number', 'placeholder' => !empty($this->lang->line('label_phone')) ? $this->lang->line('label_phone') : 'Phone', 'class' => 'form-control']) ?>
                        </div>
                    </div>
                </div>
            </div>
            </form>
            <?php include('include-footer.php'); ?>
        </div>
    </div>

    <script>
        not_in_workspace_user = <?php echo json_encode(array_values($not_in_workspace_user)); ?>;
    </script>

    <?php include('include-js.php'); ?>

    <!-- Page Specific JS File -->
    <script src="assets/js/page/components-user.js"></script>

</body>

</html>