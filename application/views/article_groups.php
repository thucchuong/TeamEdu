<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title><?= !empty($this->lang->line('label_article_groups')) ? $this->lang->line('label_article_groups') : 'Article Groups'; ?> &mdash; <?= get_compnay_title(); ?></title>
  <?php
  require_once(APPPATH . 'views/include-css.php');
  ?>

</head>

<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">

      <?php
      require_once(APPPATH . 'views/include-header.php');
      ?>

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1><?= !empty($this->lang->line('label_article_groups')) ? $this->lang->line('label_article_groups') : 'Article Groups'; ?></h1>
            <div class="section-header-breadcrumb">
              <?php if (check_permissions("knowledgebase", "create")) { ?>
                <div class="btn-group mr-2 no-shadow">
                  <a class="btn btn-primary text-white" href="<?= base_url('knowledgebase') ?>" class="btn"><i class="fas fa-list"></i> <?= !empty($this->lang->line('label_article')) ? $this->lang->line('label_article') : 'Articles'; ?></a>
                </div>
                <i class="btn btn-primary btn-rounded no-shadow" id="modal-add-article-group" data-value="add"><?= !empty($this->lang->line('label_add_group')) ? $this->lang->line('label_add_group') : 'Add Group'; ?></i>
              <?php } ?>
            </div>
          </div>
          <div class="section-body">
            <div class="row">
              <div class='col-md-12'>
                <div class="card">
                  <div class="card-body">
                    <table class='table-striped' id='payment_mode_list' data-toggle="table" data-url="<?= base_url('knowledgebase/get_groups_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="title" data-sort-order="asc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-options='{ "fileName": "Payment-Mode-list" }' data-query-params="queryParams">
                      <thead>
                        <tr>
                          <th data-field="id" data-visible="false" data-sortable="true"><?= !empty($this->lang->line('label_id')) ? $this->lang->line('label_id') : 'ID'; ?></th>
                          <th data-field="title" data-sortable="true"><?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?></th>
                          <th data-field="description" data-sortable="true" data-visible="true"><?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?></th>
                          <?php if ((check_permissions("knowledgebase", "update")) || (check_permissions("knowledgebase", "delete"))) { ?>
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

      <?php if (check_permissions("knowledgebase", "create")) { ?>
      <?= form_open('knowledgebase/create_article_group', 'id="modal-add-article-group-part"', 'class="modal-part"'); ?>
      <div id="modal-title" class="d-none"><?= !empty($this->lang->line('label_add_article_group')) ? $this->lang->line('label_add_article_group') : 'Add Article Group'; ?></div>
      <div id="modal-footer-add-title" class="d-none"><?= !empty($this->lang->line('label_add')) ? $this->lang->line('label_add') : 'Add'; ?></div>
      <div class="row">
        <div class="col-md-6" id="title">
          <div class="form-group">
            <label><?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?></label><span class="asterisk"> *</span>
            <div class="input-group">
              <?= form_input(['name' => 'title', 'placeholder' => !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title', 'class' => 'form-control']) ?>
            </div>
          </div>
        </div>

        <div class="col-md-6" id="description">
          <div class="form-group">
            <label><?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?></label><span class="asterisk"> *</span>
            <div class="input-group">
              <?= form_textarea(['name' => 'description', 'placeholder' => !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description', 'class' => 'form-control']) ?>
            </div>
          </div>
        </div>
      </div>
      </form>
      <?php } ?>
      <div class="modal-edit-article-group"></div>
      <?= form_open('knowledgebase/edit_article_group', 'id="modal-edit-article-group-part"', 'class="modal-part"'); ?>
      <div id="modal-title" class="d-none"><?= !empty($this->lang->line('label_edit_article_group')) ? $this->lang->line('label_edit_article_group') : 'Edit Article Group'; ?></div>
      <div id="modal-footer-edit-title" class="d-none"><?= !empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit'; ?></div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label><?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?></label><span class="asterisk"> *</span>
            <div class="input-group">
              <input name="update_id" type="hidden" id="update_id" value="">
              <?= form_input(['name' => 'title', 'placeholder' => !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title', 'class' => 'form-control', 'id' => 'update_title']) ?>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label><?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?></label><span class="asterisk"> *</span>
            <div class="input-group">
              <?= form_textarea(['name' => 'description', 'placeholder' => !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description', 'class' => 'form-control', 'id' => 'update_description']) ?>
            </div>
          </div>
        </div>
      </div>
      </form>

      <?php
      require_once(APPPATH . 'views/include-footer.php');
      ?>
    </div>
  </div>

  <?php
  require_once(APPPATH . 'views/include-js.php');
  ?>

  <!-- Page Specific JS File -->

  <script src="<?= base_url('assets/js/page/components-knowledgebase.js'); ?>"></script>

</body>

</html>