<?php use App\Models\StudyPlanModel; ?>
<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="portal-heading">
    <div>
        <p class="portal-term"><?= esc(StudyPlanModel::TERM) ?></p>
        <h1>Rencana Studi (FRS)</h1>
    </div>
</div>
<dl class="portal-overview">
    <div><dt>Mahasiswa</dt><dd><?= esc($student['name']) ?><small><?= esc($student['nim']) ?> · <?= esc($student['program']) ?></small></dd></div>
    <div><dt>Dosen wali</dt><dd><?= esc($student['advisor'] ?? 'Belum ditetapkan') ?></dd></div>
    <div><dt>Status FRS</dt><dd><?php $status = $plan['status'] ?? 'belum'; include APPPATH . 'Views/plan_status.php'; ?></dd></div>
</dl>
<?php if ($plan): ?>
    <section class="portal-section" aria-labelledby="classes-title">
        <div class="portal-tools">
            <div>
                <h2 id="classes-title">Kelas yang diajukan</h2>
                <p class="portal-subtitle">
                    <?= ($plan['status'] === 'disetujui' && $plan['approver'])
                        ? 'Disetujui oleh ' . esc($plan['approver'])
                        : 'Diajukan pada ' . esc(date('d/m/Y H:i', strtotime($plan['submitted_at']))) ?>
                </p>
            </div>
            <strong><?= array_sum(array_column($classes, 'credits')) ?> SKS</strong>
        </div>
        <?php include APPPATH . 'Views/class_table.php'; ?>
    </section>
<?php elseif (! $student['advisor']): ?>
    <p class="portal-section portal-note">Pengajuan FRS tersedia setelah dosen wali ditetapkan.</p>
<?php elseif ($classes): ?>
    <?php include APPPATH . 'Views/record_form.php'; ?>
<?php else: ?>
    <p class="portal-section portal-note">Belum ada kelas yang ditawarkan untuk semester ini.</p>
<?php endif ?>
<?= $this->endSection() ?>
