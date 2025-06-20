<?php $data = get_system_settings('general');
if (!empty($data)) {
    $data = $data;
}
$primary_color = isset($data['primary_color']) && $data['primary_color'] != "" ?  $data['primary_color'] : '#6610F2';
$secondary_color = isset($data['secondary_color']) && $data['secondary_color'] != "" ?  $data['secondary_color'] : '#DC3545';
$rtl = is_rtl() ? 'rtl/' : '';
?>

<link rel="shortcut icon" href="<?= !empty($data['favicon']) ? base_url('assets/icons/' . $data['favicon']) : base_url('assets/icons/logo-half.png'); ?>">

<!-- General CSS Files -->

<link rel="stylesheet" href="<?= base_url('assets/modules/bootstrap/css/' . $rtl . 'bootstrap.min.css'); ?>">
<link rel="stylesheet" href="<?= base_url('assets/modules/fontawesome/css/all.min.css'); ?>">

<!-- CSS Libraries -->
<link rel="stylesheet" href="<?= base_url('assets/modules/dragula/dragula.min.css'); ?>">

<link rel="stylesheet" href="<?= base_url('assets/modules/bootstrap-daterangepicker/daterangepicker.css'); ?>">
<link rel="stylesheet" href="<?= base_url('assets/modules/select2/dist/css/select2.min.css'); ?>">
<link rel="stylesheet" href="<?= base_url('assets/modules/jqvmap/dist/jqvmap.min.css'); ?>">
<link rel="stylesheet" href="<?= base_url('assets/modules/summernote/summernote-bs4.css'); ?>">
<link rel="stylesheet" href="<?= base_url('assets/modules/owlcarousel2/dist/assets/owl.carousel.min.css'); ?>">
<link rel="stylesheet" href="<?= base_url('assets/modules/owlcarousel2/dist/assets/owl.theme.default.min.css'); ?>">

<link rel="stylesheet" href="<?= base_url('assets/modules/chocolat/dist/css/chocolat.css'); ?>">

<link rel="stylesheet" href="<?= base_url('assets/modules/dropzonejs/dropzone.css'); ?>">

<!-- filepond Css -->
<link href="<?= base_url('assets/filepond/dist/filepond.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url('assets/filepond/dist/filepond-plugin-image-preview.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url('assets/filepond/dist/filepond-plugin-pdf-preview.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url('assets/filepond/dist/filepond-plugin-media-preview.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url('assets/filepond/dist/filepond-plugin-media-preview.min.css'); ?>" rel="stylesheet" type="text/css" />
<!-- filepond Css -->

<link rel="stylesheet" href="<?= base_url('assets/modules/izitoast/css/iziToast.min.css'); ?>">

<link rel="stylesheet" href="<?= base_url('assets/modules/selectize/selectize.min.css'); ?>">
<link rel="stylesheet" href="<?= base_url('assets/modules/cropper/cropper.min.css'); ?>">
<link rel="stylesheet" href="<?= base_url('assets/modules/bootstrap-table/bootstrap-table.min.css'); ?>">
<link rel="stylesheet" href="<?= base_url('assets/modules/lightbox/lightbox.min.css'); ?>">

<link rel="stylesheet" href="<?= base_url('assets/fullcalendar/core/main.css'); ?>">
<link rel="stylesheet" href="<?= base_url('assets/fullcalendar/daygrid/main.css'); ?>">
<link rel="stylesheet" href="<?= base_url('assets/fullcalendar/list/main.css'); ?>">

<!-- Todolist -->
<link rel="stylesheet" href="<?= base_url('assets/css/todolist.css'); ?>">

<!-- Template CSS -->
<link rel="stylesheet" href="<?= base_url('assets/css/' . $rtl . 'style.css'); ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/' . $rtl . 'components.css'); ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/' . $rtl . 'custom.css'); ?>">

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato&display=swap">
<?php $my_system_fonts = get_system_fonts();
if ($my_system_fonts != 'default' && !empty($my_system_fonts->id) && !empty($my_system_fonts->font_cdn) && !empty($my_system_fonts->font_name) && !empty($my_system_fonts->font_family) && !empty($my_system_fonts->class)) { ?>
    <link rel="stylesheet" href="<?= $my_system_fonts->font_cdn ?>">
    <style>
        body {
            font-family: <?= $my_system_fonts->font_family ?>;
        }
    </style>
<?php } ?>


<style>
    body {
        --primary-color: <?= $primary_color ?>;
        --secondary-color: <?= $secondary_color ?>;
    }
</style>
<!-- Firebase JavaScript SDK -->
<!-- <script src="https://www.gstatic.com/firebasejs/9.5.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.5.0/firebase-messaging.js"></script> -->
<!-- <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script> -->
<!-- <script async type="module" src="<?= base_url('firebase-config.js'); ?>"></script> -->

<script>
    base_url = "<?php echo base_url(); ?>";
    csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
        csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
</script>