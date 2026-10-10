<?php
/**
 * [Rigolettres] Nom d'auteur affiché publiquement.
 *
 * Le compte WordPress qui a créé les pages et produits a pour « nom à afficher »
 * son adresse e-mail. Blocksy l'imprimait comme auteur dans les résultats de
 * recherche et sous les articles (texte, attribut title, données structurées).
 * Tant que le profil n'est pas corrigé (Comptes → Profil → Nom à afficher
 * publiquement), on remplace toute adresse e-mail par le nom de la marque.
 */

if (!defined('ABSPATH')) exit;

function rigo_public_author_name($name) {
    return (is_string($name) && is_email($name)) ? 'Rigolettres' : $name;
}
add_filter('the_author', 'rigo_public_author_name');
add_filter('get_the_author_display_name', 'rigo_public_author_name');
