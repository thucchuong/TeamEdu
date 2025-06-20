<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>test &mdash; <?= get_compnay_title(); ?></title>
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
                        <h1><?= !empty($this->lang->line('label_screenshot')) ? $this->lang->line('label_screenshot') : 'Screenshot' ?></h1>
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class='col-md-12'>
                                <div class="card">
                                    <div class="card-body">
                                        <i class="btn btn-primary btn-rounded no-shadow" id="startCaptureBtn"><?= !empty($this->lang->line('label_start_screenshot')) ? $this->lang->line('label_start_screenshot') : 'Start Screenshot' ?></i><br />
                                        <i class="btn btn-primary btn-rounded no-shadow" style="display: none" id="stopCaptureBtn"><?= !empty($this->lang->line('label_stop_screenshot')) ? $this->lang->line('label_stop_screenshot') : 'Stop Screenshot' ?></i><br />
                                        <canvas id="canvas" style="display: none"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <?php
            require_once(APPPATH . 'views/include-footer.php');
            ?>
        </div>
    </div>
    <?php
    require_once(APPPATH . 'views/include-js.php');
    ?>
    <script src="assets/js/page/components-screenshot.js"></script>
</body>

</html>