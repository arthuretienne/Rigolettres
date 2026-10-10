<?php
/**
 * Blocksy Child Rigolettres — bootstrap.
 *
 * - Charge la feuille du parent puis celle de l'enfant (versionnée par mtime → busting auto).
 * - Préconnexion + chargement Google Fonts : Fraunces (titres), Nunito (texte), Caveat (script).
 *   Seules les graisses utilisées par le design system sont demandées.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_enqueue_scripts', function () {
    $parent = get_template_directory_uri() . '/style.css';
    wp_enqueue_style('blocksy-parent', $parent, [], wp_get_theme(get_template())->get('Version'));

    $child_path = get_stylesheet_directory() . '/style.css';
    $child_uri  = get_stylesheet_directory_uri() . '/style.css';
    $version    = file_exists($child_path) ? filemtime($child_path) : '1.0.0';
    wp_enqueue_style('blocksy-child', $child_uri, ['blocksy-parent'], $version);
}, 20);

add_action('wp_head', function () {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Nunito:wght@400;600;700;800&family=Caveat:wght@700&display=swap">' . "\n";
}, 1);

/**
 * Prix de livraison le moins cher affiché au client, en euros.
 *
 * Il n'y a plus de livraison offerte (méthode « Livraison gratuite » retirée de
 * la zone WooCommerce le 2026-10-03). L'argument commercial devient « à partir
 * de X € en point relais ». Source de vérité unique : la valeur est reprise
 * dans le footer, la hero boutique, le drawer panier et la fiche produit.
 *
 * Doit rester aligné sur la tranche la plus basse de la méthode
 * « Point relais - Mondial Relay » (Réglages → Expédition → France Métropolitaine).
 *
 * Surcharge : add_filter('rigo_shipping_from_price', function () { return 5.90; });
 */
function rigo_shipping_from_price() {
    return (float) apply_filters('rigo_shipping_from_price', 4.90);
}

/**
 * Le prix formaté pour l'affichage, insécable : « 4,90 € ».
 */
function rigo_shipping_from_label() {
    $price = number_format(rigo_shipping_from_price(), 2, ',', ' ');

    return '<span class="nowrap">' . esc_html($price) . '&nbsp;€</span>';
}

/**
 * Charge automatiquement tous les modules dans includes/.
 * Chaque fichier = un ancien snippet Code Snippets, migré 1:1.
 * Pour désactiver un module : commenter la ligne ou renommer le fichier en .php.off.
 */
$rigo_includes = glob(get_stylesheet_directory() . '/includes/*.php');
if ($rigo_includes) {
    foreach ($rigo_includes as $rigo_file) {
        require_once $rigo_file;
    }
}
unset($rigo_includes, $rigo_file);
