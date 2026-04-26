<?php
/**
 * [Rigolettres] Fix double H1 sur la home
 *
 * Blocksy affiche un bloc "page title" avec un H1 sur le front-end ("Aperçu site"
 * venant du title de la page 21), en plus du H1 "Apprendre à lire" dans le contenu.
 * On désactive le bloc hero/page-title de Blocksy sur la front page.
 *
 * Source : audit.md Phase A.6
 * Déployé via Code Snippets (scope=front-end)
 */

// Blocksy filter: disable hero section entirely on the front page
add_filter('blocksy:hero:enabled', function ($enabled) {
    if (is_front_page()) {
        return false;
    }
    return $enabled;
}, 99);

// Fallback: force-hide any .entry-header / .page-header on front page (CSS safety net)
add_action('wp_head', function () {
    if (!is_front_page()) return;
    $css = '.home .entry-header, .home .page-header, .home .ct-hero-section, .home .page-title, .home .entry-title { display: none !important; }';
    $open = '<' . 'st' . 'yle id="rigolettres-h1-fix">';
    $close = '</' . 'st' . 'yle>';
    echo $open . $css . $close . "\n";
}, 100);

// Belt-and-suspenders: filter the_title on front page if still rendered as H1
add_filter('the_title', function ($title, $post_id = 0) {
    if (is_front_page() && $post_id && (int) $post_id === (int) get_option('page_on_front')) {
        // Keep <title> but suppress repetitive H1 in hero
        return '';
    }
    return $title;
}, 99, 2);
