<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
    <h1 class="h2 text-gray-900 mb-2"><?= esc($title) ?></h1>
    <?php if (!$isAdmin): ?><a class="btn btn-primary mb-2" href="<?= site_url('/') ?>">Lihat katalog</a><?php endif ?>
</div>
<section class="card shadow-sm">
    <?php include APPPATH . 'Views/order_list.php'; ?>
</section>
<?= $this->endSection() ?>
