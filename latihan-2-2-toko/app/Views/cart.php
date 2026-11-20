<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<h1 class="h2 text-gray-900 mb-4">Keranjang</h1>
<?php if (!$items): ?>
    <section class="card shadow-sm">
        <div class="card-body">
            <h2 class="h5 text-gray-900 mb-3">Keranjang masih kosong.</h2>
            <a class="btn btn-primary" href="<?= site_url('/') ?>">Lihat katalog</a>
        </div>
    </section>
<?php else: ?>
    <div class="row">
        <div class="col-lg-7 col-xl-8 mb-4">
            <section class="card shadow-sm" aria-labelledby="cart-title">
                <div class="card-header py-3">
                    <h2 class="h6 m-0 font-weight-bold text-primary" id="cart-title">Produk di keranjang</h2>
                </div>
                <ul class="list-group list-group-flush">
                    <?php foreach ($items as $item): ?>
                        <li class="list-group-item">
                            <div class="d-flex flex-wrap justify-content-between align-items-start mb-2">
                                <div class="mr-2">
                                    <h3 class="h6 font-weight-bold text-gray-900 mb-1"><?= esc($item['name']) ?></h3>
                                    <p class="small text-gray-700 mb-0">Rp<?= number_format($item['price'], 0, ',', '.') ?> per barang · Stok <?= esc($item['stock']) ?></p>
                                </div>
                                <strong class="text-gray-900">Rp<?= number_format($item['subtotal'], 0, ',', '.') ?></strong>
                            </div>
                            <div class="d-flex flex-wrap align-items-center">
                                <form class="col-8 col-sm-6 col-xl-4 pl-0 my-1" method="post" action="<?= site_url('keranjang/' . $item['id']) ?>">
                                    <?= csrf_field() ?>
                                    <div class="input-group">
                                        <input class="form-control" type="number" min="0" max="<?= esc($item['stock'], 'attr') ?>" name="quantity" value="<?= esc($item['quantity'], 'attr') ?>" aria-label="Jumlah <?= esc($item['name'], 'attr') ?>" required>
                                        <div class="input-group-append"><button class="btn btn-outline-primary">Ubah</button></div>
                                    </div>
                                </form>
                                <form class="my-1" method="post" action="<?= site_url('keranjang/' . $item['id'] . '/hapus') ?>">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-outline-danger" aria-label="Hapus <?= esc($item['name'], 'attr') ?> dari keranjang">Hapus</button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach ?>
                </ul>
            </section>
        </div>
        <div class="col-lg-5 col-xl-4">
            <form class="card shadow-sm" method="post" action="<?= site_url('checkout') ?>">
                <?= csrf_field() ?>
                <div class="card-header py-3">
                    <h2 class="h6 m-0 font-weight-bold text-primary">Checkout</h2>
                </div>
                <div class="card-body">
                    <p class="d-flex justify-content-between text-gray-900 border-bottom pb-2 mb-3">
                        <span>Total</span><strong>Rp<?= number_format($total, 0, ',', '.') ?></strong>
                    </p>
                    <div class="form-group">
                        <label class="text-gray-900" for="name">Nama penerima</label>
                        <input class="form-control" name="name" id="name" value="<?= old('name', '', 'attr') ?>" maxlength="100" required>
                    </div>
                    <div class="form-group">
                        <label class="text-gray-900" for="address">Alamat</label>
                        <textarea class="form-control" name="address" id="address" rows="3" maxlength="300" required><?= old('address') ?></textarea>
                    </div>
                    <p class="small text-gray-700">Pembayaran saat barang diterima (simulasi).</p>
                    <button class="btn btn-primary btn-block">Buat pesanan</button>
                </div>
            </form>
        </div>
    </div>
<?php endif ?>
<?= $this->endSection() ?>
