<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title><?= !empty($this->lang->line('label_knowledgebase')) ? $this->lang->line('label_knowledgebase') : 'Knowledgebase'; ?> &mdash; <?= get_compnay_title(); ?></title>
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
            <h1><?= !empty($this->lang->line('label_knowledgebase')) ? $this->lang->line('label_knowledgebase') : 'Knowledgebase'; ?></h1>
            <div class="section-header-breadcrumb">
              <?php if (check_permissions("knowledgebase", "create")) { ?>
                <div class="btn-group mr-2 no-shadow">
                  <a class="btn btn-primary text-white" href="<?= base_url('knowledgebase/article_groups') ?>" class="btn"><i class="fas fa-list"></i> <?= !empty($this->lang->line('label_article_groups')) ? $this->lang->line('label_article_groups') : 'Article Groups'; ?></a>
                </div>
                <div class="btn-group mr-2 no-shadow">
                  <a class="btn btn-primary text-white" href="<?= base_url('knowledgebase/add-article') ?>" class="btn"><i class="fas fa-plus"></i> <?= !empty($this->lang->line('label_add_article')) ? $this->lang->line('label_add_article') : 'Add Article'; ?></a>
                </div>
              <?php } ?>
            </div>
          </div>
          <div class="section-body">
            <div class="row">
              <div class='col-md-12'>
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="form-group col-md-3">
                        <select class="form-control" name="group_id" id="article_group">
                          <option value=""><?= !empty($this->lang->line('label_select_group')) ? $this->lang->line('label_select_group') : 'Select Group'; ?></option>
                          <?php
                          foreach ($groups as $group) { ?>
                            <option value="<?= $group['id'] ?>"><?= $group['title'] ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="form-group col-md-4">
                        <input placeholder="<?= !empty($this->lang->line('label_article_due_dates_between')) ? $this->lang->line('label_article_due_dates_between') : 'Article Due Dates Between'; ?>" id="article_between" name="article_between" type="text" class="form-control" autocomplete="off">
                        <input id="article_start_date" name="article_start_date" type="hidden">
                        <input id="article_end_date" name="article_end_date" type="hidden">
                      </div>
                      <div class="form-group col-md-2">
                        <i class="btn btn-primary btn-rounded no-shadow" id="fillter-articles"><?= !empty($this->lang->line('label_filter')) ? $this->lang->line('label_filter') : 'Filter'; ?></i>
                      </div>
                    </div>
                    <table class='table-striped' id='articles_list' data-toggle="table" data-url="<?= base_url('knowledgebase/get_article_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                      "fileName": "article-list"
                    }' data-query-params="queryParams">
                      <thead>
                        <tr>
                          <th data-field="title" data-sortable="true"><?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?></th>
                          <th data-field="group_name" data-sortable="true"><?= !empty($this->lang->line('label_group_name')) ? $this->lang->line('label_group_name') : 'Group Name'; ?></th>
                          <th data-field="date_published" data-sortable="false"><?= !empty($this->lang->line('label_assigned_date')) ? $this->lang->line('label_assigned_date') : 'Date Published'; ?></th>
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

      </section>
    </div>

    <!--forms code goes here-->

    <?php include('include-footer.php'); ?>
  </div>
  </div>

  <?php include('include-js.php'); ?>
  <script src="<?= base_url('assets/js/page/components-knowledgebase.js'); ?>"></script>

</body>

</html>