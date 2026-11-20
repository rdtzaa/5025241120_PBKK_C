<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="lab-login">
    <div class="lab-login-photo">
        <img src="<?= base_url('images/circuit-board.jpg') ?>" alt="">
        <div class="lab-photo-caption"><span>ICT Robotic</span><p>Inventaris<br>Robotika ITS</p></div>
    </div>
    <section class="lab-login-panel">
        <h1>Login</h1>
        <form method="post" action="<?= site_url('login') ?>">
            <?= csrf_field() ?>
            <label class="form-label" for="username">Username</label>
            <input class="form-control mb-3" id="username" name="username" maxlength="40" autocomplete="username" required>
            <label class="form-label" for="password">Password</label>
            <input class="form-control mb-4" type="password" id="password" name="password" autocomplete="current-password" required>
            <button class="btn btn-primary w-100">Login</button>
        </form>
    </section>
</div>
<?= $this->endSection() ?>
