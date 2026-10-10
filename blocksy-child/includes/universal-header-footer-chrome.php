<?php
/**
 * [Rigolettres] En-tête et pied de page universels.
 *
 * Un seul en-tête et un seul pied de page pour tout le site, accueil comprise.
 * Le header/footer natif de Blocksy est masqué (style.css, section 8), et ceux
 * qui traînaient dans le contenu de la page d'accueil sont retirés au rendu par
 * includes/contenu-nettoyage-css-herite.php.
 *
 * Structure :
 *   - bandeau d'annonce (fermable, mémorisé pour la session)
 *   - en-tête sticky : logo, navigation, mega-menu Boutique, compte, panier
 *   - tiroir de navigation (mobile + tablette, < 1024 px)
 *   - pied de page
 *
 * Le mega-menu est un enfant direct de <header> positionné en `top:100%` :
 * il suit l'en-tête sans calcul JS, y compris quand le bandeau d'annonce
 * défile ou que l'en-tête se replie au scroll.
 *
 * Tout le CSS vit dans style.css (sections 8 et 9).
 */

if (!defined('ABSPATH')) exit;

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
if (!function_exists('rigo_account_url')) {
    function rigo_account_url() {
        if (function_exists('wc_get_page_permalink')) {
            $url = wc_get_page_permalink('myaccount');
            if ($url) return $url;
        }
        return home_url('/my-account/');
    }
}

/**
 * Attribut aria-current="page" si l'URL donnée est la page affichée.
 * Sert à marquer l'entrée active du menu (repère visuel + lecteurs d'écran).
 */
if (!function_exists('rigo_nav_current')) {
    function rigo_nav_current($url) {
        $current = isset($_SERVER['REQUEST_URI']) ? wp_parse_url(wp_unslash($_SERVER['REQUEST_URI']), PHP_URL_PATH) : '';
        $target  = wp_parse_url($url, PHP_URL_PATH);
        if (!$current || !$target) return '';
        return (untrailingslashit($current) === untrailingslashit($target)) ? ' aria-current="page"' : '';
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

/**
 * Catalogue du mega-menu : groupe => [libellé, [id produit => [nom, sous-titre]]].
 * Partagé entre le panneau desktop et le tiroir mobile.
 */
if (!function_exists('rigo_mega_catalog')) {
    function rigo_mega_catalog() {
        return [
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
    }
}

// ── EN-TÊTE ────────────────────────────────────────────────────────────────
add_action('wp_body_open', function () {
    if (is_admin()) return;

    $logo     = 'https://rigolettres.fr/wp-content/uploads/2026/04/logo-pato-provisoire.png';
    $cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart/');
    $shop     = rigo_shop_url();
    $account  = rigo_account_url();
    $count    = rigo_cart_count();
    $mega     = rigo_mega_catalog();

    $links = [
        ['Pour les pros', home_url('/pour-orthophonistes/'), 'Pour les orthophonistes'],
        ['La méthode',    home_url('/methode-syllabique/'),  'La méthode syllabique'],
        ['Brigitte',      home_url('/a-propos/'),            'L’histoire de Brigitte'],
    ];
    // « Blog » n'apparaît que si une page des articles est définie (Réglages → Lecture).
    // Sans elle, get_permalink(0) renvoyait l'URL de la page courante : le lien « Blog »
    // pointait sur lui-même partout, et /blog/ répond 404.
    $blog_id = (int) get_option('page_for_posts');
    if ($blog_id) {
        $links[] = ['Blog', get_permalink($blog_id), 'Blog'];
    }
    $links[] = ['Contact', home_url('/contact/'), 'Contact'];
    ?>
    <a class="rigo-skip-link" href="#main">Aller au contenu</a>

    <div class="rigo-promo-bar" id="rigo-promo-bar" role="region" aria-label="Annonce">
      <p class="rigo-promo-text">✨ Jeux conçus par une orthophoniste, fabriqués en France dans la Sarthe</p>
      <button type="button" class="rigo-promo-close" id="rigo-promo-close" aria-label="Fermer l’annonce">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" width="16" height="16" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <script>try{if(sessionStorage.getItem('rigo_promo_closed')){document.getElementById('rigo-promo-bar').hidden=true;}}catch(e){}</script>

    <header class="site-header site-header--injected" id="site-header">
      <div class="container header-inner">

        <a class="brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Rigolettres, accueil">
          <span class="brand-pato">
            <img decoding="async" src="<?php echo esc_url($logo); ?>" alt="" width="44" height="44">
          </span>
          <?php echo rigo_wordmark_html(); ?>
        </a>

        <nav class="nav" aria-label="Menu principal" id="main-nav">
          <div class="nav-has-mega" id="rigo-nav-boutique">
            <a href="<?php echo esc_url($shop); ?>" class="nav-link"<?php echo rigo_nav_current($shop); ?>>Boutique</a>
            <button type="button" class="nav-mega-toggle" id="rigo-mega-toggle" aria-expanded="false" aria-controls="rigo-mega-boutique" aria-label="Afficher le sous-menu Boutique">
              <svg class="nav-chevron" viewBox="0 0 16 16" fill="currentColor" width="14" height="14" aria-hidden="true">
                <path d="M3.47 5.47a.75.75 0 0 1 1.06 0L8 8.94l3.47-3.47a.75.75 0 1 1 1.06 1.06l-4 4a.75.75 0 0 1-1.06 0l-4-4a.75.75 0 0 1 0-1.06z"/>
              </svg>
            </button>
          </div>
          <?php foreach ($links as $l): ?>
          <a href="<?php echo esc_url($l[1]); ?>" class="nav-link"<?php echo rigo_nav_current($l[1]); ?>><?php echo esc_html($l[0]); ?></a>
          <?php endforeach; ?>
        </nav>

        <div class="header-actions">
          <a href="<?php echo esc_url($account); ?>" class="account header-action-btn" aria-label="Mon compte">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" width="22" height="22" aria-hidden="true">
              <circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"/>
            </svg>
          </a>
          <a href="<?php echo esc_url($cart_url); ?>" class="cart header-action-btn" aria-label="Voir le panier">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" width="22" height="22" aria-hidden="true">
              <path d="M3 4h3l2.6 13.3a2 2 0 0 0 2 1.7h8.4a2 2 0 0 0 2-1.6L22 8H6"/>
              <circle cx="10" cy="21" r="1.2"/><circle cx="18" cy="21" r="1.2"/>
            </svg>
            <span class="cart-count" data-cart-count="<?php echo (int) $count; ?>"><?php echo (int) $count; ?></span>
          </a>
          <button class="hamburger" type="button" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="mobile-menu" id="rigo-hamburger">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
          </button>
        </div>

      </div><!-- .header-inner -->

      <!-- Mega-menu Boutique : enfant du header, positionné en top:100% -->
      <div class="mega-panel" id="rigo-mega-boutique" role="region" aria-label="Sous-menu Boutique">
        <div class="mega-inner container">

          <?php foreach ($mega as $col): ?>
          <div class="mega-col">
            <p class="mega-col-title"><?php echo esc_html($col['label']); ?></p>
            <ul class="mega-list">
              <?php foreach ($col['items'] as $pid => $info):
                $url = get_permalink($pid);
                if (!$url) continue;
              ?>
              <li>
                <a href="<?php echo esc_url($url); ?>" class="mega-product-link">
                  <span class="mega-product-name"><?php echo esc_html($info[0]); ?></span>
                  <span class="mega-product-sub"><?php echo esc_html($info[1]); ?></span>
                </a>
              </li>
              <?php endforeach; ?>
            </ul>
          </div>
          <?php endforeach; ?>

          <div class="mega-col mega-col--cta">
            <img src="<?php echo esc_url($logo); ?>" alt="" width="56" height="56" loading="lazy">
            <p class="mega-cta-label">Pas sûr(e) de votre choix ?</p>
            <p class="mega-cta-desc">Notre quiz en 3 questions vous recommande le jeu idéal.</p>
            <a href="#rigo-quiz" class="btn btn-primary btn-sm rigo-quiz-trigger">Aide au choix</a>
            <a href="<?php echo esc_url($shop); ?>" class="mega-all-btn">Voir toute la boutique →</a>
          </div>

        </div><!-- .mega-inner -->
      </div><!-- #rigo-mega-boutique -->
    </header>

    <!-- Tiroir de navigation (mobile + tablette) -->
    <div class="rigo-navdrawer" id="mobile-menu" aria-hidden="true">
      <div class="rigo-navdrawer-panel" role="dialog" aria-modal="true" aria-label="Menu de navigation" tabindex="-1">

        <div class="rigo-navdrawer-head">
          <a class="rigo-navdrawer-brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Rigolettres, accueil">
            <?php echo rigo_wordmark_html(); ?>
          </a>
          <button type="button" class="mobile-close" aria-label="Fermer le menu" id="rigo-mobile-close">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" width="22" height="22" aria-hidden="true">
              <path d="M18 6 6 18M6 6l12 12"/>
            </svg>
          </button>
        </div>

        <nav class="mobile-nav" aria-label="Menu mobile">

          <a href="<?php echo esc_url($shop); ?>" class="mobile-link mobile-link--strong"<?php echo rigo_nav_current($shop); ?>>Toute la boutique</a>

          <?php foreach ($mega as $col): ?>
          <details class="mobile-details" name="rigo-mobile-shop">
            <summary class="mobile-summary"><?php echo esc_html($col['label']); ?></summary>
            <div class="mobile-submenu">
              <?php foreach ($col['items'] as $pid => $info):
                $url = get_permalink($pid);
                if (!$url) continue;
              ?>
              <a href="<?php echo esc_url($url); ?>" class="mobile-sub-link">
                <span class="mobile-sub-name"><?php echo esc_html($info[0]); ?></span>
                <span class="mobile-sub-meta"><?php echo esc_html($info[1]); ?></span>
              </a>
              <?php endforeach; ?>
            </div>
          </details>
          <?php endforeach; ?>

          <?php foreach ($links as $l): ?>
          <a href="<?php echo esc_url($l[1]); ?>" class="mobile-link"<?php echo rigo_nav_current($l[1]); ?>><?php echo esc_html($l[2]); ?></a>
          <?php endforeach; ?>
          <a href="<?php echo esc_url($account); ?>" class="mobile-link"<?php echo rigo_nav_current($account); ?>>Mon compte</a>

        </nav>

        <div class="rigo-navdrawer-foot">
          <a href="#rigo-quiz" class="btn btn-primary rigo-quiz-trigger mobile-cta">Quel jeu pour mon enfant ?</a>
        </div>

      </div><!-- .rigo-navdrawer-panel -->
    </div><!-- .rigo-navdrawer -->

    <script>
    (function(){
      'use strict';
      var doc = document, html = doc.documentElement;
      var header = doc.getElementById('site-header');
      if (!header) return;

      /* ── Hauteur d'en-tête exposée au CSS (ancres, tiroirs) ── */
      function setHeaderHeight(){
        html.style.setProperty('--rigo-hdr-h', header.offsetHeight + 'px');
      }
      setHeaderHeight();
      if ('ResizeObserver' in window) { new ResizeObserver(setHeaderHeight).observe(header); }
      else { window.addEventListener('resize', setHeaderHeight); }

      /* ── Bandeau d'annonce ── */
      var promo = doc.getElementById('rigo-promo-bar');
      var promoClose = doc.getElementById('rigo-promo-close');
      if (promo && promoClose) {
        promoClose.addEventListener('click', function(){
          promo.hidden = true;
          try { sessionStorage.setItem('rigo_promo_closed', '1'); } catch(e) {}
        });
      }

      /* ── Tiroir de navigation ── */
      var hamburger = doc.getElementById('rigo-hamburger');
      var drawer    = doc.getElementById('mobile-menu');
      var panel     = drawer ? drawer.querySelector('.rigo-navdrawer-panel') : null;
      var closeBtn  = doc.getElementById('rigo-mobile-close');
      var FOCUSABLE = 'a[href], button:not([disabled]), summary, [tabindex]:not([tabindex="-1"])';

      function drawerIsOpen(){ return drawer && drawer.classList.contains('is-open'); }
      function openDrawer(){
        if (!drawer) return;
        megaHide(false);
        drawer.classList.add('is-open');
        drawer.setAttribute('aria-hidden','false');
        hamburger.setAttribute('aria-expanded','true');
        hamburger.setAttribute('aria-label','Fermer le menu');
        doc.body.classList.add('mobile-menu-open');
        if (closeBtn) closeBtn.focus();
      }
      function closeDrawer(restoreFocus){
        if (!drawerIsOpen()) return;
        drawer.classList.remove('is-open');
        drawer.setAttribute('aria-hidden','true');
        hamburger.setAttribute('aria-expanded','false');
        hamburger.setAttribute('aria-label','Ouvrir le menu');
        doc.body.classList.remove('mobile-menu-open');
        if (restoreFocus !== false) hamburger.focus();
      }
      if (hamburger && drawer) {
        hamburger.addEventListener('click', function(){ drawerIsOpen() ? closeDrawer() : openDrawer(); });
        if (closeBtn) closeBtn.addEventListener('click', function(){ closeDrawer(); });
        drawer.addEventListener('click', function(e){
          if (e.target === drawer) { closeDrawer(); return; }
          /* Le quiz s'ouvre par-dessus : on referme le tiroir sans voler le focus */
          if (e.target.closest('.rigo-quiz-trigger')) closeDrawer(false);
        });
        /* Piège de focus : Tab boucle à l'intérieur du tiroir ouvert */
        drawer.addEventListener('keydown', function(e){
          if (e.key !== 'Tab' || !drawerIsOpen()) return;
          var items = Array.prototype.filter.call(panel.querySelectorAll(FOCUSABLE), function(el){
            return el.offsetParent !== null;
          });
          if (!items.length) return;
          var first = items[0], last = items[items.length - 1];
          if (e.shiftKey && doc.activeElement === first) { e.preventDefault(); last.focus(); }
          else if (!e.shiftKey && doc.activeElement === last) { e.preventDefault(); first.focus(); }
        });
        /* Passage en largeur desktop tiroir ouvert : on le referme */
        var mq = window.matchMedia('(min-width: 1024px)');
        var onMq = function(){ if (mq.matches) closeDrawer(false); };
        if (mq.addEventListener) mq.addEventListener('change', onMq); else if (mq.addListener) mq.addListener(onMq);
      }

      /* ── Mega-menu Boutique ── */
      var navItem   = doc.getElementById('rigo-nav-boutique');
      var megaPanel = doc.getElementById('rigo-mega-boutique');
      var megaBtn   = doc.getElementById('rigo-mega-toggle');
      var megaLink  = navItem ? navItem.querySelector('.nav-link') : null;
      var openTimer = null, closeTimer = null;
      var canHover  = window.matchMedia('(hover: hover) and (pointer: fine)');

      function megaIsOpen(){ return megaPanel && megaPanel.classList.contains('is-open'); }
      function clearTimers(){ clearTimeout(openTimer); clearTimeout(closeTimer); openTimer = closeTimer = null; }
      function megaShow(){
        if (!megaPanel) return;
        clearTimers();
        megaPanel.classList.add('is-open');
        megaBtn.setAttribute('aria-expanded','true');
        megaBtn.setAttribute('aria-label','Masquer le sous-menu Boutique');
        html.classList.add('rigo-mega-open');
      }
      function megaHide(restoreFocus){
        if (!megaPanel) return;
        clearTimers();
        var hadFocus = megaPanel.contains(doc.activeElement);
        megaPanel.classList.remove('is-open');
        megaBtn.setAttribute('aria-expanded','false');
        megaBtn.setAttribute('aria-label','Afficher le sous-menu Boutique');
        html.classList.remove('rigo-mega-open');
        if (restoreFocus || (restoreFocus !== false && hadFocus)) megaBtn.focus();
      }

      if (navItem && megaPanel && megaBtn) {
        /* Souris : ouverture avec une courte intention (évite l'ouverture au simple
           survol en diagonale), fermeture différée pour laisser rejoindre le panneau. */
        var hoverIn  = function(){ if (!canHover.matches) return; clearTimeout(closeTimer); if (!megaIsOpen()) openTimer = setTimeout(megaShow, 90); };
        var hoverOut = function(){ if (!canHover.matches) return; clearTimeout(openTimer); closeTimer = setTimeout(function(){ megaHide(false); }, 220); };
        navItem.addEventListener('mouseenter', hoverIn);
        megaPanel.addEventListener('mouseenter', hoverIn);
        navItem.addEventListener('mouseleave', hoverOut);
        megaPanel.addEventListener('mouseleave', hoverOut);

        /* Clic, tactile et clavier : le bouton chevron ouvre et ferme */
        megaBtn.addEventListener('click', function(){ megaIsOpen() ? megaHide(false) : megaShow(); });

        /* Flèche bas depuis « Boutique » ou le chevron : ouvre et entre dans le panneau */
        var arrowOpen = function(e){
          if (e.key !== 'ArrowDown') return;
          e.preventDefault();
          megaShow();
          var first = megaPanel.querySelector('a[href]');
          if (first) first.focus();
        };
        megaBtn.addEventListener('keydown', arrowOpen);
        if (megaLink) megaLink.addEventListener('keydown', arrowOpen);

        /* Le focus quitte la zone (Tab) : fermeture */
        header.addEventListener('focusout', function(e){
          if (!megaIsOpen()) return;
          var next = e.relatedTarget;
          if (next && (navItem.contains(next) || megaPanel.contains(next))) return;
          if (!next) return; /* clic dans le vide : géré par le listener de clic */
          megaHide(false);
        });
        doc.addEventListener('click', function(e){
          if (megaIsOpen() && !navItem.contains(e.target) && !megaPanel.contains(e.target)) megaHide(false);
        });
        /* Un lien du panneau ouvre le quiz : on referme le panneau */
        megaPanel.addEventListener('click', function(e){
          if (e.target.closest('.rigo-quiz-trigger')) megaHide(false);
        });
      }

      /* ── Échap : ferme ce qui est ouvert, rend le focus au déclencheur ── */
      doc.addEventListener('keydown', function(e){
        if (e.key !== 'Escape') return;
        if (drawerIsOpen()) { closeDrawer(); return; }
        if (megaIsOpen()) megaHide(true);
      });

      /* ── Pastille panier ──
         La page sort du cache : le compteur rendu en PHP vaut 0 pour tout le monde.
         1) on le resynchronise si un panier existe ; 2) plusieurs scripts ne mettent
         à jour que le texte : un observateur recopie le texte dans l'attribut
         data-cart-count, dont dépend l'affichage (masqué à 0). */
      var badges = header.querySelectorAll('.cart-count');
      function syncBadge(el){
        var n = parseInt(el.textContent, 10) || 0;
        if (el.getAttribute('data-cart-count') !== String(n)) el.setAttribute('data-cart-count', String(n));
      }
      if ('MutationObserver' in window) {
        Array.prototype.forEach.call(badges, function(el){
          new MutationObserver(function(){ syncBadge(el); }).observe(el, {childList:true, characterData:true, subtree:true});
        });
      }
      function setBadge(count){
        Array.prototype.forEach.call(header.querySelectorAll('.cart-count'), function(el){
          el.textContent = String(count);
          syncBadge(el);
        });
      }
      function refreshBadge(){
        fetch('/wp-json/wc/store/v1/cart?_=' + Date.now(), {credentials:'include', cache:'no-store'})
          .then(function(r){ return r.json(); })
          .then(function(data){ setBadge(data.items_count || 0); })
          .catch(function(){});
      }
      if (doc.cookie.indexOf('woocommerce_items_in_cart=') !== -1) refreshBadge();

      /* Panier et commande (blocs WooCommerce) : la pastille suit le panier en direct
         quand on change une quantité ou qu'on retire un article. */
      window.addEventListener('load', function(){
        var data = window.wp && window.wp.data;
        if (!data || !data.subscribe || !data.select) return;
        var last = null;
        data.subscribe(function(){
          var store = data.select('wc/store/cart');
          if (!store || !store.getCartData) return;
          var cart = store.getCartData();
          var n = cart && typeof cart.itemsCount === 'number' ? cart.itemsCount : null;
          if (n !== null && n !== last) { last = n; setBadge(n); }
        });
      });
      /* Ajout au panier classique (cartes de la boutique, bouton de la fiche produit) */
      if (window.jQuery) {
        window.jQuery(doc.body).on('added_to_cart removed_from_cart', refreshBadge);
      }
    })();
    </script>
    <?php
}, 1);

// ── PIED DE PAGE ───────────────────────────────────────────────────────────
add_action('wp_footer', function () {
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
            <img decoding="async" loading="lazy" src="<?php echo esc_url($logo); ?>" alt="" width="40" height="40">
            <?php echo rigo_wordmark_html(); ?>
          </div>
          <p>
            Des jeux et des livres pour apprendre à lire en s&rsquo;amusant, conçus par une orthophoniste qui a passé sa vie à aider les enfants à déchiffrer.
          </p>
          <p class="footer-address">
            <strong>Rigolettres</strong> · Mamers (Sarthe)<br>
            <a href="mailto:contact@rigolettres.fr">contact@rigolettres.fr</a>
          </p>
        </div>
        <nav class="footer-col" aria-label="Boutique">
          <h2 class="footer-col-title">Boutique</h2>
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
        </nav>
        <nav class="footer-col" aria-label="Découvrir">
          <h2 class="footer-col-title">Découvrir</h2>
          <a href="<?php echo esc_url(rigo_home_url('histoire')); ?>">L&rsquo;histoire de Brigitte</a>
          <a href="<?php echo esc_url(home_url('/methode-syllabique/')); ?>">La méthode syllabique</a>
          <a href="<?php echo esc_url(home_url('/a-propos/')); ?>">À propos</a>
          <a href="<?php echo esc_url(home_url('/temoignages/')); ?>">Témoignages</a>
          <a href="<?php echo esc_url(rigo_home_url('presse')); ?>">Dans la presse</a>
          <a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact</a>
        </nav>
        <nav class="footer-col" aria-label="Guides et conseils">
          <h2 class="footer-col-title">Guides &amp; conseils</h2>
          <a href="<?php echo esc_url(home_url('/guide-parents-lecture/')); ?>">Mon enfant ne sait pas lire en CP</a>
          <a href="<?php echo esc_url(home_url('/apprendre-lire-cp/')); ?>">Apprendre à lire en CP</a>
          <a href="<?php echo esc_url(home_url('/dysorthographie-aide/')); ?>">Dysorthographie : aider</a>
          <a href="<?php echo esc_url(home_url('/jeux-pour-dyslexique/')); ?>">Jeux pour enfant dyslexique</a>
          <a href="<?php echo esc_url(home_url('/jeu-conjugaison/')); ?>">Jeux de conjugaison</a>
          <a href="<?php echo esc_url(home_url('/cadeau-cp-utile/')); ?>">Cadeau utile pour un CP</a>
          <a href="<?php echo esc_url(home_url('/pour-orthophonistes/')); ?>">Pour les orthophonistes</a>
        </nav>
        <nav class="footer-col" aria-label="Infos pratiques">
          <h2 class="footer-col-title">Infos pratiques</h2>
          <a href="<?php echo esc_url(home_url('/livraison-retours/')); ?>">Livraison &amp; retours</a>
          <a href="<?php echo esc_url(rigo_account_url()); ?>">Mon compte</a>
          <a href="<?php echo esc_url(home_url('/cgv/')); ?>">CGV</a>
          <a href="<?php echo esc_url(home_url('/mentions-legales/')); ?>">Mentions légales</a>
          <a href="<?php echo esc_url(get_privacy_policy_url() ?: home_url('/politique-confidentialite/')); ?>">Confidentialité</a>
        </nav>
      </div>
      <div class="container footer-bottom">
        <p>© <?php echo esc_html(date('Y')); ?> Rigolettres — Marque déposée. Fabriqué à Mamers, en France. 🇫🇷</p>
        <p class="footer-made">Fait avec <span class="footer-heart" aria-hidden="true">♥</span> pour Brigitte.</p>
      </div>
    </footer>
    <?php
}, 5);

// ── Compteur panier : fragment WooCommerce (ajout au panier en AJAX) ────────
add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {
    $count = rigo_cart_count();
    $fragments['.site-header .cart-count'] =
        '<span class="cart-count" data-cart-count="' . (int) $count . '">' . (int) $count . '</span>';
    return $fragments;
});
