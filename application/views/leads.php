<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title><?= !empty($this->lang->line('label_leads')) ? $this->lang->line('label_leads') : 'Leads'; ?> &mdash; <?= get_compnay_title(); ?></title>

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
            <h1><?= !empty($this->lang->line('label_leads')) ? $this->lang->line('label_leads') : 'Leads'; ?></h1>
            <div class="section-header-breadcrumb">
              <div class="btn-group mr-2 no-shadow d-block">
                <a href="<?= base_url('leads') ?>" class="btn btn-primary text-white"><i class="fas fa-th-large"></i> <?= !empty($this->lang->line('label_grid_view')) ? $this->lang->line('label_grid_view') : 'Grid View'; ?></a>
                <a href="<?= base_url('leads/lists') ?>" class="btn"><i class="fas fa-list"></i> <?= !empty($this->lang->line('label_list_view')) ? $this->lang->line('label_list_view') : 'List View'; ?></a>
                <?php if (check_permissions("leads", "create")) { ?>
                  <i class="btn btn-primary btn-rounded no-shadow" id="modal-add-lead"><?= !empty($this->lang->line('label_create_lead')) ? $this->lang->line('label_create_lead') : 'Create Lead'; ?></i>
                <?php } ?>
              </div>
            </div>
          </div>
          <div class="section-body">
            <div class="row">
            <div class="col-md-12">
                <div class="row horizontal-scroll-cards board" data-plugin="dragula" data-containers="[&quot;lead-list-new&quot;,&quot;lead-list-qualified&quot;,&quot;lead-list-discussion&quot;,&quot;lead-list-won&quot;,&quot;lead-list-lost&quot;]">
                  <div class="tasks animated" data-sr-id="1">
                    <div class="mt-0 task-header text-uppercase"><?= !empty($this->lang->line('label_new')) ? $this->lang->line('label_new') : 'new'; ?> (<span class="count"><?= !empty($new) ? $new : 0; ?></span>)</div>
                    <div id="lead-list-new" data-status="new" class="task-list-items">

                      <?php foreach ($leads as $lead) {
                        // _pre1($lead);
                        if (($lead->status == 'new') && (in_array($user->id, $admin_ids) || in_array($user->id, explode(",", $lead->user_id)) || is_member() || is_client())) {
                      ?>

                          <div class="card mb-0" id="<?= $lead->id ?>">
                            <div class="card-body p-3">
                              <div class="card-header-action float-right">
                                <div class="dropdown card-widgets">
                                  <a href="#" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-cog"></i></a>
                                  <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item has-icon lead_status modal-edit-client-leads-ajax" href="#" data-id="<?= $lead->id ?>"><i class="fas fa-plus"></i><?= (!empty($this->lang->line('label_convert_to_client')) ? $this->lang->line('label_convert_to_client') : 'Convert to Client') ?></a>
                                    <a class="dropdown-item has-icon modal-edit-leads-ajax" data-id="<?= $lead->id ?>" href="#"><i class="fas fa-pencil-alt"></i> <?= !empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit'; ?></a>
                                    <a class="dropdown-item has-icon modal-duplicate-lead" data-id="<?= $lead->id ?>" href="#"><i class="fas fa-copy"></i> <?= !empty($this->lang->line('label_duplicate')) ? $this->lang->line('label_duplicate') : 'Duplicate'; ?></a>
                                    <a class="dropdown-item has-icon delete-leads-type-alert" data-lead_id="<?= $lead->id ?>" data-project_id="<?= $current_project->id ?>" href="#"><i class="far fa-trash-alt"></i> <?= !empty($this->lang->line('label_delete')) ? $this->lang->line('label_delete') : 'Delete'; ?></a>
                                  </div>
                                </div>
                              </div>
                              <div>
                                <a href="#" data-id="<?= $lead->id ?>" class="text-body modal-add-lead-details-ajax"><?= $lead->title ?></a>
                              </div>
                              <p class="mt-2 mb-2">
                                <span class="d-inline-block">
                                  <?= $lead->description ?>
                                </span>
                              </p>
                              <small class="float-right text-muted mt-2"><i class="text-primary fas fa-calendar-alt"></i> <?= $lead->assigned_date ?></small>

                              <?php $i = 0;
                              $j = 0;
                              $user_ids_array = explode(",", $lead->user_ids);

                              foreach ($user_ids_array as $lead_user) {
                                if ($i < 2) {
                                  if (isset($all_user[$i]->profile) && !empty($all_user[$i]->profile)) {
                              ?>
                                    <figure class="avatar mr-2 avatar-sm" data-toggle="tooltip" data-title="<?= $all_user[$i]->first_name ?>">
                                      <img alt="image" src="<?= base_url('assets/profiles/' . $all_user[$i]->profile); ?>" class="rounded-circle">
                                    </figure>
                                  <?php } else { ?>
                                    <figure data-toggle="tooltip" data-title="<?= $all_user[$i]->first_name ?>" class="avatar mr-2 avatar-sm" data-initial="<?= mb_substr($all_user[$i]->first_name, 0, 1) . '' . mb_substr($all_user[$i]->last_name, 0, 1); ?>">
                                    </figure>
                                  <?php }
                                } else {
                                  if ($j == 0) {
                                  ?>
                                    <figure data-toggle="tooltip" data-title="More" class="avatar mr-2 avatar-sm" data-initial="+">
                                    </figure>
                              <?php $j++;
                                  }
                                }
                                $i++;
                              } ?>

                            </div>
                          </div>
                      <?php }
                      } ?>

                    </div>
                  </div>

                  <div class="tasks animated" data-sr-id="2">
                    <div class="mt-0 task-header text-uppercase"><?= !empty($this->lang->line('label_qualified')) ? $this->lang->line('label_qualified') : 'Qualified'; ?> (<span class="count"><?= !empty($qualified) ? $qualified : 0; ?></span>)</div>
                    <div id="lead-list-qualified" data-status="qualified" class="task-list-items">

                      <?php foreach ($leads as $lead) {
                        if (($lead->status == 'qualified') && (in_array($user->id, $admin_ids) || in_array($user->id, explode(",", $lead->user_ids)) || is_member() || is_client())) {
                      ?>

                          <div class="card mb-0" id="<?= $lead->id ?>">
                            <div class="card-body p-3">
                              <div class="card-header-action float-right">
                                <div class="dropdown card-widgets">
                                  <a href="#" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-cog"></i></a>
                                  <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item has-icon lead_status modal-edit-client-leads-ajax" href="#" data-id="<?= $lead->id ?>"><i class="fas fa-plus"></i><?= (!empty($this->lang->line('label_convert_to_client')) ? $this->lang->line('label_convert_to_client') : 'Convert to Client') ?></a>
                                    <a class="dropdown-item has-icon modal-edit-leads-ajax" data-id="<?= $lead->id ?>" href="#"><i class="fas fa-pencil-alt"></i> <?= !empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit'; ?></a>
                                    <a class="dropdown-item has-icon modal-duplicate-lead" data-id="<?= $lead->id ?>" href="#"><i class="fas fa-copy"></i> <?= !empty($this->lang->line('label_duplicate')) ? $this->lang->line('label_duplicate') : 'Duplicate'; ?></a>
                                    <a class="dropdown-item has-icon delete-leads-type-alert" data-lead_id="<?= $lead->id ?>" data-project_id="<?= $current_project->id ?>" href="#"><i class="far fa-trash-alt"></i> <?= !empty($this->lang->line('label_delete')) ? $this->lang->line('label_delete') : 'Delete'; ?></a>
                                  </div>
                                </div>
                              </div>
                              <div>
                                <a href="#" data-id="<?= $lead->id ?>" class="text-body modal-add-lead-details-ajax"><?= $lead->title ?></a>
                              </div>
                              <p class="mt-2 mb-2">
                                <span class="d-inline-block">
                                  <?= $lead->description ?>
                                </span>
                              </p>
                              <small class="float-right text-muted mt-2"><i class="text-primary fas fa-calendar-alt"></i> <?= $lead->assigned_date ?></small>

                              <?php $i = 0;
                              $j = 0;
                              $user_ids_array = explode(",", $lead->user_ids);

                              foreach ($user_ids_array as $lead_user) {
                                if ($i < 2) {
                                  if (isset($all_user[$i]->profile) && !empty($all_user[$i]->profile)) {
                              ?>
                                    <figure class="avatar mr-2 avatar-sm" data-toggle="tooltip" data-title="<?= $all_user[$i]->first_name ?>">
                                      <img alt="image" src="<?= base_url('assets/profiles/' . $all_user[$i]->profile); ?>" class="rounded-circle">
                                    </figure>
                                  <?php } else { ?>
                                    <figure data-toggle="tooltip" data-title="<?= $all_user[$i]->first_name ?>" class="avatar mr-2 avatar-sm" data-initial="<?= mb_substr($all_user[$i]->first_name, 0, 1) . '' . mb_substr($all_user[$i]->last_name, 0, 1); ?>">
                                    </figure>
                                  <?php }
                                } else {
                                  if ($j == 0) {
                                  ?>
                                    <figure data-toggle="tooltip" data-title="More" class="avatar mr-2 avatar-sm" data-initial="+">
                                    </figure>
                              <?php $j++;
                                  }
                                }
                                $i++;
                              } ?>

                            </div>
                          </div>
                      <?php }
                      } ?>

                    </div>
                  </div>

                  <div class="tasks animated" data-sr-id="3">
                    <div class="mt-0 task-header text-uppercase"><?= !empty($this->lang->line('label_discussion')) ? $this->lang->line('label_discussion') : 'Discussion'; ?> (<span class="count"><?= !empty($discussion) ? $discussion : 0; ?></span>)</div>
                    <div id="lead-list-discussion" data-status="discussion" class="task-list-items">

                      <?php foreach ($leads as $lead) {
                        if (($lead->status == 'discussion') && (in_array($user->id, $admin_ids) || in_array($user->id, explode(",", $lead->user_ids)) || is_member() || is_client())) {
                      ?>

                          <div class="card mb-0" id="<?= $lead->id ?>">
                            <div class="card-body p-3">
                              <div class="card-header-action float-right">
                                <div class="dropdown card-widgets">
                                  <a href="#" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-cog"></i></a>
                                  <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item has-icon lead_status modal-edit-client-leads-ajax" href="#" data-id="<?= $lead->id ?>"><i class="fas fa-plus"></i><?= (!empty($this->lang->line('label_convert_to_client')) ? $this->lang->line('label_convert_to_client') : 'Convert to Client') ?></a>
                                    <a class="dropdown-item has-icon modal-edit-leads-ajax" data-id="<?= $lead->id ?>" href="#"><i class="fas fa-pencil-alt"></i> <?= !empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit'; ?></a>
                                    <a class="dropdown-item has-icon modal-duplicate-lead" data-id="<?= $lead->id ?>" href="#"><i class="fas fa-copy"></i> <?= !empty($this->lang->line('label_duplicate')) ? $this->lang->line('label_duplicate') : 'Duplicate'; ?></a>
                                    <a class="dropdown-item has-icon delete-leads-type-alert" data-lead_id="<?= $lead->id ?>" data-project_id="<?= $current_project->id ?>" href="#"><i class="far fa-trash-alt"></i> <?= !empty($this->lang->line('label_delete')) ? $this->lang->line('label_delete') : 'Delete'; ?></a>
                                  </div>
                                </div>
                              </div>
                              <div>
                                <a href="#" data-id="<?= $lead->id ?>" class="text-body modal-add-lead-details-ajax"><?= $lead->title ?></a>
                              </div>
                              <p class="mt-2 mb-2">
                                <span class="d-inline-block">
                                  <?= $lead->description ?>
                                </span>
                              </p>
                              <small class="float-right text-muted mt-2"><i class="text-primary fas fa-calendar-alt"></i> <?= $lead->assigned_date ?></small>

                              <?php $i = 0;
                              $j = 0;
                              $user_ids_array = explode(",", $lead->user_ids);

                              foreach ($user_ids_array as $lead_user) {
                                if ($i < 2) {
                                  if (isset($all_user[$i]->profile) && !empty($all_user[$i]->profile)) {
                              ?>
                                    <figure class="avatar mr-2 avatar-sm" data-toggle="tooltip" data-title="<?= $all_user[$i]->first_name ?>">
                                      <img alt="image" src="<?= base_url('assets/profiles/' . $all_user[$i]->profile); ?>" class="rounded-circle">
                                    </figure>
                                  <?php } else { ?>
                                    <figure data-toggle="tooltip" data-title="<?= $all_user[$i]->first_name ?>" class="avatar mr-2 avatar-sm" data-initial="<?= mb_substr($all_user[$i]->first_name, 0, 1) . '' . mb_substr($all_user[$i]->last_name, 0, 1); ?>">
                                    </figure>
                                  <?php }
                                } else {
                                  if ($j == 0) {
                                  ?>
                                    <figure data-toggle="tooltip" data-title="More" class="avatar mr-2 avatar-sm" data-initial="+">
                                    </figure>
                              <?php $j++;
                                  }
                                }
                                $i++;
                              } ?>

                            </div>
                          </div>
                      <?php }
                      } ?>

                    </div>
                  </div>

                  <div class="tasks animated" data-sr-id="4">
                    <div class="mt-0 task-header text-uppercase"><?= !empty($this->lang->line('label_won')) ? $this->lang->line('label_won') : 'Won'; ?> (<span class="count"><?= !empty($won) ? $won  : 0; ?></span>)</div>
                    <div id="lead-list-won" data-status="won " class="task-list-items">

                      <?php foreach ($leads as $lead) {
                        if (($lead->status == 'won') && (in_array($user->id, $admin_ids) || in_array($user->id, explode(",", $lead->user_ids)) || is_member() || is_client())) {
                      ?>

                          <div class="card mb-0" id="<?= $lead->id ?>">
                            <div class="card-body p-3">
                              <div class="card-header-action float-right">
                                <div class="dropdown card-widgets">
                                  <a href="#" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-cog"></i></a>
                                  <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item has-icon lead_status modal-edit-client-leads-ajax" href="#" data-id="<?= $lead->id ?>"><i class="fas fa-plus"></i><?= (!empty($this->lang->line('label_convert_to_client')) ? $this->lang->line('label_convert_to_client') : 'Convert to Client') ?></a>
                                    <a class="dropdown-item has-icon modal-edit-leads-ajax" data-id="<?= $lead->id ?>" href="#"><i class="fas fa-pencil-alt"></i> <?= !empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit'; ?></a>
                                    <a class="dropdown-item has-icon modal-duplicate-lead" data-id="<?= $lead->id ?>" href="#"><i class="fas fa-copy"></i> <?= !empty($this->lang->line('label_duplicate')) ? $this->lang->line('label_duplicate') : 'Duplicate'; ?></a>
                                    <a class="dropdown-item has-icon delete-leads-type-alert" data-lead_id="<?= $lead->id ?>" data-project_id="<?= $current_project->id ?>" href="#"><i class="far fa-trash-alt"></i> <?= !empty($this->lang->line('label_delete')) ? $this->lang->line('label_delete') : 'Delete'; ?></a>
                                  </div>
                                </div>
                              </div>
                              <div>
                                <a href="#" data-id="<?= $lead->id ?>" class="text-body modal-add-lead-details-ajax"><?= $lead->title ?></a>
                              </div>
                              <p class="mt-2 mb-2">
                                <span class="d-inline-block">
                                  <?= $lead->description ?>
                                </span>
                              </p>
                              <small class="float-right text-muted mt-2"><i class="text-primary fas fa-calendar-alt"></i> <?= $lead->assigned_date ?></small>

                              <?php $i = 0;
                              $j = 0;
                              $user_ids_array = explode(",", $lead->user_ids);

                              foreach ($user_ids_array as $lead_user) {
                                if ($i < 2) {
                                  if (isset($all_user[$i]->profile) && !empty($all_user[$i]->profile)) {
                              ?>
                                    <figure class="avatar mr-2 avatar-sm" data-toggle="tooltip" data-title="<?= $all_user[$i]->first_name ?>">
                                      <img alt="image" src="<?= base_url('assets/profiles/' . $all_user[$i]->profile); ?>" class="rounded-circle">
                                    </figure>
                                  <?php } else { ?>
                                    <figure data-toggle="tooltip" data-title="<?= $all_user[$i]->first_name ?>" class="avatar mr-2 avatar-sm" data-initial="<?= mb_substr($all_user[$i]->first_name, 0, 1) . '' . mb_substr($all_user[$i]->last_name, 0, 1); ?>">
                                    </figure>
                                  <?php }
                                } else {
                                  if ($j == 0) {
                                  ?>
                                    <figure data-toggle="tooltip" data-title="More" class="avatar mr-2 avatar-sm" data-initial="+">
                                    </figure>
                              <?php $j++;
                                  }
                                }
                                $i++;
                              } ?>

                            </div>
                          </div>
                      <?php }
                      } ?>

                    </div>
                  </div>

                  <div class="tasks animated" data-sr-id="5">
                    <div class="mt-0 task-header text-uppercase"><?= !empty($this->lang->line('label_lost')) ? $this->lang->line('label_lost') : 'Lost'; ?> (<span class="count"><?= !empty($lost) ? $lost : 0; ?></span>)</div>
                    <div id="lead-list-lost" data-status="lost" class="task-list-items">

                      <?php foreach ($leads as $lead) {
                        if (($lead->status == 'lost') && (in_array($user->id, $admin_ids) || in_array($user->id, explode(",", $lead->user_ids)) || is_member() || is_client())) {
                      ?>

                          <div class="card mb-0" id="<?= $lead->id ?>">
                            <div class="card-body p-3">
                              <div class="card-header-action float-right">
                                <div class="dropdown card-widgets">
                                  <a href="#" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-cog"></i></a>
                                  <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item has-icon lead_status modal-edit-client-leads-ajax" href="#" data-id="<?= $lead->id ?>"><i class="fas fa-plus"></i><?= (!empty($this->lang->line('label_convert_to_client')) ? $this->lang->line('label_convert_to_client') : 'Convert to Client') ?></a>
                                    <a class="dropdown-item has-icon modal-edit-leads-ajax" data-id="<?= $lead->id ?>" href="#"><i class="fas fa-pencil-alt"></i> <?= !empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit'; ?></a>
                                    <a class="dropdown-item has-icon modal-duplicate-lead" data-id="<?= $lead->id ?>" href="#"><i class="fas fa-copy"></i> <?= !empty($this->lang->line('label_duplicate')) ? $this->lang->line('label_duplicate') : 'Duplicate'; ?></a>
                                    <a class="dropdown-item has-icon delete-leads-type-alert" data-lead_id="<?= $lead->id ?>" data-project_id="<?= $current_project->id ?>" href="#"><i class="far fa-trash-alt"></i> <?= !empty($this->lang->line('label_delete')) ? $this->lang->line('label_delete') : 'Delete'; ?></a>
                                  </div>
                                </div>
                              </div>
                              <div>
                                <a href="#" data-id="<?= $lead->id ?>" class="text-body modal-add-lead-details-ajax"><?= $lead->title ?></a>
                              </div>
                              <p class="mt-2 mb-2">
                                <span class="d-inline-block">
                                  <?= $lead->description ?>
                                </span>
                              </p>
                              <small class="float-right text-muted mt-2"><i class="text-primary fas fa-calendar-alt"></i> <?= $lead->assigned_date ?></small>

                              <?php $i = 0;
                              $j = 0;
                              $user_ids_array = explode(",", $lead->user_ids);

                              foreach ($user_ids_array as $lead_user) {
                                if ($i < 2) {
                                  if (isset($all_user[$i]->profile) && !empty($all_user[$i]->profile)) {
                              ?>
                                    <figure class="avatar mr-2 avatar-sm" data-toggle="tooltip" data-title="<?= $all_user[$i]->first_name ?>">
                                      <img alt="image" src="<?= base_url('assets/profiles/' . $all_user[$i]->profile); ?>" class="rounded-circle">
                                    </figure>
                                  <?php } else { ?>
                                    <figure data-toggle="tooltip" data-title="<?= $all_user[$i]->first_name ?>" class="avatar mr-2 avatar-sm" data-initial="<?= mb_substr($all_user[$i]->first_name, 0, 1) . '' . mb_substr($all_user[$i]->last_name, 0, 1); ?>">
                                    </figure>
                                  <?php }
                                } else {
                                  if ($j == 0) {
                                  ?>
                                    <figure data-toggle="tooltip" data-title="More" class="avatar mr-2 avatar-sm" data-initial="+">
                                    </figure>
                              <?php $j++;
                                  }
                                }
                                $i++;
                              } ?>

                            </div>
                          </div>
                      <?php }
                      } ?>
                    </div>
                  </div>
                </div>
            </div>
          </div>
        </section>
      </div>
      <?php if (check_permissions("leads", "create")) { ?>
        <?= form_open(base_url('leads/create'), 'id="modal-add-lead-part"', 'class="modal-part"'); ?>
        <div id="modal-title" class="d-none"><?= !empty($this->lang->line('label_create_lead')) ? $this->lang->line('label_create_lead') : 'Create Lead'; ?></div>
        <div id="modal-footer-add-title" class="d-none"><?= !empty($this->lang->line('label_add')) ? $this->lang->line('label_add') : 'Add'; ?></div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label><?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?></label>
              <div class="input-group">
                <input type="text" class="form-control" placeholder=<?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?> name="title">
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label><?= !empty($this->lang->line('label_email')) ? $this->lang->line('label_email') : 'Email'; ?></label>
              <div class="input-group">
                <input type="hidden" name="group_id" value="3">
                <input type="email" class="demo-default" placeholder=<?= !empty($this->lang->line('label_email')) ? $this->lang->line('label_email') : 'Email'; ?> name="email" id="email">
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label><?= !empty($this->lang->line('label_phone')) ? $this->lang->line('label_phone') : 'Phone'; ?></label>
              <div class="input-group">
                <input type="number" class="form-control" placeholder=<?= !empty($this->lang->line('label_phone')) ? $this->lang->line('label_phone') : 'Phone'; ?> name="phone">
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></label>
              <select name="status" class="form-control">
                <option value="new"><?= !empty($this->lang->line('label_new')) ? $this->lang->line('label_new') : 'New'; ?></option>
                <option value="qualified"><?= !empty($this->lang->line('label_qualified')) ? $this->lang->line('label_qualified') : 'Qualified'; ?></option>
                <option value="discussion"><?= !empty($this->lang->line('label_discussion')) ? $this->lang->line('label_discussion') : 'Discussion'; ?></option>
                <option value="won"><?= !empty($this->lang->line('label_won')) ? $this->lang->line('label_won') : 'Won'; ?></option>
                <option value="lost"><?= !empty($this->lang->line('label_lost')) ? $this->lang->line('label_lost') : 'Lost'; ?></option>
              </select>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label><?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?></label>
              <div class="input-group">
                <textarea type="textarea" class="form-control" placeholder=<?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?> name="description"></textarea>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label><?= !empty($this->lang->line('label_assigned_date')) ? $this->lang->line('label_assigned_date') : 'Assigned Date'; ?></label>
              <div class="input-group">
                <input type="datetime-local" class="form-control" placeholder=<?= !empty($this->lang->line('label_assigned_date')) ? $this->lang->line('label_assigned_date') : 'Assigned Date'; ?> name="assigned_date">
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label><?= !empty($this->lang->line('label_select_assigned_users')) ? $this->lang->line('label_select_assigned_users') : 'Assigned'; ?></label>
              <select class="form-control select2" multipl="" name="users[]" id="users">
                <?php foreach ($all_user as $all_users) { ?>
                  <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
        </form>
      <?php } ?>

      <div class="modal-edit-leads"></div>

      <form action="<?= base_url('leads/edit'); ?>" method="post" class="modal-part" id="modal-edit-leads-part">
        <div id="modal-title" class="d-none"><?= !empty($this->lang->line('label_edit_lead')) ? $this->lang->line('label_edit_lead') : 'Edit Lead'; ?></div>
        <div id="modal-footer-edit-title" class="d-none"><?= !empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit'; ?></div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label><?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?></label>
              <div class="input-group">
                <input type="hidden" name="update_id" id="update_id">
                <input type="text" class="form-control" placeholder=<?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?> name="title" id="update_title">
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label><?= !empty($this->lang->line('label_email')) ? $this->lang->line('label_email') : 'Email'; ?></label>
              <div class="input-group">
                <input type="email" class="form-control" placeholder=<?= !empty($this->lang->line('label_email')) ? $this->lang->line('label_email') : 'Email'; ?> name="email" id="update_email" readonly>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label><?= !empty($this->lang->line('label_phone')) ? $this->lang->line('label_phone') : 'Phone'; ?></label>
              <div class="input-group">
                <input type="number" class="form-control" placeholder=<?= !empty($this->lang->line('label_phone')) ? $this->lang->line('label_phone') : 'Phone'; ?> name="phone" id="update_phone">
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="status"><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></label>
              <select name="status" class="form-control" id="update_status">
                <option value="new"><?= !empty($this->lang->line('label_new')) ? $this->lang->line('label_new') : 'New'; ?></option>
                <option value="qualified"><?= !empty($this->lang->line('label_qualified')) ? $this->lang->line('label_qualified') : 'Qualified'; ?></option>
                <option value="discussion"><?= !empty($this->lang->line('label_discussion')) ? $this->lang->line('label_discussion') : 'Discussion'; ?></option>
                <option value="won"><?= !empty($this->lang->line('label_won')) ? $this->lang->line('label_won') : 'Won'; ?></option>
                <option value="lost"><?= !empty($this->lang->line('label_lost')) ? $this->lang->line('label_lost') : 'Lost'; ?></option>
              </select>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label><?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?></label>
              <div class="input-group">
                <textarea type="textarea" id="update_description" class="form-control" placeholder=<?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?> name="description"></textarea>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label><?= !empty($this->lang->line('label_assigned_date')) ? $this->lang->line('label_assigned_date') : 'Assigned Date'; ?></label>
              <div class="input-group">
                <input type="datetime-local" id="update_assigned_date" class="form-control" placeholder=<?= !empty($this->lang->line('label_assigned_date')) ? $this->lang->line('label_assigned_date') : 'Assigned Date'; ?> name="assigned_date">
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label><?= !empty($this->lang->line('label_select_assigned_users')) ? $this->lang->line('label_select_assigned_users') : 'Assigned'; ?></label>
              <select class="form-control select2" multiple="" name="users[]" id="update_users">
                <?php foreach ($all_user as $all_users) { ?>
                  <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
    </div>
    </form>
    <div class="modal-edit-client-leads"></div>
    <?= form_open('auth/create_user', 'id="modal-edit-client-leads-part"', 'class="modal-part"'); ?>
    <input type="hidden" name="user_type" value="client">
    <input type="hidden" name="update_id" id="update_id">
    <input type="hidden" name="admin_id" value="<?= $this->session->userdata('user_id') ?>">
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label><?= !empty($this->lang->line('label_email')) ? $this->lang->line('label_email') : 'Email'; ?></label>
          <div class="input-group">
            <input type="email" name="email" class="form-control update_email" readonly>
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

      <div class="col-md-6" id="company">
        <div class="form-group">
          <label><?= !empty($this->lang->line('label_company')) ? $this->lang->line('label_company') : 'Company'; ?></label>
          <div class="input-group">
            <?= form_input(['name' => 'company', 'placeholder' => !empty($this->lang->line('label_company')) ? $this->lang->line('label_company') : 'Company', 'class' => 'form-control update_title']) ?>
          </div>
        </div>
      </div>

      <div class="col-md-6" id="phone">
        <div class="form-group">
          <label><?= !empty($this->lang->line('label_phone')) ? $this->lang->line('label_phone') : 'Phone'; ?></label>
          <div class="input-group">
            <?= form_input(['name' => 'phone', 'type' => 'number', 'placeholder' => !empty($this->lang->line('label_phone')) ? $this->lang->line('label_phone') : 'Phone', 'class' => 'form-control update_phone']) ?>
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
      <div class="col-md-12" id="address">
        <div class="form-group">
          <label for="address"><?= !empty($this->lang->line('label_address')) ? $this->lang->line('label_address') : 'Address'; ?></label>
          <textarea type="textarea" class="form-control" placeholder=!empty($this->lang->line('label_address')) ? $this->lang->line('label_address') : "Address" name="address" id="address"></textarea>
        </div>
      </div>
      <div class="col-md-6" id="city">
        <div class="form-group">
          <label><?= !empty($this->lang->line('label_city')) ? $this->lang->line('label_city') : 'City'; ?></label>
          <div class="input-group">
            <?= form_input(['name' => 'city', 'placeholder' => !empty($this->lang->line('label_city')) ? $this->lang->line('label_city') : 'City', 'class' => 'form-control']) ?>
          </div>
        </div>
      </div>
      <div class="col-md-6" id="state">
        <div class="form-group">
          <label><?= !empty($this->lang->line('label_state')) ? $this->lang->line('label_state') : 'State'; ?></label>
          <div class="input-group">
            <?= form_input(['name' => 'state', 'placeholder' => !empty($this->lang->line('label_state')) ? $this->lang->line('label_state') : 'State', 'class' => 'form-control']) ?>
          </div>
        </div>
      </div>
      <div class="col-md-6" id="zip_code">
        <div class="form-group">
          <label><?= !empty($this->lang->line('label_zip_code')) ? $this->lang->line('label_zip_code') : 'Zip Code'; ?></label>
          <div class="input-group">
            <?= form_input(['name' => 'zip_code', 'placeholder' => !empty($this->lang->line('label_zip_code')) ? $this->lang->line('label_zip_code') : 'Zip Code', 'class' => 'form-control', 'type' => 'number']) ?>
          </div>
        </div>
      </div>
      <div class="col-md-6" id="country">
        <div class="form-group">
          <label><?= !empty($this->lang->line('label_country')) ? $this->lang->line('label_country') : 'Country'; ?></label>
          <div class="input-group">
            <?= form_input(['name' => 'country', 'placeholder' => !empty($this->lang->line('label_country')) ? $this->lang->line('label_country') : 'Country', 'class' => 'form-control']) ?>
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
  <script src="<?= base_url('assets/js/page/components-leads.js'); ?>"></script>

</body>

</html>