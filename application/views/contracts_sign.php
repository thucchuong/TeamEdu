<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>><?= !empty($this->lang->line('label_contracts_sign')) ? $this->lang->line('label_contracts_sign') : ' Contracts Sign'; ?></title>
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
                    </div>
                    <div class="section-body">
                        <div class="contracts">
                            <div class="row">
                                <?php
                                $json = json_encode($contracts);
                                $contract = json_decode($json, true);
                                ?>
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-6 text-left">
                                            <?php
                                            $full_logo = get_compnay_logo();
                                            ?>
                                            <img alt="Task Hub" src="<?= !empty($full_logo) ? base_url('assets/icons/' . $full_logo) : base_url('assets/icons/logo.png'); ?>" width="200px">
                                        </div>
                                        <div class="col-6 text-right">
                                            <span><?= !empty($this->lang->line('label_contracts')) ? $this->lang->line('label_contracts') : 'Contracts'; ?> -<?= ' ' . rand(000, 000) . $contract['id'] ?></span>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-6">
                                            <div class="text-left">
                                                <h4><?= !empty($this->lang->line('label_service_provider')) ? $this->lang->line('label_service_provider') : 'Service Provider'; ?></h4>
                                            </div>
                                            <div class="x-title resizetext">
                                                <div class="text-muted"><?= $contract['user_first_name'] ?></div>
                                                <div class="text-muted"><?= $contract['user_last_name'] ?></div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-right">
                                                <div class="">
                                                    <h4><?= !empty($this->lang->line('label_client')) ? $this->lang->line('label_client') : 'Client'; ?></h4>
                                                </div>
                                                <div class="x-title resizetext">
                                                    <div class="text-muted"><?= $contract['client_first_name'] ?></div>
                                                    <div class="text-muted"><?= $contract['client_last_name'] ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-5">
                                        <div class="col-6">
                                            <span><?= !empty($this->lang->line('label_start_date')) ? $this->lang->line('label_start_date') : 'Start Date'; ?> : </span><span class="text-muted"><?= date("d-M-Y", strtotime($contract['start_date'])); ?></span><br>
                                            <span><?= !empty($this->lang->line('label_end_date')) ? $this->lang->line('label_end_date') : 'End Date'; ?> : </span><span class="text-muted"><?= date("d-M-Y", strtotime($contract['end_date'])); ?></span><br>
                                            <span><?= !empty($this->lang->line('label_prepared_by')) ? $this->lang->line('label_prepared_by') : 'Prepared By'; ?> : </span><span class="text-muted"><?= $contract['user_first_name'] . ' ' . $contract['user_last_name'] ?></span>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-right">
                                                <span><?= !empty($this->lang->line('label_contract_id')) ? $this->lang->line('label_contract_id') : 'Contract ID'; ?> : </span><span class="text-muted">CO-<?= rand(000, 000) . $contract['id'] ?></span><br>
                                                <span><?= !empty($this->lang->line('label_value')) ? $this->lang->line('label_value') : 'Value'; ?> : </span><span class="text-muted"><?= get_currency_symbol() . $contract['value'] ?></span><br>
                                                <span><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?> : </span>
                                                <span class="text-muted">
                                                    <?php
                                                    $signed_status = '';
                                                    if (empty($contract['provider_sign']) && empty($contract['client_sign'])) {
                                                        $signed_status = '<div class="badge badge-primary">Pending</div>';
                                                    } elseif (!empty($contract['provider_sign'] && $contract['client_sign'])) {
                                                        $signed_status = '<div class="badge badge-success">Active</div>';
                                                    } elseif ($contract['provider_sign'] || $contract['client_sign']) {
                                                        $signed_status = '<div class="badge badge-warning">Awaiting</div>';
                                                    }
                                                    echo $signed_status;
                                                    ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-5">
                                        <div class="col-12">
                                            <?= $contract['description'] ?>
                                        </div>
                                    </div>
                                    <div class="row mt-5">
                                        <div class="col-6 text-left">
                                            <div class="p-l-0">
                                                <h5><?= !empty($this->lang->line('label_service_provider')) ? $this->lang->line('label_service_provider') : 'Service Provider'; ?></h5>
                                                <?php
                                                if (isset($contract['provider_sign']) && !empty($contract['provider_sign'])) { ?>
                                                    <img alt="Signature" src="<?= base_url('assets/sign/' . $contract['provider_sign']); ?>" height="100px" width="200px"><br>
                                                    <?php if (is_admin() || is_member()) {
                                                        if (check_permissions("contracts", "delete")) {
                                                    ?>

                                                            <button type="button" id="section-not-to-print" class="btn btn-outline-primary delete-contracts-provider-sign-alert" data-toggle="modal" data-id="<?= $contract['id'] ?>"><?= !empty($this->lang->line('label_delete_signature')) ? $this->lang->line('label_delete_signature') : 'Delete signature'; ?></button>
                                                    <?php }
                                                    }
                                                } else {
                                                    echo "<h6>Unsigned</h6>"; ?>
                                                    <?php if (is_admin() || is_member()) {
                                                        if (check_permissions("contracts", "create")) { ?>
                                                            <button type="button" id="section-not-to-print" class="btn btn-outline-primary provider-sign-data mt-1" data-toggle="modal" data-id="<?= $contract['id'] ?>" data-target=".edit-contracts-provider-sign-modal"><?= !empty($this->lang->line('label_create_signature')) ? $this->lang->line('label_create_signature') : 'Create Signature'; ?></button><br>
                                                <?php }
                                                    }
                                                } ?>
                                            </div>
                                        </div>
                                        <div class="col-6 text-right">
                                            <div class="p-r-0">
                                                <h5><?= !empty($this->lang->line('label_client')) ? $this->lang->line('label_client') : 'Client'; ?></h5>
                                                <?php
                                                if (isset($contract['client_sign']) && !empty($contract['client_sign'])) { ?>
                                                    <img alt="Signature" src="<?= base_url('assets/sign/' . $contract['client_sign']); ?>" height="100px" width="200px"><br>
                                                    <?php if (is_client()) {
                                                        if (check_permissions("contracts", "delete")) { ?>
                                                            <button type="button" id="section-not-to-print" class="btn btn-outline-primary delete-contracts-client-sign-alert" data-toggle="modal" data-id="<?= $contract['id'] ?>"><?= !empty($this->lang->line('label_delete_signature')) ? $this->lang->line('label_delete_signature') : 'Delete signature'; ?></button>

                                                    <?php }
                                                    }
                                                } else {
                                                    echo '<h6>Unsigned</h6>'; ?>
                                                    <?php if (is_client()) {
                                                        if (check_permissions("contracts", "create")) { ?>
                                                            <button type="button" id="section-not-to-print" class="btn btn-outline-primary client-sign-data mt-1" data-toggle="modal" data-id="<?= $contract['id'] ?>" data-target=".edit-contracts-client-sign-modal"><?= !empty($this->lang->line('label_create_signature')) ? $this->lang->line('label_create_signature') : 'Create Signature'; ?></button><br>
                                                <?php }
                                                    }
                                                } ?>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <div class="modal fade edit-contracts-provider-sign-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><?= !empty($this->lang->line('label_contracts_sign')) ? $this->lang->line('label_contracts_sign') : ' Contracts Sign'; ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action='<?= base_url('contracts/create_contracts_sign') ?>' method="post" enctype="multipart/form-data" id="sign_form">
                            <input name="id" type="hidden" id="update_id" value="">
                            <div class="form-group col-12">
                                <label><?= !empty($this->lang->line('label_first_name')) ? $this->lang->line('label_first_name') : 'First Name'; ?></label>
                                <input name="provider_first_name" class="form-control" id="update_provider_first_name">
                            </div>
                            <div class="form-group col-12">
                                <label><?= !empty($this->lang->line('label_last_name')) ? $this->lang->line('label_last_name') : 'Last Name'; ?></label>
                                <input name="provider_last_name" class="form-control" id="update_provider_last_name">
                            </div>
                            <div class="form-group col-12">
                                <label><?= !empty($this->lang->line('label_signature')) ? $this->lang->line('label_signature') : 'Signature'; ?></label>
                            </div>
                            <div class="form-group col-12">
                                <canvas id="signature-pad1" height="181" style="touch-action: none; user-select: none; border:1px solid #6c757d !important;" width="720"></canvas>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSaveSign" class="btn btn-primary"><?= !empty($this->lang->line('label_save')) ? $this->lang->line('label_save') : 'Save'; ?></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade edit-contracts-client-sign-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><?= !empty($this->lang->line('label_contracts_sign')) ? $this->lang->line('label_contracts_sign') : ' Contracts Sign'; ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action='<?= base_url('contracts/create_client_contracts_sign') ?>' method="post" enctype="multipart/form-data" id="client_sign_form">
                            <input name="id" type="hidden" id="update_client_id" value="">
                            <div class="form-group col-12">
                                <label><?= !empty($this->lang->line('label_first_name')) ? $this->lang->line('label_first_name') : 'First Name'; ?></label>
                                <input name="client_first_name" class="form-control" id="update_client_first_name">
                            </div>
                            <div class="form-group col-12">
                                <label><?= !empty($this->lang->line('label_last_name')) ? $this->lang->line('label_last_name') : 'Last Name'; ?></label>
                                <input name="client_last_name" class="form-control" id="update_client_last_name">
                            </div>
                            <div class="form-group col-12">
                                <label><?= !empty($this->lang->line('label_signature')) ? $this->lang->line('label_signature') : 'Signature'; ?></label>
                            </div>
                            <div class="form-group col-12">
                                <canvas id="signature-pad2" height="181" style="touch-action: none; user-select: none; border:1px solid #6c757d !important;" width="720"></canvas>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnClientSaveSign" class="btn btn-primary"><?= !empty($this->lang->line('label_save')) ? $this->lang->line('label_save') : 'Save'; ?></button>
                    </div>
                </div>
            </div>
        </div>

        <?php include('include-footer.php'); ?>

    </div>
    <?php include('include-js.php'); ?>
</body>
<script>
    contracts_id = <?= $contract['id']; ?>
</script>
<script src="<?= base_url('assets/js/page/components-contracts.js'); ?>"></script>

</html>