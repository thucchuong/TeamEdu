<?php defined('BASEPATH') or exit('No direct script access allowed');

$config['progress_bar_classes']['todo'] = 'info';
$config['progress_bar_classes']['inprogress'] = 'danger';
$config['progress_bar_classes']['review'] = 'warning';
$config['progress_bar_classes']['done']  = 'success';

$config['allowed_types'] = 'webp|gif|jpg|jpeg|png|bmp|eps|mp4|mp3|wav|3gp|avchd|avi|flv|mkv|mov|webm|wmv|mpg|mpeg|ogg|doc|docx|txt|pdf|ppt|pptx|xls|xsls|zip|7z|bz2|gz|gzip|rar|tar';

$config['type'] = array(
    'image' => array(
        'types' => array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'eps','webp'),
        'icon' => ''
    ),
    'video' => array(
        'types' => array('mp4', '3gp', 'avchd', 'avi', 'flv', 'mkv', 'mov', 'webm', 'wmv', 'mpg', 'mpeg', 'ogg'),
        'icon' => 'assets/admin/images/video-file.png'
    ),
    'document' => array(
        'types' => array('doc', 'docx', 'txt', 'pdf', 'ppt', 'pptx'),
        'icon' => 'assets/admin/images/doc-file.png'
    ),
    'spreadsheet' => array(
        'types' => array('xls', 'xsls'),
        'icon' => 'assets/admin/images/xls-file.png'
    ),
    'archive' => array(
        'types' => array('zip', '7z', 'bz2', 'gz', 'gzip', 'rar', 'tar'),
        'icon' => 'assets/admin/images/zip-file.png'
    )
);

$config['system_modules'] = [
    'projects' =>  array('create', 'read', 'update', 'delete'),
    'statuses' =>  array('create', 'read', 'update', 'delete'),
    'milestone' =>  array('create', 'read', 'update', 'delete'),
    'tasks' =>  array('create', 'read', 'update', 'delete'),
    'calendar' =>  array('create', 'read', 'update'),
    'payslips' =>  array('create', 'read', 'update', 'delete'),
    'chat' =>  array('create', 'read', 'delete'),
    'users' =>  array('create', 'read', 'update', 'delete'),
    'clients' =>  array('create', 'read', 'update', 'delete'),
    'leave_requests' =>  array('create', 'read', 'update'),
    'notes' =>  array('create', 'read', 'update', 'delete'),
    'announcements' =>  array('create', 'read', 'update', 'delete'),
    'knowledgebase' =>  array('create', 'read', 'update', 'delete'),
    'meetings' =>  array('create', 'read', 'update', 'delete'),
    'time_tracker' =>  array('create', 'read'),
    'leads' =>  array('create', 'read', 'update', 'delete'),
    'todos' =>  array('create', 'read', 'update', 'delete'),
    'contracts' =>  array('create', 'read', 'update', 'delete'),
    'upcoming_birthdays' =>  array('read'),
    'upcoming_work_anniversaries' =>  array('read'),
    'members_on_leave' =>  array('read'),
];
