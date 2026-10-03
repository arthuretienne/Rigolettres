<?php
/**
 * Migré depuis Code Snippet #23 : [Rigolettres] Info livraison fiche produit
 * Description : Bloc infos livraison (prix d'entrée point relais + délai + transporteurs) sous le bouton ATC.
 */

if (!defined('ABSPATH')) exit;

/**
 * [Rigolettres] Info livraison sur fiche produit
 *
 * Affiche un bloc d'infos livraison sous les trust badges :
 *  - Prix d'entrée en point relais (rigo_shipping_from_price(), 4,90 € aujourd'hui)
 *  - Expédié sous 48h (lundi–vendredi)
 *  - Colissimo / Mondial Relay (via Boxtal Connect)
 *  - Livraison en France métropolitaine
 *
 * Il n'y a plus de livraison offerte depuis le 2026-10-03 (méthode retirée de
 * la zone WooCommerce), d'où l'argument « à partir de » au lieu du franco.
 *
 * Source : audit.md Sprint 2
 */

add_action('woocommerce_after_add_to_cart_button', function () {
    global $product;
    if (!$product) return;
    $open_div = '<' . 'di' . 'v';
    $close_div = '</' . 'di' . 'v>';

    echo $open_div . ' class="rigo-shipping-info">';

    echo $open_div . ' class="rigo-ship-threshold">🚚 Livraison en <strong>point relais</strong> dès ' . rigo_shipping_from_label() . $close_div;

    echo $open_div . ' class="rigo-ship-details">';
    echo '<span class="rigo-ship-item">📦 Expédié sous <strong>48h</strong> (lun–ven)</span>';
    echo '<span class="rigo-ship-item">📍 Colissimo &amp; Mondial Relay</span>';
    echo '<span class="rigo-ship-item">🇫🇷 France métropolitaine</span>';
    echo $close_div;

    echo $close_div; // .rigo-shipping-info
}, 35);

// CSS
add_action('wp_head', function () {
    if (!is_product()) return;
    $open = '<' . 'st' . 'yle id="rigo-shipping-info-css">';
    $close = '</' . 'st' . 'yle>';
    $css = '
.rigo-shipping-info {
    margin-top: 16px;
    padding: 14px 16px;
    background: #F4FBF9;
    border: 1.5px solid #8BC84B;
    border-radius: 10px;
    font-family: Nunito, sans-serif;
    font-size: 13.5px;
    color: #2D4A1E;
    line-height: 1.5;
}
.rigo-ship-free-eligible {
    font-size: 14px;
    margin-bottom: 8px;
    color: #27AE60;
}
.rigo-ship-threshold {
    font-size: 13.5px;
    margin-bottom: 8px;
    color: #5D6D7E;
}
.rigo-ship-details {
    display: flex;
    flex-wrap: wrap;
    gap: 6px 16px;
}
.rigo-ship-item {
    white-space: nowrap;
    color: #445;
}
@media (max-width: 480px) {
    .rigo-ship-details {
        flex-direction: column;
        gap: 4px;
    }
}
';
    echo $open . $css . $close . "\n";
}, 30);
