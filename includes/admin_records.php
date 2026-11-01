<?php
/**
 * Shared list for admin/enquiries.php and admin/trials.php.
 * Needs: $key ('enquiries' | 'trials'), $self (file name), $heading, $columns (label => field)
 */
$table = admin_table($key);
$show  = in_array($_GET['show'] ?? '', ['new', 'contacted'], true) ? $_GET['show'] : 'all';
$pdo   = db();
$rows  = [];
$dbError = false;

if ($pdo) {
    try {
        $sql = "SELECT * FROM {$table}";
        $args = [];
        if ($show !== 'all') {
            $sql .= ' WHERE is_contacted = ?';
            $args[] = $show === 'contacted' ? 1 : 0;
        }
        $sql .= ' ORDER BY created_at DESC, id DESC LIMIT 200';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($args);
        $rows = $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log('Admin list failed: ' . $e->getMessage());
        $dbError = true;
    }
} else {
    $dbError = true;
}
?>
<h1 class="admin-h1"><?= e($heading) ?></h1>

<p class="filter-bar" role="group" aria-label="Filter">
    <?php foreach (['all' => 'All', 'new' => 'New', 'contacted' => 'Contacted'] as $k => $label): ?>
        <a class="filter-btn" href="<?= e(url('admin/' . $self) . ($k === 'all' ? '' : '?show=' . $k)) ?>" aria-pressed="<?= $show === $k ? 'true' : 'false' ?>"><?= e($label) ?></a>
    <?php endforeach; ?>
</p>

<?php if ($dbError): ?>
    <div class="alert alert-error" role="alert">Could not load records. Check the database settings.</div>
<?php elseif (!$rows): ?>
    <p>No records to show.</p>
<?php else: ?>
<div class="table-wrap">
<table class="records">
    <thead>
        <tr>
            <th scope="col">Received</th>
            <?php foreach ($columns as $label => $field): ?><th scope="col"><?= e($label) ?></th><?php endforeach; ?>
            <th scope="col">Status</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($rows as $r): ?>
        <tr class="<?= $r['is_contacted'] ? 'row-done' : 'row-new' ?>">
            <td data-label="Received"><?= e(date('d M Y, g:i A', strtotime($r['created_at']))) ?></td>
            <?php foreach ($columns as $label => $field): ?>
                <td data-label="<?= e($label) ?>">
                <?php if ($field === 'phone'): ?>
                    <a href="tel:+91<?= e($r['phone']) ?>"><?= e($r['phone']) ?></a>
                    <a class="small" href="https://wa.me/91<?= e($r['phone']) ?>" target="_blank" rel="noopener">WhatsApp</a>
                <?php elseif ($field === 'email' && $r['email']): ?>
                    <a href="mailto:<?= e($r['email']) ?>"><?= e($r['email']) ?></a>
                <?php elseif ($field === 'preferred_date' && $r[$field]): ?>
                    <?= e(date('d M Y', strtotime($r[$field]))) ?>
                <?php else: ?>
                    <?= nl2br(e($r[$field] ?? '')) ?>
                <?php endif; ?>
                </td>
            <?php endforeach; ?>
            <td data-label="Status"><span class="badge <?= $r['is_contacted'] ? 'badge-done' : 'badge-new' ?>"><?= $r['is_contacted'] ? 'Contacted' : 'New' ?></span></td>
            <td data-label="Actions" class="actions">
                <form method="post" action="<?= e(url('admin/' . $self)) ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                    <input type="hidden" name="show" value="<?= e($show) ?>">
                    <input type="hidden" name="action" value="<?= $r['is_contacted'] ? 'new' : 'contacted' ?>">
                    <button type="submit" class="btn btn-sm btn-dark"><?= $r['is_contacted'] ? 'Mark new' : 'Mark contacted' ?></button>
                </form>
                <form method="post" action="<?= e(url('admin/' . $self)) ?>" data-confirm="Delete this record permanently?">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                    <input type="hidden" name="show" value="<?= e($show) ?>">
                    <input type="hidden" name="action" value="delete">
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<p class="muted">Showing up to the latest 200 records.</p>
<?php endif; ?>
