<?php
/**
 * Migré depuis Code Snippet #28 : [Rigolettres] 17 — Sticky nav scroll hide/show
 * Description : Header se cache au scroll vers le bas, réapparaît au scroll vers le haut + ombre backdrop-filter
 */

if (!defined('ABSPATH')) exit;

/**
 * [Rigolettres] Sticky nav — hide on scroll down, reveal on scroll up
 *
 * Comportement :
 * - Header se cache proprement quand on scroll vers le bas (> 80px)
 * - Réapparaît instantanément quand on remonte (comme Allbirds, Respire)
 * - Ajoute une ombre douce quand le header est sticky
 * - Sur mobile : hamburger padding safe-area-inset géré
 *
 * Scope : front-end
 * Priority : 8
 */

add_action('wp_footer', function () {
    ?>
    <style id="rigo-sticky-nav-css">
    /* ── Transition header ── */
    #masthead,
    .site-header,
    header.site-header,
    .ct-header,
    header[class*="header"] {
        transition: transform 280ms cubic-bezier(.4,0,.2,1),
                    box-shadow 280ms ease,
                    background 200ms ease !important;
        will-change: transform;
        position: sticky !important;
        top: 0 !important;
        z-index: 500 !important;
    }

    /* Classe ajoutée quand le header est éloigné du top */
    .rigo-header-scrolled #masthead,
    .rigo-header-scrolled .site-header,
    .rigo-header-scrolled .ct-header,
    .rigo-header-scrolled header[class*="header"] {
        box-shadow: 0 2px 16px rgba(31,41,55,.10) !important;
        background: rgba(251,248,241,.97) !important;
        backdrop-filter: blur(8px) !important;
        -webkit-backdrop-filter: blur(8px) !important;
    }

    /* Classe appliquée quand on scroll vers le bas — header caché */
    .rigo-header-hidden #masthead,
    .rigo-header-hidden .site-header,
    .rigo-header-hidden .ct-header,
    .rigo-header-hidden header[class*="header"] {
        transform: translateY(-100%) !important;
        box-shadow: none !important;
    }

    /* Safe-area iPhone encoche (iOS 11+) */
    @supports (padding-top: env(safe-area-inset-top)) {
        #masthead,
        .site-header,
        .ct-header {
            padding-top: env(safe-area-inset-top);
        }
    }
    </style>

    <script id="rigo-sticky-nav-js">
    (function () {
        var lastY = 0;
        var ticking = false;
        var HIDE_THRESHOLD = 80;  // px depuis le top avant d'activer le hide
        var SCROLL_DELTA   = 8;   // px de scroll minimal pour déclencher

        function update() {
            var currentY = window.scrollY || window.pageYOffset;
            var delta    = currentY - lastY;
            var html     = document.documentElement;

            // Ajout classe "scrolled" dès qu'on quitte le top
            if (currentY > 20) {
                html.classList.add('rigo-header-scrolled');
            } else {
                html.classList.remove('rigo-header-scrolled');
                html.classList.remove('rigo-header-hidden');
                lastY = currentY;
                ticking = false;
                return;
            }

            // Scroll vers le bas et au-delà du seuil → cacher
            if (delta > SCROLL_DELTA && currentY > HIDE_THRESHOLD) {
                html.classList.add('rigo-header-hidden');
            }
            // Scroll vers le haut → montrer
            else if (delta < -SCROLL_DELTA) {
                html.classList.remove('rigo-header-hidden');
            }

            lastY = currentY;
            ticking = false;
        }

        window.addEventListener('scroll', function () {
            if (!ticking) {
                window.requestAnimationFrame(update);
                ticking = true;
            }
        }, { passive: true });
    })();
    </script>
    <?php
}, 8);
