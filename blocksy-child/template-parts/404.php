<?php
/**
 * Page introuvable (404).
 *
 * Surcharge du gabarit Blocksy (template-parts/404.php), qui n'offrait qu'un
 * champ de recherche — le site n'a pas de moteur de recherche interne. On
 * propose à la place les trois sorties utiles : boutique, aide au choix, contact.
 * Même structure que l'original (bandeau de titre + contenu) pour hériter des
 * styles de style.css §6.
 */

if (!defined('ABSPATH')) exit;

$rigo_shop = function_exists('rigo_shop_url') ? rigo_shop_url() : home_url('/shop/');
?>
<div class="ct-container" <?php echo function_exists('blocksy_get_v_spacing') ? blocksy_get_v_spacing() : ''; ?>>
	<section class="ct-no-results rigo-404">

		<section class="hero-section" data-type="type-1">
			<header class="entry-header">
				<p class="rigo-eyebrow">Erreur 404</p>
				<h1 class="page-title" itemprop="headline">Oups&nbsp;! Cette page est introuvable.</h1>
				<div class="page-description">
					Le lien a peut-être changé, ou la page n’existe plus. Voici de quoi repartir du bon pied.
				</div>
			</header>
		</section>

		<div class="entry-content is-layout-flow">
			<div class="rigo-404-actions">
				<a class="btn btn-primary" href="<?php echo esc_url($rigo_shop); ?>">Voir la boutique</a>
				<a class="btn btn-ghost rigo-quiz-trigger" href="#rigo-quiz">Quel jeu pour mon enfant&nbsp;?</a>
			</div>
			<p class="rigo-404-links">
				<a href="<?php echo esc_url(home_url('/')); ?>">Retour à l’accueil</a>
				<a href="<?php echo esc_url(home_url('/methode-syllabique/')); ?>">La méthode syllabique</a>
				<a href="<?php echo esc_url(home_url('/contact/')); ?>">Nous écrire</a>
			</p>
		</div>

	</section>
</div>
