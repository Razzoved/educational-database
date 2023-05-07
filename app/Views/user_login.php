<?php
    /**
     * View serving as an entry point to the administration interface.
     *
     * @
     */
    helper('form') ?> <!-- set_value function -->
?>

<?= $this->extend('layouts/form') ?>

<?= $this->section('content') ?>
    <div class="page page--centered page--dark page--w30">
        <form class="form" method="post" action="<?= url_to('Authentication::login') ?>" autocomplete="on">

        <!-- logo with errors -->
            <div class="form__group form__group--centered">
                <img class="form__logo" src="<?= base_url('public/assets/enai-logo-transparent.png') ?>" alt="ENAI logo">
                <?= $this->include('errors/one', ['errors' => $errors]) ?>
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
            <button class="form__input tooltip" data-tooltip="Fill the email field before resetting!" type="button" onclick="redirectToReset()">Forgot password</button>
            <button class="form__submit" type="submit">Sign in</button>
        </form>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script type="text/javascript">
    const redirectToReset = () => {
        const url = '<?= url_to('Authentication::reset', '@email@') ?>';
        const email = document.getElementById('email');
        window.location.href = url.replace('@email@', email.value);
    }
</script>
<?= $this->endSection() ?>
