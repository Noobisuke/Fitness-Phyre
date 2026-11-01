<?php
require_once __DIR__ . '/../includes/admin_auth.php';
require_admin();
admin_handle_record_action('trials', 'trials.php');

$admin_title = 'Trial requests';
$key = 'trials'; $self = 'trials.php'; $heading = 'Trial requests';
$columns = ['Name' => 'name', 'Phone' => 'phone', 'Email' => 'email', 'Date' => 'preferred_date', 'Time' => 'preferred_time', 'Goal' => 'fitness_goal', 'Message' => 'message'];

include __DIR__ . '/../includes/admin_header.php';
include __DIR__ . '/../includes/admin_records.php';
include __DIR__ . '/../includes/admin_footer.php';
