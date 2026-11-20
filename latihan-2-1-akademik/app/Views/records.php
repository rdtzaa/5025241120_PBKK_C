<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="portal-heading">
    <div><h1>Transkrip nilai</h1><p>Daftar mata kuliah dan nilai yang sudah tercatat.</p></div>
    <dl class="portal-count"><dt>SKS bernilai</dt><dd><?= array_sum(array_column($rows, 'credits')) ?></dd></dl>
</div>
<section class="portal-section" aria-labelledby="transcript-title">
    <div class="portal-tools"><h2 id="transcript-title">Riwayat nilai</h2></div>
    <?php if ($rows): ?>
        <div class="table-responsive" role="region" aria-label="Riwayat nilai" tabindex="0">
            <table class="table align-middle mb-0 portal-table">
                <thead><tr><th scope="col">Mata kuliah</th><th scope="col">Kode</th><th scope="col">SKS</th><th scope="col">Nilai (0–100)</th></tr></thead>
                <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr><td class="fw-semibold"><?= esc($row['name']) ?></td><td class="portal-code"><?= esc($row['code']) ?></td><td><?= esc($row['credits']) ?></td><td><span class="portal-score"><?= esc($row['score']) ?></span></td></tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="portal-note">Belum ada nilai yang tercatat untuk akun ini.</p>
    <?php endif ?>
</section>
<?= $this->endSection() ?>
