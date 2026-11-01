<?php /* Opening hours block. Shows times only when they are verified in config/site.php */ ?>
<?php if (hours_available()): ?>
    <table class="hours-table">
        <caption class="sr-only">Opening hours</caption>
        <tbody>
        <?php foreach (site('hours') as $day => $h): ?>
            <tr>
                <th scope="row"><?= e($day) ?></th>
                <td><?= (!empty($h['open']) && !empty($h['close'])) ? e(fmt_time($h['open']) . ' - ' . fmt_time($h['close'])) : 'Closed' ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p class="hours-pending"><strong>Opening hours:</strong> please call or WhatsApp us to confirm today's timings.</p>
<?php endif; ?>
