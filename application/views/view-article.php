<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title><?= !empty($this->lang->line('label_knowledgebase')) ? $this->lang->line('label_knowledgebase') : 'Knowledgebase'; ?> &mdash; <?= $company_title = get_compnay_title(); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="<?= base_url('/assets/css/article.css') ?>">
</head>

<body>
  <nav class="navbar navbar-expand-sm navbar-white bg-white" id="navbar">
    <div class="container-fluid">
      <a class="navbar-brand p-3" id="logo" href="<?= base_url() ?>"><img src="<?= base_url('/assets/icons/logo.png') ?>" width="200"></a>
      <div class="d-flex flex-row-reverse">
        <div class="collapse navbar-collapse" id="mynavbar">
          <a href="<?= base_url() ?>" class="btn btn-primary px-4 mx-5" role="button"><?= !empty($this->lang->line('label_login')) ? $this->lang->line('label_login') : 'Log In'; ?>Log In</a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="main-content" id="main-content">
    <section class="section">
      <div class="section-body pb-5">
        <div id="wrapper">
          <div id="content">
            <div class="container">
              <div class="row" id="row">
                <div class="section-knowledge-base">
                  <div class="row" id="row">
                    <div class="section-header pt-3 mt-3">
                      <div class="text-center">
                        <h2><?= !empty($this->lang->line('label_search_knowledgebase_article')) ? $this->lang->line('label_search_knowledgebase_article') : 'Search Knowledgebase Article'; ?></h2>
                      </div>
                      <input type="search" name="search_article" class="form-control mb-4" name="title" id="search_article" value="" placeholder=<?= !empty($this->lang->line('label_search_article')) ? $this->lang->line('label_search_article') : 'Search Article'; ?>>
                    </div>
                    <div class="panel_s mb-4" id="panel_s">
                      <div id="get_articles"></div>
                    </div>
                    <div class="col-md-8">
                      <div class="panel_s" id="panel_s">
                        <div class="panel-body" id="panel-body">
                          <h4 class="tw-mt-0 tw-mb-4 kb-article-single-heading tw-font-semibold tw-text-xl tw-text-neutral-700"> <?= $article[0]['title']; ?> </h4>
                          <div class="tc-content kb-article-content tw-text-neutral-700">
                            <?= json_decode($article[0]['description']); ?>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <h4 class="related-heading mt-4"> Related Articles </h4>
                      <ul class="articles_list">
                        <?php foreach ($group_vise_data as $tempdata) { ?>
                          <li class="related_list">
                            <h6 class="article-heading article-related-heading">
                              <a class="ar_list" href="<?= base_url('knowledgebase/view-article/' . $tempdata->slug) ?>"> <?= $tempdata->title ?> </a>
                            </h6>
                            <div style="max-height: 4.5em; overflow: hidden;"> <?= strip_tags(json_decode($tempdata->description)); ?> </div>
                          </li>
                        <?php } ?>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <div class="footer p-3 bg-light bg-gradient text-center">
    <?= "&copy " . date("Y") . " All rights reserved by " . $company_title  ?>
  </div>
  <script src="<?= base_url('assets/modules/jquery.min.js'); ?>"></script>
  <script src="<?= base_url('assets/js/page/components-view-article.js'); ?>"></script>
</body>

</html>
<script>
  base_url = "<?php echo base_url(); ?>";
  csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
    csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
</script>