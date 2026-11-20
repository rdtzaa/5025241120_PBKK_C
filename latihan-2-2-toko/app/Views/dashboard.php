<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<h1 class="h2 text-gray-900 mb-4">Dashboard toko</h1>
<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card border-left-success shadow-sm h-100">
            <div class="card-body">
                <h2 class="h6 text-success font-weight-bold">Omzet pesanan selesai</h2>
                <p class="h4 text-gray-900 mb-0">Rp<?= number_format($summary['revenue'], 0, ',', '.') ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card border-left-primary shadow-sm h-100">
            <div class="card-body">
                <h2 class="h6 text-primary font-weight-bold">Pesanan aktif</h2>
                <p class="h4 text-gray-900 mb-0"><?= esc($summary['open_orders']) ?> <small class="text-gray-700">dari <?= esc($summary['total_orders']) ?> pesanan</small></p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card border-left-warning shadow-sm h-100">
            <div class="card-body">
                <h2 class="h6 text-gray-800 font-weight-bold">Produk dengan stok ≤ 5</h2>
                <p class="h4 text-gray-900 mb-0"><?= count($lowStock) ?></p>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-8 mb-3">
        <section class="card shadow-sm">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                <h2 class="h6 m-0 font-weight-bold text-primary">Pesanan terbaru</h2>
                <a class="btn btn-sm btn-outline-primary" href="<?= site_url('admin/pesanan') ?>">Lihat semua</a>
            </div>
            <?php $orders = $recent; $isAdmin = true; include APPPATH . 'Views/order_list.php'; ?>
        </section>
    </div>
    <div class="col-xl-4 mb-3">
        <section class="card shadow-sm">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                <h2 class="h6 m-0 font-weight-bold text-primary">Stok rendah</h2>
                <a class="btn btn-sm btn-outline-primary" href="<?= site_url('admin/produk') ?>">Kelola stok</a>
            </div>
            <?php if ($lowStock): ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($lowStock as $product): ?>
                        <li class="list-group-item d-flex justify-content-between text-gray-900">
                            <span class="mr-2"><?= esc($product['name']) ?></span><strong><?= esc($product['stock']) ?></strong>
                        </li>
                    <?php endforeach ?>
                </ul>
            <?php else: ?>
                <p class="p-3 mb-0">Semua produk punya stok lebih dari lima.</p>
            <?php endif ?>
        </section>
    </div>
</div>
<?= $this->endSection() ?>
