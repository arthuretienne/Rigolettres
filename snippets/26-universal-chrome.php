<?php
/**
 * [Rigolettres] Universal header + footer (site-wide one-page chrome)
 *
 * Problème résolu : la home portait son <header>/<footer> dans le contenu de page.
 * Toutes les autres pages (fiche produit, cart, checkout, blog) tombaient sur le
 * header/footer Blocksy par défaut → incohérence visuelle.
 *
 * Solution :
 *  1. Injecte le MÊME header custom + footer custom sur toutes les pages SAUF la home
 *     (la home garde son header/footer natif stocké dans le contenu page).
 *  2. Masque le header/footer Blocksy (ct-header / ct-footer) PARTOUT.
 *  3. Menu one-page : tous les liens pointent vers /#section (absolu) pour que
 *     cliquer "Nos jeux" depuis /product/pato/ ramène vers la home puis scroll.
 *  4. Cart count live via fragments WC.
 *
 * Scope : global
 * Priority : 2 (avant 24-blocksy-design-system-override pour poser les tokens)
 */

// ── Helpers ────────────────────────────────────────────────────────────────
if (!function_exists('rigo_home_url')) {
    function rigo_home_url($anchor = '') {
        return home_url('/') . ($anchor ? '#' . ltrim($anchor, '#') : '');
    }
}
if (!function_exists('rigo_cart_count')) {
    function rigo_cart_count() {
        if (function_exists('WC') && WC() && WC()->cart) {
            return (int) WC()->cart->get_cart_contents_count();
        }
        return 0;
    }
}
if (!function_exists('rigo_shop_url')) {
    function rigo_shop_url() {
        if (function_exists('wc_get_page_id')) {
            $id = wc_get_page_id('shop');
            if ($id > 0) return get_permalink($id);
        }
        return home_url('/shop/');
    }
}

// ── Wordmark réutilisable ──────────────────────────────────────────────────
if (!function_exists('rigo_wordmark_html')) {
    function rigo_wordmark_html() {
        $colors = ['#E74C3C','#F39C12','#8BC84B','#27B4E5','#9B59B6','#E74C3C','#F39C12','#8BC84B','#27B4E5','#E91E63','#8BC84B'];
        $letters = ['R','i','g','o','l','e','t','t','r','e','s'];
        $html = '<span class="brand-word" aria-hidden="true">';
        foreach ($letters as $i => $l) {
            $html .= '<span style="color:' . esc_attr($colors[$i]) . '">' . $l . '</span>';
        }
        $html .= '</span>';
        return $html;
    }
}

// ── HEADER injecté ─────────────────────────────────────────────────────────
add_action('wp_body_open', function () {
    if (is_front_page()) return; // home a déjà son header dans le contenu
    if (is_admin()) return;

    $logo = 'https://rigolettres.fr/wp-content/uploads/2026/04/logo-pato-provisoire.png';
    $cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart/');
    $count = rigo_cart_count();
    ?>
    <header class="site-header site-header--injected">
      <div class="container header-inner">
        <a class="brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Rigolettres, accueil">
          <span class="brand-pato">
            <img decoding="async" src="<?php echo esc_url($logo); ?>" alt="" aria-hidden="true">
          </span>
          <?php echo rigo_wordmark_html(); ?>
          <span class="visually-hidden">Rigolettres</span>
        </a>
        <nav class="nav" aria-label="Menu principal">
          <a href="<?php echo esc_url(rigo_home_url('histoire')); ?>">Notre histoire</a>
          <a href="<?php echo esc_url(rigo_home_url('gamme')); ?>">Nos jeux</a>
          <a href="<?php echo esc_url(rigo_home_url('methode')); ?>">La méthode</a>
          <a href="<?php echo esc_url(home_url('/guide-parents-lecture/')); ?>">Conseils</a>
          <a href="<?php echo esc_url(rigo_home_url('presse')); ?>">On en parle</a>
          <a href="<?php echo esc_url(rigo_home_url('contact')); ?>">Contact</a>
        </nav>
        <div class="header-actions">
          <a href="<?php echo esc_url($cart_url); ?>" class="cart" aria-label="Panier">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 4h3l2.6 13.3a2 2 0 0 0 2 1.7h8.4a2 2 0 0 0 2-1.6L22 8H6"/><circle cx="10" cy="21" r="1.2"/><circle cx="18" cy="21" r="1.2"/></svg>
            <span class="cart-count" data-cart-count><?php echo (int) $count; ?></span>
          </a>
        </div>
      </div>
    </header>
    <?php
}, 1);

// ── FOOTER injecté ─────────────────────────────────────────────────────────
add_action('wp_footer', function () {
    if (is_front_page()) return; // home a son footer
    if (is_admin()) return;

    $logo = 'https://rigolettres.fr/wp-content/uploads/2026/04/logo-pato-provisoire.png';
    $shop = rigo_shop_url();
    ?>
    <footer id="contact" class="site-footer site-footer--injected">
      <svg class="footer-grass" viewBox="0 0 1200 80" preserveAspectRatio="none" aria-hidden="true">
        <path d="M0 30 Q 100 5, 200 25 T 400 25 T 600 20 T 800 30 T 1000 25 T 1200 30 L 1200 80 L 0 80 Z" fill="#8BC84B"/>
        <path d="M0 40 Q 100 15, 200 35 T 400 35 T 600 30 T 800 40 T 1000 35 T 1200 40 L 1200 80 L 0 80 Z" fill="#6FA832"/>
      </svg>
      <div class="container footer-inner">
        <div class="footer-brand">
          <div class="footer-logo">
            <img decoding="async" src="<?php echo esc_url($logo); ?>" alt="">
            <?php echo rigo_wordmark_html(); ?>
          </div>
          <p>
            Des jeux et des livres pour apprendre à lire en s&rsquo;amusant, conçus par une orthophoniste qui a passé sa vie à aider les enfants à déchiffrer.
          </p>
          <p class="footer-address">
            <strong>Rigolettres</strong> · Mamers (Sarthe)<br>
            contact@rigolettres.fr
          </p>
        </div>
        <div class="footer-col">
          <h4>Boutique</h4>
          <a href="<?php echo esc_url($shop); ?>">Tous les jeux</a>
          <?php
          $shop_links = [
              28 => 'Rigolettres N°1 — Pato',
              29 => 'Rigolettres N°2 — Sons',
              30 => 'Rigoloverbes',
              31 => 'Grammaire — Niveau 1',
              32 => 'Grammaire — Niveau 2',
          ];
          foreach ($shop_links as $pid => $label) {
              $url = get_permalink($pid);
              if ($url) echo '<a href="' . esc_url($url) . '">' . esc_html($label) . '</a>';
          }
          ?>
        </div>
        <div class="footer-col">
          <h4>Découvrir</h4>
          <a href="<?php echo esc_url(rigo_home_url('histoire')); ?>">L&rsquo;histoire de Brigitte</a>
          <a href="<?php echo esc_url(home_url('/methode-syllabique/')); ?>">La méthode syllabique</a>
          <a href="<?php echo esc_url(home_url('/a-propos/')); ?>">À propos</a>
          <a href="<?php echo esc_url(home_url('/temoignages/')); ?>">Témoignages</a>
          <a href="<?php echo esc_url(rigo_home_url('presse')); ?>">Dans la presse</a>
          <a href="<?php echo esc_url(rigo_home_url('contact')); ?>">Contact</a>
        </div>
        <div class="footer-col">
          <h4>Guides &amp; conseils</h4>
          <a href="<?php echo esc_url(home_url('/guide-parents-lecture/')); ?>">Mon enfant ne sait pas lire en CP</a>
          <a href="<?php echo esc_url(home_url('/apprendre-lire-cp/')); ?>">Apprendre à lire en CP</a>
          <a href="<?php echo esc_url(home_url('/dysorthographie-aide/')); ?>">Dysorthographie : aider</a>
          <a href="<?php echo esc_url(home_url('/jeux-pour-dyslexique/')); ?>">Jeux pour enfant dyslexique</a>
          <a href="<?php echo esc_url(home_url('/jeu-conjugaison/')); ?>">Jeux de conjugaison</a>
          <a href="<?php echo esc_url(home_url('/cadeau-cp-utile/')); ?>">Cadeau utile pour un CP</a>
          <a href="<?php echo esc_url(home_url('/pour-orthophonistes/')); ?>">Pour les orthophonistes</a>
        </div>
        <div class="footer-col">
          <h4>Infos pratiques</h4>
          <a href="<?php echo esc_url(rigo_home_url('livraison')); ?>">Livraison &amp; retours</a>
          <a href="<?php echo esc_url(home_url('/mon-compte/')); ?>">Mon compte</a>
          <a href="<?php echo esc_url(home_url('/conditions-generales-de-vente/')); ?>">CGV</a>
          <a href="<?php echo esc_url(home_url('/mentions-legales/')); ?>">Mentions légales</a>
          <a href="<?php echo esc_url(get_privacy_policy_url() ?: home_url('/politique-de-confidentialite/')); ?>">Confidentialité</a>
        </div>
      </div>
      <div class="container footer-bottom">
        <p>© <?php echo date('Y'); ?> Rigolettres — Marque déposée. Fabriqué à Mamers, en France. 🇫🇷</p>
        <p class="footer-made">Fait avec <span style="color:#E74C3C">♥</span> pour Brigitte.</p>
      </div>
    </footer>
    <?php
}, 5);

// ── CSS universel header/footer (design tokens + chrome) ───────────────────
add_action('wp_enqueue_scripts', function () {
    if (is_admin()) return;

    $css = <<<CSS
:root {
  --bg: #FBF8F1;
  --ink: #2D2420;
  --muted: #6B7280;
  --border: #E7E2D5;
  --primary: #27B4E5;
  --primary-dark: #1E8DB8;
  --accent: #68a033;
  --red: #E74C3C;
  --yellow: #FBCF33;
  --yellow-bright: #F39C12;
  --ease: cubic-bezier(.2,.8,.2,1);
}

/* Masquer chrome Blocksy PARTOUT (sauf back-office) */
header#header.ct-header,
footer#footer.ct-footer {
  display: none !important;
}

/* Le body Blocksy a parfois un padding-top pour header sticky → reset */
body.ct-header-sticky,
body.has-ct-header-spacing {
  padding-top: 0 !important;
}

/* Container */
.site-header .container,
.site-footer .container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
@media (min-width: 768px) {
  .site-header .container,
  .site-footer .container { padding: 0 32px; }
}

.visually-hidden {
  position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px;
  overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0;
}

/* ───────── HEADER ───────── */
.site-header {
  position: sticky; top: 0; z-index: 50;
  background: rgba(251, 248, 241, .92);
  backdrop-filter: saturate(1.4) blur(10px);
  -webkit-backdrop-filter: saturate(1.4) blur(10px);
  border-bottom: 1px solid var(--border);
  width: 100%;
}
.site-header .header-inner {
  display: flex; align-items: center; justify-content: space-between;
  gap: 24px; padding: 14px 24px;
}
@media (min-width: 768px) {
  .site-header .header-inner { padding: 18px 32px; }
}
.site-header .brand {
  display: inline-flex; align-items: center; gap: 10px;
  padding: 4px 0; text-decoration: none;
  transition: transform .2s var(--ease);
}
.site-header .brand:hover { transform: rotate(-2deg); }
.site-header .brand-pato img {
  width: 44px; height: 44px; object-fit: contain;
  filter: drop-shadow(0 2px 4px rgba(31,41,55,.15));
}
.site-header .brand-word,
.site-footer .brand-word {
  font-family: "Kalam", cursive;
  font-weight: 700;
  font-size: 28px;
  letter-spacing: -.01em;
  line-height: 1;
}
.site-header .brand-word span { display: inline-block; transition: transform .25s var(--ease); }
.site-header .brand:hover .brand-word span:nth-child(odd)  { transform: translateY(-2px) rotate(-4deg); }
.site-header .brand:hover .brand-word span:nth-child(even) { transform: translateY(2px)  rotate(3deg); }

.site-header .nav {
  display: none;
  gap: 28px;
  font-weight: 700;
  font-size: 15px;
  font-family: "Nunito", sans-serif;
}
@media (min-width: 1024px) { .site-header .nav { display: flex; } }
.site-header .nav a {
  position: relative;
  color: var(--ink);
  padding: 4px 0;
  text-decoration: none;
  transition: color .2s;
}
.site-header .nav a::after {
  content: ""; position: absolute;
  left: 0; right: 100%; bottom: -2px; height: 2px;
  background: var(--primary);
  transition: right .25s var(--ease);
}
.site-header .nav a:hover { color: var(--primary-dark); }
.site-header .nav a:hover::after { right: 0; }

.site-header .header-actions { display: flex; align-items: center; gap: 8px; }
.site-header .header-actions a {
  position: relative;
  display: inline-flex; align-items: center; justify-content: center;
  width: 40px; height: 40px; border-radius: 50%;
  color: var(--ink); text-decoration: none;
  transition: background .2s;
}
.site-header .header-actions a:hover { background: rgba(39,180,229,.08); color: var(--primary-dark); }
.site-header .header-actions svg { width: 22px; height: 22px; }

.site-header .cart-count {
  position: absolute; top: 2px; right: 2px;
  min-width: 18px; height: 18px; padding: 0 5px;
  background: var(--red); color: #fff;
  font-size: 11px; font-weight: 800;
  border-radius: 9px;
  display: grid; place-items: center;
  border: 2px solid var(--bg);
  line-height: 1;
}
.site-header .cart-count[data-cart-count="0"] { display: none; }

/* Menu mobile : affiche le logo et panier, cache la nav (pour l'instant — hamburger v2) */
@media (max-width: 1023px) {
  .site-header .nav { display: none; }
}

/* ───────── FOOTER ───────── */
.site-footer {
  background: var(--ink);
  color: rgba(255,255,255,.85);
  padding: 20px 0 32px;
  position: relative;
  margin-bottom: 0;
  margin-top: 48px;
  font-family: "Nunito", sans-serif;
}
.site-footer .footer-grass {
  position: absolute;
  top: -60px;
  left: 0; right: 0;
  width: 100%; height: 80px;
  display: block;
  pointer-events: none;
  z-index: 2;
}
.site-footer .footer-inner {
  display: grid;
  grid-template-columns: 1fr;
  gap: 32px;
  padding-top: 56px;
  padding-bottom: 48px;
}
@media (min-width: 768px) {
  .site-footer .footer-inner { grid-template-columns: 1fr 1fr; gap: 40px; }
}
@media (min-width: 1100px) {
  .site-footer .footer-inner { grid-template-columns: 1.4fr 1fr 1fr 1.2fr 1fr; gap: 40px; }
}
.site-footer .footer-logo {
  display: inline-flex; align-items: center; gap: 10px;
  margin-bottom: 14px;
}
.site-footer .footer-logo img { width: 40px; height: 40px; }
.site-footer .footer-logo .brand-word { font-size: 24px; color: #fff; }
.site-footer .footer-brand p {
  font-size: 14.5px; line-height: 1.6;
  color: rgba(255,255,255,.7);
  margin: 0 0 18px; max-width: 360px;
}
.site-footer .footer-address { font-size: 13.5px; color: rgba(255,255,255,.55); }
.site-footer .footer-col h4 {
  font-family: "Kalam", cursive;
  font-weight: 700;
  font-size: 20px;
  margin: 0 0 16px;
  color: #fff;
  letter-spacing: -.01em;
}
.site-footer .footer-col a {
  display: block;
  padding: 4px 0;
  font-size: 14px;
  color: rgba(255,255,255,.7);
  text-decoration: none;
  transition: color .2s, transform .2s;
}
.site-footer .footer-col a:hover { color: var(--yellow-bright); transform: translateX(3px); }
.site-footer .footer-bottom {
  display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center;
  padding-top: 24px;
  padding-bottom: 0;
  border-top: 1px solid rgba(255,255,255,.08);
  gap: 12px;
  font-size: 13px;
  color: rgba(255,255,255,.55);
}
.site-footer .footer-made {
  font-family: "Kalam", cursive;
  font-size: 15px;
  margin: 0;
}
.site-footer .footer-bottom p { margin: 0; }

/* Fix débordements texte : wrap tout, max-width images */
.site-footer, .site-header { overflow-wrap: anywhere; word-break: normal; }
.site-footer img, .site-header img { max-width: 100%; height: auto; }
CSS;

    // wp_add_inline_style pour survivre à LiteSpeed Cache (piggyback sur blocksy)
    if (wp_style_is('blocksy-styles', 'registered')) {
        wp_add_inline_style('blocksy-styles', $css);
    } elseif (wp_style_is('ct-main-styles', 'registered')) {
        wp_add_inline_style('ct-main-styles', $css);
    } else {
        // Fallback : enqueue direct
        wp_register_style('rigo-universal-chrome', false);
        wp_enqueue_style('rigo-universal-chrome');
        wp_add_inline_style('rigo-universal-chrome', $css);
    }
}, 20);

// ── Cart count live refresh (WC fragments) ─────────────────────────────────
add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {
    $count = rigo_cart_count();
    $fragments['.site-header .cart-count[data-cart-count]'] =
        '<span class="cart-count" data-cart-count="' . (int) $count . '">' . (int) $count . '</span>';
    return $fragments;
});
