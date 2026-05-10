<?php
/**
 * Migré depuis Code Snippet #31 : [Rigolettres] 20 — PWA manifest + favicon
 * Description : Endpoint /manifest.json dynamique + theme-color + apple-touch-icon + SVG favicon inline
 */

if (!defined('ABSPATH')) exit;

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
    // Logo Pato uploadé en avril 2026 (cf. /wp-content/uploads/2026/04/)
    $pato_png = home_url('/wp-content/uploads/2026/04/logo-pato-provisoire.png');

    $favicon_url = RIGO_FAVICON_MEDIA_ID
        ? wp_get_attachment_image_url(RIGO_FAVICON_MEDIA_ID, 'thumbnail')
        : $pato_png;

    // Favicon par défaut WP (Apparence → Personnaliser → Identité du site)
    // Si l'admin l'a défini via `site_icon`, on respecte. Sinon, fallback Pato.
    $site_icon_id = (int) get_option('site_icon');
    $favicon_32   = $site_icon_id ? wp_get_attachment_image_url($site_icon_id, [32, 32])  : $pato_png;
    $favicon_192  = $site_icon_id ? wp_get_attachment_image_url($site_icon_id, [192, 192]) : $pato_png;
    $apple_icon   = $site_icon_id ? wp_get_attachment_image_url($site_icon_id, [180, 180]) : $pato_png;
    ?>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#27B4E5">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Rigolettres">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url($favicon_32); ?>">
    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo esc_url($favicon_192); ?>">
    <link rel="shortcut icon" type="image/png" href="<?php echo esc_url($favicon_32); ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url($apple_icon); ?>">
    <?php
}, 3);

// ── 3. Flush rewrite rules à l'activation ────────────────────────────────
add_action('wp_loaded', function () {
    if (get_option('rigo_manifest_flushed') !== '1') {
        flush_rewrite_rules();
        update_option('rigo_manifest_flushed', '1');
    }
});
