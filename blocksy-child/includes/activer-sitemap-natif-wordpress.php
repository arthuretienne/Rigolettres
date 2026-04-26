<?php
/**
 * Migré depuis Code Snippet #12 : [Rigolettres] Activer sitemap natif WordPress
 * Description : Force wp_sitemaps_enabled=true + flush rewrite+htaccess. Source: audit.md Phase A.8
 */

if (!defined('ABSPATH')) exit;

/**
 * [Rigolettres] Activer sitemap natif WordPress
 *
 * Force wp_sitemaps_enabled=true même quand blog_public=0 (site en noindex).
 * Flush rewrite rules avec htaccess une fois au démarrage.
 * Source : audit.md Phase A.8
 */

// Doit s'enregistrer avant init priority 1 (wp_sitemaps_get_server)
// Code Snippets global snippets s'exécutent sur plugins_loaded → OK
add_filter('wp_sitemaps_enabled', '__return_true', 1);
add_filter('wp_sitemaps_max_urls', function($max) { return 500; }, 10);

// Flush rewrite rules avec hard=true (écrit le .htaccess) — une fois via transient
add_action('init', function() {
    if (!get_transient('rigolettres_sitemap_flushed_v2')) {
        flush_rewrite_rules(true); // true = met à jour .htaccess aussi
        set_transient('rigolettres_sitemap_flushed_v2', 1, MONTH_IN_SECONDS);
    }
}, 100);
