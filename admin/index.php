<?php
require_once __DIR__ . '/../includes/admin_auth.php';
require_admin();

$counts = ['enq_new' => 0, 'enq_all' => 0, 'tri_new' => 0, 'tri_all' => 0];
$dbError = false;
$pdo = db();
if ($pdo) {
    try {
        $counts['enq_new'] = (int)$pdo->query('SELECT COUNT(*) FROM enquiries WHERE is_contacted = 0')->fetchColumn();
        $counts['enq_all'] = (int)$pdo->query('SELECT COUNT(*) FROM enquiries')->fetchColumn();
        $counts['tri_new'] = (int)$pdo->query('SELECT COUNT(*) FROM trial_requests WHERE is_contacted = 0')->fetchColumn();
        $counts['tri_all'] = (int)$pdo->query('SELECT COUNT(*) FROM trial_requests')->fetchColumn();
    } catch (PDOException $e) {
        error_log('Admin dashboard failed: ' . $e->getMessage());
        $dbError = true;
    }
} else {
    $dbError = true;
}

$admin_title = 'Dashboard';
include __DIR__ . '/../includes/admin_header.php';
?>
<h1 class="admin-h1">Dashboard</h1>
<?php if ($dbError): ?><div class="alert alert-error" role="alert">Could not read the database. Check config/db.php.</div><?php endif; ?>

<div class="grid grid-2">
    <a class="card stat" href="<?= e(url('admin/enquiries.php?show=new')) ?>">
        <span class="stat-num"><?= $counts['enq_new'] ?></span>
        <span>new enquiries</span>
        <small><?= $counts['enq_all'] ?> in total</small>
    </a>
    <a class="card stat" href="<?= e(url('admin/trials.php?show=new')) ?>">
        <span class="stat-num"><?= $counts['tri_new'] ?></span>
        <span>new trial requests</span>
        <small><?= $counts['tri_all'] ?> in total</small>
    </a>
</div>
<p class="muted">"New" means nobody has marked the request as contacted yet.</p>
<?php include __DIR__ . '/../includes/admin_footer.php'; ?>
