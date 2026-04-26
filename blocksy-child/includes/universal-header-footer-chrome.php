<?php
/**
 * Migré depuis Code Snippet #39 : [Rigolettres] 26 — Universal header/footer chrome
 * Description : Header + footer site-wide avec colonne Guides vers pages pilier SEO
 */

if (!defined('ABSPATH')) exit;

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
    if (is_admin()) return;

    $logo     = 'https://rigolettres.fr/wp-content/uploads/2026/04/logo-pato-provisoire.png';
    $cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart/');
    $shop     = rigo_shop_url();
    $count    = rigo_cart_count();
    $blog_url = get_permalink((int) get_option('page_for_posts')) ?: home_url('/blog/');

    // Catalogue pour le mega-menu : id => [nom, sous-titre niveau/thème]
    $mega = [
        'lecture' => [
            'label' => 'Jeux de lecture',
            'items' => [
                28 => ['Pato le Chien — N°1',  'CP · 5-7 ans · Syllabes à 2 lettres'],
                29 => ['Luna les Sons — N°2',   'CE1 · 7-8 ans · Sons complexes'],
                75 => ['Zoé — N°3',             'CE1-CE2 · 7-9 ans · Syllabes avancées'],
            ],
        ],
        'verbes' => [
            'label' => 'Rigoloverbes',
            'items' => [
                76 => ['Présent',        'CE2-CM1 · Toutes les terminaisons'],
                77 => ['Imparfait',      'CM1 · Conjugaison narrative'],
                78 => ['Futur simple',   'CM1-CM2 · Projections et récits'],
                79 => ['Passé composé',  'CE2-CM1 · Auxiliaires être/avoir'],
                30 => ['Passé simple',   'CM2 · Textes littéraires'],
            ],
        ],
        'livres' => [
            'label' => 'Livres & Packs',
            'items' => [
                31 => ['Grammaire — Niveau 1',       'CP-CE1 · Les fondamentaux'],
                32 => ['Grammaire — Niveau 2',       'CE2-CM · Approfondissement'],
                80 => ['Pack Rigolettres R1+R2',     'Économie 3 €'],
                81 => ['Pack Rigolettres R1+R2+R3',  'Économie 7 €'],
                83 => ['Pack 5 Rigoloverbes',        'Intégrale conjugaison'],
            ],
        ],
    ];
    ?>
    <header class="site-header site-header--injected" id="site-header">
      <div class="container header-inner">

        <a class="brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Rigolettres, accueil">
          <span class="brand-pato">
            <img decoding="async" src="<?php echo esc_url($logo); ?>" alt="" aria-hidden="true" width="44" height="44">
          </span>
          <?php echo rigo_wordmark_html(); ?>
          <span class="visually-hidden">Rigolettres</span>
        </a>

        <nav class="nav" aria-label="Menu principal" id="main-nav">

          <!-- ▾ BOUTIQUE — trigger seulement, panel en dehors du <header> -->
          <div class="nav-has-mega" id="rigo-nav-boutique">
            <a href="<?php echo esc_url($shop); ?>" class="nav-link" aria-haspopup="true" aria-expanded="false" aria-controls="rigo-mega-boutique">
              Boutique
              <svg class="nav-chevron" viewBox="0 0 16 16" fill="currentColor" width="12" height="12" aria-hidden="true">
                <path d="M3.47 5.47a.75.75 0 0 1 1.06 0L8 8.94l3.47-3.47a.75.75 0 1 1 1.06 1.06l-4 4a.75.75 0 0 1-1.06 0l-4-4a.75.75 0 0 1 0-1.06z"/>
              </svg>
            </a>
          </div><!-- .nav-has-mega -->

          <!-- Liens simples vers pages réelles -->
          <a href="<?php echo esc_url(home_url('/pour-orthophonistes/')); ?>" class="nav-link">Pour les pros</a>
          <a href="<?php echo esc_url(home_url('/methode-syllabique/')); ?>" class="nav-link">La méthode</a>
          <a href="<?php echo esc_url(home_url('/a-propos/')); ?>" class="nav-link">Brigitte</a>
          <a href="<?php echo esc_url($blog_url); ?>" class="nav-link">Blog</a>
          <a href="<?php echo esc_url(rigo_home_url('contact')); ?>" class="nav-link">Contact</a>

        </nav><!-- .nav -->

        <div class="header-actions">
          <a href="<?php echo esc_url($cart_url); ?>" class="cart header-action-btn" aria-label="Voir le panier">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" width="22" height="22" aria-hidden="true">
              <path d="M3 4h3l2.6 13.3a2 2 0 0 0 2 1.7h8.4a2 2 0 0 0 2-1.6L22 8H6"/>
              <circle cx="10" cy="21" r="1.2"/><circle cx="18" cy="21" r="1.2"/>
            </svg>
            <span class="cart-count" data-cart-count><?php echo (int) $count; ?></span>
          </a>
          <button class="hamburger" type="button" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="mobile-menu" id="rigo-hamburger">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
          </button>
        </div>

      </div><!-- .header-inner -->
    </header>

    <!-- ─── Mega-panel HORS du header (évite le piège will-change/stacking context) ─── -->
    <div class="mega-panel" id="rigo-mega-boutique" role="region" aria-label="Sous-menu Boutique">
      <div class="mega-inner container">

        <?php foreach ($mega as $col): ?>
        <div class="mega-col">
          <p class="mega-col-title"><?php echo esc_html($col['label']); ?></p>
          <?php foreach ($col['items'] as $pid => $info):
            $url = get_permalink($pid);
            if (!$url) continue;
          ?>
          <a href="<?php echo esc_url($url); ?>" class="mega-product-link">
            <span class="mega-product-name"><?php echo esc_html($info[0]); ?></span>
            <span class="mega-product-sub"><?php echo esc_html($info[1]); ?></span>
          </a>
          <?php endforeach; ?>
          <a href="<?php echo esc_url($shop); ?>" class="mega-see-all">Voir tout →</a>
        </div>
        <?php endforeach; ?>

        <!-- CTA colonne -->
        <div class="mega-col mega-col--cta">
          <img src="<?php echo esc_url($logo); ?>" alt="Pato" width="60" height="60">
          <p class="mega-cta-label">Pas sûr(e) de votre choix ?</p>
          <p class="mega-cta-desc">Notre quiz en 3 questions vous recommande le jeu idéal.</p>
          <a href="#rigo-quiz" class="btn-primary rigo-quiz-trigger">Aide au choix</a>
          <a href="<?php echo esc_url($shop); ?>" class="mega-all-btn">Voir toute la boutique →</a>
        </div>

      </div><!-- .mega-inner -->
    </div><!-- #rigo-mega-boutique -->

    <!-- ─── Mobile menu (drawer droit) ─────────────────────────────────── -->
    <div class="mobile-menu" id="mobile-menu" aria-hidden="true" role="dialog" aria-modal="true" aria-label="Menu navigation">
      <div class="mobile-menu-inner">

        <div class="mobile-menu-head">
          <a href="<?php echo esc_url(home_url('/')); ?>" aria-label="Rigolettres, accueil">
            <?php echo rigo_wordmark_html(); ?>
          </a>
          <button type="button" class="mobile-close" aria-label="Fermer le menu" id="rigo-mobile-close">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" width="22" height="22" aria-hidden="true">
              <path d="M18 6 6 18M6 6l12 12"/>
            </svg>
          </button>
        </div>

        <nav class="mobile-nav" aria-label="Menu mobile">

          <!-- Boutique accordion -->
          <details class="mobile-details" open>
            <summary class="mobile-summary">Boutique</summary>
            <div class="mobile-submenu">
              <?php foreach ($mega as $col): ?>
              <p class="mobile-sub-label"><?php echo esc_html($col['label']); ?></p>
              <?php foreach ($col['items'] as $pid => $info):
                $url = get_permalink($pid);
                if (!$url) continue;
              ?>
              <a href="<?php echo esc_url($url); ?>" class="mobile-sub-link"><?php echo esc_html($info[0]); ?></a>
              <?php endforeach; ?>
              <?php endforeach; ?>
              <a href="<?php echo esc_url($shop); ?>" class="mobile-see-all-btn">Voir toute la boutique →</a>
            </div>
          </details>

          <a href="<?php echo esc_url(home_url('/pour-orthophonistes/')); ?>" class="mobile-link">Pour les orthophonistes</a>
          <a href="<?php echo esc_url(home_url('/methode-syllabique/')); ?>" class="mobile-link">La méthode syllabique</a>
          <a href="<?php echo esc_url(home_url('/a-propos/')); ?>" class="mobile-link">L'histoire de Brigitte</a>
          <a href="<?php echo esc_url($blog_url); ?>" class="mobile-link">Blog</a>
          <a href="<?php echo esc_url(rigo_home_url('contact')); ?>" class="mobile-link">Contact</a>

        </nav>

        <div class="mobile-menu-foot">
          <a href="#rigo-quiz" class="btn-primary rigo-quiz-trigger mobile-cta">Aide au choix →</a>
        </div>

      </div><!-- .mobile-menu-inner -->
    </div><!-- .mobile-menu -->

    <script>
    (function(){
      'use strict';
      var hamburger   = document.getElementById('rigo-hamburger');
      var mobileMenu  = document.getElementById('mobile-menu');
      var mobileClose = document.getElementById('rigo-mobile-close');

      function openMobile(){
        mobileMenu.classList.add('is-open');
        mobileMenu.setAttribute('aria-hidden','false');
        hamburger.setAttribute('aria-expanded','true');
        document.body.classList.add('mobile-menu-open');
        if (mobileClose) mobileClose.focus();
      }
      function closeMobile(){
        mobileMenu.classList.remove('is-open');
        mobileMenu.setAttribute('aria-hidden','true');
        hamburger.setAttribute('aria-expanded','false');
        document.body.classList.remove('mobile-menu-open');
        if (hamburger) hamburger.focus();
      }

      if (hamburger)   hamburger.addEventListener('click', openMobile);
      if (mobileClose) mobileClose.addEventListener('click', closeMobile);

      // Clic sur le backdrop
      if (mobileMenu) mobileMenu.addEventListener('click', function(e){
        if (e.target === mobileMenu) closeMobile();
      });

      // Escape
      document.addEventListener('keydown', function(e){
        if (e.key === 'Escape' && mobileMenu && mobileMenu.classList.contains('is-open')) closeMobile();
      });

      // Mega-menu desktop — le panel est un sibling du header (hors stacking context)
      var navItem   = document.getElementById('rigo-nav-boutique');
      var megaPanel = document.getElementById('rigo-mega-boutique');
      var megaTrig  = navItem && navItem.querySelector('.nav-link');

      if (navItem && megaPanel && megaTrig) {
        function megaShow(){ megaTrig.setAttribute('aria-expanded','true');  megaPanel.classList.add('is-open'); }
        function megaHide(){ megaTrig.setAttribute('aria-expanded','false'); megaPanel.classList.remove('is-open'); }

        navItem.addEventListener('mouseenter', megaShow);
        navItem.addEventListener('mouseleave', function(e){
          // Ne pas fermer si on entre dans le panel
          if (!megaPanel.contains(e.relatedTarget)) megaHide();
        });
        megaPanel.addEventListener('mouseleave', function(e){
          if (!navItem.contains(e.relatedTarget)) megaHide();
        });
        megaTrig.addEventListener('focus', megaShow);
        navItem.addEventListener('focusout', function(e){
          if (!navItem.contains(e.relatedTarget) && !megaPanel.contains(e.relatedTarget)) megaHide();
        });
        megaPanel.addEventListener('focusout', function(e){
          if (!megaPanel.contains(e.relatedTarget) && !navItem.contains(e.relatedTarget)) megaHide();
        });
        document.addEventListener('click', function(e){
          if (!navItem.contains(e.target) && !megaPanel.contains(e.target)) megaHide();
        });
      }

      // Positionner le mega-panel exactement sous le header sticky
      function setMegaTop(){
        var hdr = document.getElementById('site-header');
        if (!hdr) return;
        document.documentElement.style.setProperty('--rigo-hdr-h', hdr.getBoundingClientRect().height + 'px');
      }
      setMegaTop();
      window.addEventListener('resize', setMegaTop);
    })();
    </script>
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

/*
 * CSS du header/footer injectés : déplacé dans blocksy-child/style.css
 * (section "Universal chrome — header/footer injectés"). Cascade naturelle,
 * tokens --rigo-*, plus de wp_add_inline_style legacy.
 */

// ── Cart count live refresh (WC fragments) ─────────────────────────────────
add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {
    $count = rigo_cart_count();
    $fragments['.site-header .cart-count[data-cart-count]'] =
        '<span class="cart-count" data-cart-count="' . (int) $count . '">' . (int) $count . '</span>';
    return $fragments;
});
