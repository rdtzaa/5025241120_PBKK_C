<?php use App\Models\StudyPlanModel; ?>
<form class="portal-section" method="post" action="<?= site_url('frs') ?>" id="frs-form" data-max-credits="<?= StudyPlanModel::MAX_CREDITS ?>">
    <?= csrf_field() ?>
    <div class="portal-tools">
        <div>
            <h2>Pilih kelas</h2>
            <p class="portal-subtitle">Pilih kelas untuk ditinjau oleh dosen wali.</p>
        </div>
        <output id="frs-credits" aria-live="polite">0 dari <?= StudyPlanModel::MAX_CREDITS ?> SKS</output>
    </div>
    <?php $selectable = true; include APPPATH . 'Views/class_table.php'; ?>
    <div class="portal-action-row"><button class="btn btn-primary">Ajukan FRS</button></div>
</form>
<script>
    const form = document.getElementById('frs-form');
    const credits = document.getElementById('frs-credits');
    form.addEventListener('change', () => {
        const total = Array.from(form.querySelectorAll('input[data-credits]:checked'))
            .reduce((sum, choice) => sum + Number(choice.dataset.credits), 0);
        credits.textContent = total + ' dari ' + form.dataset.maxCredits + ' SKS';
    });
</script>
