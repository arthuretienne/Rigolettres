<?php
/**
 * [Rigolettres] WooCommerce Design System Override
 *
 * Override complet des styles WooCommerce par défaut pour correspondre
 * au design system Rigolettres (design-system.md) :
 * — Typographie : Kalam (titres) + Nunito (corps)
 * — Palette : crème #FBF8F1, encre #1F2937, vert #68a033, bleu #27B4E5
 * — Border-radius pill sur boutons, 16px sur cartes
 * — Micro-animations hover
 * — Shop grid, fiche produit, tabs, breadcrumb, cart, checkout, notices
 *
 * Scope : front-end
 * Priority : 1 (doit s'appliquer EN PREMIER, avant tout le reste)
 */

add_action('wp_head', function () {
    ?>
    <style id="rigo-wc-ds">

    /* ══════════════════════════════════════════════════
       0. VARIABLES CSS (design tokens)
    ══════════════════════════════════════════════════ */
    :root {
        --rg-bg:            #FBF8F1;
        --rg-bg-alt:        #FFFFFF;
        --rg-ink:           #1F2937;
        --rg-ink-soft:      #4B5563;
        --rg-muted:         #9CA3AF;
        --rg-border:        #E7E2D5;
        --rg-primary:       #27B4E5;
        --rg-primary-dark:  #1E92BC;
        --rg-primary-soft:  #E3F5FC;
        --rg-green:         #68a033;
        --rg-green-dark:    #5a8c2b;
        --rg-green-soft:    #EEF7DE;
        --rg-warm:          #D9A066;
        --rg-warm-soft:     #F7E9D8;
        --rg-radius-sm:     8px;
        --rg-radius-md:     16px;
        --rg-radius-lg:     24px;
        --rg-radius-pill:   9999px;
        --rg-shadow-sm:     0 1px 2px rgba(31,41,55,.06);
        --rg-shadow-md:     0 4px 12px rgba(31,41,55,.08);
        --rg-shadow-lg:     0 12px 32px rgba(31,41,55,.10);
        --rg-transition:    200ms ease-out;
    }

    /* ══════════════════════════════════════════════════
       1. BASE WOOCOMMERCE — fond, texte, liens
    ══════════════════════════════════════════════════ */
    .woocommerce,
    .woocommerce-page {
        background-color: var(--rg-bg);
        color: var(--rg-ink);
        font-family: "Nunito", system-ui, sans-serif;
    }

    /* Supprimer les marges parasites WC */
    .woocommerce .woocommerce-notices-wrapper,
    .woocommerce-page .woocommerce-notices-wrapper { margin-bottom: 0; }

    /* ══════════════════════════════════════════════════
       2. BREADCRUMB
    ══════════════════════════════════════════════════ */
    .woocommerce .woocommerce-breadcrumb {
        font-family: "Nunito", sans-serif;
        font-size: 13px;
        color: var(--rg-muted);
        background: transparent;
        padding: 12px 0;
        margin-bottom: 8px;
        border-bottom: none;
    }
    .woocommerce .woocommerce-breadcrumb a {
        color: var(--rg-muted);
        text-decoration: none;
        transition: color var(--rg-transition);
    }
    .woocommerce .woocommerce-breadcrumb a:hover { color: var(--rg-primary); }

    /* ══════════════════════════════════════════════════
       3. NOTICES / ALERTS
    ══════════════════════════════════════════════════ */
    .woocommerce-message,
    .woocommerce-info,
    .woocommerce-error,
    .wc-block-components-notice-banner {
        font-family: "Nunito", sans-serif;
        border-radius: var(--rg-radius-md);
        border-top: none !important;
        border-left: 4px solid;
        padding: 14px 18px !important;
        margin-bottom: 20px;
    }
    .woocommerce-message {
        background: var(--rg-green-soft) !important;
        border-left-color: var(--rg-green) !important;
        color: #2D5A1B !important;
    }
    .woocommerce-info {
        background: var(--rg-primary-soft) !important;
        border-left-color: var(--rg-primary) !important;
        color: #0B4F6B !important;
    }
    .woocommerce-error {
        background: #FEF2F2 !important;
        border-left-color: #EF4444 !important;
        color: #7F1D1D !important;
    }
    .woocommerce-message::before,
    .woocommerce-info::before { display: none; } /* enlever l'icône WC par défaut */

    /* ══════════════════════════════════════════════════
       4. BOUTIQUE / ARCHIVE — grille produits
    ══════════════════════════════════════════════════ */

    /* Titre de page boutique */
    .woocommerce-products-header__title.page-title,
    .woocommerce-loop-product__title,
    h1.page-title {
        font-family: "Kalam", cursive;
        font-weight: 700;
        color: var(--rg-ink);
    }
    .woocommerce-products-header__title.page-title { font-size: 36px; }

    /* Résultats + tri */
    .woocommerce-result-count,
    .woocommerce-ordering select {
        font-family: "Nunito", sans-serif;
        font-size: 13.5px;
        color: var(--rg-ink-soft);
    }
    .woocommerce-ordering select {
        border: 1.5px solid var(--rg-border);
        border-radius: var(--rg-radius-sm);
        background: var(--rg-bg-alt);
        padding: 6px 12px;
        appearance: auto;
    }

    /* Carte produit */
    .woocommerce ul.products li.product,
    .wc-block-grid__product {
        background: var(--rg-bg-alt);
        border: 1.5px solid var(--rg-border);
        border-radius: var(--rg-radius-md);
        overflow: hidden;
        transition: transform var(--rg-transition), box-shadow var(--rg-transition);
        box-shadow: var(--rg-shadow-sm);
        padding: 0 !important;
        margin-bottom: 24px;
    }
    .woocommerce ul.products li.product:hover,
    .wc-block-grid__product:hover {
        transform: translateY(-3px);
        box-shadow: var(--rg-shadow-md);
    }

    /* Image produit dans la carte */
    .woocommerce ul.products li.product a img,
    .wc-block-grid__product-image img {
        border-radius: 0;
        margin-bottom: 0;
        display: block;
        width: 100%;
        aspect-ratio: 1 / 1;
        object-fit: cover;
    }

    /* Corps de la carte */
    .woocommerce ul.products li.product .woocommerce-loop-product__title,
    .wc-block-grid__product-title {
        font-family: "Nunito", sans-serif;
        font-weight: 800;
        font-size: 15px;
        color: var(--rg-ink);
        padding: 14px 16px 4px;
        margin: 0;
    }

    .woocommerce ul.products li.product .price,
    .wc-block-grid__product-price {
        font-family: "Nunito", sans-serif;
        font-weight: 700;
        font-size: 17px;
        color: var(--rg-green);
        padding: 0 16px 6px;
        display: block;
    }
    .woocommerce ul.products li.product .price del { color: var(--rg-muted); font-size: 13px; font-weight: 400; }

    /* Bouton "Ajouter au panier" sur carte */
    .woocommerce ul.products li.product .button,
    .wc-block-grid__product-add-to-cart .wp-block-button__link {
        font-family: "Nunito", sans-serif !important;
        font-weight: 700 !important;
        font-size: 13.5px !important;
        background: var(--rg-green) !important;
        color: #fff !important;
        border: none !important;
        border-radius: var(--rg-radius-pill) !important;
        padding: 10px 20px !important;
        margin: 8px 16px 16px !important;
        display: inline-block !important;
        width: calc(100% - 32px) !important;
        text-align: center !important;
        box-sizing: border-box !important;
        transition: background var(--rg-transition), transform var(--rg-transition) !important;
        cursor: pointer !important;
        text-decoration: none !important;
        box-shadow: none !important;
    }
    .woocommerce ul.products li.product .button:hover,
    .wc-block-grid__product-add-to-cart .wp-block-button__link:hover {
        background: var(--rg-green-dark) !important;
        transform: none !important;
    }
    .woocommerce ul.products li.product .button.loading::after {
        border-top-color: #fff;
    }

    /* Rating étoiles */
    .woocommerce ul.products li.product .star-rating,
    .woocommerce .star-rating {
        padding: 4px 16px 0;
        color: var(--rg-warm);
    }
    .woocommerce .star-rating span::before { color: var(--rg-warm); }

    /* ══════════════════════════════════════════════════
       5. FICHE PRODUIT — layout général
    ══════════════════════════════════════════════════ */

    /* Fond et conteneur */
    .single-product .site-main,
    .single-product #main {
        background: var(--rg-bg);
    }

    /* Titre produit */
    .single-product .product_title.entry-title {
        font-family: "Kalam", cursive !important;
        font-size: 32px !important;
        line-height: 1.25 !important;
        color: var(--rg-ink) !important;
        font-weight: 700 !important;
        margin-bottom: 8px !important;
    }

    /* Prix */
    .single-product .woocommerce-Price-amount.amount {
        font-family: "Nunito", sans-serif;
        font-size: 28px;
        font-weight: 800;
        color: var(--rg-green);
    }
    .single-product .woocommerce-Price-currencySymbol { font-size: 20px; }
    .single-product .price del .woocommerce-Price-amount { color: var(--rg-muted); font-size: 16px; font-weight: 400; }
    .single-product .price ins { text-decoration: none; }

    /* Texte court / description */
    .single-product .woocommerce-product-details__short-description {
        font-family: "Nunito", sans-serif;
        font-size: 15px;
        line-height: 1.7;
        color: var(--rg-ink-soft);
        margin: 12px 0 20px;
    }
    .single-product .woocommerce-product-details__short-description p { margin-bottom: 10px; }
    .single-product .woocommerce-product-details__short-description ul {
        list-style: none;
        padding: 0;
        margin: 8px 0;
    }
    .single-product .woocommerce-product-details__short-description ul li {
        padding: 4px 0 4px 22px;
        position: relative;
    }
    .single-product .woocommerce-product-details__short-description ul li::before {
        content: "✓";
        position: absolute;
        left: 0;
        color: var(--rg-green);
        font-weight: 700;
    }

    /* Bouton Ajouter au panier principal */
    .single-product .single_add_to_cart_button {
        font-family: "Nunito", sans-serif !important;
        font-weight: 800 !important;
        font-size: 16px !important;
        background: var(--rg-green) !important;
        color: #fff !important;
        border: none !important;
        border-radius: var(--rg-radius-pill) !important;
        padding: 14px 32px !important;
        text-transform: none !important;
        letter-spacing: 0 !important;
        cursor: pointer !important;
        transition: background var(--rg-transition), box-shadow var(--rg-transition) !important;
        box-shadow: 0 4px 16px rgba(104,160,51,.25) !important;
        width: 100% !important;
    }
    .single-product .single_add_to_cart_button:hover {
        background: var(--rg-green-dark) !important;
        box-shadow: 0 6px 20px rgba(104,160,51,.35) !important;
    }

    /* Qty input */
    .woocommerce .quantity input.qty {
        font-family: "Nunito", sans-serif;
        font-size: 16px;
        font-weight: 700;
        border: 2px solid var(--rg-border);
        border-radius: var(--rg-radius-sm);
        background: var(--rg-bg-alt);
        color: var(--rg-ink);
        padding: 10px 8px;
        width: 64px;
        text-align: center;
    }
    .woocommerce .quantity input.qty:focus {
        border-color: var(--rg-green);
        outline: none;
        box-shadow: 0 0 0 3px rgba(104,160,51,.15);
    }

    /* Galerie */
    .woocommerce-product-gallery__image img {
        border-radius: var(--rg-radius-md);
        border: 1.5px solid var(--rg-border);
    }
    .woocommerce-product-gallery__image--placeholder {
        background: var(--rg-bg);
        border: 2px dashed var(--rg-border);
        border-radius: var(--rg-radius-md);
    }
    .flex-viewport { border-radius: var(--rg-radius-md); overflow: hidden; }

    /* ══════════════════════════════════════════════════
       6. TABS (Description / Avis)
    ══════════════════════════════════════════════════ */
    .woocommerce-tabs.wc-tabs-wrapper {
        margin-top: 48px;
        background: transparent;
    }

    /* Barre d'onglets */
    .woocommerce-tabs .tabs {
        border-bottom: 2px solid var(--rg-border) !important;
        padding: 0 !important;
        margin-bottom: 0 !important;
        display: flex;
        gap: 4px;
    }
    .woocommerce-tabs .tabs li {
        background: transparent !important;
        border: none !important;
        border-radius: var(--rg-radius-sm) var(--rg-radius-sm) 0 0 !important;
        margin: 0 !important;
    }
    .woocommerce-tabs .tabs li a {
        font-family: "Nunito", sans-serif !important;
        font-size: 14px !important;
        font-weight: 700 !important;
        color: var(--rg-muted) !important;
        padding: 10px 20px !important;
        border: none !important;
        background: transparent !important;
        border-bottom: 3px solid transparent !important;
        display: block !important;
        transition: color var(--rg-transition) !important;
    }
    .woocommerce-tabs .tabs li.active a,
    .woocommerce-tabs .tabs li:hover a {
        color: var(--rg-ink) !important;
        border-bottom-color: var(--rg-green) !important;
        background: transparent !important;
    }

    /* Panneau onglet */
    .woocommerce-tabs .panel {
        background: var(--rg-bg-alt) !important;
        border: 1.5px solid var(--rg-border) !important;
        border-top: none !important;
        border-radius: 0 0 var(--rg-radius-md) var(--rg-radius-md) !important;
        padding: 28px 32px !important;
        box-shadow: var(--rg-shadow-sm) !important;
    }
    .woocommerce-tabs .panel h2 {
        font-family: "Kalam", cursive;
        font-size: 24px;
        color: var(--rg-ink);
        margin-bottom: 16px;
    }
    .woocommerce-tabs .panel p,
    .woocommerce-tabs .panel li {
        font-family: "Nunito", sans-serif;
        font-size: 15px;
        line-height: 1.75;
        color: var(--rg-ink-soft);
    }
    .woocommerce-tabs .panel h3 {
        font-family: "Nunito", sans-serif;
        font-weight: 800;
        font-size: 17px;
        color: var(--rg-ink);
        margin: 20px 0 8px;
    }

    /* ══════════════════════════════════════════════════
       7. PRODUITS LIÉS / UPSELLS / CROSS-SELLS
    ══════════════════════════════════════════════════ */
    .related.products > h2,
    .upsells.products > h2,
    .cross-sells > h2 {
        font-family: "Kalam", cursive;
        font-size: 26px;
        color: var(--rg-ink);
        margin-bottom: 20px;
    }

    /* ══════════════════════════════════════════════════
       8. PANIER
    ══════════════════════════════════════════════════ */
    .woocommerce-cart-form table.cart,
    .wp-block-woocommerce-cart .wc-block-cart {
        background: var(--rg-bg-alt);
        border: 1.5px solid var(--rg-border);
        border-radius: var(--rg-radius-md);
        overflow: hidden;
    }
    .woocommerce-cart-form table.cart th {
        font-family: "Nunito", sans-serif;
        font-weight: 800;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--rg-muted);
        background: var(--rg-bg) !important;
        border-bottom: 1.5px solid var(--rg-border) !important;
        padding: 14px 16px !important;
    }
    .woocommerce-cart-form table.cart td {
        font-family: "Nunito", sans-serif;
        font-size: 14.5px;
        color: var(--rg-ink-soft);
        border-bottom: 1px solid var(--rg-border) !important;
        padding: 16px !important;
        vertical-align: middle;
    }
    .woocommerce-cart-form table.cart .product-name a {
        font-weight: 700;
        color: var(--rg-ink);
        text-decoration: none;
    }
    .woocommerce-cart-form table.cart .product-name a:hover { color: var(--rg-primary); }
    .woocommerce-cart-form table.cart .product-price,
    .woocommerce-cart-form table.cart .product-subtotal {
        font-weight: 800;
        color: var(--rg-green);
    }

    /* Totaux panier */
    .cart_totals h2 {
        font-family: "Kalam", cursive;
        font-size: 22px;
        color: var(--rg-ink);
        margin-bottom: 16px;
    }
    .cart_totals table {
        font-family: "Nunito", sans-serif;
        background: var(--rg-bg-alt);
        border: 1.5px solid var(--rg-border);
        border-radius: var(--rg-radius-md);
        overflow: hidden;
    }
    .cart_totals table th,
    .cart_totals table td {
        padding: 14px 18px !important;
        border-bottom: 1px solid var(--rg-border) !important;
        font-size: 14.5px;
    }
    .cart_totals table .order-total td { font-weight: 800; color: var(--rg-ink); font-size: 18px; }
    .cart_totals table .order-total .woocommerce-Price-amount { color: var(--rg-green); }

    /* Bouton procéder au paiement */
    .wc-proceed-to-checkout .checkout-button,
    .woocommerce a.checkout-button {
        font-family: "Nunito", sans-serif !important;
        font-weight: 800 !important;
        font-size: 16px !important;
        background: var(--rg-green) !important;
        color: #fff !important;
        border-radius: var(--rg-radius-pill) !important;
        padding: 14px 28px !important;
        border: none !important;
        width: 100% !important;
        text-align: center !important;
        text-transform: none !important;
        letter-spacing: 0 !important;
        box-shadow: 0 4px 16px rgba(104,160,51,.25) !important;
        transition: background var(--rg-transition) !important;
    }
    .wc-proceed-to-checkout .checkout-button:hover { background: var(--rg-green-dark) !important; }

    /* Coupon */
    .coupon input[type="text"],
    .woocommerce .coupon input.input-text {
        font-family: "Nunito", sans-serif;
        border: 1.5px solid var(--rg-border);
        border-radius: var(--rg-radius-sm);
        padding: 10px 14px;
        font-size: 14px;
        background: var(--rg-bg-alt);
        color: var(--rg-ink);
    }
    .woocommerce .coupon .button {
        font-family: "Nunito", sans-serif !important;
        font-weight: 700 !important;
        background: transparent !important;
        color: var(--rg-ink) !important;
        border: 1.5px solid var(--rg-border) !important;
        border-radius: var(--rg-radius-pill) !important;
        padding: 10px 20px !important;
        transition: all var(--rg-transition) !important;
    }
    .woocommerce .coupon .button:hover {
        background: var(--rg-ink) !important;
        color: #fff !important;
    }

    /* Bouton "Mettre à jour le panier" */
    .woocommerce-cart-form button[name="update_cart"] {
        font-family: "Nunito", sans-serif !important;
        font-size: 13px !important;
        color: var(--rg-muted) !important;
        background: transparent !important;
        border: 1.5px solid var(--rg-border) !important;
        border-radius: var(--rg-radius-pill) !important;
        padding: 8px 16px !important;
        cursor: pointer !important;
    }

    /* ══════════════════════════════════════════════════
       9. CHECKOUT
    ══════════════════════════════════════════════════ */
    .woocommerce-checkout h3#order_review_heading,
    .woocommerce-checkout h3 {
        font-family: "Kalam", cursive;
        font-size: 22px;
        color: var(--rg-ink);
        border-bottom: 2px solid var(--rg-border);
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    /* Labels et inputs */
    .woocommerce-checkout label,
    .woocommerce form .form-row label {
        font-family: "Nunito", sans-serif;
        font-size: 13.5px;
        font-weight: 700;
        color: var(--rg-ink-soft);
        margin-bottom: 5px;
        display: block;
    }
    .woocommerce-checkout input[type="text"],
    .woocommerce-checkout input[type="email"],
    .woocommerce-checkout input[type="tel"],
    .woocommerce-checkout input[type="password"],
    .woocommerce-checkout select,
    .woocommerce-checkout textarea,
    .woocommerce form .form-row input.input-text,
    .woocommerce form .form-row select {
        font-family: "Nunito", sans-serif !important;
        font-size: 15px !important;
        border: 1.5px solid var(--rg-border) !important;
        border-radius: var(--rg-radius-sm) !important;
        background: var(--rg-bg-alt) !important;
        color: var(--rg-ink) !important;
        padding: 10px 14px !important;
        width: 100% !important;
        box-sizing: border-box !important;
        transition: border-color var(--rg-transition), box-shadow var(--rg-transition) !important;
    }
    .woocommerce-checkout input:focus,
    .woocommerce-checkout select:focus,
    .woocommerce form .form-row input.input-text:focus {
        border-color: var(--rg-green) !important;
        box-shadow: 0 0 0 3px rgba(104,160,51,.15) !important;
        outline: none !important;
    }

    /* Récap commande */
    #order_review table.shop_table {
        font-family: "Nunito", sans-serif;
        background: var(--rg-bg-alt);
        border: 1.5px solid var(--rg-border);
        border-radius: var(--rg-radius-md);
        overflow: hidden;
    }
    #order_review table.shop_table th,
    #order_review table.shop_table td {
        padding: 12px 16px !important;
        border-bottom: 1px solid var(--rg-border) !important;
        font-size: 14px;
    }
    #order_review table.shop_table .order-total td {
        font-weight: 800;
        font-size: 18px;
    }
    #order_review table.shop_table .order-total .woocommerce-Price-amount { color: var(--rg-green); }

    /* Bouton Passer la commande */
    #place_order {
        font-family: "Nunito", sans-serif !important;
        font-weight: 800 !important;
        font-size: 17px !important;
        background: var(--rg-green) !important;
        color: #fff !important;
        border: none !important;
        border-radius: var(--rg-radius-pill) !important;
        padding: 16px 32px !important;
        width: 100% !important;
        cursor: pointer !important;
        text-transform: none !important;
        letter-spacing: 0 !important;
        box-shadow: 0 6px 20px rgba(104,160,51,.30) !important;
        transition: background var(--rg-transition) !important;
    }
    #place_order:hover { background: var(--rg-green-dark) !important; }

    /* Méthodes de paiement */
    .woocommerce-checkout #payment {
        background: var(--rg-bg) !important;
        border: 1.5px solid var(--rg-border) !important;
        border-radius: var(--rg-radius-md) !important;
    }
    .woocommerce-checkout #payment ul.payment_methods {
        border-bottom: 1px solid var(--rg-border) !important;
    }
    .woocommerce-checkout #payment ul.payment_methods li label {
        font-family: "Nunito", sans-serif !important;
        font-size: 14.5px !important;
        font-weight: 700 !important;
        color: var(--rg-ink) !important;
    }
    .woocommerce-checkout #payment div.payment_box {
        background: var(--rg-bg-alt) !important;
        color: var(--rg-ink-soft) !important;
        font-family: "Nunito", sans-serif !important;
    }

    /* ══════════════════════════════════════════════════
       10. COMPTE CLIENT / MY ACCOUNT
    ══════════════════════════════════════════════════ */
    .woocommerce-MyAccount-navigation ul {
        list-style: none;
        padding: 0;
        background: var(--rg-bg-alt);
        border: 1.5px solid var(--rg-border);
        border-radius: var(--rg-radius-md);
        overflow: hidden;
    }
    .woocommerce-MyAccount-navigation ul li a {
        display: block;
        padding: 12px 18px;
        font-family: "Nunito", sans-serif;
        font-size: 14px;
        font-weight: 600;
        color: var(--rg-ink-soft);
        border-bottom: 1px solid var(--rg-border);
        text-decoration: none;
        transition: background var(--rg-transition), color var(--rg-transition);
    }
    .woocommerce-MyAccount-navigation ul li:last-child a { border-bottom: none; }
    .woocommerce-MyAccount-navigation ul li.is-active a,
    .woocommerce-MyAccount-navigation ul li a:hover {
        background: var(--rg-green-soft);
        color: var(--rg-green);
    }

    /* ══════════════════════════════════════════════════
       11. PAGINATION
    ══════════════════════════════════════════════════ */
    .woocommerce-pagination ul li span.current,
    .woocommerce-pagination ul li a {
        font-family: "Nunito", sans-serif;
        font-size: 14px;
        font-weight: 700;
        border-radius: var(--rg-radius-sm) !important;
        border: 1.5px solid var(--rg-border) !important;
    }
    .woocommerce-pagination ul li span.current {
        background: var(--rg-green) !important;
        color: #fff !important;
        border-color: var(--rg-green) !important;
    }
    .woocommerce-pagination ul li a:hover {
        background: var(--rg-green-soft) !important;
        color: var(--rg-green) !important;
        border-color: var(--rg-green) !important;
    }

    /* ══════════════════════════════════════════════════
       12. BADGES WC NATIFS (Sale, Out of stock)
    ══════════════════════════════════════════════════ */
    .woocommerce span.onsale {
        background: #EF4444 !important;
        color: #fff !important;
        font-family: "Nunito", sans-serif !important;
        font-size: 12px !important;
        font-weight: 800 !important;
        border-radius: var(--rg-radius-full) !important;
        min-width: 44px !important;
        min-height: 44px !important;
        line-height: 44px !important;
        padding: 0 !important;
    }

    /* ══════════════════════════════════════════════════
       13. RESPONSIVE OVERRIDES
    ══════════════════════════════════════════════════ */
    @media (max-width: 768px) {
        .single-product .product_title.entry-title { font-size: 26px !important; }
        .woocommerce-tabs .panel { padding: 20px 18px !important; }
        .cart_totals table th, .cart_totals table td { padding: 12px !important; }
        .woocommerce-checkout input[type="text"],
        .woocommerce-checkout input[type="email"],
        .woocommerce form .form-row input.input-text { font-size: 16px !important; } /* évite zoom iOS */
    }
    </style>
    <?php
}, 1); // priority 1 = en tout premier
