<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Invoices-report &mdash;<?= get_compnay_title(); ?></title>
<style>
    .grey-bg {  
    background-color: #F5F7FA;
}

</style>
<link rel="stylesheet" type="text/css" href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/css/bootstrap-extended.min.css">
    <?php include('include-css.php'); ?>
</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <?php include('include-header.php'); ?>

            <!-- Main Content -->
            <div class="main-content">
            <div class="row">
                        <?php if (check_permissions("projects", "read")) { ?>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                <div class="card overflow-hidden">
                                    <div class="card-content">
                                        <div class="card-body cleartfix">
                                            <div class="media align-items-stretch">
                                                <div class="align-self-center">
                                                    <i class="fas fa-briefcase text-primary font-large-2 mr-2"></i>
                                                </div>
                                                <div class="media-body">
                                                    <h4><?= !empty($this->lang->line('label_total_projects')) ? $this->lang->line('label_total_projects') : 'Total Projects'; ?></h4>
                                                </div>
                                                <div class="align-self-center">
                                                    <h1> <?= $total_project ?></h1>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (check_permissions("tasks", "read")) { ?>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="card-body cleartfix">
                                            <div class="media align-items-stretch">
                                                <div class="align-self-center">
                                                    <i class="fas fa-newspaper text-warning font-large-2 mr-2"></i>
                                                </div>
                                                <div class="media-body">
                                                    <h5><?= !empty($this->lang->line('label_total_tasks')) ? $this->lang->line('label_total_tasks') : 'Total Tasks'; ?></h5>
                                                </div>
                                                <div class="align-self-center">
                                                    <h1><?= $total_task ?></h1>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (check_permissions("users", "read")) { ?>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="card-body cleartfix">
                                            <div class="media align-items-stretch">
                                                <div class="align-self-center">
                                                    <i class="fas fa-user text-danger font-large-2 mr-2"></i>
                                                </div>
                                                <div class="media-body">
                                                    <h4><?= !empty($this->lang->line('label_users')) ? $this->lang->line('label_users') : 'Users'; ?></h4>
                                                </div>
                                                <div class="align-self-center">
                                                    <h1><?= $total_user ?></h1>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (check_permissions("notes", "read")) { ?>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="card-body cleartfix">
                                            <div class="media align-items-stretch">
                                                <div class="align-self-center">
                                                    <i class="icon-wallet success font-large-2 mr-2"></i>
                                                </div>
                                                <div class="media-body">
                                                    <h4><?= !empty($this->lang->line('label_clients')) ? $this->lang->line('label_clients') : 'Clients'; ?></h4>
                                                </div>
                                                <div class="align-self-center">
                                                    <h1><?= $total_client ?></h1>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
            <div class="row">
                                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-mid-dark">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total Projects</h4>
                                    </div>
                                    <div class="card-body">
                                        48                                    </div>
                                </div>
                            </div>
                        </div>
                                                                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-mid-dark">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total Tasks</h4>
                                    </div>
                                    <div class="card-body">
                                        48                                    </div>
                                </div>
                            </div>
                        </div>
                                                                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-mid-dark">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Users</h4>
                                    </div>
                                    <div class="card-body">
                                        4                                    </div>
                                </div>
                            </div>
                        </div>
                                                                                                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                <div class="card card-statistic-1">
                                    <div class="card-icon bg-mid-dark">
                                        <i class="fas fa-sticky-note"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>Clients</h4>
                                        </div>
                                        <div class="card-body">
                                            3                                        </div>
                                    </div>
                                </div>
                            </div>

                                                  
                    </div>
            <div class="grey-bg container-fluid">
  <section id="minimal-statistics">
    <div class="row">
      <div class="col-12 mt-3 mb-1">
        <h4 class="text-uppercase">Minimal Statistics Cards</h4>
        <p>Statistics on minimal cards.</p>
      </div>
    </div>
    <div class="row">
      <div class="col-xl-3 col-sm-6 col-12"> 
        <div class="card">
          <div class="card-content">
            <div class="card-body">
              <div class="media d-flex">
                <div class="align-self-center">
                  <i class="icon-pencil primary font-large-2 float-left"></i>
                </div>
                <div class="media-body text-right">
                  <h3>278</h3>
                  <span>New Posts</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 col-12">
        <div class="card">
          <div class="card-content">
            <div class="card-body">
              <div class="media d-flex">
                <div class="align-self-center">
                  <i class="icon-speech warning font-large-2 float-left"></i>
                </div>
                <div class="media-body text-right">
                  <h3>156</h3>
                  <span>New Comments</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 col-12">
        <div class="card">
          <div class="card-content">
            <div class="card-body">
              <div class="media d-flex">
                <div class="align-self-center">
                  <i class="icon-graph success font-large-2 float-left"></i>
                </div>
                <div class="media-body text-right">
                  <h3>64.89 %</h3>
                  <span>Bounce Rate</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 col-12">
        <div class="card">
          <div class="card-content">
            <div class="card-body">
              <div class="media d-flex">
                <div class="align-self-center">
                  <i class="icon-pointer danger font-large-2 float-left"></i>
                </div>
                <div class="media-body text-right">
                  <h3>423</h3>
                  <span>Total Visits</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  
    <div class="row">
      <div class="col-xl-3 col-sm-6 col-12">
        <div class="card">
          <div class="card-content">
            <div class="card-body">
              <div class="media d-flex">
                <div class="media-body text-left">
                  <h3 class="danger">278</h3>
                  <span>New Projects</span>
                </div>
                <div class="align-self-center">
                  <i class="icon-rocket danger font-large-2 float-right"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 col-12">
        <div class="card">
          <div class="card-content">
            <div class="card-body">
              <div class="media d-flex">
                <div class="media-body text-left">
                  <h3 class="success">156</h3>
                  <span>New Clients</span>
                </div>
                <div class="align-self-center">
                  <i class="icon-user success font-large-2 float-right"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <div class="col-xl-3 col-sm-6 col-12">
        <div class="card">
          <div class="card-content">
            <div class="card-body">
              <div class="media d-flex">
                <div class="media-body text-left">
                  <h3 class="warning">64.89 %</h3>
                  <span>Conversion Rate</span>
                </div>
                <div class="align-self-center">
                  <i class="icon-pie-chart warning font-large-2 float-right"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 col-12">
        <div class="card">
          <div class="card-content">
            <div class="card-body">
              <div class="media d-flex">
                <div class="media-body text-left">
                  <h3 class="primary">423</h3>
                  <span>Support Tickets</span>
                </div>
                <div class="align-self-center">
                  <i class="icon-support primary font-large-2 float-right"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  
    <div class="row">
      <div class="col-xl-3 col-sm-6 col-12">
        <div class="card">
          <div class="card-content">
            <div class="card-body">
              <div class="media d-flex">
                <div class="media-body text-left">
                  <h3 class="primary">278</h3>
                  <span>New Posts</span>
                </div>
                <div class="align-self-center">
                  <i class="icon-book-open primary font-large-2 float-right"></i>
                </div>
              </div>
              <div class="progress mt-1 mb-0" style="height: 7px;">
                <div class="progress-bar bg-primary" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 col-12">
        <div class="card">
          <div class="card-content">
            <div class="card-body">
              <div class="media d-flex">
                <div class="media-body text-left">
                  <h3 class="warning">156</h3>
                  <span>New Comments</span>
                </div>
                <div class="align-self-center">
                  <i class="icon-bubbles warning font-large-2 float-right"></i>
                </div>
              </div>
              <div class="progress mt-1 mb-0" style="height: 7px;">
                <div class="progress-bar bg-warning" role="progressbar" style="width: 35%" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <div class="col-xl-3 col-sm-6 col-12">
        <div class="card">
          <div class="card-content">
            <div class="card-body">
              <div class="media d-flex">
                <div class="media-body text-left">
                  <h3 class="success">64.89 %</h3>
                  <span>Bounce Rate</span>
                </div>
                <div class="align-self-center">
                  <i class="icon-cup success font-large-2 float-right"></i>
                </div>
              </div>
              <div class="progress mt-1 mb-0" style="height: 7px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 col-12">
        <div class="card">
          <div class="card-content">
            <div class="card-body">
              <div class="media d-flex">
                <div class="media-body text-left">
                  <h3 class="danger">423</h3>
                  <span>Total Visits</span>
                </div>
                <div class="align-self-center">
                  <i class="icon-direction danger font-large-2 float-right"></i>
                </div>
              </div>
              <div class="progress mt-1 mb-0" style="height: 7px;">
                <div class="progress-bar bg-danger" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <section id="stats-subtitle">
  <div class="row">
    <div class="col-12 mt-3 mb-1">
      <h4 class="text-uppercase">Statistics With Subtitle</h4>
      <p>Statistics on minimal cards with Title &amp; Sub Title.</p>
    </div>
  </div>

  <div class="row">
    <div class="col-xl-6 col-md-12">
      <div class="card overflow-hidden">
        <div class="card-content">
          <div class="card-body cleartfix">
            <div class="media align-items-stretch">
              <div class="align-self-center">
                <i class="fas fa-briefcase primary font-large-2 mr-2"></i>
              </div>
              <div class="media-body">
                <h4>Total Projects</h4>
              </div>
              <div class="align-self-center">
                <h1>48</h1>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-6 col-md-12">
      <div class="card">
        <div class="card-content">
          <div class="card-body cleartfix">
            <div class="media align-items-stretch">
              <div class="align-self-center">
                <i class="icon-speech warning font-large-2 mr-2"></i>
              </div>
              <div class="media-body">
                <h4>Total Comments</h4>
                <span>Monthly blog comments</span>
              </div>
              <div class="align-self-center"> 
                <h1>84,695</h1>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xl-6 col-md-12">
      <div class="card">
        <div class="card-content">
          <div class="card-body cleartfix">
            <div class="media align-items-stretch">
              <div class="align-self-center">
                <h1 class="mr-2">$76,456.00</h1>
              </div>
              <div class="media-body">
                <h4>Total Sales</h4>
                <span>Monthly Sales Amount</span>
              </div>
              <div class="align-self-center">
                <i class="icon-heart danger font-large-2"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-6 col-md-12">
      <div class="card">
        <div class="card-content">
          <div class="card-body cleartfix">
            <div class="media align-items-stretch">
              <div class="align-self-center">
                <h1 class="mr-2">$36,000.00</h1>
              </div>
              <div class="media-body">
                <h4>Total Cost</h4>
                <span>Monthly Cost</span>
              </div>
              <div class="align-self-center">
                <i class="icon-wallet success font-large-2"></i>
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

            </section>
        </div>

        <?php include('include-footer.php'); ?>
    </div>
    </div>

    <?php include('include-js.php'); ?>

    <script src="<?= base_url('assets/js/page/components-invoice-report.js'); ?>"></script>

</html>