<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
    <h1 class="h2 text-gray-900 mb-2">Produk dan stok</h1>
    <a class="btn btn-primary mb-2" href="<?= site_url('admin/produk/baru') ?>">Tambah produk</a>
</div>
<section class="card shadow-sm">
    <?php if ($products): ?>
        <div class="table-responsive" tabindex="0" role="region" aria-label="Produk dan stok">
            <table class="table table-hover mb-0">
                <thead class="thead-light"><tr><th scope="col">SKU</th><th scope="col">Produk</th><th scope="col">Harga</th><th scope="col">Stok</th><th scope="col">Aksi</th></tr></thead>
                <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td class="text-nowrap"><?= esc($product['sku']) ?></td>
                        <th scope="row"><?= esc($product['name']) ?></th>
                        <td class="text-nowrap">Rp<?= number_format($product['price'], 0, ',', '.') ?></td>
                        <td><span class="badge <?= $product['stock'] <= 5 ? 'badge-warning' : 'badge-light' ?>"><?= esc($product['stock']) ?></span></td>
                        <td><a class="btn btn-sm btn-outline-primary" href="<?= site_url('admin/produk/' . $product['id'] . '/edit') ?>">Edit</a></td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="p-3 mb-0">Belum ada produk.</p>
    <?php endif ?>
</section>
<?= $this->endSection() ?>
