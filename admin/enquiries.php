<?php
require_once __DIR__ . '/../includes/admin_auth.php';
require_admin();
admin_handle_record_action('enquiries', 'enquiries.php');

$admin_title = 'Enquiries';
$key = 'enquiries'; $self = 'enquiries.php'; $heading = 'Enquiries';
$columns = ['Name' => 'name', 'Phone' => 'phone', 'Email' => 'email', 'Interested in' => 'interested_in', 'Preferred time' => 'preferred_time', 'Message' => 'message'];

include __DIR__ . '/../includes/admin_header.php';
include __DIR__ . '/../includes/admin_records.php';
include __DIR__ . '/../includes/admin_footer.php';
