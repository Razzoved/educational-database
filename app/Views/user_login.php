<?php

/**
 * View serving as an entry point to the administration interface.
 *
 * @
 */
helper('form');
$errors = $errors ?? [];
?>

<?= $this->extend('layouts/form') ?>

<?= $this->section('content') ?>
<div class="page page--centered page--dark page--w30">
    <form class="form" method="post" action="<?= url_to('Authentication::login') ?>" autocomplete="on">
        <?= csrf_field() ?>

        <!-- logo with errors -->
        <div class="form__group form__group--centered">
            <img class="form__logo" src="<?= base_url('assets/enai-logo-transparent.png') ?>" alt="ENAI logo">
            <?= $this->include('errors/one', ['errors' => $errors]) ?>
        </div>

        <!-- user inputs -->
        <fieldset class="form__group">

            <label class="form__label" for="email">Email</label>
            <input class="form__input" type="email" id="email" name="email" placeholder="name@example.com" value="<?= set_value('email') ?>" required>

            <label class="form__label" for="password">Password</label>
            <input class="form__input" type="password" id="password" name="password" placeholder="***********" required>

        </fieldset>

        <!-- actions -->
        <button class="form__submit" type="submit">Sign in</button>
        <button class="form__cancel" type="button" onclick="redirectToReset()">Forgot password</button>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script type="text/javascript">
    const redirectToReset = () => {
        const email = document.getElementById('email');
        if (!email.value.match(/[^@]+@[^@]+\.[^@\.]+/)) {
            console.debug('Invalid email address: ' + email);
            return alert('Please enter a valid email');
        }
        const url = '<?= url_to('Authentication::reset', '@email@') ?>';
        window.location.href = url.replace('@email@', email.value);
    }
</script>
<?= $this->endSection() ?>