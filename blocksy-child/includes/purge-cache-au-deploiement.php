<?php
/**
 * [Rigolettres] Purge du cache à chaque déploiement du thème.
 *
 * Constaté le 2026-10-10 : l'étape « Purger le cache LiteSpeed » du workflow
 * appelait /wp-json/litespeed/v1/purge/all, une route qui n'existe pas (404).
 * L'étape passait au vert quand même (curl sans --fail). Tant qu'un snippet
 * « DEV MODE » purgeait tout à chaque requête, personne ne l'a vu ; une fois le
 * cache réactivé (durée de vie : 7 jours), les pages restaient figées sur
 * l'ancien HTML tout en chargeant la nouvelle feuille de style.
 *
 * Désormais c'est le thème qui purge : à la première requête PHP après un
 * déploiement, l'empreinte des fichiers du thème a changé → purge complète.
 * Le workflow se contente d'appeler une URL non cachée pour déclencher ce code.
 *
 * Une seconde purge est rejouée deux minutes plus tard : entre-temps, OPcache
 * peut encore exécuter l'ancien PHP et remettre en cache des pages périmées.
 */

if (!defined('ABSPATH')) exit;

/**
 * Empreinte du thème déployé : date de modification la plus récente parmi les
 * fichiers qui influencent le rendu (≈ 40 appels filemtime, négligeable).
 */
function rigo_deploy_stamp() {
    $dir   = get_stylesheet_directory();
    $files = array_merge(
        [$dir . '/style.css', $dir . '/functions.php'],
        glob($dir . '/includes/*.php') ?: [],
        glob($dir . '/assets/css/*.css') ?: []
    );
    $stamp = 0;
    foreach ($files as $file) {
        $mtime = @filemtime($file);
        if ($mtime && $mtime > $stamp) $stamp = $mtime;
    }
    // Le nombre de fichiers compte aussi : un module supprimé change l'empreinte
    return $stamp . '-' . count($files);
}

/**
 * Purge complète : LiteSpeed (et, à travers lui, le CDN Hostinger s'il y est relié).
 */
function rigo_purge_all_caches() {
    if (function_exists('opcache_reset')) {
        @opcache_reset();
    }
    // API publique de LiteSpeed Cache — sans effet si l'extension est absente
    do_action('litespeed_purge_all');
}

add_action('wp_loaded', function () {
    $state = get_option('rigo_deploy_state');
    if (!is_array($state)) $state = ['stamp' => '', 'again' => 0];

    $stamp = rigo_deploy_stamp();

    // Nouveau déploiement détecté
    if ($state['stamp'] !== $stamp) {
        // On enregistre d'abord : deux requêtes simultanées ne purgent pas en boucle
        update_option('rigo_deploy_state', ['stamp' => $stamp, 'again' => time() + 120], true);
        rigo_purge_all_caches();
        return;
    }

    // Seconde purge, une fois OPcache à jour
    if (!empty($state['again']) && time() >= (int) $state['again']) {
        update_option('rigo_deploy_state', ['stamp' => $stamp, 'again' => 0], true);
        rigo_purge_all_caches();
    }
}, 5);

/**
 * Le CDN Hostinger garde le HTML en cache et ne peut pas être purgé depuis le
 * site : après un déploiement (ou un changement de prix), il continuait à servir
 * l'ancienne page pendant plus d'une heure alors que LiteSpeed était déjà purgé.
 *
 * On lui demande donc de ne pas stocker le HTML (`no-cache`). Le cache de pages
 * reste assuré par LiteSpeed, à l'origine, que le thème sait purger. Les fichiers
 * statiques (CSS, JS, images) restent servis par le CDN.
 * Les pages qui posent déjà leur propre en-tête (panier, commande, compte) ne
 * sont pas touchées.
 */
add_filter('wp_headers', function ($headers) {
    if (is_admin()) return $headers;
    if (empty($headers['Cache-Control'])) {
        $headers['Cache-Control'] = 'no-cache, must-revalidate, max-age=0';
    }
    return $headers;
}, 20);
