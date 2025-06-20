<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= !empty($this->lang->line('label_todos')) ? $this->lang->line('label_todos') : 'Todos'; ?> &mdash; <?= get_compnay_title(); ?></title>
    <?php include('include-css.php'); ?>

</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">

            <?php include('include-header.php'); ?>

            <!-- Main Content -->
            <!-- Todolist header -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1><?= !empty($this->lang->line('label_todos')) ? $this->lang->line('label_todos') : 'Todos'; ?></h1>
                    </div>
                    <!-- /Todolist -->

                    <!-- Todotable -->

                    <div class="section-body">
                        <div class="page-content page-container" id="page-content">
                            <div class="padding:0">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card px-3">
                                            <div class="card-body">
                                                <form action="<?= base_url('todo/add_todo_list') ?>" id="add_todo">

                                                    <input type="hidden" name="<?= $this->security->get_csrf_token_name();
                                                                                ?>" class="form-control" value="<?= $this->security->get_csrf_hash(); ?>">
                                                    <h4 class="card-title"><?= !empty($this->lang->line('label_todos')) ? $this->lang->line('label_todos') : 'Todos'; ?></h4>
                                                    <div class="add-items checkbox">
                                                        <?php if ((check_permissions("todos", "create")) || (check_permissions("todos", "update"))) { ?>
                                                            <div class="row">
                                                                <div class="col-md-9">
                                                                    <input type="hidden" name="id" id="todo_id">
                                                                    <input type="text" class="form-control todo-list-input" placeholder="<?= !empty($this->lang->line('label_add_todos')) ? $this->lang->line('label_add_todos') : ' Add Todo list'; ?>" name="description" id="todo_text" value="">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <button id="add" type="submit" class="add btn btn-primary font-weight-bold todo-list-add btn-lg"><?= !empty($this->lang->line('label_add')) ? $this->lang->line('label_add') : 'Add'; ?></button>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </form>
                                                <div id="tolist" class="tolist mt-5">

                                                    <?php if (!empty($lists['rows'])) : ?>
                                                        <div class="sort ">
                                                            <?php foreach ($lists['rows'] as $list) : ?>

                                                                <div class='form-check list-wrapper' draggable="true" id=" <?= $list['id'] ?>">
                                                                    <div class="row grabber">
                                                                        <div class="col-md-10">
                                                                            <div class="row h5 align-items-center">
                                                                                <div class="dragger todo-dragger"></div>
                                                                                <input class='checkbox check_test ml-3' name="<?= $list['id'] ?>" type='checkbox' <?= (isset($list['status']) && $list['status'] == '1') ? 'Checked' : '' ?> onclick='update_status(this)' id="<?= $list['id'] ?>" position="<?= $list['position'] ?>">
                                                                                <div class="d-block">
                                                                                    <label for="<?= $list['id'] ?>">
                                                                                        <?php $status = (isset($list['status']) && $list['status'] == '1') ? '1' : '' ?>
                                                                                        <p class="ml-3 mb-0" id="desc-<?= $list['id'] ?>">
                                                                                            <?= ($status == 1) ? "<s class='text-primary'>" . $list['description'] . "</s>" : $list['description']; ?>
                                                                                        </p>
                                                                                    </label>
                                                                                    <div class="text-small text-primary">
                                                                                        <i class="text-muted fas fa-calendar-alt ml-2"></i> <?= $list['created_at'] ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <hr>
                                                                        </div>
                                                                        <div class="col-md-2 mt-3">
                                                                            <?php if (check_permissions("todos", "update")) { ?>
                                                                                <div onclick='edit_task(this)' data-description="<?= $list['description']  ?>" id="<?= $list['id'] ?>" class="btn btn-primary btn-sm">
                                                                                    <i class='fa fa-pen text-white'></i>
                                                                                </div>
                                                                            <?php } ?>
                                                                            <?php if (check_permissions("todos", "delete")) { ?>
                                                                                <div onclick='remove(this)' id="<?= $list['id'] ?>" class="btn btn-danger btn-sm">
                                                                                    <i class='fa fa-trash  text-white'></i>
                                                                                </div>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>

                                                    <?php else :  ?>
                                                        <div class="text-center empty-todos" data-type="empty-todos">
                                                            <h5 class="text-primary"><i class="fa fa-list"></i> <?= !empty($this->lang->line('label_no_todo_list')) ? $this->lang->line('label_no_todo_list') : 'No Todos created yet! Create your first Todo now'; ?></h5>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            </section>
            <!-- </div> -->
            <?php include('include-footer.php'); ?>
        </div>
    </div>

    <?php include('include-js.php'); ?>
    <!-- Page Specific JS File -->
    <!--  -->
</body>

</html>