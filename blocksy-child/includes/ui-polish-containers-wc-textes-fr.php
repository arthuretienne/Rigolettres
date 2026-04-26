<?php
/**
 * Migré depuis Code Snippet #41 : [Rigolettres] 27 — UI Polish (containers WC + textes FR)
 * Description : Corrige conteneurs fiche produit/panier/checkout/shop/404 + gettext FR
 */

if (!defined('ABSPATH')) exit;

/**
 * [Rigolettres] 27 — UI Polish : conteneurs WooCommerce, textes EN, débordements
 *
 * Corrige les zones "bleed to edge" sur fiche produit / panier / checkout / shop / 404
 * Remplace les H1 anglais WooCommerce et 404 ("Shop", "Oops! That page can't be found.")
 * Harmonise la largeur de contenu à 1200px centré avec padding latéral.
 *
 * Scope: front-end
 */

/* ------------------------------------------------------------------
 * 1. Injecte CSS via wp_add_inline_style (survit LiteSpeed purge)
 * ------------------------------------------------------------------ */
add_action( 'wp_enqueue_scripts', function () {
	if ( is_admin() ) return;
	// Home garde son layout propre (déjà contenu dans les blocs HTML)
	if ( is_front_page() ) return;

	$css = <<<CSS
/* ============================================
 * Rigolettres 27 — UI Polish (non-home pages)
 * ============================================ */

/* Reset any Blocksy default padding/container that conflicts */
:root { --rigo-container: 1200px; --rigo-pad-x: 24px; --rigo-pad-y: 48px; }
@media (min-width: 768px){ :root { --rigo-pad-x: 32px; } }

/* Main wrap — add consistent vertical breathing room on non-home pages */
main.site-main {
  padding-top: 28px !important;
  padding-bottom: 64px !important;
  background: var(--bg, #FBF8F1) !important;
}

/* ---------- Product pages ---------- */
body.single-product .product.type-product {
  max-width: var(--rigo-container) !important;
  margin-left: auto !important;
  margin-right: auto !important;
  padding-left: var(--rigo-pad-x) !important;
  padding-right: var(--rigo-pad-x) !important;
  box-sizing: border-box !important;
}
body.single-product .product.type-product > .summary,
body.single-product .product.type-product > .woocommerce-product-gallery {
  padding: 0 !important; /* parent already has padding */
}

/* Tabs — wrap dans container centré, fond crème subtil */
body.single-product .woocommerce-tabs {
  max-width: var(--rigo-container) !important;
  margin: 48px auto 0 !important;
  padding: 0 var(--rigo-pad-x) !important;
  box-sizing: border-box !important;
}
body.single-product .woocommerce-tabs ul.wc-tabs {
  border-bottom: 2px solid var(--border, #E6E0D5) !important;
  margin: 0 0 24px !important;
  padding: 0 !important;
  display: flex; flex-wrap: wrap; gap: 4px;
}
body.single-product .woocommerce-tabs ul.wc-tabs li {
  background: transparent !important;
  border: 0 !important;
  margin: 0 !important;
  padding: 0 !important;
}
body.single-product .woocommerce-tabs ul.wc-tabs li a {
  display: inline-block;
  padding: 12px 20px !important;
  font-weight: 700 !important;
  color: var(--ink-soft, #5C524A) !important;
  border-bottom: 3px solid transparent !important;
  transition: color .2s, border-color .2s;
}
body.single-product .woocommerce-tabs ul.wc-tabs li.active a,
body.single-product .woocommerce-tabs ul.wc-tabs li a:hover {
  color: var(--primary-dark, #1f8fc4) !important;
  border-bottom-color: var(--primary, #27B4E5) !important;
}
body.single-product .woocommerce-Tabs-panel {
  background: #fff !important;
  border: 1px solid var(--border, #E6E0D5) !important;
  border-radius: 12px !important;
  padding: 28px !important;
  box-shadow: 0 2px 6px rgba(31,41,55,.04);
}
@media (max-width: 640px){
  body.single-product .woocommerce-Tabs-panel { padding: 20px 16px !important; border-radius: 10px !important; }
  body.single-product .woocommerce-tabs ul.wc-tabs li a { padding: 10px 14px !important; font-size: 14px !important; }
}

/* Up-sells + Related */
body.single-product .up-sells,
body.single-product .related.products {
  max-width: var(--rigo-container) !important;
  margin-left: auto !important;
  margin-right: auto !important;
  padding-left: var(--rigo-pad-x) !important;
  padding-right: var(--rigo-pad-x) !important;
  box-sizing: border-box !important;
}
body.single-product .up-sells h2,
body.single-product .related.products h2 {
  font-family: "Kalam", cursive !important;
  font-size: clamp(22px, 3vw, 30px) !important;
  font-weight: 700 !important;
  margin: 0 0 24px !important;
  color: var(--ink, #2D2420) !important;
}

/* ---------- Shop archive ---------- */
body.woocommerce.archive .site-main,
body.post-type-archive-product .site-main {
  padding-top: 32px !important;
}
body.woocommerce.archive ul.products,
body.post-type-archive-product ul.products {
  max-width: var(--rigo-container) !important;
  margin-left: auto !important;
  margin-right: auto !important;
  padding-left: var(--rigo-pad-x) !important;
  padding-right: var(--rigo-pad-x) !important;
  box-sizing: border-box !important;
}
body.woocommerce.archive .woocommerce-products-header,
body.post-type-archive-product .woocommerce-products-header {
  max-width: var(--rigo-container) !important;
  margin: 0 auto 16px !important;
  padding: 0 var(--rigo-pad-x) !important;
}
body.woocommerce.archive .woocommerce-products-header h1,
body.post-type-archive-product .woocommerce-products-header h1 {
  font-family: "Kalam", cursive !important;
  font-size: clamp(30px, 4vw, 44px) !important;
  color: var(--ink, #2D2420) !important;
  margin: 0 0 8px !important;
}
body.woocommerce.archive .woocommerce-result-count,
body.post-type-archive-product .woocommerce-result-count {
  font-size: 14px !important;
  color: var(--ink-soft, #5C524A) !important;
}

/* ---------- Cart ---------- */
body.woocommerce-cart .wp-block-woocommerce-cart,
body.woocommerce-cart .wc-block-components-sidebar-layout,
body.woocommerce-cart .cart_totals,
body.woocommerce-cart .woocommerce-cart-form {
  max-width: var(--rigo-container) !important;
  margin-left: auto !important;
  margin-right: auto !important;
  box-sizing: border-box !important;
}
body.woocommerce-cart .wp-block-woocommerce-cart,
body.woocommerce-cart .wc-block-cart {
  padding-left: var(--rigo-pad-x) !important;
  padding-right: var(--rigo-pad-x) !important;
}

/* ---------- Checkout ---------- */
body.woocommerce-checkout .wp-block-woocommerce-checkout,
body.woocommerce-checkout .wc-block-checkout,
body.woocommerce-checkout .wc-block-components-sidebar-layout {
  max-width: var(--rigo-container) !important;
  margin-left: auto !important;
  margin-right: auto !important;
  padding-left: var(--rigo-pad-x) !important;
  padding-right: var(--rigo-pad-x) !important;
  box-sizing: border-box !important;
}

/* ---------- My account ---------- */
body.woocommerce-account .woocommerce,
body.woocommerce-account .u-columns {
  max-width: var(--rigo-container) !important;
  margin-left: auto !important;
  margin-right: auto !important;
  padding-left: var(--rigo-pad-x) !important;
  padding-right: var(--rigo-pad-x) !important;
  box-sizing: border-box !important;
}
body.woocommerce-account h1,
body.woocommerce-account h2 {
  font-family: "Kalam", cursive !important;
  color: var(--ink, #2D2420) !important;
}

/* ---------- 404 page ---------- */
body.error404 .site-main {
  display: flex !important;
  flex-direction: column !important;
  align-items: center !important;
  justify-content: center !important;
  min-height: 60vh !important;
  padding: 80px 24px !important;
  text-align: center;
}
body.error404 .site-main h1,
body.error404 .site-main .page-title {
  font-family: "Kalam", cursive !important;
  font-size: clamp(32px, 5vw, 56px) !important;
  color: var(--ink, #2D2420) !important;
  margin: 0 0 16px !important;
}
body.error404 .site-main .hero-section { display: none !important; }

/* ---------- Générique : table responsive (éviter débordement horizontal) ---------- */
.woocommerce-tabs table,
.woocommerce table.shop_table {
  width: 100% !important;
  max-width: 100% !important;
  display: block !important;
  overflow-x: auto !important;
  -webkit-overflow-scrolling: touch;
}
@media (min-width: 768px){
  .woocommerce-tabs table,
  .woocommerce table.shop_table { display: table !important; }
}

/* ---------- Prévenir débordement horizontal mobile global ---------- */
html, body { overflow-x: hidden !important; }

/* Images produit — aspect fixe pour éviter hauteur folle */
body.single-product .woocommerce-product-gallery img {
  max-height: min(70vh, 640px) !important;
  object-fit: contain !important;
  background: #fff !important;
  border-radius: 12px !important;
}

/* ---------- Fil d'Ariane / nav WC cohérente ---------- */
.woocommerce-breadcrumb {
  max-width: var(--rigo-container) !important;
  margin: 0 auto 24px !important;
  padding: 0 var(--rigo-pad-x) !important;
  font-size: 13px !important;
  color: var(--ink-soft, #5C524A) !important;
}
.woocommerce-breadcrumb a { color: var(--primary, #27B4E5) !important; }
.woocommerce-breadcrumb a:hover { text-decoration: underline; }

/* ---------- Notice WC (add-to-cart etc.) ---------- */
.woocommerce-notices-wrapper,
.woocommerce-message,
.woocommerce-info,
.woocommerce-error {
  max-width: var(--rigo-container) !important;
  margin: 0 auto 16px !important;
  padding-left: var(--rigo-pad-x) !important;
  padding-right: var(--rigo-pad-x) !important;
  box-sizing: border-box !important;
}

CSS;

	// Piggyback on Blocksy style handle (survit LiteSpeed). Fallback chain.
	if ( wp_style_is( 'blocksy-styles', 'registered' ) ) {
		wp_add_inline_style( 'blocksy-styles', $css );
	} elseif ( wp_style_is( 'ct-main-styles', 'registered' ) ) {
		wp_add_inline_style( 'ct-main-styles', $css );
	} else {
		wp_register_style( 'rigo-ui-polish', false );
		wp_enqueue_style( 'rigo-ui-polish' );
		wp_add_inline_style( 'rigo-ui-polish', $css );
	}
}, 30 );


/* ------------------------------------------------------------------
 * 2. Filtre gettext : remplace textes EN par FR
 * ------------------------------------------------------------------ */
add_filter( 'gettext', function ( $translated, $original, $domain ) {
	$map = [
		// 404 page
		"Oops! That page can&rsquo;t be found."      => "Oups ! Cette page est introuvable.",
		"Oops! That page can't be found."            => "Oups ! Cette page est introuvable.",
		"It looks like nothing was found at this location. Maybe try a search?" => "On dirait que cette page n'existe pas. Essayez une recherche ou retournez à l'accueil.",
		// Shop default title
		"Shop"                                        => "Notre gamme",
		// Generic WC texts
		"Search Results for: %s"                     => "Résultats pour : %s",
		"Nothing Found"                              => "Rien trouvé",
		"Ready to publish your first post?"          => "Prêt à publier votre premier article ?",
	];
	if ( isset( $map[ $original ] ) ) return $map[ $original ];
	return $translated;
}, 20, 3 );


/* ------------------------------------------------------------------
 * 3. Force le titre de la page boutique à "Notre gamme"
 * ------------------------------------------------------------------ */
add_filter( 'woocommerce_page_title', function ( $page_title ) {
	if ( is_shop() ) return 'Notre gamme';
	return $page_title;
}, 20 );


/* ------------------------------------------------------------------
 * 4. Breadcrumb home link → "Accueil" au lieu de "Home"
 * ------------------------------------------------------------------ */
add_filter( 'woocommerce_breadcrumb_defaults', function ( $args ) {
	$args['home'] = 'Accueil';
	return $args;
} );
