<?php
/**
 * [Rigolettres] Activer sitemap natif WordPress
 *
 * Force wp_sitemaps_enabled à true au cas où un plugin/thème le désactive.
 * Accès : https://rigolettres.fr/wp-sitemap.xml
 *
 * Source : audit.md Phase A.8
 * Déployé via Code Snippets (scope=global)
 */

// Force-enable WordPress native sitemaps
add_filter('wp_sitemaps_enabled', '__return_true', 99);

// Optionally increase max URLs per sitemap (default 2000)
add_filter('wp_sitemaps_max_urls', function($max) {
    return 500; // Conservative limit for small catalog
}, 10);
