<?php use App\Models\StudyPlanModel; ?>
<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="portal-heading">
    <div><p class="portal-term"><?= esc(StudyPlanModel::TERM) ?></p><h1>Mahasiswa perwalian</h1></div>
    <dl class="portal-count"><dt>Menunggu persetujuan</dt><dd><?= count(array_keys(array_column($students, 'status'), 'menunggu', true)) ?></dd></dl>
</div>
<section class="portal-section" aria-labelledby="wards-title">
    <div class="portal-tools"><h2 id="wards-title">Daftar mahasiswa</h2><strong><?= count($students) ?> mahasiswa</strong></div>
    <?php if ($students): ?>
        <div class="table-responsive" role="region" aria-label="Daftar mahasiswa perwalian" tabindex="0">
            <table class="table align-middle mb-0 portal-table">
                <thead><tr><th scope="col">Mahasiswa</th><th scope="col">Program studi</th><th scope="col">Status FRS</th><th scope="col">Detail</th></tr></thead>
                <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><strong><?= esc($student['name']) ?></strong><small class="portal-code"><?= esc($student['nim']) ?></small></td>
                        <td><?= esc($student['program']) ?></td>
                        <td><?php $status = $student['status'] ?? 'belum'; include APPPATH . 'Views/plan_status.php'; ?></td>
                        <td><a class="btn btn-outline-primary" href="<?= site_url('perwalian/' . $student['id']) ?>" aria-label="Lihat FRS <?= esc($student['name'], 'attr') ?>">Lihat FRS</a></td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="portal-note">Belum ada mahasiswa perwalian.</p>
    <?php endif ?>
</section>
<?= $this->endSection() ?>
