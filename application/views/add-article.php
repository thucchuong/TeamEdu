<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= !empty($this->lang->line('label_add_article')) ? $this->lang->line('label_add_article') : 'Add Article'; ?> &mdash; <?= get_compnay_title(); ?></title>
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
                        <h1><?= !empty($this->lang->line('label_add_article')) ? $this->lang->line('label_add_article') : 'Add Article'; ?></h1>
                        <div class="section-header-breadcrumb">                            
                            <div class="btn-group mr-2 no-shadow">
                                <a class="btn btn-primary text-white" href="<?= base_url('knowledgebase') ?>" class="btn"><i class="fas fa-list"></i> <?= !empty($this->lang->line('label_articles')) ? $this->lang->line('label_articles') : 'Articles'; ?></a>
                            </div>    
                        </div>
                    </div>
                    <div class="section-body">
                        <div class="row mt-sm-4">
                            <div class='col-md-12'>
                                <div class="card">
                                    <div class="card-body">
                                        <form action="<?= base_url('knowledgebase/create') ?>" id="create_article">
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" class="form-control" value="<?= $this->security->get_csrf_hash(); ?>">
                                            <div class="form-group">
                                                <label><?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title' ?></label>
                                                <span class="asterisk">*</span>
                                                <input id="title" type="text" class="form-control" name="title"  placeholder="<?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title' ?>">
                                            </div>
                                            <div class="form-group">
                                                <label><?= !empty($this->lang->line('label_group')) ? $this->lang->line('label_group') : 'Group' ?></label>
                                                <span class="asterisk">*</span>
                                                <div class="input-group">                                            
                                                    <select class="custom-select select2" id="article_group_id" name="group_id">
                                                        <option value="" selected><?= !empty($this->lang->line('label_select_group')) ? $this->lang->line('label_select_group') : 'Select Group'; ?>...</option>
                                                        <?php 
                                                        foreach ($groups as $group) { ?>
                                                            <option value="<?= $group['id'] ?>"><?= $group['title'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <div class="wrapper" id="wrp" style="display: none;">
                                                        <hr><a href="#" id="modal-add-article-group" style="text-decoration: none;">+ <?= !empty($this->lang->line('label_add_group')) ? $this->lang->line('label_add_group') : 'Add Group'; ?></a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label><?= !empty($this->lang->line('label_article_description')) ? $this->lang->line('label_article_description') : 'Article Description' ?></label>
                                                <span class="asterisk">*</span>
                                                <textarea name="description" id="add_article" class="form-control" placeholder="<?= !empty($this->lang->line('label_article_description')) ? $this->lang->line('label_article_description') : 'Article Description' ?>" data-height="150"></textarea>
                                            </div>

                                            <div class="row text-center">
                                                <div class="col-md-3"></div>
                                                <div class="card-footer col-md-6">
                                                    <button class="btn btn-primary mb-2" id="submit_button"><?= !empty($this->lang->line('label_submit')) ? $this->lang->line('label_submit') : 'Add'; ?></button>
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
        </div>
    </div>

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

    <?php include('include-footer.php'); ?>

    </div>
    </div>
    <?php include('include-js.php'); ?>
</body>

<script src="<?= base_url('assets/js/page/components-knowledgebase.js'); ?>"></script>

</html>