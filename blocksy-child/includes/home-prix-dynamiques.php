<?php
/**
 * [Rigolettres] Prix dynamiques sur les cartes produit de la home
 *
 * La section « Notre petite collection » de la page d'accueil (id 21) est du
 * HTML Gutenberg écrit à la main : chaque carte contient un prix en dur dans
 * <span class="product-price">20 <small>€</small></span>.
 *
 * Constaté le 2026-10-10 : les cinq prix avaient divergé du back-office.
 *   Pato N°1      affichait 20 €  au lieu de 28 €
 *   Luna N°2      affichait 20 €  au lieu de 30 €
 *   Passé Simple  affichait 22 €  au lieu de 28 €
 *   Grammaire 1   affichait 24 €  au lieu de 18 €
 *   Grammaire 2   affichait 24 €  au lieu de 25 €
 *
 * Trois cartes annonçaient donc un prix INFÉRIEUR à celui du panier : en vente
 * à distance le prix affiché engage le vendeur, et c'est de toute façon le
 * meilleur moyen de faire abandonner un panier.
 *
 * Plutôt que de corriger les cinq nombres (qui redivergeront à la prochaine
 * hausse), on les remplace au rendu par le prix réel du produit, retrouvé via
 * l'attribut data-pid déjà présent sur le bouton « Ajouter au panier ».
 *
 * Si un produit est introuvable ou sans prix, le HTML d'origine est laissé tel
 * quel : on ne casse jamais l'affichage.
 */

if (!defined('ABSPATH')) exit;

add_filter('the_content', function ($content) {
    if (!is_front_page() || !function_exists('wc_get_product')) {
        return $content;
    }

    if (strpos($content, 'class="product-price"') === false) {
        return $content;
    }

    $patched = preg_replace_callback(
        '#<span class="product-price">\s*[0-9][0-9.,\s]*<small>€</small></span>(\s*<button[^>]*data-pid="(\d+)")#u',
        function ($m) {
            $product = wc_get_product((int) $m[2]);
            if (!$product) {
                return $m[0];
            }

            $price = (float) $product->get_price();
            if ($price <= 0) {
                return $m[0];
            }

            // 28 → « 28 » ; 28.50 → « 28,50 »
            $decimals = (floor($price) === $price) ? 0 : 2;
            $label    = number_format($price, $decimals, ',', ' ');

            return '<span class="product-price">' . esc_html($label) . ' <small>€</small></span>' . $m[1];
        },
        $content
    );

    // preg_replace_callback renvoie null en cas d'erreur (backtrack limit…).
    return (null === $patched) ? $content : $patched;
}, 20);
