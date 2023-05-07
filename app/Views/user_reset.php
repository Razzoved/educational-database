<?php
    /**
     * View for reseting passwords.
     */
    helper('form');
    $errors = $errors ?? [];
?>

<?= $this->extend('layouts/form') ?>

<?= $this->section('content') ?>
    <div class="page page--centered page--dark page--w30">
        <form class="form" method="post" action="<?= url_to('Authentication::resetSubmit')?>">

            <!-- logo with errors -->
            <div class="form__group form__group--centered">
                <img class="form__logo" src="<?= base_url('public/assets/enai-logo-transparent.png') ?>" alt="ENAI logo">
                <?= $this->include('errors/one', ['errors' => $errors]) ?>
            </div>

            <input type="hidden" id="id" name="id" value="<?= set_value('id') ?>" required>

            <label for="token" class="form__label">Token</label>
            <input class="form__input"
                type="text"
                id="token"
                name="token"
                value="<?= set_value('token') ?>"
                required>

            <!-- user inputs -->
            <fieldset id="password-changer" class="form__group">

                <label class="form__label" for="password">New password</label>
                <input class="form__input"
                    type="password"
                    id="password"
                    name="password"
                    placeholder="**********"
                    autocomplete="new-password"
                    required>

                <label class="form__label" for="confirmPassword">Confirm new password</label>
                <input class="form__input"
                    type="password"
                    id="confirmPassword"
                    name="confirmPassword"
                    placeholder="**********"
                    autocomplete="new-password"
                    required>

            </fieldset>

            <!-- actions -->
            <div class="form__group form__group--horizontal">
                <button class="form__submit" type="submit">Change password</button>
                <button class="form__cancel" type="button" onclick="window.location.href='<?= url_to('Authentication::index') ?>'">Cancel</button>
            </div>
        </form>
    </div>
<?= $this->endSection() ?>
