<?php use App\Models\StudyPlanModel; ?>
<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="portal-heading">
    <div>
        <a class="portal-term" href="<?= site_url('perwalian') ?>">Mahasiswa perwalian</a>
        <h1>Tinjau FRS</h1>
        <p><?= esc(StudyPlanModel::TERM) ?></p>
    </div>
</div>
<dl class="portal-overview">
    <div><dt>Mahasiswa</dt><dd><?= esc($student['name']) ?><small><?= esc($student['nim']) ?> · <?= esc($student['program']) ?></small></dd></div>
    <div><dt>Status FRS</dt><dd><?php $status = $plan['status'] ?? 'belum'; include APPPATH . 'Views/plan_status.php'; ?></dd></div>
</dl>
<?php if (!$plan): ?>
    <p class="portal-section portal-note">Mahasiswa ini belum mengajukan FRS.</p>
<?php else: ?>
    <section class="portal-section" aria-labelledby="classes-title">
        <div class="portal-tools">
            <h2 id="classes-title">Kelas yang diajukan</h2>
            <strong><?= array_sum(array_column($classes, 'credits')) ?> SKS</strong>
        </div>
        <?php include APPPATH . 'Views/class_table.php'; ?>
        <?php if ($plan['status'] === 'menunggu'): ?>
            <form class="portal-action-row" method="post" action="<?= site_url('perwalian/' . $student['id'] . '/setujui') ?>">
                <?= csrf_field() ?>
                <button class="btn btn-primary">Setujui FRS</button>
            </form>
        <?php elseif ($plan['approver']): ?>
            <p class="portal-note mb-0">Disetujui oleh <?= esc($plan['approver']) ?> pada <?= esc(date('d/m/Y H:i', strtotime($plan['approved_at']))) ?> WIB.</p>
        <?php endif ?>
    </section>
<?php endif ?>
<?= $this->endSection() ?>
