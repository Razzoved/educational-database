<footer class="footer">
    <!-- Section: Social media -->
    <section class="footer__big-area">
        <!-- Facebook -->
        <a target="_blank" href="https://www.facebook.com/academicintegrity.eu" role="button">
            <i class="footer__icon fa-brands fa-facebook"></i>
        </a>

        <!-- Twitter -->
        <a target="_blank" href="https://twitter.com/enaintegrity" role="button">
            <i class="footer__icon fa-brands fa-twitter"></i>
        </a>

        <!-- LinkedIn -->
        <a target="_blank" href="https://www.linkedin.com/in/enai-european-network-for-academic-integrity-9a4578168/" role="button">
            <i class="footer__icon fa-brands fa-linkedin"></i>
        </a>

        <!-- YouTube -->
        <a target="_blank" href="https://www.youtube.com/@europeannetworkforacademic4752" role="button">
            <i class="footer__icon fa-brands fa-youtube"></i>
        </a>

        <!-- Instagram -->
        <a target="_blank" href="https://www.instagram.com/enai_integrity/" role="button">
            <i class="footer__icon fa-brands fa-instagram"></i>
        </a>

        <!-- Mail -->
        <a target="_blank" href="mailto:%20info@academicintegrity.eu" role="button">
            <i class="footer__icon fa-solid fa-envelope"></i>
        </a>
    </section>

    <?php $aboutURL = model(ConfigModel::class)->find('about_url') ?>
    <?php if ($aboutURL) : ?>
        <section class="footer__small-area">
            <a id="link-about" href="<?= $aboutURL->value ?>" style="color: white">
                About
            </a>
        </section>
    <?php endif; ?>

    <!-- Copyright -->
    <section class="footer__small-area">
        <p class="footer__text">© 2023 ENAI. All rights reserved</p>
    </section>
</footer>
