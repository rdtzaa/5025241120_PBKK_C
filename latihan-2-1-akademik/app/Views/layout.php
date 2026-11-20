<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?> | Portal Akademik</title>
    <link rel="stylesheet" href="<?= base_url('css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/academic.css') ?>">
</head>
<body>
<?php $account = session('academic_account'); ?>
<header class="portal-header">
    <div class="portal-width portal-bar">
        <a class="portal-brand" href="<?= site_url('/') ?>">Portal Akademik</a>
        <?php if ($account): ?>
            <form class="portal-account" method="post" action="<?= site_url('logout') ?>">
                <?= csrf_field() ?>
                <span><?= esc($account['name']) ?><small><?= $account['role'] === 'dosen' ? 'Dosen wali' : 'Mahasiswa' ?></small></span>
                <button class="btn btn-outline-light">Keluar</button>
            </form>
        <?php endif ?>
    </div>
</header>
<?php if ($account): ?>
    <?php $menu = $account['role'] === 'dosen'
        ? ['perwalian' => 'Mahasiswa perwalian']
        : ['frs' => 'FRS', 'jadwal' => 'Jadwal kuliah', 'nilai' => 'Penilaian']; ?>
    <div class="portal-links">
        <nav class="portal-width portal-nav" aria-label="Menu akademik">
            <?php foreach ($menu as $path => $label): ?>
                <a class="portal-tab" <?= ($section ?? '') === $path ? 'aria-current="page"' : '' ?> href="<?= site_url($path) ?>"><?= esc($label) ?></a>
            <?php endforeach ?>
        </nav>
    </div>
<?php endif ?>
<main class="portal-width portal-main">
    <?php foreach (['success', 'error'] as $status): ?>
        <?php if ($message = session()->getFlashdata($status)): ?>
            <div class="alert alert-<?= $status === 'error' ? 'danger' : 'success' ?>" role="<?= $status === 'error' ? 'alert' : 'status' ?>"><?= esc($message) ?></div>
        <?php endif ?>
    <?php endforeach ?>
    <?= $this->renderSection('content') ?>
</main>
</body>
</html>
