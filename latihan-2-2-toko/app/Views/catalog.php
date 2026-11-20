<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<?php
$role = session('shop_account')['role'] ?? null;
$productPhotos = [
    'ATK-01' => 'photo-notebook',
    'ATK-02' => 'photo-pen',
    'ATK-03' => 'photo-notes',
    'ATK-04' => 'photo-folder',
];
?>
<section class="card shadow-sm border-left-primary mb-4" aria-labelledby="catalog-title">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="h2 text-gray-900 mb-2" id="catalog-title">Katalog alat tulis</h1>
                <p class="text-gray-700 mb-0">Perlengkapan untuk mencatat kuliah dan merapikan dokumen.</p>
            </div>
            <div class="col-lg-4 d-none d-lg-block">
                <img class="img-fluid rounded" src="<?= base_url('images/katalog-hero.webp') ?>" alt="">
            </div>
        </div>
    </div>
</section>
<?php if (!$products): ?>
    <p class="alert alert-info">Belum ada produk di katalog.</p>
<?php endif ?>
<div class="row">
    <?php foreach ($products as $product): ?>
        <div class="col-sm-6 col-xl-3 mb-4">
            <article class="card shadow-sm h-100 overflow-hidden">
                <?php if (isset($productPhotos[$product['sku']])): ?>
                    <div class="product-photo <?= $productPhotos[$product['sku']] ?>">
                        <img src="<?= base_url('images/foto-produk.webp') ?>" alt="" loading="lazy">
                    </div>
                <?php endif ?>
                <div class="card-body d-flex flex-column">
                    <h2 class="h5 font-weight-bold text-gray-900"><?= esc($product['name']) ?></h2>
                    <p class="text-gray-700"><?= esc($product['description']) ?></p>
                    <div class="d-flex justify-content-between align-items-center flex-wrap mt-auto mb-2">
                        <strong class="text-gray-900 mr-2">Rp<?= number_format($product['price'], 0, ',', '.') ?></strong>
                        <span class="badge badge-light"><?= $product['stock'] > 0 ? 'Stok ' . esc($product['stock']) : 'Stok habis' ?></span>
                    </div>
                    <?php if ($product['stock'] > 0 && $role === 'pelanggan'): ?>
                        <form method="post" action="<?= site_url('keranjang/' . $product['id']) ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="quantity" value="1">
                            <button class="btn btn-primary btn-block" name="add" value="1" aria-label="Tambah <?= esc($product['name'], 'attr') ?> ke keranjang">
                                Tambah ke keranjang
                            </button>
                        </form>
                    <?php elseif ($product['stock'] > 0 && $role === null): ?>
                        <a class="btn btn-outline-primary btn-block" href="<?= site_url('login') ?>">Masuk untuk membeli</a>
                    <?php endif ?>
                </div>
            </article>
        </div>
    <?php endforeach ?>
</div>
<?= $this->endSection() ?>
