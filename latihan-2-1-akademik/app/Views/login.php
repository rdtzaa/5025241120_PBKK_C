<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<section class="portal-login">
    <div class="portal-login-intro">
        <h1>Masuk ke Portal Akademik</h1>
        <p>Mahasiswa dapat mengajukan FRS dan melihat jadwal serta nilai. Dosen wali dapat meninjau pengajuan mahasiswa bimbingannya.</p>
    </div>
    <form class="portal-section portal-login-form" method="post" action="<?= site_url('login') ?>">
        <?= csrf_field() ?>
        <label class="form-label" for="username">NIM atau username</label>
        <input class="form-control mb-3" id="username" name="username" maxlength="40" autocomplete="username" required>
        <label class="form-label" for="password">Kata sandi</label>
        <input class="form-control mb-4" type="password" id="password" name="password" maxlength="72" autocomplete="current-password" required>
        <button class="btn btn-primary w-100">Masuk</button>
    </form>
</section>
<?= $this->endSection() ?>
