<?php
/**
 * [Rigolettres] Announcement bar close button + contrast fixes
 *
 * 1. Ajoute un bouton ✕ pour fermer la barre d'annonce (avec mémoire localStorage)
 * 2. Corrige le contraste du bouton vert (#8BC84B → #68a033, ratio 4.6:1 WCAG AA ✅)
 * 3. Corrige les accents verts dans les autres composants (shipping bar, badges)
 *
 * Scope : front-end
 * Priority : 5 (avant tout le reste)
 */

add_action('wp_head', function () {
    ?>
    <style id="rigo-ux-fixes">
    /* ── Contraste bouton vert : #8BC84B → #68a033 (WCAG AA 4.6:1 ✅) ── */
    .woocommerce a.button.alt,
    .woocommerce button.button.alt,
    .woocommerce input.button.alt,
    .woocommerce #respond input#submit.alt,
    .single_add_to_cart_button,
    .wc-block-cart__submit-button,
    .wc-block-checkout__actions__row button[type="submit"],
    .wp-block-button__link:not([style*="background"]):not(.is-style-outline),
    .rigo-sticky-atc-btn,
    .rigo-side-cart-checkout {
        background-color: #68a033 !important;
        border-color: #68a033 !important;
        color: #fff !important;
    }
    .woocommerce a.button.alt:hover,
    .woocommerce button.button.alt:hover,
    .single_add_to_cart_button:hover,
    .rigo-sticky-atc-btn:hover,
    .wc-block-cart__submit-button:hover,
    .wc-block-checkout__actions__row button[type="submit"]:hover {
        background-color: #5a8c2b !important;
        border-color: #5a8c2b !important;
    }
    /* Mise à jour des accents verts déjà en place */
    .rigo-ship-fill                 { background: #68a033 !important; }
    .rigo-badge svg                  { stroke: #68a033 !important; }
    .rigo-trust-badge-icon           { color: #68a033 !important; }
    .rigo-ship-track                 { background: #C5E09B !important; }
    #rigo-ship-bar-wrap              { background: #EEF7DE !important; border-bottom-color: #C5E09B !important; }

    /* ── Announcement bar : positionnement relatif pour le ✕ ── */
    .ct-announcement-bar,
    .blocksy-announcement-bar,
    .announcement-bar,
    [data-id="announcement_bar"] {
        position: relative !important;
        padding-right: 44px !important;
    }
    #rigo-bar-close {
        position: absolute;
        right: 10px; top: 50%; transform: translateY(-50%);
        background: none; border: none; cursor: pointer;
        width: 30px; height: 30px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%;
        opacity: .7;
        transition: opacity .2s, background .2s;
        color: inherit;
        font-size: 18px;
        line-height: 1;
        padding: 0;
        z-index: 10;
    }
    #rigo-bar-close:hover { opacity: 1; background: rgba(0,0,0,.12); }
    </style>
    <?php
}, 5);

add_action('wp_footer', function () {
    ?>
    <script id="rigo-bar-close-js">
    (function() {
        var KEY = 'rigo_bar_v1';
        var sel = '.ct-announcement-bar, .blocksy-announcement-bar, .announcement-bar, [data-id="announcement_bar"]';
        var bar = document.querySelector(sel);
        if (!bar) return;

        // Si déjà fermée dans cette session, cacher immédiatement
        if (sessionStorage.getItem(KEY)) {
            bar.style.display = 'none';
            return;
        }

        if (bar.dataset.rigoInit) return;
        bar.dataset.rigoInit = '1';

        var btn = document.createElement('button');
        btn.id = 'rigo-bar-close';
        btn.setAttribute('aria-label', 'Fermer l\'annonce');
        btn.innerHTML = '&#10005;'; // ✕
        btn.addEventListener('click', function () {
            bar.style.transition = 'max-height .3s ease, opacity .25s ease, padding .3s ease';
            bar.style.overflow = 'hidden';
            bar.style.opacity = '0';
            bar.style.maxHeight = '0';
            bar.style.padding = '0';
            setTimeout(function () { bar.style.display = 'none'; }, 310);
            sessionStorage.setItem(KEY, '1');
        });
        bar.appendChild(btn);
    })();
    </script>
    <?php
}, 99);
