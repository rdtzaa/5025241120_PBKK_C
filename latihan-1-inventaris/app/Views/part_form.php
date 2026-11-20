<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="lab-page-title">
    <a href="<?= site_url('/') ?>">Daftar komponen</a>
    <h1><?= esc($title) ?></h1>
</div>
<form class="lab-surface lab-editor" method="post" enctype="multipart/form-data" action="<?= site_url($part ? 'komponen/' . $part['id'] . '/edit' : 'komponen/baru') ?>">
    <?= csrf_field() ?>
    <div class="lab-form-fields">
        <?php foreach (['code' => 'Kode', 'name' => 'Nama', 'category' => 'Kategori', 'stock' => 'Stok', 'location' => 'Lokasi penyimpanan'] as $field => $label): ?>
            <div class="<?= $field === 'location' ? 'lab-field-wide' : '' ?>">
                <label class="form-label" for="<?= $field ?>"><?= $label ?></label>
                <input class="form-control" id="<?= $field ?>" name="<?= $field ?>" value="<?= old($field, $part[$field] ?? '', 'attr') ?>" <?= $field === 'stock' ? 'type="number" min="0" max="99999"' : 'type="text"' ?> required>
            </div>
        <?php endforeach ?>
    </div>
    <div class="lab-upload">
        <?php if (!empty($part['photo'])): ?>
            <img class="inventory-photo" src="<?= site_url('komponen/' . $part['id'] . '/foto') ?>" alt="Foto komponen saat ini">
        <?php endif ?>
        <div>
            <label class="form-label" for="photo"><?= !empty($part['photo']) ? 'Ganti foto' : 'Foto komponen' ?></label>
            <input class="form-control" type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/webp" aria-describedby="photo-hint">
            <p class="form-text mb-0" id="photo-hint">Opsional. JPG, PNG, atau WebP, maksimal 2 MB.</p>
        </div>
    </div>
    <div class="d-flex flex-wrap gap-2 mt-4">
        <button class="btn btn-primary">Simpan</button>
        <a class="btn btn-outline-secondary" href="<?= site_url('/') ?>">Batal</a>
    </div>
</form>
<?= $this->endSection() ?>
