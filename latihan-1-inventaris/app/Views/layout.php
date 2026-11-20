<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?> | Inventaris Robotik</title>
    <link rel="stylesheet" href="<?= base_url('css/sb-admin.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/inventory.css') ?>">
</head>
<body class="<?= session('user') ? 'lab-app' : 'lab-guest' ?>">
<?php $account = session('user'); ?>
<header class="sb-topnav lab-header">
    <a class="lab-brand" href="<?= site_url('/') ?>">Inventaris<span>Robotik</span></a>
    <?php if ($account): ?>
        <form method="post" action="<?= site_url('logout') ?>" class="lab-account">
            <?= csrf_field() ?>
            <span><strong><?= esc($account['username']) ?></strong> <span class="lab-role"><?= $account['role'] === 'admin' ? 'Admin' : 'Pembina' ?></span></span>
            <button class="btn lab-logout">Keluar</button>
        </form>
    <?php endif ?>
</header>
<div id="layoutSidenav" class="<?= $account ? 'lab-shell' : 'lab-entry' ?>">
    <?php if ($account): ?>
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav lab-nav" aria-label="Menu inventaris">
                <a <?= $title === 'Daftar komponen' ? 'aria-current="page"' : '' ?> href="<?= site_url('/') ?>">Daftar komponen</a>
                <?php if ($account['role'] === 'admin'): ?>
                    <a <?= $title === 'Tambah komponen' ? 'aria-current="page"' : '' ?> href="<?= site_url('komponen/baru') ?>">Tambah komponen</a>
                <?php endif ?>
            </nav>
        </div>
    <?php endif ?>
    <main id="layoutSidenav_content">
        <div class="lab-content">
            <?php foreach (['success', 'error'] as $status): ?>
                <?php if ($message = session()->getFlashdata($status)): ?>
                    <div class="alert alert-<?= $status === 'error' ? 'danger' : 'success' ?>" role="status"><?= esc($message) ?></div>
                <?php endif ?>
            <?php endforeach ?>
            <?php if ($errors = session()->getFlashdata('errors')): ?>
                <ul class="alert alert-danger list-unstyled" role="alert">
                    <?php foreach ($errors as $error): ?><li><?= esc($error) ?></li><?php endforeach ?>
                </ul>
            <?php endif ?>
            <?= $this->renderSection('content') ?>
        </div>
    </main>
</div>
</body>
</html>
