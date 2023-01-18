<?= $this->extend('layouts/empty') ?>

<?= $this->section('header') ?>

    <!-- metadata -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico"/>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"
          integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi"
          crossorigin="anonymous">

    <link rel="stylesheet" href="<?= base_url('css/signin.css') ?>">
    <title>Sign in</title>

<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <main class="form-signin">

        <?= $this->include('errors/validation') ?>

        <?php helper('form') ?>

        <?= form_open('login') ?>
            <?php $extra = ['class' => 'form-control'] ?>

            <img class="img-fluid mb-4" src="<?= base_url('assets/enai-logo_horizontal.png') ?>" alt="">

            <div class="form-floating">
                <?= form_input(['id' => 'fEmail', 'name' => 'email', 'placeholder' => 'name@example.com'], set_value('email'), $extra, 'email') ?>
                <?= form_label('Email address', 'fEmail') ?>
            </div>
            <div class="form-floating">
                <?= form_password(['id' => 'fPassword', 'name' => 'password', 'placeholder' => 'Password'], '', $extra) ?>
                <?= form_label('Password', 'fPassword') ?>
            </div>

            <?= form_submit(['class' => 'btn btn-lg btn-dark mt-2 w-100'], 'Sign in') ?>
        </form>

    </main>

<?= $this->endSection() ?>
