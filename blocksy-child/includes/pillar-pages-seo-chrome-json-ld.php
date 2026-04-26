<?php
/**
 * Migré depuis Code Snippet #42 : [Rigolettres] 28 — Pillar pages SEO chrome + JSON-LD
 * Description : CSS + Schema.org Article/FAQPage/Breadcrumb pour pages pilier d'autorite topique
 */

if (!defined('ABSPATH')) exit;

/**
 * [Rigolettres] 28 — Pages pilier SEO : chrome + JSON-LD FAQ/Article
 *
 * Styling des pages d'autorité topique (expertise Brigitte orthophoniste) :
 *   - /methode-syllabique/, /apprendre-lire-cp/
 *   - /jeux-pour-dyslexique/, /cadeau-cp-utile/, /jeu-conjugaison/
 *   - /dysorthographie-aide/, /guide-parents-lecture/, /pour-orthophonistes/
 *
 * Ajoute :
 *   1. CSS typographique dense et lisible (hero, sommaire, cartes produit, FAQ <details>, signature Brigitte)
 *   2. JSON-LD Article + FAQPage (boost SEO rich snippets + GEO/LLM citation)
 *   3. Fil d'Ariane contextuel Accueil › Guide › Titre
 *
 * Scope: front-end
 */

/* ------------------------------------------------------------------
 * Slugs gérés
 * ------------------------------------------------------------------ */
function rigo_pillar_slugs() {
	return [
		'methode-syllabique',
		'apprendre-lire-cp',
		'jeux-pour-dyslexique',
		'cadeau-cp-utile',
		'jeu-conjugaison',
		'dysorthographie-aide',
		'guide-parents-lecture',
		'pour-orthophonistes',
	];
}

function rigo_is_pillar_page() {
	if ( ! is_page() ) return false;
	$post = get_post();
	if ( ! $post ) return false;
	return in_array( $post->post_name, rigo_pillar_slugs(), true );
}

/* ------------------------------------------------------------------
 * 1. CSS des pages pilier
 * ------------------------------------------------------------------ */
add_action( 'wp_enqueue_scripts', function () {
	if ( is_admin() || ! rigo_is_pillar_page() ) return;

	$css = <<<CSS
/* ==========================================================================
 * Rigolettres 28 — Pages pilier SEO
 * ========================================================================== */

.rigo-pillar-article {
  max-width: 760px;
  margin: 0 auto;
  padding: 0 20px 64px;
  font-family: "Nunito", system-ui, -apple-system, sans-serif;
  color: var(--ink, #2D2420);
  line-height: 1.65;
  font-size: 17px;
}

/* ---------- HERO ---------- */
.rigo-pillar-hero {
  padding: 48px 0 32px;
  border-bottom: 1px solid var(--border, #E6E0D5);
  margin-bottom: 40px;
}
.rigo-pillar-eyebrow {
  font-family: "Kalam", cursive;
  font-size: 15px;
  color: var(--primary, #27B4E5);
  margin: 0 0 12px;
  letter-spacing: .02em;
  font-weight: 700;
}
.rigo-pillar-hero h1 {
  font-family: "Kalam", cursive;
  font-size: clamp(30px, 4.5vw, 48px);
  line-height: 1.15;
  font-weight: 700;
  margin: 0 0 20px;
  color: var(--ink, #2D2420);
  letter-spacing: -.01em;
}
.rigo-pillar-lede {
  font-size: 19px;
  line-height: 1.6;
  color: var(--ink-soft, #5C524A);
  margin: 0 0 20px;
  font-weight: 400;
}
.rigo-pillar-lede strong {
  color: var(--ink, #2D2420);
  font-weight: 700;
}
.rigo-pillar-meta {
  font-size: 14px;
  color: var(--ink-mute, #8A7F73);
  margin: 0;
}

/* ---------- TOC (table of contents) ---------- */
.rigo-pillar-toc {
  background: #FFF8ED;
  border: 1px solid #F3E6C7;
  border-radius: 12px;
  padding: 20px 24px;
  margin: 0 0 48px;
}
.rigo-pillar-toc-title {
  font-family: "Kalam", cursive;
  font-size: 18px;
  font-weight: 700;
  color: var(--ink, #2D2420);
  margin: 0 0 10px;
}
.rigo-pillar-toc ol {
  margin: 0;
  padding: 0 0 0 20px;
  font-size: 15px;
  columns: 2;
  column-gap: 24px;
}
@media (max-width: 640px) { .rigo-pillar-toc ol { columns: 1; } }
.rigo-pillar-toc li { margin: 4px 0; break-inside: avoid; }
.rigo-pillar-toc a {
  color: var(--primary-dark, #1f8fc4);
  text-decoration: none;
  transition: color .15s;
}
.rigo-pillar-toc a:hover { color: var(--primary, #27B4E5); text-decoration: underline; }

/* ---------- H2 / H3 ---------- */
.rigo-pillar-article h2,
.rigo-pillar-h2 {
  font-family: "Kalam", cursive;
  font-size: clamp(24px, 3vw, 32px);
  line-height: 1.2;
  font-weight: 700;
  margin: 56px 0 20px;
  color: var(--ink, #2D2420);
  scroll-margin-top: 120px;
  position: relative;
  padding-left: 16px;
}
.rigo-pillar-article h2::before,
.rigo-pillar-h2::before {
  content: "";
  position: absolute;
  left: 0; top: 8px; bottom: 8px;
  width: 4px;
  background: var(--primary, #27B4E5);
  border-radius: 2px;
}
.rigo-pillar-article h3 {
  font-family: "Nunito", sans-serif;
  font-size: 20px;
  font-weight: 800;
  margin: 36px 0 12px;
  color: var(--ink, #2D2420);
  line-height: 1.3;
}

/* ---------- Paragraph & list ---------- */
.rigo-pillar-article p { margin: 0 0 16px; }
.rigo-pillar-article ul,
.rigo-pillar-article ol { margin: 0 0 20px; padding-left: 22px; }
.rigo-pillar-article li { margin: 8px 0; }
.rigo-pillar-article strong { font-weight: 800; color: var(--ink, #2D2420); }
.rigo-pillar-article em { font-style: italic; }

.rigo-pillar-article a {
  color: var(--primary-dark, #1f8fc4);
  text-decoration: underline;
  text-decoration-thickness: 1.5px;
  text-underline-offset: 2px;
  transition: color .15s;
}
.rigo-pillar-article a:hover { color: var(--primary, #27B4E5); }

/* ---------- QUOTE ---------- */
.rigo-pillar-quote,
.rigo-pillar-article blockquote.rigo-pillar-quote {
  background: #FBF8F1;
  border-left: 4px solid var(--accent, #68a033);
  padding: 20px 24px;
  margin: 32px 0;
  border-radius: 0 8px 8px 0;
  font-style: italic;
  font-size: 18px;
  line-height: 1.55;
}
.rigo-pillar-quote p { margin: 0 0 8px; color: var(--ink, #2D2420); }
.rigo-pillar-quote cite {
  font-style: normal;
  font-size: 14px;
  color: var(--ink-soft, #5C524A);
  font-family: "Kalam", cursive;
  font-weight: 700;
}

/* ---------- CARTES PRODUIT intégrées ---------- */
.rigo-pillar-product-card {
  background: #fff;
  border: 1px solid var(--border, #E6E0D5);
  border-radius: 14px;
  padding: 24px;
  margin: 28px 0;
  box-shadow: 0 2px 8px rgba(31,41,55,.04);
  transition: box-shadow .2s, transform .2s;
}
.rigo-pillar-product-card:hover {
  box-shadow: 0 4px 16px rgba(31,41,55,.08);
  transform: translateY(-2px);
}
.rigo-pillar-product-card.rigo-pillar-product-card--gift {
  background: linear-gradient(135deg, #FFF4D6, #FFE8B3);
  border-color: #F4C95D;
}
.rigo-pillar-product-badge {
  display: inline-block;
  background: var(--primary, #27B4E5);
  color: #fff;
  padding: 4px 10px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 800;
  letter-spacing: .03em;
  text-transform: uppercase;
  margin-bottom: 12px;
}
.rigo-pillar-product-title {
  font-family: "Kalam", cursive;
  font-size: 24px !important;
  margin: 0 0 6px !important;
  color: var(--ink, #2D2420);
  padding-left: 0 !important;
}
.rigo-pillar-product-title::before { display: none !important; }
.rigo-pillar-product-subtitle {
  color: var(--ink-soft, #5C524A);
  font-size: 15px;
  font-style: italic;
  margin: 0 0 16px !important;
}
.rigo-pillar-product-cta {
  margin-top: 16px !important;
  text-align: right;
}
.rigo-pillar-cta-btn {
  display: inline-block;
  background: var(--accent, #68a033);
  color: #fff !important;
  padding: 10px 20px;
  border-radius: 10px;
  font-weight: 800;
  text-decoration: none !important;
  transition: background .15s, transform .15s;
}
.rigo-pillar-cta-btn:hover {
  background: #57872a;
  color: #fff !important;
  transform: translateX(2px);
}

/* ---------- FAQ <details> ---------- */
.rigo-pillar-faq {
  border-top: 1px solid var(--border, #E6E0D5);
  padding-top: 8px;
  margin: 24px 0 48px;
}
.rigo-pillar-faq-item {
  border-bottom: 1px solid var(--border, #E6E0D5);
  padding: 18px 0;
}
.rigo-pillar-faq-item summary {
  list-style: none;
  cursor: pointer;
  font-weight: 800;
  font-size: 17px;
  color: var(--ink, #2D2420);
  padding-right: 32px;
  position: relative;
  line-height: 1.4;
}
.rigo-pillar-faq-item summary::-webkit-details-marker { display: none; }
.rigo-pillar-faq-item summary::after {
  content: "+";
  position: absolute;
  right: 0; top: 0;
  font-size: 24px;
  font-weight: 400;
  line-height: 1;
  color: var(--primary, #27B4E5);
  transition: transform .2s;
}
.rigo-pillar-faq-item[open] summary::after { content: "−"; }
.rigo-pillar-faq-item summary:hover { color: var(--primary-dark, #1f8fc4); }
.rigo-pillar-faq-item p {
  margin: 12px 0 0;
  color: var(--ink-soft, #5C524A);
  line-height: 1.6;
}

/* ---------- SIGNATURE BRIGITTE ---------- */
.rigo-pillar-signature {
  background: #FBF8F1;
  border: 1px solid var(--border, #E6E0D5);
  border-radius: 14px;
  padding: 24px 28px;
  margin: 48px 0;
}
.rigo-pillar-signature-intro {
  font-size: 13px;
  color: var(--ink-mute, #8A7F73);
  text-transform: uppercase;
  letter-spacing: .08em;
  margin: 0 0 6px;
}
.rigo-pillar-signature-name {
  font-family: "Kalam", cursive;
  font-size: 24px;
  margin: 0 0 4px;
  color: var(--ink, #2D2420);
}
.rigo-pillar-signature-title {
  font-size: 14px;
  color: var(--ink-soft, #5C524A);
  margin: 0 0 12px;
}
.rigo-pillar-signature-bio {
  font-size: 15px;
  color: var(--ink-soft, #5C524A);
  margin: 0;
  line-height: 1.55;
}

/* ---------- INTERNAL LINKS ---------- */
.rigo-pillar-internal-links {
  background: #fff;
  border: 2px dashed #D4E8F5;
  border-radius: 14px;
  padding: 24px 28px;
  margin: 48px 0;
}
.rigo-pillar-internal-links h2.rigo-pillar-h2 {
  margin-top: 0 !important;
  font-size: 22px !important;
}
.rigo-pillar-links-list {
  list-style: none;
  padding: 0;
  margin: 0;
}
.rigo-pillar-links-list li {
  padding: 8px 0;
  border-bottom: 1px solid #F3EFE6;
}
.rigo-pillar-links-list li:last-child { border-bottom: 0; }
.rigo-pillar-links-list a {
  text-decoration: none !important;
  display: inline-block;
  transition: transform .15s;
}
.rigo-pillar-links-list a::before {
  content: "→ ";
  color: var(--primary, #27B4E5);
  font-weight: 800;
  margin-right: 4px;
}
.rigo-pillar-links-list a:hover { transform: translateX(4px); }

/* ---------- MOBILE ---------- */
@media (max-width: 640px) {
  .rigo-pillar-article { padding: 0 16px 48px; font-size: 16px; }
  .rigo-pillar-hero { padding: 24px 0 20px; }
  .rigo-pillar-hero h1 { font-size: 26px; }
  .rigo-pillar-lede { font-size: 17px; }
  .rigo-pillar-article h2, .rigo-pillar-h2 { font-size: 22px; margin-top: 40px; }
  .rigo-pillar-article h3 { font-size: 18px; }
  .rigo-pillar-product-card { padding: 18px; }
  .rigo-pillar-signature, .rigo-pillar-internal-links { padding: 18px 20px; }
  .rigo-pillar-toc { padding: 16px 18px; }
}
CSS;

	if ( wp_style_is( 'blocksy-styles', 'registered' ) ) {
		wp_add_inline_style( 'blocksy-styles', $css );
	} elseif ( wp_style_is( 'ct-main-styles', 'registered' ) ) {
		wp_add_inline_style( 'ct-main-styles', $css );
	} else {
		wp_register_style( 'rigo-pillar-pages', false );
		wp_enqueue_style( 'rigo-pillar-pages' );
		wp_add_inline_style( 'rigo-pillar-pages', $css );
	}
}, 30 );


/* ------------------------------------------------------------------
 * 2. JSON-LD Article + FAQPage (Schema.org)
 *    Boost rich snippets Google + citations GEO (ChatGPT/Perplexity/Claude)
 * ------------------------------------------------------------------ */
add_action( 'wp_head', function () {
	if ( ! rigo_is_pillar_page() ) return;

	$post = get_post();
	if ( ! $post ) return;

	$url       = get_permalink( $post );
	$title     = html_entity_decode( get_the_title( $post ), ENT_QUOTES, 'UTF-8' );
	$excerpt   = wp_strip_all_tags( get_the_excerpt( $post ) );
	$date_pub  = mysql2date( 'c', $post->post_date_gmt, false );
	$date_mod  = mysql2date( 'c', $post->post_modified_gmt, false );

	// Article Schema
	$article = [
		'@context'         => 'https://schema.org',
		'@type'            => 'Article',
		'headline'         => $title,
		'description'      => $excerpt,
		'url'              => $url,
		'mainEntityOfPage' => $url,
		'datePublished'    => $date_pub,
		'dateModified'     => $date_mod,
		'inLanguage'       => 'fr-FR',
		'author'           => [
			'@type'       => 'Person',
			'name'        => 'Brigitte Étienne',
			'jobTitle'    => 'Orthophoniste',
			'description' => 'Orthophoniste libérale à Mamers depuis 1978, créatrice Rigolettres',
			'url'         => home_url( '/a-propos/' ),
			'worksFor'    => [
				'@type' => 'Organization',
				'name'  => 'Rigolettres',
			],
		],
		'publisher'        => [
			'@type' => 'Organization',
			'name'  => 'Rigolettres',
			'url'   => home_url( '/' ),
			'logo'  => [
				'@type' => 'ImageObject',
				'url'   => 'https://rigolettres.fr/wp-content/uploads/2026/04/logo-pato-provisoire.png',
			],
		],
	];

	// FAQ Schema — extrait des <details> de la page via regex sur post_content
	$faq = rigo_extract_faq_from_content( $post->post_content );
	$faq_schema = null;
	if ( ! empty( $faq ) ) {
		$faq_schema = [
			'@context'   => 'https://schema.org',
			'@type'      => 'FAQPage',
			'mainEntity' => array_map( function ( $q ) {
				return [
					'@type'          => 'Question',
					'name'           => $q['q'],
					'acceptedAnswer' => [
						'@type' => 'Answer',
						'text'  => $q['a'],
					],
				];
			}, $faq ),
		];
	}

	// Breadcrumb Schema
	$breadcrumb = [
		'@context'        => 'https://schema.org',
		'@type'           => 'BreadcrumbList',
		'itemListElement' => [
			[
				'@type'    => 'ListItem',
				'position' => 1,
				'name'     => 'Accueil',
				'item'     => home_url( '/' ),
			],
			[
				'@type'    => 'ListItem',
				'position' => 2,
				'name'     => 'Guides',
				'item'     => home_url( '/#guides' ),
			],
			[
				'@type'    => 'ListItem',
				'position' => 3,
				'name'     => $title,
				'item'     => $url,
			],
		],
	];

	echo "\n<!-- Rigolettres 28 — Pillar JSON-LD -->\n";
	echo '<script type="application/ld+json">' . wp_json_encode( $article, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
	if ( $faq_schema ) {
		echo '<script type="application/ld+json">' . wp_json_encode( $faq_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
	}
	echo '<script type="application/ld+json">' . wp_json_encode( $breadcrumb, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}, 10 );


/* ------------------------------------------------------------------
 * Helper : extrait les {question, answer} depuis <details><summary>...</summary><p>...</p></details>
 * ------------------------------------------------------------------ */
function rigo_extract_faq_from_content( $content ) {
	$faq = [];
	if ( preg_match_all(
		'/<details[^>]*class="[^"]*rigo-pillar-faq-item[^"]*"[^>]*>\s*<summary[^>]*>(.*?)<\/summary>(.*?)<\/details>/si',
		$content,
		$matches,
		PREG_SET_ORDER
	) ) {
		foreach ( $matches as $m ) {
			$q = trim( wp_strip_all_tags( $m[1] ) );
			$a = trim( wp_strip_all_tags( $m[2] ) );
			if ( $q && $a ) $faq[] = [ 'q' => $q, 'a' => $a ];
		}
	}
	return $faq;
}


/* ------------------------------------------------------------------
 * 3. Meta description + OG tags spécifiques pages pilier
 *    (remplace le fallback du snippet SEO global)
 * ------------------------------------------------------------------ */
add_action( 'wp_head', function () {
	if ( ! rigo_is_pillar_page() ) return;
	$post = get_post();
	$excerpt = wp_strip_all_tags( get_the_excerpt( $post ) );
	if ( $excerpt ) {
		echo '<meta name="description" content="' . esc_attr( $excerpt ) . '">' . "\n";
		echo '<meta property="og:description" content="' . esc_attr( $excerpt ) . '">' . "\n";
	}
	echo '<meta property="og:type" content="article">' . "\n";
	echo '<meta property="article:author" content="Brigitte Étienne">' . "\n";
	echo '<meta property="article:section" content="Guides parents">' . "\n";
}, 5 );
