<?php
/**
 * [Rigolettres] En-tête sticky : se replie au défilement vers le bas,
 * réapparaît dès qu'on remonte.
 *
 * Le script ne fait que poser deux classes sur <html> :
 *   - rigo-header-scrolled : la page a quitté le haut (ombre sous l'en-tête)
 *   - rigo-header-hidden   : l'en-tête est replié
 * Le CSS correspondant vit dans style.css (section 8) et ne cible QUE
 * #site-header. L'ancienne version ciblait `header[class*="header"]`, ce qui
 * rendait aussi sticky les titres de page (`header.entry-header`).
 *
 * L'en-tête ne se replie jamais tant que le mega-menu ou le tiroir est ouvert,
 * ni quand le focus clavier est dedans.
 */

if (!defined('ABSPATH')) exit;

add_action('wp_footer', function () {
    if (is_admin()) return;
    ?>
    <script id="rigo-sticky-nav-js">
    (function () {
        var html = document.documentElement;
        var header = document.getElementById('site-header');
        if (!header) return;

        var lastY = window.scrollY || 0;
        var ticking = false;
        var HIDE_AFTER = 160; // px depuis le haut avant d'autoriser le repli
        var DELTA = 8;        // amplitude minimale pour changer d'état

        function locked() {
            return html.classList.contains('rigo-mega-open')
                || document.body.classList.contains('mobile-menu-open')
                || header.contains(document.activeElement);
        }

        function update() {
            var y = window.scrollY || 0;
            var delta = y - lastY;

            html.classList.toggle('rigo-header-scrolled', y > 20);

            if (y <= HIDE_AFTER || locked()) {
                html.classList.remove('rigo-header-hidden');
            } else if (delta > DELTA) {
                html.classList.add('rigo-header-hidden');
            } else if (delta < -DELTA) {
                html.classList.remove('rigo-header-hidden');
            }

            if (Math.abs(delta) > DELTA || y <= HIDE_AFTER) lastY = y;
            ticking = false;
        }

        window.addEventListener('scroll', function () {
            if (!ticking) {
                window.requestAnimationFrame(update);
                ticking = true;
            }
        }, { passive: true });

        // Le focus clavier revient dans l'en-tête replié : on le montre
        header.addEventListener('focusin', function () {
            html.classList.remove('rigo-header-hidden');
        });

        update();
    })();
    </script>
    <?php
}, 8);
