<?= $this->extend('layouts/form') ?>

<?= $this->section('header') ?>
    <!-- metadata -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('public/assets/favicon.ico') ?>"/>
    <title>Sign in</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?php helper('form') ?> <!-- set_value function -->

    <div class="page page--centered page--dark">
        <form class="form" method="post" action="<?= url_to('Authentication::login') ?>" autocomplete="on">
            <!-- logo with errors -->
            <div class="form__group form__group--centered">
                <img class="form__logo" src="<?= base_url('public/assets/enai-logo-transparent.png') ?>" alt="ENAI logo">
                <?= $this->include('errors/validation_single') ?>
            </div>
            <!-- user inputs -->
            <fieldset class="form__group">
                <label class="form__label" for="email">Email</label>
                <input class="form__input"
                    type="email"
                    id="email"
                    name="email"
                    placeholder="name@example.com"
                    value="<?= set_value('email') ?>"
                    required>
                <label class="form__label" for="password">Password</label>
                <input class="form__input"
                    type="password"
                    id="password"
                    name="password"
                    placeholder="***********"
                    required>
            </fieldset>
            <!-- actions -->
            <button class="form__submit" type="submit">Sign in</button>
        </form>
    </div>
<?= $this->endSection() ?>
