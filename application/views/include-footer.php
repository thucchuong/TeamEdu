<?php $current_version = get_current_version(); ?>
<form action="<?= base_url('workspace/create') ?>" id="modal-add-Workspace-part" class="modal-part">
    <div id="modal-title" class="d-none"><?= !empty($this->lang->line('label_create_new_workspace')) ? $this->lang->line('label_create_new_workspace') : 'Create Workspace'; ?></div>
    <div id="modal-footer-add-title" class="d-none"><?= !empty($this->lang->line('label_add')) ? $this->lang->line('label_add') : 'Add'; ?></div>
    <div class="form-group">
        <label><?= !empty($this->lang->line('label_name')) ? $this->lang->line('label_name') : 'Name'; ?></label>
        <div class="input-group">
            <input type="text" name="title" placeholder="Workspace Name" class="form-control">
        </div>
    </div>
</form>

<div class="modal-edit-workspace"></div>
<form action="<?= base_url('workspace/edit') ?>" id="modal-edit-workspace-part" class="modal-part">
    <div id="modal-edit-workspace-title" class="d-none"><?= !empty($this->lang->line('label_edit_workspace')) ? $this->lang->line('label_edit_workspace') : 'Edit Workspace'; ?></div>
    <div id="modal-footer-edit-title" class="d-none"><?= !empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit'; ?></div>
    <input type="hidden" id="workspace_id" name="workspace_id">
    <div class="form-group">
        <label><?= !empty($this->lang->line('label_name')) ? $this->lang->line('label_name') : 'Name'; ?></label>
        <div class="input-group">
            <input type="text" id="updt_title" name="title" placeholder="Workspace Name" class="form-control">
        </div>
    </div>
    <div class="form-group status_div">
        <label><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></label>
        <div class="selectgroup w-100">
            <label class="selectgroup-item">
                <input type="radio" name="status" value="1" class="selectgroup-input">
                <span class="selectgroup-button"><?= !empty($this->lang->line('label_active')) ? $this->lang->line('label_active') : 'Active'; ?></span>
            </label>
            <label class="selectgroup-item">
                <input type="radio" name="status" value="0" class="selectgroup-input">
                <span class="selectgroup-button"><?= !empty($this->lang->line('label_deactive')) ? $this->lang->line('label_deactive') : 'Deactive'; ?></span>
            </label>
        </div>
    </div>
</form>

<footer class="main-footer">
    <div class="footer-left">
    </div>
    <div class="footer-right">
        <?= !empty($this->lang->line('label_copyright')) ? $this->lang->line('label_copyright') : 'Copyright'; ?> &copy; <?= date('Y'); ?> <div class="bullet"></div> <span class="badge badge-success">v <?= (isset($current_version) && !empty($current_version)) ? $current_version : '1.0' ?></span>
        <div class="bullet"></div> <?= !empty($this->lang->line('label_design_developed_by')) ? $this->lang->line('label_design_developed_by') : 'Design & Developed By'; ?> <a href="<?= footer_url() ?>" target="_blank"><?= get_compnay_title() ?></a>
    </div>
</footer>


   
