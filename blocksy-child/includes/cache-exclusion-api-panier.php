<?php
/**
 * [Rigolettres] L'API panier de WooCommerce ne doit jamais être mise en cache.
 *
 * Constaté le 2026-10-10, après la réactivation de LiteSpeed Cache : la route
 * GET /wp-json/wc/store/v1/cart était servie depuis le cache public
 * (x-litespeed-cache: hit, max-age 7 jours). Chaque visiteur recevait donc le
 * panier — et le jeton de sécurité (en-tête Nonce) — du premier visiteur passé
 * après la purge :
 *   - pastille panier, tiroir panier et tiroir de confirmation affichaient un
 *     panier vide alors que le visiteur avait des articles ;
 *   - un ajout au panier fait avec le jeton d'un autre pouvait être refusé ;
 *   - risque de voir le contenu du panier d'un autre visiteur.
 *
 * On marque toute réponse de l'API Store (wc/store/…) comme privée et non
 * cachable, pour LiteSpeed comme pour tout cache intermédiaire.
 * Les scripts du thème ajoutent en plus un paramètre anti-cache à leurs appels.
 */

if (!defined('ABSPATH')) exit;

/**
 * Vrai si la requête courante vise l'API Store de WooCommerce.
 */
function rigo_is_store_api_request() {
    $uri = isset($_SERVER['REQUEST_URI']) ? (string) wp_unslash($_SERVER['REQUEST_URI']) : '';
    if (strpos($uri, '/wc/store/') !== false) return true;
    // Permaliens simples : ?rest_route=/wc/store/v1/cart
    $route = isset($_GET['rest_route']) ? (string) wp_unslash($_GET['rest_route']) : '';
    return strpos($route, '/wc/store/') !== false;
}

// 1. LiteSpeed : pas de mise en cache pour ces routes
add_action('rest_api_init', function () {
    if (!rigo_is_store_api_request()) return;

    if (!defined('DONOTCACHEPAGE')) {
        define('DONOTCACHEPAGE', true);
    }
    // API publique de LiteSpeed Cache (sans effet si l'extension est absente)
    do_action('litespeed_control_set_nocache', 'API Store WooCommerce : réponse propre à chaque visiteur');
}, 1);

// 2. En-têtes HTTP : privé, non stocké (navigateurs, CDN, proxys)
add_filter('rest_post_dispatch', function ($response, $server, $request) {
    if ($response instanceof WP_REST_Response && strpos((string) $request->get_route(), '/wc/store/') === 0) {
        $response->header('Cache-Control', 'no-store, no-cache, must-revalidate, private');
        $response->header('X-LiteSpeed-Cache-Control', 'no-cache');
    }
    return $response;
}, 20, 3);
