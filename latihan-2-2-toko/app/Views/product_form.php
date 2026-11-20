<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-8">
        <h1 class="h2 text-gray-900 mb-3"><?= esc($title) ?></h1>
        <form class="card shadow-sm" method="post" action="<?= site_url($product ? 'admin/produk/' . $product['id'] . '/edit' : 'admin/produk/baru') ?>">
            <?= csrf_field() ?>
            <?php if ($product): ?><input type="hidden" name="previous_stock" value="<?= esc($product['stock'], 'attr') ?>"><?php endif ?>
            <div class="card-body">
                <?php foreach (['sku' => ['SKU', 30], 'name' => ['Nama produk', 100]] as $field => [$label, $length]): ?>
                    <div class="form-group">
                        <label for="<?= $field ?>"><?= $label ?></label>
                        <input class="form-control" id="<?= $field ?>" name="<?= $field ?>" maxlength="<?= $length ?>" value="<?= old($field, $product[$field] ?? '', 'attr') ?>" required>
                    </div>
                <?php endforeach ?>
                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea class="form-control" name="description" id="description" rows="3" maxlength="300" required><?= old('description', $product['description'] ?? '') ?></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group col-sm-6">
                        <label for="price">Harga (Rp)</label>
                        <input class="form-control" type="number" id="price" name="price" min="0" max="1000000000" value="<?= old('price', $product['price'] ?? '', 'attr') ?>" required>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="stock">Stok</label>
                        <input class="form-control" type="number" id="stock" name="stock" min="0" max="999999" value="<?= old('stock', $product['stock'] ?? '', 'attr') ?>" required>
                    </div>
                </div>
                <button class="btn btn-primary">Simpan produk</button>
                <a class="btn btn-outline-secondary" href="<?= site_url('admin/produk') ?>">Batal</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
