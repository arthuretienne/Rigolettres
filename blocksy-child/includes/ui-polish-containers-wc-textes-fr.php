<?php
/**
 * [Rigolettres] Textes français des gabarits Blocksy / WooCommerce.
 *
 * Remplace les libellés anglais restants (404, titre de boutique, recherche)
 * et le lien « Home » du fil d'Ariane.
 *
 * L'ancien bloc CSS « UI polish » de ce module (conteneurs fiche produit,
 * panier, commande, 404…) a été repris proprement dans style.css : il
 * empilait des !important et des polices non chargées.
 */

if (!defined('ABSPATH')) exit;

/* ------------------------------------------------------------------
 * 1. Filtre gettext : remplace textes EN par FR
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
 * 2. Force le titre de la page boutique à "Notre gamme"
 * ------------------------------------------------------------------ */
add_filter( 'woocommerce_page_title', function ( $page_title ) {
	if ( is_shop() ) return 'Notre gamme';
	return $page_title;
}, 20 );


/* ------------------------------------------------------------------
 * 3. Breadcrumb home link → "Accueil" au lieu de "Home"
 * ------------------------------------------------------------------ */
add_filter( 'woocommerce_breadcrumb_defaults', function ( $args ) {
	$args['home'] = 'Accueil';
	return $args;
} );
