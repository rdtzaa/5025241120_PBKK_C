<?php
$statusLabel = match ($status) {
    'disetujui' => 'Disetujui',
    'menunggu' => 'Menunggu persetujuan',
    default => 'Belum diajukan',
};
?>
<span class="portal-status portal-status-<?= esc($status ?? 'belum', 'attr') ?>"><?= esc($statusLabel) ?></span>
