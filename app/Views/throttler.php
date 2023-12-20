<?= $this->extend('layouts/empty') ?>

<?= $this->section('header') ?>
<?= $this->include('header') ?>
<link rel="stylesheet" href="<?= base_url('public/css/public.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main class="container">
    <div class="page page--centered">
        <div class="page__content">
            <h1>Too many requests.</h1>
            <p>Try again after a minute.</p>
        </div>
    </div>
</main>
<?= $this->endSection() ?>