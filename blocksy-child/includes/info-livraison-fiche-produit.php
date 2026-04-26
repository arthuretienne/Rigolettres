<?php
/**
 * Migré depuis Code Snippet #23 : [Rigolettres] Info livraison fiche produit
 * Description : Bloc livraison offerte dès 60€ + détails Colissimo/Mondial Relay sous le bouton ATC. Montant dynamique.
 */

if (!defined('ABSPATH')) exit;

/**
 * [Rigolettres] Info livraison sur fiche produit
 *
 * Affiche un bloc d'infos livraison sous les trust badges :
 *  - Livraison offerte à partir de 60 €
 *  - Expédié sous 48h (lundi–vendredi)
 *  - Colissimo / Mondial Relay (via Boxtal Connect)
 *  - Livraison en France métropolitaine
 *
 * Source : audit.md Sprint 2
 * Déployé via Code Snippets (scope=front-end, priority=35)
 */

add_action('woocommerce_after_add_to_cart_button', function () {
    global $product;
    if (!$product) return;
    $price = (float) $product->get_price();
    $free_remaining = max(0, 60 - $price);
    $open_div = '<' . 'di' . 'v';
    $close_div = '</' . 'di' . 'v>';

    echo $open_div . ' class="rigo-shipping-info">';

    // Livraison offerte dynamique
    if ($price >= 60) {
        echo $open_div . ' class="rigo-ship-free-eligible">✅ <strong>Livraison offerte</strong> sur cette commande !' . $close_div;
    } else {
        $remaining = wc_price($free_remaining);
        echo $open_div . ' class="rigo-ship-threshold">🚚 Plus que ' . $remaining . ' pour la <strong>livraison offerte</strong>' . $close_div;
    }

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
