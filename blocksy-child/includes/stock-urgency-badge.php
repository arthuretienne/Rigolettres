<?php
/**
 * Migré depuis Code Snippet #21 : [Rigolettres] Stock urgency badge
 * Description : Badge orange/rouge stock ≤ 5 sur fiche produit + pastille sur cards archive. Pulsation CSS quand dernier exemplaire.
 */

if (!defined('ABSPATH')) exit;

/**
 * [Rigolettres] Stock urgency badge
 *
 * Affiche "Plus que X en stock !" en orange quand stock ≤ 5
 * sur la fiche produit WooCommerce (sous le bouton ATC).
 * Affiche aussi un badge "Rupture imminente" sur les cards boutique.
 *
 * Source : audit.md Sprint 2
 * Déployé via Code Snippets (scope=front-end, priority=30)
 */

// Fiche produit — sous le bouton ATC
add_action('woocommerce_after_add_to_cart_button', function () {
    global $product;
    if (!$product || !$product->managing_stock()) return;
    $qty = $product->get_stock_quantity();
    if ($qty === null || $qty > 5) return;

    $open_div = '<' . 'di' . 'v';
    if ($qty <= 0) {
        echo $open_div . ' class="rigo-stock-badge rigo-stock-out">⚠️ Rupture de stock</' . 'div>';
    } elseif ($qty === 1) {
        echo $open_div . ' class="rigo-stock-badge rigo-stock-critical">🔥 Dernier exemplaire en stock !</' . 'div>';
    } else {
        echo $open_div . ' class="rigo-stock-badge rigo-stock-low">⚡ Plus que ' . (int)$qty . ' en stock — commandez vite !</' . 'div>';
    }
}, 30);

// CSS
add_action('wp_head', function () {
    if (!is_product() && !is_shop() && !is_product_category()) return;
    $open = '<' . 'st' . 'yle id="rigo-stock-badge-css">';
    $close = '</' . 'st' . 'yle>';
    $css = '
.rigo-stock-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-top: 10px;
    padding: 8px 14px;
    border-radius: 8px;
    font-family: Nunito, sans-serif;
    font-size: 14px;
    font-weight: 700;
    letter-spacing: .01em;
    line-height: 1.2;
}
.rigo-stock-low {
    background: #FFF3CD;
    color: #856404;
    border: 1.5px solid #FFD04A;
}
.rigo-stock-critical {
    background: #FFE5E5;
    color: #C0392B;
    border: 1.5px solid #F1948A;
    animation: rigo-pulse 1.6s ease-in-out infinite;
}
.rigo-stock-out {
    background: #F8F9FA;
    color: #6C757D;
    border: 1.5px solid #DEE2E6;
}
@keyframes rigo-pulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(231,76,60,0); }
    50% { box-shadow: 0 0 0 5px rgba(231,76,60,.18); }
}
/* Archive cards — petite pastille */
.woocommerce ul.products li.product .rigo-stock-pill {
    position: absolute;
    top: 8px;
    left: 8px;
    background: #E74C3C;
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    font-family: Nunito, sans-serif;
    padding: 3px 8px;
    border-radius: 20px;
    z-index: 5;
    line-height: 1.4;
}
';
    echo $open . $css . $close . "\n";
}, 30);

// Badge sur les cards archive (shop / catégorie)
add_action('woocommerce_before_shop_loop_item_title', function () {
    global $product;
    if (!$product || !$product->managing_stock()) return;
    $qty = $product->get_stock_quantity();
    if ($qty === null || $qty > 5 || $qty <= 0) return;
    echo '<span class="rigo-stock-pill">⚡ Plus que ' . (int)$qty . ' en stock</span>';
}, 5);
