<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<?php $admin = session('user')['role'] === 'admin'; ?>
<section class="lab-intro" aria-labelledby="parts-title">
    <div>
        <h1 id="parts-title">Daftar komponen</h1>
        <p>Pantau stok dan lokasi penyimpanan di lab.</p>
    </div>
    <dl class="lab-counts">
        <div><dt><?= $query === '' ? 'Jenis komponen' : 'Hasil pencarian' ?></dt><dd><?= count($parts) ?></dd></div>
        <div><dt>Unit dalam daftar</dt><dd><?= array_sum(array_column($parts, 'stock')) ?></dd></div>
    </dl>
</section>
<section class="lab-surface" aria-label="Inventaris komponen">
    <form method="get" action="<?= site_url('/') ?>" class="lab-search">
        <label for="q">Cari komponen</label>
        <div>
            <input class="form-control" id="q" name="q" value="<?= esc($query, 'attr') ?>" placeholder="Kode atau nama komponen">
            <button class="btn btn-primary">Cari</button>
            <?php if ($query !== ''): ?><a class="btn btn-outline-secondary" href="<?= site_url('/') ?>">Reset</a><?php endif ?>
        </div>
    </form>
    <div class="table-responsive" role="region" aria-label="Tabel komponen" tabindex="0">
        <table class="table align-middle mb-0 lab-table">
            <thead><tr><th scope="col">Foto</th><th scope="col">Kode</th><th scope="col">Nama</th><th scope="col">Kategori</th><th scope="col">Stok</th><th scope="col">Lokasi</th><?php if ($admin): ?><th scope="col">Aksi</th><?php endif ?></tr></thead>
            <tbody>
            <?php foreach ($parts as $part): ?>
                <tr>
                    <td><?php if ($part['photo']): ?>
                        <a href="<?= site_url('komponen/' . $part['id'] . '/foto') ?>"><img class="inventory-photo" src="<?= site_url('komponen/' . $part['id'] . '/foto') ?>" alt="<?= esc($part['name'], 'attr') ?>" loading="lazy"></a>
                    <?php else: ?><span class="lab-photo-empty" aria-label="Tanpa foto">–</span><?php endif ?></td>
                    <td class="lab-code"><?= esc($part['code']) ?></td>
                    <td class="fw-semibold"><?= esc($part['name']) ?></td>
                    <td><?= esc($part['category']) ?></td>
                    <td><span class="lab-stock <?= (int) $part['stock'] === 0 ? 'lab-stock-empty' : '' ?>"><?= esc($part['stock']) ?></span></td>
                    <td class="text-nowrap"><?= esc($part['location']) ?></td>
                    <?php if ($admin): ?>
                        <td class="text-nowrap">
                            <a class="btn btn-outline-secondary btn-sm" href="<?= site_url('komponen/' . $part['id'] . '/edit') ?>">Edit</a>
                            <form class="d-inline" method="post" action="<?= site_url('komponen/' . $part['id'] . '/hapus') ?>" onsubmit="return confirm('Hapus komponen ini?')">
                                <?= csrf_field() ?><button class="btn btn-outline-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    <?php endif ?>
                </tr>
            <?php endforeach ?>
            <?php if (!$parts): ?><tr><td colspan="<?= $admin ? 7 : 6 ?>" class="text-center p-4">Komponen tidak ditemukan.</td></tr><?php endif ?>
            </tbody>
        </table>
    </div>
</section>
<?= $this->endSection() ?>
