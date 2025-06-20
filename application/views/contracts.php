<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= !empty($this->lang->line('label_contracts')) ? $this->lang->line('label_contracts') : 'Contracts'; ?> &mdash; <?= get_compnay_title(); ?></title>

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
                        <h1><?= !empty($this->lang->line('label_contracts')) ? $this->lang->line('label_contracts') : 'Contracts'; ?></h1>
                        <div class="section-header-breadcrumb">
                            <?php if (check_permissions("contracts", "create")) { ?>
                                <div class="btn-group mr-2 no-shadow">
                                    <a class="btn btn-primary text-white" href="<?= base_url($role . '/contracts/contracts_type') ?>" class="btn"><i class="fas fa-list"></i> <?= !empty($this->lang->line('label_add_contracts_type')) ? $this->lang->line('label_add_contracts_type') : 'Add Contracts Type'; ?></a>
                                </div>
                                <div class="breadcrumb-item"><i class="btn btn-primary btn-rounded no-shadow" id="modal-add-contracts"><?= !empty($this->lang->line('label_create_contracts')) ? $this->lang->line('label_create_contracts') : 'Create Contracts'; ?></i></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <!-- <div class="form-group col-md-3">
                                <select name="status" id="status" class="form-control">
                                    <option value=""><?= !empty($this->lang->line('label_select_status')) ? $this->lang->line('label_select_status') : 'Select Status'; ?></option>
                                    <option value="pending"><?= !empty($this->lang->line('label_pending')) ? $this->lang->line('label_pending') : 'Pending'; ?></option>
                                    <option value="active"><?= !empty($this->lang->line('label_active')) ? $this->lang->line('label_active') : 'Active'; ?></option>
                                    <option value="awaiting"><?= !empty($this->lang->line('label_awaiting')) ? $this->lang->line('label_awaiting') : 'Awaiting'; ?></option>
                                </select>
                            </div> -->
                            <div class="form-group col-md-3">
                                <select class="form-control select2" name="project_id" id="filter_project_id">
                                    <option value="" selected><?= !empty($this->lang->line('label_select_project')) ? $this->lang->line('label_select_project') : 'Select Project'; ?></option>
                                    <?php foreach ($projects as $project) {
                                    ?>
                                        <option value="<?= $project['id'] ?>"><?= $project['title'] ?></option>
                                    <?php
                                    } ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <select class="form-control" name="users_id" id="user_id">
                                    <option value=""><?= !empty($this->lang->line('label_select_users')) ? $this->lang->line('label_select_users') : 'Select Users'; ?></option>
                                    <?php foreach ($all_user as $all_users) { ?>
                                        <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                                    <?php
                                    } ?>
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <i class="btn btn-primary btn-rounded no-shadow" id="filter-contracts"><?= !empty($this->lang->line('label_filter')) ? $this->lang->line('label_filter') : 'Filter'; ?></i>
                            </div>
                        </div>
                        <div class='col-md-12'>
                            <div class="card">
                                <div class="card-body">
                                    <table class='table-striped' id='contracts_list' data-toggle="table" data-url="<?= base_url('contracts/get_contracts_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                      "fileName": "Contracts-list"
                    }' data-query-params="queryParams">
                                        <thead>
                                            <tr>
                                                <th data-field="id" data-sortable="true"><?= !empty($this->lang->line('label_id')) ? $this->lang->line('label_id') : 'ID'; ?></th>
                                                <th data-field="title" data-sortable="true"><?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'title'; ?></th>
                                                <th data-field="description" data-sortable="true" data-visible="false"><?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?></th>
                                                <th data-field="users_id" data-sortable="false"><?= !empty($this->lang->line('label_client')) ? $this->lang->line('label_client') : 'Client'; ?></th>
                                                <th data-field="workspace_id" data-sortable="false" data-visible="false"><?= !empty($this->lang->line('label_workspace_id')) ? $this->lang->line('label_workspace_id') : 'Workspace Id'; ?></th>
                                                <th data-field="project_id" data-sortable="true"><?= !empty($this->lang->line('label_project')) ? $this->lang->line('label_project') : 'Project'; ?></th>
                                                <th data-field="contract_type_id" data-sortable="true"><?= !empty($this->lang->line('label_contract_type')) ? $this->lang->line('label_contract_type') : 'Contract Type'; ?></th>
                                                <th data-field="value" data-sortable="false"><?= !empty($this->lang->line('label_value')) ? $this->lang->line('label_value') : 'Value'; ?></th>
                                                <th data-field="signed_status" data-sortable="false"><?= !empty($this->lang->line('label_signed_status')) ? $this->lang->line('label_signed_status') : 'Signed Status'; ?></th>
                                                <th data-field="start_date" data-sortable="false" data-visible="false"><?= !empty($this->lang->line('label_start_date')) ? $this->lang->line('label_start_date') : 'Start Date'; ?></th>
                                                <th data-field="end_date" data-sortable="false" data-visible="false"><?= !empty($this->lang->line('label_end_date')) ? $this->lang->line('label_end_date') : 'End Date'; ?></th>
                                                <?php if ((check_permissions("contracts", "update")) || (check_permissions("contracts", "create")) || (check_permissions("contracts", "delete"))) { ?>
                                                    <th data-field="action" data-sortable="false"><?= !empty($this->lang->line('label_action')) ? $this->lang->line('label_action') : 'Action'; ?></th>
                                                <?php } ?>
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
        <?php if (check_permissions("contracts", "create")) { ?>
            <form action="<?= base_url('contracts/create'); ?>" method="post" class="modal-part" id="modal-add-contracts-part">
                <div id="modal-title" class="d-none"><?= !empty($this->lang->line('label_create_contracts')) ? $this->lang->line('label_create_contracts') : 'Create Contracts'; ?></div>
                <div id="modal-footer-add-title" class="d-none"><?= !empty($this->lang->line('label_add')) ? $this->lang->line('label_add') : 'Add'; ?></div>
                <div class="row">

                    <div class="col-md-12 <?= is_client($this->session->userdata['user_id']) ? 'd-none' : '' ?>">
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_select_client')) ? $this->lang->line('label_select_client') : 'Select Client'; ?></label><span class="asterisk"> *</span>
                            <select class="form-control select2" name="client_id" id="client_id" onchange="get_projects()">
                                <option value="" selected>Choose...</option>
                                <?php foreach ($all_user as $all_users) {

                                    if (is_client($all_users->id)) { ?>
                                        <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                                <?php
                                    }
                                } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_select_project')) ? $this->lang->line('label_select_project') : 'Select Project'; ?></label>
                            <select class="form-control select2 contract_project_id" name="project_id" id="project_id">
                                <option value="" selected>Choose...</option>
                                <?php if (is_client($this->session->userdata['user_id'])) {
                                    foreach ($projects as $project) {
                                ?>
                                        <option value="<?= $project['id'] ?>"><?= $project['title'] ?></option>
                                <?php
                                    }
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?></label><span class="asterisk"> *</span>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder=<?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?> name="title">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_value')) ? $this->lang->line('label_value') : 'Value'; ?></label><span class="asterisk"> *</span>
                            <div class="input-group">
                                <input type="number" class="form-control" placeholder=<?= !empty($this->lang->line('label_value')) ? $this->lang->line('label_value') : 'Value'; ?> name="value">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="start_date"><?= !empty($this->lang->line('label_start_date')) ? $this->lang->line('label_start_date') : 'Start Date'; ?></label>
                            <input class="form-control datetimepicker" type="text" id="start_date" name="start_date" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_date"><?= !empty($this->lang->line('label_end_date')) ? $this->lang->line('label_end_date') : 'End Date'; ?></label>
                            <input class="form-control datetimepicker" type="text" id="end_date" name="end_date" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_contracts_type')) ? $this->lang->line('label_contracts_type') : 'Contracts Type'; ?></label><span class="asterisk"> *</span>
                            <div class="input-group">
                                <select class="custom-select select2" id="contract_type_id" name="contract_type_id">
                                    <option value="" selected><?= !empty($this->lang->line('label_choose_contracts_type')) ? $this->lang->line('label_choose_contracts_type') : 'Choose Contracts Type'; ?>...</option>
                                    <?php
                                    foreach ($contracts as $contract) { ?>
                                        <option value="<?= $contract['id'] ?>"><?= $contract['type'] ?></option>
                                    <?php } ?>
                                </select>
                                <div class="wrapper" id="wrp" style="display: none;">
                                    <hr><a href="#" id="modal-add-contracts-type"> + <?= !empty($this->lang->line('label_add_contracts_type')) ? $this->lang->line('label_add_contracts_type') : 'Add Contracts Type'; ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?></label><span class="asterisk"> *</span>
                            <div class="input-group">
                                <textarea type="textarea" id="description" class="form-control" placeholder=<?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?> name="description"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        <?php } ?>
        <div class="modal-edit-contracts"></div>
        <?= form_open('contracts/edit', 'id="modal-edit-contracts-part"', 'class="modal-part"'); ?>
        <div id="modal-title" class="d-none"><?= !empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit'; ?><?= !empty($this->lang->line('label_contracts')) ? ' ' . $this->lang->line('label_contracts') : ' Contracts'; ?></div>
        <div id="modal-footer-edit-title" class="d-none"><?= !empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit'; ?></div>
        <input name="id" type="hidden" id="update_id" value="id">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="clients"><?= !empty($this->lang->line('label_select_clients')) ? $this->lang->line('label_select_clients') : 'Select Clients'; ?></label><span class="asterisk"> *</span>
                    <select id="update_clients" name="clients" class="form-control select2">
                        <?php foreach ($all_user as $all_users) {
                            if (is_client($all_users->id)) { ?>
                                <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                        <?php }
                        } ?>
                    </select>
                </div>
            </div>
            
            <div class="col-md-12">
                <div class="form-group">
                    <label><?= !empty($this->lang->line('label_select_project')) ? $this->lang->line('label_select_project') : 'Select Project'; ?></label>
                    <select class="form-control select2" name="project_id" id="update_contract_project_id">
                        <?php foreach ($projects as $project) {
                        ?>
                            <option value="<?= $project['id'] ?>"><?= $project['title'] ?></option>
                        <?php
                        } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label><?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?></label><span class="asterisk"> *</span>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder=<?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?> name="title" id="update_title">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label><?= !empty($this->lang->line('label_value')) ? $this->lang->line('label_value') : 'Value'; ?></label><span class="asterisk"> *</span>
                    <div class="input-group">
                        <input type="number" class="form-control" placeholder=<?= !empty($this->lang->line('label_value')) ? $this->lang->line('label_value') : 'Value'; ?> name="value" id="update_value">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="start_date"><?= !empty($this->lang->line('label_start_date')) ? $this->lang->line('label_start_date') : 'Start Date'; ?></label>
                    <input class="form-control datetimepicker" type="text" id="update_start_date" name="start_date" autocomplete="off">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="end_date"><?= !empty($this->lang->line('label_end_date')) ? $this->lang->line('label_end_date') : 'End Date'; ?></label>
                    <input class="form-control datetimepicker" type="text" id="update_end_date" name="end_date" autocomplete="off">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label><?= !empty($this->lang->line('label_contracts_type')) ? $this->lang->line('label_contracts_type') : 'Contracts Type'; ?></label><span class="asterisk"> *</span>
                    <div class="input-group">
                        <select class="custom-select select2" id="update_contract_type_id" name="contract_type_id">
                            <option value="" selected><?= !empty($this->lang->line('label_choose_contracts_type')) ? $this->lang->line('label_choose_contracts_type') : 'Choose Contracts Type'; ?>...</option>
                            <?php
                            foreach ($contracts as $contract) { ?>
                                <option value="<?= $contract['id'] ?>"><?= $contract['type'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label><?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?></label><span class="asterisk"> *</span>
                    <div class="input-group">
                        <textarea type="textarea" id="update_description" class="form-control" placeholder=<?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?> name="description"></textarea>
                    </div>
                </div>
            </div>

        </div>
        </form>

        <?= form_open('contracts/contracts_type_create', 'id="modal-add-contracts-type-part"', 'class="modal-part"'); ?>
        <div id="modal-title" class="d-none"> <?= !empty($this->lang->line('label_add_contracts_type')) ? $this->lang->line('label_add_contracts_type') : 'Add Contracts Type'; ?></div>
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
        </div>
        </form>
        </section>
    </div>

    <!--forms code goes here-->

    <?php include('include-footer.php'); ?>
    </div>
    </div>

    <?php include('include-js.php'); ?>
    <script src="<?= base_url('assets/js/page/components-contracts.js'); ?>"></script>

</body>

</html>