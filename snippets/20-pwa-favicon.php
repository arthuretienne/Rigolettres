<?php
/**
 * [Rigolettres] PWA manifest + Favicon
 *
 * 1. Génère /manifest.json dynamiquement (add-to-homescreen, thème crème)
 * 2. Injecte <link> manifest + apple-touch-icon + theme-color dans <head>
 * 3. Favicon 32px + 192px depuis le logo Pato (WP media id=59 = og-cover,
 *    on utilise le logo uploadé — fallback SVG Pato inline)
 *
 * ⚠️ Action Arthur : uploader un favicon.ico / favicon-32.png dans WP Media
 *    et remplacer FAVICON_ID ci-dessous (actuellement = 0 → SVG inline)
 *
 * Scope : global
 * Priority : 3
 */

define('RIGO_FAVICON_MEDIA_ID', 0); // ← remplacer par l'ID du fichier favicon uploadé

// ── 1. Endpoint /manifest.json ────────────────────────────────────────────
add_action('init', function () {
    add_rewrite_rule('^manifest\.json$', 'index.php?rigo_manifest=1', 'top');
});
add_filter('query_vars', function ($vars) { $vars[] = 'rigo_manifest'; return $vars; });
add_action('template_redirect', function () {
    if (!get_query_var('rigo_manifest')) return;
    $manifest = [
        'name'             => 'Rigolettres',
        'short_name'       => 'Rigolettres',
        'description'      => 'Jeux éducatifs pour apprendre à lire — créés par une orthophoniste',
        'start_url'        => '/',
        'display'          => 'standalone',
        'background_color' => '#FBF8F1',
        'theme_color'      => '#27B4E5',
        'lang'             => 'fr-FR',
        'icons'            => [
            ['src' => '/wp-content/uploads/pato-icon-192.png', 'sizes' => '192x192', 'type' => 'image/png'],
            ['src' => '/wp-content/uploads/pato-icon-512.png', 'sizes' => '512x512', 'type' => 'image/png'],
        ],
        'categories'       => ['education', 'shopping'],
        'screenshots'      => [],
    ];
    header('Content-Type: application/manifest+json; charset=utf-8');
    header('Cache-Control: public, max-age=86400');
    echo json_encode($manifest, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
});

// ── 2. <head> : manifest + favicon + theme-color ──────────────────────────
add_action('wp_head', function () {
    $favicon_url = RIGO_FAVICON_MEDIA_ID
        ? wp_get_attachment_image_url(RIGO_FAVICON_MEDIA_ID, 'thumbnail')
        : get_site_url() . '/wp-content/uploads/logo-pato-provisoire.png';

    // SVG favicon inline (fallback instantané, aucun fichier externe requis)
    $svg_favicon = 'data:image/svg+xml,' . rawurlencode(
        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">'
        . '<circle cx="50" cy="50" r="48" fill="#FBF8F1" stroke="#E7E2D5" stroke-width="2"/>'
        . '<text y=".9em" font-size="80" text-anchor="middle" x="50" dominant-baseline="middle" dy="0.1em">🐕</text>'
        . '</svg>'
    );
    ?>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#27B4E5">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Rigolettres">
    <link rel="icon" type="image/svg+xml" href="<?php echo esc_attr($svg_favicon); ?>">
    <link rel="apple-touch-icon" href="<?php echo esc_url($favicon_url); ?>">
    <?php
}, 3);

// ── 3. Flush rewrite rules à l'activation ────────────────────────────────
add_action('wp_loaded', function () {
    if (get_option('rigo_manifest_flushed') !== '1') {
        flush_rewrite_rules();
        update_option('rigo_manifest_flushed', '1');
    }
});
