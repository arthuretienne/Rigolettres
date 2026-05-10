<?php
/**
 * Sprint 9 — Refonte page /shop (et archives produit)
 *
 * Approche : ne PAS overrider les templates WC (lourd, casse les updates).
 * On ajoute un hero éditorial via `woocommerce_before_main_content`, un
 * banc de filtres par catégorie, et on enrichit chaque card produit avec
 * un eyebrow "niveau scolaire" et une mention "ajouter au panier" plus
 * propre. Le reste du look est porté par la section CSS `15. SHOP — refonte`
 * dans blocksy-child/style.css.
 *
 * Scope : front-end, archives produit uniquement.
 */

if (!defined('ABSPATH')) exit;

// ── 1. Hero éditorial en tête de /shop ────────────────────────────────────
add_action('woocommerce_before_main_content', function () {
    if (!is_shop() && !is_product_category() && !is_product_tag()) return;

    $title = is_shop() ? 'La boutique' : single_term_title('', false);
    $subtitle = is_shop()
        ? "Des jeux et des livres pour apprendre à lire en s'amusant, conçus à la main par Brigitte Étienne, orthophoniste à Mamers depuis 1978."
        : "Sélection de jeux et livres pour cette catégorie.";
    ?>
    <section class="rigo-shop-hero" aria-label="Boutique Rigolettres">
      <div class="rigo-shop-hero-inner container">
        <p class="rigo-shop-hero-eyebrow">Boutique · Fabriqué en France</p>
        <h1 class="rigo-shop-hero-title"><?php echo esc_html($title); ?></h1>
        <p class="rigo-shop-hero-sub"><?php echo esc_html($subtitle); ?></p>

        <nav class="rigo-shop-filters" aria-label="Filtres de catégorie">
          <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="rigo-shop-filter <?php echo is_shop() ? 'is-active' : ''; ?>">
            Tous
          </a>
          <?php
          $cats = get_terms([
              'taxonomy'   => 'product_cat',
              'hide_empty' => true,
              'parent'     => 0,
          ]);
          if (!is_wp_error($cats)) {
              foreach ($cats as $cat) {
                  $is_active = is_product_category($cat->slug);
                  $url = get_term_link($cat);
                  if (is_wp_error($url)) continue;
                  printf(
                      '<a href="%s" class="rigo-shop-filter%s">%s</a>',
                      esc_url($url),
                      $is_active ? ' is-active' : '',
                      esc_html($cat->name)
                  );
              }
          }
          ?>
        </nav>

        <div class="rigo-shop-hero-trust">
          <span>✓ <strong>Livraison offerte</strong> dès <span class="nowrap">60&nbsp;€</span></span>
          <span>✓ Expédié sous <strong>48&nbsp;h</strong></span>
          <span>✓ <strong>Conçu par une orthophoniste</strong></span>
        </div>
      </div>
    </section>
    <?php
}, 5);

// ── 2. Eyebrow "niveau scolaire" sur chaque card produit ──────────────────
add_action('woocommerce_before_shop_loop_item_title', function () {
    global $product;
    if (!$product) return;
    $niveau = $product->get_attribute('Niveau scolaire');
    if (!$niveau) return;
    echo '<span class="rigo-card-eyebrow">' . esc_html($niveau) . '</span>';
}, 8);

// ── 3. Wrapper la card content pour permettre un layout custom ────────────
add_action('woocommerce_before_shop_loop_item_title', function () {
    echo '<div class="rigo-card-body">';
}, 9);
add_action('woocommerce_after_shop_loop_item', function () {
    echo '</div>';
}, 5);

// ── 4. Texte ATC plus parlant (au lieu de "Ajouter au panier") ────────────
add_filter('woocommerce_product_add_to_cart_text', function ($text, $product) {
    if (!is_shop() && !is_product_category() && !is_product_tag()) return $text;
    if ($product && $product->is_type('simple') && $product->is_purchasable() && $product->is_in_stock()) {
        return 'Ajouter';
    }
    return $text;
}, 10, 2);

// ── 5. Désactive les éléments par défaut de l'archive WC qu'on remplace ───
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
add_action('woocommerce_archive_description', '__return_empty_string', 99);
