<?php
/**
 * [Rigolettres] Blocksy Design System Override
 *
 * Charge en priority=100 pour passer APRÈS les stylesheets Blocksy enqueués.
 *
 * Problèmes corrigés (diagnostiqués via JS sur fiche produit) :
 * 1. Blocksy palette vars (#2872fa bleu) remplacées par nos tokens design
 * 2. Nav links : system-font → Nunito, couleur blanche → encre sur pages non-home
 * 3. Header : transparent blanc invisible → fond crème solide sur toutes pages hors home
 * 4. Breadcrumb .ct-breadcrumbs : #3A4F66 Blocksy → muted gris neutre
 * 5. Footer : #F2F5F7 froid Blocksy → dark premium #1F2937
 * 6. Tab active : couleur gris → #1F2937 lisible
 * 7. Liens inline : #2872fa Blocksy → #27B4E5 Rigolettres
 * 8. Hamburger mobile : blanc → encre sur pages non-home
 *
 * Scope : front-end
 * Priority : 100 (après tous les stylesheets thème)
 */

// Approche 1 : inline sur ct-main-styles (LiteSpeed-proof, même bloc CSS que Blocksy)
add_action('wp_enqueue_scripts', function () {
    $css = '';
    ob_start();
    ?>
    /* ==== RIGOLETTRES — override Blocksy palette (append to ct-main-styles) ==== */

    /* ══════════════════════════════════════════════════════════════
       1. REMPLACEMENT COMPLET PALETTE BLOCKSY → TOKENS RIGOLETTRES
       Blocksy génère tout depuis ces 8 vars CSS. On les reprend.
    ══════════════════════════════════════════════════════════════ */
    :root {
        /* Blocksy color-1 = accent principal → notre bleu ciel */
        --theme-palette-color-1: #27B4E5 !important;
        /* Blocksy color-2 = accent hover → notre bleu foncé */
        --theme-palette-color-2: #1E92BC !important;
        /* Blocksy color-3 = texte/dark → notre encre chaude */
        --theme-palette-color-3: #1F2937 !important;
        /* Blocksy color-4 = très sombre → notre brun profond */
        --theme-palette-color-4: #3a2913 !important;
        /* Blocksy color-5 = bordures → notre bordure crème */
        --theme-palette-color-5: #E7E2D5 !important;
        /* Blocksy color-6 = fond alternatif → notre crème */
        --theme-palette-color-6: #FBF8F1 !important;
        /* Blocksy color-7 = fond clair → crème douce */
        --theme-palette-color-7: #F7F4ED !important;
        /* Blocksy color-8 = blanc */
        --theme-palette-color-8: #FFFFFF !important;

        /* Variables sémantiques Blocksy */
        --theme-link-initial-color:              #27B4E5 !important;
        --theme-link-hover-color:                #1E92BC !important;
        --theme-headings-color:                  #1F2937 !important;
        --theme-body-text-color:                 #4B5563 !important;
        --theme-button-background-initial-color: #68a033 !important;
        --theme-button-text-initial-color:       #ffffff !important;
        --theme-border-color:                    #E7E2D5 !important;
    }

    /* ══════════════════════════════════════════════════════════════
       2. HEADER — fond solide + texte sombre sur toutes pages ≠ home
       Sur home : transparent + blanc (sur hero dark) = OK
       Sur produit/funnel/pages : crème + encre = lisible
    ══════════════════════════════════════════════════════════════ */
    body:not(.home) .ct-header {
        background-color: rgba(251, 248, 241, 0.97) !important;
        backdrop-filter: blur(10px) !important;
        -webkit-backdrop-filter: blur(10px) !important;
        border-bottom: 1.5px solid #E7E2D5 !important;
        box-shadow: 0 1px 16px rgba(31, 41, 55, .07) !important;
    }

    /* ══════════════════════════════════════════════════════════════
       3. NAV LINKS — Nunito partout, couleur correcte
    ══════════════════════════════════════════════════════════════ */

    /* Font nav : Blocksy injecte son propre stack system, on force Nunito */
    .ct-header .ct-menu-link,
    .ct-header nav a,
    .ct-header .ct-menu > ul > li > a,
    .ct-header .menu-item > a {
        font-family: "Nunito", system-ui, sans-serif !important;
        font-size: 15px !important;
        font-weight: 700 !important;
        letter-spacing: 0 !important;
    }

    /* Couleur nav sur pages ≠ home : encre chaude */
    body:not(.home) .ct-header .ct-menu-link,
    body:not(.home) .ct-header nav a,
    body:not(.home) .ct-header .menu-item > a {
        color: #1F2937 !important;
    }
    body:not(.home) .ct-header .ct-menu-link:hover,
    body:not(.home) .ct-header nav a:hover,
    body:not(.home) .ct-header .menu-item > a:hover {
        color: #68a033 !important;
    }

    /* Item actif (page courante) */
    body:not(.home) .ct-header .current-menu-item > a,
    body:not(.home) .ct-header .current-page-ancestor > a {
        color: #68a033 !important;
        font-weight: 800 !important;
    }

    /* Dropdown sous-menu */
    body:not(.home) .ct-header .sub-menu a {
        color: #1F2937 !important;
        font-family: "Nunito", sans-serif !important;
        font-size: 14px !important;
    }
    body:not(.home) .ct-header .sub-menu a:hover { color: #68a033 !important; }

    /* Hamburger mobile — icône sombre sur fond crème */
    body:not(.home) .ct-toggle-dropdown-mobile-icon svg,
    body:not(.home) [data-id="mobile-trigger"] svg,
    body:not(.home) button[class*="mobile-trigger"] svg,
    body:not(.home) .ct-toggle-dropdown-mobile-icon { color: #1F2937 !important; fill: #1F2937 !important; }
    body:not(.home) .ct-toggle-dropdown-mobile-icon span,
    body:not(.home) .ct-toggle-dropdown-mobile-icon::before,
    body:not(.home) .ct-toggle-dropdown-mobile-icon::after {
        background-color: #1F2937 !important;
    }

    /* Wordmark Rigolettres : reste visible sur toutes pages */
    .ct-header .site-title,
    .ct-header [class*="logo"],
    .ct-header .ct-site-title {
        font-family: "Kalam", cursive !important;
        font-weight: 700 !important;
    }

    /* ══════════════════════════════════════════════════════════════
       4. BREADCRUMB .ct-breadcrumbs (Blocksy natif, pas WC)
    ══════════════════════════════════════════════════════════════ */
    .ct-breadcrumbs {
        font-family: "Nunito", sans-serif !important;
        font-size: 12.5px !important;
        color: #9CA3AF !important;
        padding: 10px 0 4px !important;
        background: transparent !important;
        line-height: 1.5 !important;
    }
    /* Tous les liens breadcrumb = gris muted, PAS bleu */
    .ct-breadcrumbs a,
    .ct-breadcrumbs span[itemprop="name"],
    .ct-breadcrumbs .ct-breadcrumb-item a {
        color: #9CA3AF !important;
        text-decoration: none !important;
        transition: color 150ms !important;
    }
    .ct-breadcrumbs a:hover { color: #68a033 !important; }

    /* Séparateur › discret */
    .ct-breadcrumbs .ct-breadcrumb-separator,
    .ct-breadcrumbs > span:not([itemscope]):not([class]) {
        color: #D1D5DB !important;
        margin: 0 4px !important;
    }
    /* Dernier item (page courante) = encre, bold */
    .ct-breadcrumbs .ct-breadcrumb-item:last-child,
    .ct-breadcrumbs .ct-breadcrumb-item:last-child a,
    .ct-breadcrumbs span[aria-current="page"] {
        color: #4B5563 !important;
        font-weight: 600 !important;
    }

    /* ══════════════════════════════════════════════════════════════
       5. FOOTER PREMIUM — dark #1F2937 cohérent avec marques premium
    ══════════════════════════════════════════════════════════════ */
    .ct-footer {
        background-color: #1F2937 !important;
        color: #D1D5DB !important;
        border-top: none !important;
    }
    .ct-footer * { border-color: rgba(255,255,255,.1) !important; }
    .ct-footer a {
        color: #D1D5DB !important;
        font-family: "Nunito", sans-serif !important;
        text-decoration: none !important;
        transition: color 150ms !important;
    }
    .ct-footer a:hover { color: #ffffff !important; }
    .ct-footer h1, .ct-footer h2, .ct-footer h3, .ct-footer h4, .ct-footer h5 {
        font-family: "Kalam", cursive !important;
        color: #ffffff !important;
        font-size: 18px !important;
    }
    .ct-footer p, .ct-footer li, .ct-footer span, .ct-footer label {
        font-family: "Nunito", sans-serif !important;
        font-size: 13.5px !important;
        color: #9CA3AF !important;
        line-height: 1.7 !important;
    }
    /* Copyright bar en bas du footer */
    .ct-footer-bottom-area,
    .ct-footer [class*="bottom"] {
        background-color: #161E2A !important;
        border-top: 1px solid rgba(255,255,255,.08) !important;
    }
    .ct-footer-bottom-area p,
    .ct-footer-bottom-area span,
    .ct-footer-bottom-area a {
        color: #6B7280 !important;
        font-size: 12px !important;
    }

    /* ══════════════════════════════════════════════════════════════
       6. TABS — onglet actif lisible, pas muted
    ══════════════════════════════════════════════════════════════ */
    .woocommerce-tabs .tabs li.active a,
    .woocommerce-tabs .tabs .active a {
        color: #1F2937 !important;
        font-weight: 800 !important;
        border-bottom-color: #68a033 !important;
    }
    .woocommerce-tabs .tabs li:not(.active) a {
        color: #9CA3AF !important;
        font-weight: 600 !important;
    }
    .woocommerce-tabs .tabs li:not(.active) a:hover {
        color: #4B5563 !important;
    }

    /* ══════════════════════════════════════════════════════════════
       7. LIENS INLINE — Rigolettres bleu, pas Blocksy bleu
    ══════════════════════════════════════════════════════════════ */
    .entry-content a:not(.button):not(.wp-block-button__link):not([class*="rigo"]),
    .woocommerce-product-details__short-description a,
    .woocommerce-Tabs-panel--description a,
    .wc-tab a,
    article.post a:not(.button) {
        color: #27B4E5 !important;
        text-decoration: underline !important;
        text-decoration-color: rgba(39, 180, 229, .35) !important;
        text-underline-offset: 2px !important;
    }
    .entry-content a:hover, .woocommerce-Tabs-panel--description a:hover {
        color: #1E92BC !important;
        text-decoration-color: rgba(30, 146, 188, .6) !important;
    }

    /* ══════════════════════════════════════════════════════════════
       8. FOCUS STATES — accessibilité + cohérence couleur
    ══════════════════════════════════════════════════════════════ */
    :focus-visible {
        outline: 3px solid rgba(39, 180, 229, .5) !important;
        outline-offset: 2px !important;
        border-radius: 4px !important;
    }
    button:focus-visible, .button:focus-visible, input:focus-visible, select:focus-visible {
        box-shadow: 0 0 0 3px rgba(39, 180, 229, .25) !important;
    }

    /* ══════════════════════════════════════════════════════════════
       9. BLOCKSY OFFCANVAS / MOBILE MENU — cohérence fond crème
    ══════════════════════════════════════════════════════════════ */
    .ct-drawer-canvas,
    [class*="offcanvas"],
    [class*="ct-panel"][class*="open"] {
        background-color: #1F2937 !important;
    }
    .ct-drawer-canvas .ct-menu-link,
    .ct-drawer-canvas nav a,
    [class*="offcanvas"] a {
        font-family: "Nunito", sans-serif !important;
        color: #ffffff !important;
        font-size: 18px !important;
        font-weight: 700 !important;
    }
    .ct-drawer-canvas .ct-menu-link:hover,
    .ct-drawer-canvas nav a:hover {
        color: #68a033 !important;
    }

    /* ══════════════════════════════════════════════════════════════
       10. WIDGETS / SIDEBAR
    ══════════════════════════════════════════════════════════════ */
    .widget-title, .widgettitle {
        font-family: "Kalam", cursive !important;
        color: #1F2937 !important;
        font-size: 20px !important;
        border-bottom: 2px solid #E7E2D5 !important;
        padding-bottom: 8px !important;
        margin-bottom: 16px !important;
    }

    /* ══════════════════════════════════════════════════════════════
       11. WP BLOCKS BOUTONS — cohérence totale
    ══════════════════════════════════════════════════════════════ */
    .wp-block-button.is-style-outline .wp-block-button__link {
        border-color: #1F2937 !important;
        color: #1F2937 !important;
        background: transparent !important;
        border-radius: 9999px !important;
        font-family: "Nunito", sans-serif !important;
        font-weight: 700 !important;
    }
    .wp-block-button.is-style-outline .wp-block-button__link:hover {
        background: #1F2937 !important;
        color: #fff !important;
    }

    /* ══════════════════════════════════════════════════════════════
       12. FICHE PRODUIT — micro-polish
    ══════════════════════════════════════════════════════════════ */

    /* Titre produit : couleur cohérente home vs produit */
    .single-product .product_title.entry-title {
        color: #1F2937 !important;
    }

    /* Gallery border = crème, pas gris Blocksy */
    .woocommerce-product-gallery figure img,
    .woocommerce-product-gallery .flex-viewport {
        border: 1.5px solid #E7E2D5 !important;
        border-radius: 16px !important;
    }
    .woocommerce-product-gallery .flex-viewport { overflow: hidden !important; }

    /* Thumbnails galerie */
    .woocommerce-product-gallery .flex-control-thumbs li img {
        border: 2px solid transparent !important;
        border-radius: 8px !important;
        opacity: .7 !important;
        cursor: pointer !important;
        transition: opacity 150ms, border-color 150ms !important;
    }
    .woocommerce-product-gallery .flex-control-thumbs li img.flex-active,
    .woocommerce-product-gallery .flex-control-thumbs li img:hover {
        border-color: #68a033 !important;
        opacity: 1 !important;
    }

    /* Zone ATC + meta */
    .single-product .cart { display: flex; gap: 12px; align-items: center; }
    .single-product .product_meta {
        font-family: "Nunito", sans-serif !important;
        font-size: 13px !important;
        color: #9CA3AF !important;
        margin-top: 12px !important;
        padding-top: 12px !important;
        border-top: 1px solid #E7E2D5 !important;
    }
    .single-product .product_meta a { color: #27B4E5 !important; }
    .single-product .product_meta a:hover { color: #1E92BC !important; }

    /* ══════════════════════════════════════════════════════════════
       13. SHOP / ARCHIVE PAGE — titre + toolbar
    ══════════════════════════════════════════════════════════════ */
    .woocommerce-products-header .page-title {
        font-family: "Kalam", cursive !important;
        color: #1F2937 !important;
        font-size: 36px !important;
    }
    .woocommerce-result-count {
        font-family: "Nunito", sans-serif !important;
        color: #9CA3AF !important;
        font-size: 13px !important;
    }

    /* ══════════════════════════════════════════════════════════════
       14. CART / CHECKOUT — harmonisation header/footer
    ══════════════════════════════════════════════════════════════ */
    .woocommerce-cart h1.entry-title,
    .woocommerce-checkout h1.entry-title {
        font-family: "Kalam", cursive !important;
        color: #1F2937 !important;
        font-size: 32px !important;
    }

    /* ══════════════════════════════════════════════════════════════
       15. NOTICES / ALERTS — cohérence palette (not Blocksy blue)
    ══════════════════════════════════════════════════════════════ */
    .ct-notice,
    .blocksy-notice,
    [class*="ct-alert"] {
        font-family: "Nunito", sans-serif !important;
        border-radius: 10px !important;
        border-left: 4px solid #27B4E5 !important;
        background: #E3F5FC !important;
        color: #0B4F6B !important;
        font-size: 14px !important;
        padding: 12px 16px !important;
    }

    <?php
    $css = ob_get_clean();
    // Appended to ct-main-styles-inline-css → source order gagne sur #2872fa de Blocksy
    // LiteSpeed ne peut pas supprimer ce CSS car il est dans le même bloc que le thème
    wp_add_inline_style('ct-main-styles', $css);
}, 999); // priority=999 → s'ajoute APRÈS les inline styles de Blocksy
