<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= !empty($this->lang->line('label_bulk_upload')) ? $this->lang->line('label_bulk_upload') : 'Bulk Upload'; ?> &mdash; <?= get_compnay_title(); ?></title>
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
                        <h1><?= !empty($this->lang->line('label_bulk_upload')) ? $this->lang->line('label_bulk_upload') : 'Bulk Upload'; ?></h1>
                    </div>

                    <div class="section-body">
                        <div class="col-md-12 text-left mb-3">
                            <div class="alert alert-primary mb-1">
                                <li><?= !empty($this->lang->line('label_read_and_follow_instructions_carefully_while_preparing_data')) ? $this->lang->line('label_read_and_follow_instructions_carefully_while_preparing_data') : 'Read and follow instructions carefully while preparing data'; ?></li>
                                <li><?= !empty($this->lang->line('label_download_and_save_the_sample_file_to_reduce_errors')) ? $this->lang->line('label_download_and_save_the_sample_file_to_reduce_errors') : 'Download and save the sample file to reduce errors'; ?></li>
                                <li><?= !empty($this->lang->line('label_for_adding_bulk_products_file_should_be_csv_format')) ? $this->lang->line('label_for_adding_bulk_products_file_should_be_csv_format') : 'For adding bulk products file should be .csv format'; ?></li>
                                <li><?= !empty($this->lang->line('label_make_sure_you_entered_valid_data_as_per_instructions_before_proceed')) ? $this->lang->line('label_make_sure_you_entered_valid_data_as_per_instructions_before_proceed') : 'Make sure you entered valid data as per instructions before proceed'; ?></li>
                            </div>
                        </div>

                        <div class="row mt-sm-4">
                            <div class='col-md-12'>
                                <div class="card">
                                    <div class="card-body">
                                        <form action="<?= base_url('projects/create_bulk_task_update') ?>" id="create_bulk_task_update">
                                            <div class="row">
                                                <div class="col-lg-7">
                                                    <div class="form-group">
                                                        <label for="type"><?= !empty($this->lang->line('label_type')) ? $this->lang->line('label_type') : 'Type'; ?> <small>[<?= !empty($this->lang->line('label_uploaded')) ? $this->lang->line('label_uploaded') : 'Uploaded'; ?>/<?= !empty($this->lang->line('label_updated')) ? $this->lang->line('label_updated') : 'Updated'; ?>]</small> <span class='text-danger text-sm'>*</span></label>
                                                        <select class="form-control" name='type' id='type'>
                                                            <option value=''><?= !empty($this->lang->line('label_select')) ? $this->lang->line('label_select') : 'Select'; ?></option>
                                                            <option value='upload'><?= !empty($this->lang->line('label_uploaded')) ? $this->lang->line('label_uploaded') : 'Uploaded'; ?></option>
                                                            <option value='update'><?= !empty($this->lang->line('label_updated')) ? $this->lang->line('label_updated') : 'Updated'; ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="form-group">
                                                        <label for="file"><?= !empty($this->lang->line('label_file')) ? $this->lang->line('label_file') : 'File'; ?> <span class='text-danger text-sm'>*</span></label>
                                                        <input type="file" name="upload_file" class="form-control" accept=".csv" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row text-left mb-5">
                                                <div class="card-footer col-md-12">
                                                    <button class="btn btn-primary mb-2" id="submit_button"><?= !empty($this->lang->line('label_submit')) ? $this->lang->line('label_submit') : 'Submit'; ?></button>
                                                    <div id="result" style="display: none;"></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group">
                                                    <a href="<?= base_url('assets/task/upload/task-bulk-upload-sample.csv') ?>" class="btn btn-info mb-3 " download="task-bulk-upload-sample.csv"><?= !empty($this->lang->line('label_bulk_upload_sample_file')) ? $this->lang->line('label_bulk_upload_sample_file') : 'Bulk Upload sample file'; ?> <i class="fas fa-download"></i></a>
                                                    <a href="<?= base_url('assets/task/upload/bulk-upload-instructions.txt') ?>" class="btn btn-primary mb-3"><?= !empty($this->lang->line('label_bulk_upload_instructions')) ? $this->lang->line('label_bulk_upload_instructions') : 'Bulk Upload Instructions'; ?> <i class="fas fa-download"></i></a>
                                                    <a href="<?= base_url('assets/task/upload/task-bulk-update-sample.csv') ?>" class="btn btn-info mb-3" download="task-bulk-update-sample.csv"><?= !empty($this->lang->line('label_bulk_update_sample_file')) ? $this->lang->line('label_bulk_update_sample_file') : 'Bulk Update sample file'; ?> <i class="fas fa-download"></i></a>
                                                    <a href="<?= base_url('assets/task/upload/bulk-update-instructions.txt') ?>" class="btn btn-primary mb-3"><?= !empty($this->lang->line('label_bulk_update_instructions')) ? $this->lang->line('label_bulk_update_instructions') : 'Bulk Update Instructions'; ?> <i class="fas fa-download"></i></a>
                                                    <a class='btn btn-info mb-3' id='data' href="<?php echo base_url('projects/bulk_task_download'); ?>"><?= !empty($this->lang->line('label_bulk_download_data')) ? $this->lang->line('label_bulk_download_data') : 'Bulk Download Data'; ?> <i class='fa fa-download'></i></a>
                                                </div>
                                            </div>
                                        </form>
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
</body>

</html>