<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
        <section class="card shadow-sm border-left-primary">
            <div class="card-body p-4">
                <h1 class="h3 text-gray-900 mb-4"><?= esc($title) ?></h1>
                <form method="post" action="<?= site_url($register ? 'daftar' : 'login') ?>">
                    <?= csrf_field() ?>
                    <?php if ($register): ?>
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input class="form-control" name="name" id="name" maxlength="100" autocomplete="name" required>
                        </div>
                    <?php endif ?>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input class="form-control" name="username" id="username" maxlength="30" autocapitalize="none" autocomplete="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Kata sandi</label>
                        <input class="form-control" type="password" name="password" id="password" maxlength="72" <?= $register ? 'minlength="8" autocomplete="new-password"' : 'autocomplete="current-password"' ?> required>
                    </div>
                    <button class="btn btn-primary btn-block"><?= $register ? 'Buat akun' : 'Masuk' ?></button>
                </form>
                <p class="mt-3 mb-0"><?= $register ? 'Sudah punya akun?' : 'Belum punya akun?' ?>
                    <a href="<?= site_url($register ? 'login' : 'daftar') ?>"><?= $register ? 'Masuk' : 'Daftar pelanggan' ?></a>
                </p>
            </div>
        </section>
    </div>
</div>
<?= $this->endSection() ?>
