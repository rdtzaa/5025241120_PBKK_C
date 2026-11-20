<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?> | Kertas &amp; Kawan</title>
    <link rel="stylesheet" href="<?= base_url('sb-admin2/vendor/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('sb-admin2/css/sb-admin-2.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/shop.css') ?>">
</head>
<body>
<?php
$shopAccount = session('shop_account');
$role = $shopAccount['role'] ?? null;
$home = $role === 'admin' ? 'admin' : '/';
$links = match ($role) {
    'admin' => [
        'admin' => ['Dashboard', 'fa-tachometer-alt'],
        'admin/produk' => ['Produk dan stok', 'fa-boxes'],
        'admin/pesanan' => ['Pesanan pelanggan', 'fa-clipboard-list'],
        '/' => ['Katalog toko', 'fa-store'],
    ],
    'pelanggan' => [
        '/' => ['Katalog', 'fa-store'],
        'keranjang' => ['Keranjang (' . array_sum(session('cart') ?? []) . ')', 'fa-shopping-cart'],
        'pesanan' => ['Pesanan saya', 'fa-clipboard-list'],
    ],
    default => [
        '/' => ['Katalog', 'fa-store'],
        'login' => ['Masuk', 'fa-sign-in-alt'],
        'daftar' => ['Daftar', 'fa-user-plus'],
    ],
};
$current = trim(uri_string(), '/') ?: '/';
$isActive = static fn (string $path) => $current === $path
    || (! in_array($path, ['/', 'admin'], true) && str_starts_with($current, $path . '/'));
?>
<div id="wrapper">
    <nav class="navbar-nav bg-gradient-primary sidebar sidebar-dark d-none d-md-flex" aria-label="Navigasi toko">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= site_url($home) ?>">
            <span class="sidebar-brand-icon"><i class="fas fa-store" aria-hidden="true"></i></span>
            <span class="sidebar-brand-text mx-2">Kertas &amp; Kawan</span>
        </a>
        <hr class="sidebar-divider my-0">
        <ul class="list-unstyled mb-0">
            <?php foreach ($links as $path => [$label, $icon]): ?>
                <li class="nav-item <?= $isActive($path) ? 'active' : '' ?>">
                    <a class="nav-link" <?= $isActive($path) ? 'aria-current="page"' : '' ?> href="<?= site_url($path) ?>">
                        <i class="fas fa-fw <?= $icon ?>" aria-hidden="true"></i> <span><?= esc($label) ?></span>
                    </a>
                </li>
            <?php endforeach ?>
        </ul>
    </nav>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <header class="bg-white border-bottom mb-3 px-3">
                <div class="d-flex align-items-center justify-content-between py-2">
                    <a class="font-weight-bold text-primary mr-2" href="<?= site_url($home) ?>">Kertas &amp; Kawan</a>
                    <?php if ($shopAccount): ?>
                        <form class="d-flex align-items-center" method="post" action="<?= site_url('logout') ?>">
                            <?= csrf_field() ?>
                            <span class="text-gray-800 mr-2"><?= esc($shopAccount['name']) ?></span>
                            <button class="btn btn-outline-primary btn-sm">Keluar</button>
                        </form>
                    <?php endif ?>
                </div>
                <nav class="nav nav-pills d-md-none pb-2" aria-label="Navigasi toko">
                    <?php foreach ($links as $path => [$label]): ?>
                        <a class="nav-link px-2 text-nowrap <?= $isActive($path) ? 'active' : '' ?>" <?= $isActive($path) ? 'aria-current="page"' : '' ?> href="<?= site_url($path) ?>">
                            <?= esc($label) ?>
                        </a>
                    <?php endforeach ?>
                </nav>
            </header>
            <main class="container-fluid pt-2 pt-md-3 pb-5">
                <?php foreach (['success', 'error'] as $status): ?>
                    <?php if ($message = session()->getFlashdata($status)): ?>
                        <div class="alert alert-<?= $status === 'error' ? 'danger' : 'success' ?>" role="<?= $status === 'error' ? 'alert' : 'status' ?>"><?= esc($message) ?></div>
                    <?php endif ?>
                <?php endforeach ?>
                <?php if ($errors = session()->getFlashdata('errors')): ?>
                    <ul class="alert alert-danger list-unstyled" role="alert">
                        <?php foreach ($errors as $error): ?><li><?= esc($error) ?></li><?php endforeach ?>
                    </ul>
                <?php endif ?>
                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>
</div>
</body>
</html>
