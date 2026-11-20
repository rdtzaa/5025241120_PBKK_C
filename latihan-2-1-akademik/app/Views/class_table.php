<?php use App\Models\StudyPlanModel; ?>
<div class="table-responsive" role="region" aria-label="Tabel kelas" tabindex="0">
    <table class="table align-middle mb-0 portal-table portal-classes">
        <thead>
            <tr><?php if (!empty($selectable)): ?><th scope="col">Pilih</th><?php endif ?><th scope="col">Mata kuliah</th><th scope="col">SKS</th><th scope="col">Jadwal</th><th scope="col">Ruang dan dosen</th></tr>
        </thead>
        <tbody>
        <?php foreach ($classes as $class): ?>
            <tr>
                <?php if (!empty($selectable)): ?>
                    <td><input class="form-check-input" type="checkbox" name="classes[]" value="<?= esc($class['id'], 'attr') ?>" data-credits="<?= esc($class['credits'], 'attr') ?>" aria-label="Pilih <?= esc($class['name'], 'attr') ?>"></td>
                <?php endif ?>
                <td><strong><?= esc($class['name']) ?></strong><small><?= esc($class['code']) ?> · Kelas <?= esc($class['class_name']) ?></small></td>
                <td><?= esc($class['credits']) ?></td>
                <td><strong><?= esc(StudyPlanModel::DAYS[$class['weekday']]) ?></strong><small><?= esc($class['starts_at']) ?>–<?= esc($class['ends_at']) ?> WIB</small></td>
                <td><?= esc($class['room']) ?><small><?= esc($class['lecturer']) ?></small></td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
