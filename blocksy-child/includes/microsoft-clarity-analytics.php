<?php
/**
 * Migré depuis Code Snippet #22 : [Rigolettres] Microsoft Clarity analytics
 * Description : Script Clarity dans wp_head. Exclut admins/editors. Remplacer CLARITY_PROJECT_ID par le vrai ID clarity.microsoft.com.
 */

if (!defined('ABSPATH')) exit;

/**
 * [Rigolettres] Microsoft Clarity analytics
 *
 * Injecte le script Clarity dans <head>.
 * ID Clarity à renseigner dans la constante RIGOLETTRES_CLARITY_ID.
 * Exclut les utilisateurs connectés (admin/éditeurs) pour ne pas polluer les heatmaps.
 *
 * Source : audit.md Sprint 2
 * Déployé via Code Snippets (scope=front-end, priority=5)
 *
 * ⚠️  Remplacer 'CLARITY_PROJECT_ID' par le vrai ID (ex: "abc123xyz")
 *     disponible sur clarity.microsoft.com → Settings → Overview.
 */

define('RIGOLETTRES_CLARITY_ID', 'CLARITY_PROJECT_ID');

add_action('wp_head', function () {
    // Ne pas tracker les admins/éditeurs connectés
    if (is_user_logged_in()) {
        $user = wp_get_current_user();
        if ($user && array_intersect(['administrator', 'editor', 'shop_manager'], (array)$user->roles)) {
            return;
        }
    }

    $id = esc_js(RIGOLETTRES_CLARITY_ID);
    if (empty($id) || $id === 'CLARITY_PROJECT_ID') return; // ID non configuré

    $open = '<' . 'scr' . 'ipt type="text/javascript">';
    $close = '</' . 'scr' . 'ipt>';
    echo "\n" . $open . "\n";
    echo '(function(c,l,a,r,i,t,y){';
    echo 'c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};';
    echo 't=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;';
    echo 'y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);';
    echo '})(window, document, "clarity", "script", "' . $id . '");' . "\n";
    echo $close . "\n";
}, 5);
