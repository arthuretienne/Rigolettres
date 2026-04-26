<?php
/**
 * Blocksy Child Rigolettres — bootstrap.
 *
 * - Charge la feuille du parent puis celle de l'enfant (versionnée par mtime → busting auto).
 * - Préconnexion + chargement Google Fonts (Fraunces + Nunito + Caveat).
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
    echo '<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700;9..144,800&family=Nunito:wght@400;500;600;700;800&family=Caveat:wght@500;700&display=swap">' . "\n";
}, 1);

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
