<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= !empty($this->lang->line('label_user_group_permissions')) ? $this->lang->line('label_user_group_permissions') : 'User Permissions'; ?> &mdash; <?= get_compnay_title(); ?></title>
    <?php
        require_once(APPPATH . 'views/include-css.php');
    ?>
</head>
<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <?php require_once(APPPATH . 'views/include-header.php'); ?>
 
            <!-- Main Content -->
            <div class="main-content" style="min-height: 318px;">
                <section class="section">
                    <div class="section-header">
                        <h1>
                            <?= !empty($this->lang->line('label_user_group_permissions')) ? $this->lang->line('label_user_group_permissions') : 'User Permissions'; ?>
                        </h1>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="card mb-0">
                                <div class="alert alert-danger" style="margin-bottom: 0rem">
                                    <b><?= !empty($this->lang->line('label_note')) ? $this->lang->line('label_note') : 'Note'; ?></b> <?= !empty($this->lang->line('label_admin_always_have_all_the_permission_here_you_can_set_permissions_for_members_and_clients')) ? $this->lang->line('label_admin_always_have_all_the_permission_here_you_can_set_permissions_for_members_and_clients') : 'Admin always have all the permission. Here you can set permissions for members and clients.'; ?>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class="section-body">
                        <?php
                             $user_permissions = $client_permissions_data = "";
     
                             $actions = ['create', 'read', 'update', 'delete'];
                             $total_actions = count($actions);
     
                             // /* reading member's permissions from database */
                             $user_permissions = (!empty($modules[0]['member_permissions'])) ? json_decode($modules[0]['member_permissions'],1) : [];
                             $client_permissions_data = (!empty($modules[0]['client_permissions'])) ? json_decode($modules[0]['client_permissions'], 1 ) : [];
                        ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="text-center"><?= !empty($this->lang->line('label_member_group_permissions')) ? $this->lang->line('label_member_group_permissions') : 'Member Group Permissions'; ?></h4>
                                    </div>
                                    <form action="<?= base_url('permissions/update_members_permissions') ?>" id="update_members_permissions_form">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <tbody>
                                                        <tr>
                                                            <th class="col-sm-4 text-left"><?= !empty($this->lang->line('label_module_permissions')) ? $this->lang->line('label_module_permissions') : 'Module/Permissions'; ?></th>
                                                            <?php foreach ($actions as $action) { ?>
                                                                <th class="col-sm-2 text-center"><?= ucfirst($action) ?></th>
                                                            <?php } ?>
                                                        </tr>
                                                        <?php foreach ($system_modules as $module => $member_permissions) : ?>
                                                            <tr>
                                                                <td class="col-sm-4 text-left"><?= ucfirst(str_replace("_", " ", $module)); ?></td>
                                                                <?php for ($i = 0; $i < $total_actions; $i++) {
                                                                    // $actions[$i] means // example values : create, read, update or delete 

                                                                    // $user_permissions[$module] means database permissions for the group 
                                                                    // ex : "projects" => [ ["create" => on],["read" => on], ... ]

                                                                    $checked = (isset($user_permissions[$module]) && array_key_exists($actions[$i], $user_permissions[$module]) && ($user_permissions[$module][$actions[$i]] == "on" || $user_permissions[$module][$actions[$i]] == 1)) ? "checked" : "";

                                                                    if (array_search($actions[$i], $system_modules[$module]) !== false) { ?>
                                                                        <td class="col-sm-2 text-center"> <input type="checkbox" name="<?= 'member_permissions[' . $module . '][' . $actions[$i] . ']' ?>" <?= $checked ?>></td>
                                                                    <?php } else { ?>
                                                                        <td class="col-sm-2 text-center">-</td>
                                                                <?php }
                                                                } ?>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                        <div class="card-footer text-right">
                                            <button class="btn btn-primary mb-2" id="submit_button"><?= !empty($this->lang->line('label_update_permissions')) ? $this->lang->line('label_update_permissions') : 'Update Permissions'; ?></button>
                                            <div id="result" class="disp-none"></div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="text-center"><?= !empty($this->lang->line('label_client_group_permissions')) ? $this->lang->line('label_client_group_permissions') : 'Client Group Permissions'; ?></h4>
                                    </div>
                                    <form action="<?= base_url('permissions/update_clients_permissions') ?>" id="update_clients_permissions_form">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <tbody>
                                                        <tr>
                                                            <th class="col-sm-4 text-left"><?= !empty($this->lang->line('label_module_permissions')) ? $this->lang->line('label_module_permissions') : 'Module/Permissions'; ?></th>
                                                            <?php foreach ($actions as $action) { ?>
                                                                <th class="col-sm-2 text-center"><?= ucfirst($action) ?></th>
                                                            <?php } ?>
                                                        </tr>
                                                        <?php foreach ($system_modules as $module => $client_permissions) : ?>
                                                            <tr>
                                                                <td class="col-sm-4 text-left"><?= ucfirst(str_replace("_", " ", $module)); ?></td>
                                                                <?php for ($i = 0; $i < $total_actions; $i++) {
                                                                    // $actions[$i] means // example values : create, read, update or delete 

                                                                    // $client_permissions_data[$module] means database permissions for the group 
                                                                    // ex : "projects" => [ ["create" => on],["read" => on], ... ]

                                                                    $checked = (isset($client_permissions_data[$module]) && array_key_exists($actions[$i], $client_permissions_data[$module]) && ($client_permissions_data[$module][$actions[$i]] == "on" || $client_permissions_data[$module][$actions[$i]] == 1)) ? "checked" : "";

                                                                    if (array_search($actions[$i], $system_modules[$module]) !== false) { ?>
                                                                        <td class="col-sm-2 text-center"> <input type="checkbox" name="<?= 'client_permissions[' . $module . '][' . $actions[$i] . ']' ?>" <?= $checked ?>></td>
                                                                    <?php } else { ?>
                                                                        <td class="col-sm-2 text-center">-</td>
                                                                <?php }
                                                                } ?>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                        <div class="card-footer text-right">
                                            <button class="btn btn-primary mb-2" id="submit_button_update"><?= !empty($this->lang->line('label_update_permissions')) ? $this->lang->line('label_update_permissions') : 'Update Permissions'; ?></button>
                                            <div id="result" class="disp-none"></div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <?php require_once(APPPATH . 'views/include-footer.php'); ?>
        </div>
    </div>
    <?php
    require_once(APPPATH . 'views/include-js.php');
    ?>
</body>

</html>