<?php use App\Models\StudyPlanModel; ?>
<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="portal-heading">
    <div><p class="portal-term"><?= esc(StudyPlanModel::TERM) ?></p><h1>Jadwal kuliah</h1></div>
</div>
<section class="portal-section" aria-label="Jadwal kelas">
    <?php if ($classes): ?>
        <div class="portal-tools"><h2>Kelas yang disetujui</h2><strong><?= count($classes) ?> kelas</strong></div>
        <?php include APPPATH . 'Views/class_table.php'; ?>
    <?php else: ?>
        <div class="portal-note">
            <p>Jadwal tersedia setelah dosen wali menyetujui FRS.</p>
            <a class="btn btn-primary" href="<?= site_url('frs') ?>">Lihat FRS</a>
        </div>
    <?php endif ?>
</section>
<?= $this->endSection() ?>
