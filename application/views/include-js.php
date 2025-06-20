<!-- General JS Scripts -->
<script src="<?= base_url('assets/modules/jquery.min.js'); ?>"></script>
<script src="<?= base_url('assets/modules/popper.js'); ?>"></script>
<script src="<?= base_url('assets/modules/tooltip.js'); ?>"></script>
<script src="<?= base_url('assets/modules/bootstrap/js/' . $rtl . 'bootstrap.min.js'); ?>"></script>
<script src="<?= base_url('assets/modules/nicescroll/jquery.nicescroll.min.js'); ?>"></script>
<script src="<?= base_url('assets/modules/moment.min.js'); ?>"></script>
<script src="<?= base_url('assets/modules/select2/dist/js/select2.full.min.js'); ?>"></script>
<script src="<?= base_url('assets/modules/bootstrap-daterangepicker/daterangepicker.js'); ?>"></script>
<script src="<?= base_url('assets/js/stisla.js'); ?>"></script>

<!-- JS Libraies -->
<script src="<?= base_url('assets/modules/jquery.sparkline.min.js'); ?>"></script>
<script src="<?= base_url('assets/modules/chart.min.js'); ?>"></script>
<script src="<?= base_url('assets/modules/owlcarousel2/dist/owl.carousel.min.js'); ?>"></script>
<script src="<?= base_url('assets/modules/summernote/summernote-bs4.js'); ?>"></script>
<script src="<?= base_url('assets/modules/chocolat/dist/js/jquery.chocolat.min.js'); ?>"></script>

<script src="<?= base_url('assets/modules/dropzonejs/min/dropzone.min.js'); ?>"></script>
<script src="<?= base_url('assets/modules/selectize/selectize.min.js'); ?>"></script>

<script src="<?= base_url('assets/modules/dragula/dragula.min.js'); ?>"></script>

<script src="<?= base_url('assets/modules/pagedown/Markdown.Converter.js'); ?>"></script>
<script src="<?= base_url('assets/modules/pagedown/Markdown.Sanitizer.js'); ?>"></script>
<script src="<?= base_url('assets/modules/pagedown/Markdown.Editor.js'); ?>"></script>

<script src="<?= base_url('assets/modules/izitoast/js/iziToast.min.js'); ?>"></script>

<script src="<?= base_url('assets/js/page/modules-toastr.js'); ?>"></script>
<script src="<?= base_url('assets/modules/sweetalert/sweetalert.min.js'); ?>"></script>
<script src="<?= base_url('assets/modules/cropper/cropper.min.js'); ?>"></script>

<!-- Bootstrap table -->
<script src="<?= base_url('assets/modules/bootstrap-table/bootstrap-table.min.js'); ?>"></script>
<script src="<?= base_url('assets/modules/bootstrap-table/bootstrap-table-mobile.js'); ?>"></script>
<script src="<?= base_url('assets/modules/lightbox/lightbox.min.js'); ?>"></script>

<!-- <script type="module" src="<?= base_url('assets/modules/firebase/firebase-app.js'); ?>"></script>
<script type="module" src="<?= base_url('assets/modules/firebase/firebase-messaging.js'); ?>"></script> -->
<script type="module" src="<?= base_url('firebase-config.js'); ?>"></script>



<!-- Export table JS -->
<script src="<?= base_url('assets/js/tableExport.js'); ?>"></script>

<!-- Export table bootstrap min.js -->
<script src="<?= base_url('assets/js/bootstrap-table-export.min.js'); ?>"></script>

<script src="<?= base_url('assets/fullcalendar/core/main.js'); ?>"></script>
<script src="<?= base_url('assets/fullcalendar/interaction/main.js'); ?>"></script>
<script src="<?= base_url('assets/fullcalendar/daygrid/main.js'); ?>"></script>
<script src="<?= base_url('assets/fullcalendar/list/main.js'); ?>"></script>
<script src="<?= base_url('assets/fullcalendar/google-calendar/main.js'); ?>"></script>
<script src="<?= base_url('assets/libs/jquery.validate.min.js'); ?>"></script>

<script src="<?= base_url('assets/js/jquery.atwho.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/jquery.caret.min.js'); ?>"></script>

<!-- Todolist -->
<script src="<?= base_url('assets/js/todolist.js'); ?>"></script>

<!-- Filepond -->

 <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
 <script src="<?= base_url('assets/filepond/dist/filepond.min.js') ?>"></script>
 <script src="<?= base_url('assets/filepond/dist/filepond-plugin-image-preview.min.js') ?>"></script>
 <script src="<?= base_url('assets/filepond/dist/filepond-plugin-pdf-preview.min.js') ?>"></script>
 <script src="<?= base_url('assets/filepond/dist/filepond-plugin-file-validate-size.js') ?>"></script>
 <script src="<?= base_url('assets/filepond/dist/filepond-plugin-file-validate-type.js') ?>"></script>
 <script src="<?= base_url('assets/filepond/dist/filepond-plugin-image-validate-size.js') ?>"></script>
 <script src="<?= base_url('assets/filepond/dist/filepond.jquery.js') ?>"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.8.4/tinymce.min.js"></script>

<!-- Sortsble JS -->
<script src="<?= base_url('assets/js/sortable.js'); ?>"></script>
<script src="<?= base_url('assets/js/sortable.min.js'); ?>"></script>

<!-- jquery UI JS -->
<script src="<?= base_url('assets/js/page/jquery-ui.js'); ?>"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<!-- Time tracker JS -->
<script src="<?= base_url('assets/js/page/time_tracker.js'); ?>"></script>
<script src="<?= base_url('assets/js/timer.js'); ?>"></script>

<script src="<?= base_url('assets/js/scripts.js'); ?>"></script>
<script src="<?= base_url('assets/js/custom.js'); ?>"></script>
<script src="<?= base_url('assets/js/common.js'); ?>"></script>


<?php if ($this->session->flashdata('message')) { ?>
    <script>
        iziToast.<?= $this->session->flashdata('message_type'); ?>({
            title: "<?= $this->session->flashdata('message'); ?>",
            message: '',
            position: 'topRight'
        });
    </script>
<?php } ?>

<!-- Jquery sortable -->
<script src="<?= base_url('assets/js/page/jquery-sortable.js'); ?>"></script>