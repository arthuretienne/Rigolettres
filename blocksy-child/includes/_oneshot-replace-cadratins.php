<?php
/**
 * ⚠️ ONE-SHOT — Remplace les tirets cadratins (—) sur le contenu visible
 *
 * Source : audit.md sprint 8 (suite 8) — Arthur trouve que les tirets cadratins
 * "font IA et pas humain" et veut les retirer du contenu rédactionnel.
 *
 * Comportement :
 *  - S'exécute une SEULE fois (option DB rigo_cadratins_replaced_v1).
 *  - Itère sur tous les pages, posts, products PUBLIÉS.
 *  - Remplace " — " (espace + em-dash + espace) par ", " dans
 *    post_content, post_excerpt, post_title.
 *  - Logue le compte d'occurrences modifiées dans une option.
 *  - Ne touche PAS les commentaires HTML Gutenberg (`<!-- wp:... -->`)
 *    car ils n'utilisent pas le caractère em-dash (U+2014).
 *
 * À supprimer après run réussi : ce fichier doit être retiré au sprint suivant
 * (le flag DB empêche un second run, mais le fichier ne sert plus à rien).
 */

if (!defined('ABSPATH')) exit;

add_action('wp_loaded', function () {
    // Garde-fou : ne tourne qu'une fois.
    if (get_option('rigo_cadratins_replaced_v1') === 'done') return;

    // Sécurité : ne pas tourner pendant un AJAX/REST request léger pour éviter
    // de tuer une requête front. Tourne au prochain admin/page hit.
    if (defined('DOING_AJAX') && DOING_AJAX) return;
    if (defined('REST_REQUEST') && REST_REQUEST && !current_user_can('edit_posts')) return;

    global $wpdb;

    $em_dash       = "\xE2\x80\x94"; // U+2014 EM DASH
    $needle_spaced = ' ' . $em_dash . ' ';
    $needle_left   = $em_dash . ' ';
    $needle_right  = ' ' . $em_dash;
    $replacement   = ', ';

    $rows = $wpdb->get_results(
        "SELECT ID, post_content, post_excerpt, post_title
         FROM {$wpdb->posts}
         WHERE post_status IN ('publish','draft','private')
           AND post_type IN ('page','post','product','product_variation')
           AND (post_content LIKE '%\xE2\x80\x94%'
                OR post_excerpt LIKE '%\xE2\x80\x94%'
                OR post_title LIKE '%\xE2\x80\x94%')",
        ARRAY_A
    );

    $touched = 0;
    $occurrences = 0;

    foreach ($rows as $row) {
        $content = $row['post_content'];
        $excerpt = $row['post_excerpt'];
        $title   = $row['post_title'];

        $count_c = 0; $count_e = 0; $count_t = 0;

        // Ordre important : d'abord " — " (cas le plus fréquent), puis les bords.
        $content = str_replace($needle_spaced, $replacement, $content, $count_c);
        $excerpt = str_replace($needle_spaced, $replacement, $excerpt, $count_e);
        $title   = str_replace($needle_spaced, $replacement, $title,   $count_t);

        // ⚠️ On NE TOUCHE PAS aux em-dash sans espace ou en bord (rare et
        // ambigu : tirets de dialogue, plages "1991—2025", etc.). Si Arthur
        // veut une passe plus agressive, on fera un v2.

        $changed = ($count_c + $count_e + $count_t) > 0;
        if (!$changed) continue;

        $occurrences += $count_c + $count_e + $count_t;
        $touched++;

        $wpdb->update(
            $wpdb->posts,
            [
                'post_content' => $content,
                'post_excerpt' => $excerpt,
                'post_title'   => $title,
            ],
            ['ID' => (int) $row['ID']],
            ['%s','%s','%s'],
            ['%d']
        );

        clean_post_cache((int) $row['ID']);
    }

    update_option('rigo_cadratins_replaced_v1', 'done', false);
    update_option('rigo_cadratins_replaced_v1_stats', [
        'touched_posts' => $touched,
        'occurrences'   => $occurrences,
        'ran_at'        => current_time('mysql'),
    ], false);

    // Purge LiteSpeed pour rafraîchir les caches HTML
    if (function_exists('do_action')) {
        do_action('litespeed_purge_all');
    }
}, 999);
