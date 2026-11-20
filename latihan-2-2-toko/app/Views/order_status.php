<?php
$label = App\Models\OrderModel::LABELS[$entry['status']];
$color = match ($entry['status']) {
    'baru' => 'primary', 'diproses' => 'warning', 'selesai' => 'success', 'dibatalkan' => 'secondary',
};
?>
<span class="badge badge-<?= $color ?>"><?= esc($label) ?></span>
