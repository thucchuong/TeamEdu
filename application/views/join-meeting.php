<html>

<head>
    <title>Join meeting &mdash; <?= get_compnay_title(); ?></title>
    <?php
    $data = get_system_settings('general');

    if (!empty($data)) {
        $data = json_decode(isset($data[0]['data']));
    }
    ?>
    <link rel="shortcut icon" href="<?= !empty($data->favicon) ? base_url('assets/icons/' . $data->favicon) : base_url('assets/icons/logo-half.png'); ?>">
</head>

<body>
    <div id="meet" />
</body>
<input type="hidden" name="room_name" id="room_name" value="<?= $room_name ?>">
<input type="hidden" name="user_name" id="user_name" value="<?= $user_display_name ?>">
<input type="hidden" name="user_email" id="user_email" value="<?= $user_email ?>">
<input type="hidden" name="meeting_id" id="meeting_id" value="<?= $meeting_id ?>">
<input type="hidden" name="is_meeting_admin" id="is_meeting_admin" value="<?= $is_meeting_admin ?>">
<input type="hidden" name="base_url" id="base_url" value="<?= base_url(); ?>">

</html>
<script src="<?= base_url('assets/modules/jquery.min.js'); ?>"></script>
<script src='https://8x8.vc/external_api.js'></script>
<script src="<?= base_url('assets/js/page/components-join-meeting.js'); ?>"></script>