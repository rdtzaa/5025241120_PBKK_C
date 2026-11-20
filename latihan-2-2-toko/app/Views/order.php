<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-9 col-xl-7">
        <h1 class="h2 text-gray-900 mb-3"><?= esc($title) ?></h1>
        <article class="card shadow-sm border-left-primary">
            <div class="card-header py-3 d-flex flex-wrap align-items-center justify-content-between">
                <h2 class="h6 m-0 text-primary font-weight-bold">Pesanan #<?= esc($order['id']) ?></h2>
                <?php $entry = $order; include APPPATH . 'Views/order_status.php'; ?>
            </div>
            <div class="card-body">
                <p class="small text-gray-700 mb-1">Penerima</p>
                <p class="font-weight-bold text-gray-900 mb-1"><?= esc($order['name']) ?></p>
                <p class="text-gray-700 text-break"><?= nl2br(esc($order['address'])) ?></p>
                <?php if ($order['created_at']): ?>
                    <p class="small text-gray-700">Dibuat <?= esc(date('d/m/Y H:i', strtotime($order['created_at']))) ?> WIB</p>
                <?php endif ?>
                <ul class="list-group list-group-flush mb-3">
                    <?php foreach ($items as $item): ?>
                        <li class="list-group-item px-0 d-flex justify-content-between text-gray-900">
                            <span class="mr-2"><?= esc($item['product_name']) ?> × <?= esc($item['quantity']) ?></span>
                            <span class="text-nowrap">Rp<?= number_format($item['unit_price'] * $item['quantity'], 0, ',', '.') ?></span>
                        </li>
                    <?php endforeach ?>
                </ul>
                <p class="d-flex justify-content-between border-top pt-3 text-gray-900">
                    <span>Total</span><strong>Rp<?= number_format($order['total'], 0, ',', '.') ?></strong>
                </p>
                <a class="btn btn-outline-primary" href="<?= site_url($isAdmin ? 'admin/pesanan' : 'pesanan') ?>">Kembali ke pesanan</a>
            </div>
            <?php if ($isAdmin && isset(App\Models\OrderModel::NEXT[$order['status']])): ?>
                <div class="card-footer bg-white">
                    <form method="post" action="<?= site_url('admin/pesanan/' . $order['id'] . '/status') ?>">
                        <?= csrf_field() ?>
                        <?php foreach (App\Models\OrderModel::NEXT[$order['status']] as $next): ?>
                            <?php if ($next === 'dibatalkan' && in_array(null, array_column($items, 'product_id'), true)) { continue; } ?>
                            <button class="btn <?= $next === 'dibatalkan' ? 'btn-outline-danger' : 'btn-primary' ?> my-1 mr-2" name="status" value="<?= $next ?>">
                                <?= match ($next) {
                                    'diproses' => 'Proses pesanan', 'selesai' => 'Tandai selesai', 'dibatalkan' => 'Batalkan pesanan',
                                } ?>
                            </button>
                        <?php endforeach ?>
                    </form>
                </div>
            <?php endif ?>
        </article>
    </div>
</div>
<?= $this->endSection() ?>
