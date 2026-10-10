<?php
/**
 * [Rigolettres] Nettoyage du contenu hérité des pages + titres de page.
 *
 * Problème : plusieurs pages embarquaient leur propre feuille de style DANS
 * leur contenu (base de données) :
 *   - Accueil (page d'accueil) : ~40 Ko de CSS « Kalam + bleu », plus un
 *     second en-tête et un second pied de page codés en dur.
 *   - Panier, Commande, Mon compte : un bloc <style id="rigol-wc-style">
 *     bourré de !important (titres Kalam, champs crème, boutons bleus).
 * Ces feuilles passaient après celle du thème et imposaient un deuxième
 * design system : c'est la cause première des écarts de polices, de couleurs
 * et de boutons entre l'accueil, WooCommerce et les pages classiques.
 *
 * Solution : on ne touche PAS à la base. Au rendu, on retire ces blocs du
 * contenu, et le style est servi par le thème (style.css + assets/css/home.css),
 * à partir des tokens communs. Pour revenir en arrière : désactiver ce module.
 *
 * Fait aussi : désactive le titre de page Blocksy quand le contenu apporte
 * déjà son propre <h1> (sinon deux H1 par page).
 */

if (!defined('ABSPATH')) exit;

/**
 * Retire de $html le premier fragment compris entre $open (début de balise
 * ouvrante) et $close (balise fermante), bornes incluses.
 * Recherche par position, sans regex : pas de limite de backtracking sur un
 * contenu de 50 Ko, et si le fragment est introuvable le HTML ressort intact.
 */
function rigo_strip_fragment($html, $open, $close) {
    $start = strpos($html, $open);
    if ($start === false) return $html;
    $end = strpos($html, $close, $start);
    if ($end === false) return $html;
    return substr($html, 0, $start) . substr($html, $end + strlen($close));
}

add_filter('the_content', function ($content) {
    if (is_admin() || !is_string($content) || $content === '') return $content;

    // Accueil : feuille de style, en-tête et pied de page codés dans le contenu
    if (is_front_page()) {
        if (strpos($content, 'rigol-preview-root') === false) return $content;

        $style_at = strpos($content, '<style');
        $root_at  = strpos($content, 'rigol-preview-root');
        // On ne retire que la feuille placée AVANT le conteneur de la page
        if ($style_at !== false && $style_at < $root_at) {
            $content = rigo_strip_fragment($content, '<style', '</style>');
        }
        $content = rigo_strip_fragment($content, '<header class="site-header">', '</header>');
        $content = rigo_strip_fragment($content, '<footer id="contact" class="site-footer">', '</footer>');
        return $content;
    }

    // Panier / Commande / Mon compte : ancienne feuille « rigol-wc-style »
    if (strpos($content, 'id="rigol-wc-style"') !== false) {
        $content = rigo_strip_fragment($content, '<style id="rigol-wc-style">', '</style>');
    }

    return $content;
}, 25);

/**
 * Feuille de style de la page d'accueil (sections hero, histoire, gamme…).
 * Chargée uniquement sur l'accueil, après style.css dont elle consomme les tokens.
 */
add_action('wp_enqueue_scripts', function () {
    if (!is_front_page()) return;

    $path = get_stylesheet_directory() . '/assets/css/home.css';
    if (!file_exists($path)) return;

    wp_enqueue_style(
        'rigo-home',
        get_stylesheet_directory_uri() . '/assets/css/home.css',
        ['blocksy-child'],
        filemtime($path)
    );
}, 25);

/**
 * Un seul H1 par page.
 * Blocksy imprime le titre de la page dans un bandeau ; on le coupe quand :
 *   - le contenu de la page contient déjà un <h1> (À propos, pages guides…)
 *   - on est sur une archive produit (le hero de la boutique porte le H1)
 */
add_filter('blocksy:hero:enabled', function ($enabled) {
    if (function_exists('is_shop') && (is_shop() || is_product_taxonomy())) {
        return false;
    }
    if (is_singular(['page', 'post'])) {
        $post = get_post();
        if ($post && stripos($post->post_content, '<h1') !== false) {
            return false;
        }
    }
    return $enabled;
}, 99);
