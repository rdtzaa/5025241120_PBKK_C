<?php if ($orders): ?>
    <div class="table-responsive" tabindex="0" role="region" aria-label="Daftar pesanan">
        <table class="table table-hover mb-0">
            <thead class="thead-light">
                <tr><th scope="col">Nomor</th><th scope="col">Tanggal</th><th scope="col">Penerima</th><th scope="col">Total</th><th scope="col">Status</th><th scope="col">Detail</th></tr>
            </thead>
            <tbody>
            <?php foreach ($orders as $entry): ?>
                <tr>
                    <th scope="row">#<?= esc($entry['id']) ?></th>
                    <td class="text-nowrap"><?= $entry['created_at'] ? esc(date('d/m/Y H:i', strtotime($entry['created_at']))) : '—' ?></td>
                    <td><?= esc($entry['name']) ?></td>
                    <td class="text-nowrap">Rp<?= number_format($entry['total'], 0, ',', '.') ?></td>
                    <td><?php include APPPATH . 'Views/order_status.php'; ?></td>
                    <td><a class="btn btn-sm btn-outline-primary" href="<?= site_url(($isAdmin ? 'admin/pesanan/' : 'pesanan/') . $entry['id']) ?>" aria-label="Lihat pesanan <?= esc($entry['id'], 'attr') ?>">Lihat</a></td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p class="p-3 mb-0">Belum ada pesanan.</p>
<?php endif ?>
