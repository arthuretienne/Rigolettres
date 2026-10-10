<?php
/**
 * Migré depuis Code Snippet #16 : [Rigolettres] Side-cart drawer
 * Description : Drawer overlay panier côté droit — s'ouvre sur add-to-cart et clic icône panier. Source: audit.md Phase D.2
 */

if (!defined('ABSPATH')) exit;

/**
 * [Rigolettres] Side-cart drawer
 *
 * Drawer en overlay à droite qui s'ouvre quand on clique "Ajouter au panier"
 * ou quand on clique sur l'icône panier dans le header. Affiche le contenu
 * du panier via WooCommerce Store API (JSON). Pas de rechargement de page.
 *
 * Source : audit.md Phase D.2
 * Déployé via Code Snippets (scope=front-end)
 */

add_action('wp_footer', function () {
    if (is_admin()) return;
    ?>
    <!-- Rigolettres Side Cart Drawer -->
    <div id="rigo-cart-overlay" aria-hidden="true"></div>
    <aside id="rigo-cart-drawer" role="dialog" aria-modal="true" aria-label="Votre panier" aria-hidden="true">
      <div class="rigo-drawer-head">
        <h2 class="rigo-drawer-title">Votre panier</h2>
        <button class="rigo-drawer-close" aria-label="Fermer le panier" type="button">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </button>
      </div>
      <div id="rigo-drawer-items" class="rigo-drawer-items">
        <div class="rigo-drawer-loading">Chargement…</div>
      </div>
      <div id="rigo-drawer-footer" class="rigo-drawer-footer" style="display:none">
        <div class="rigo-drawer-subtotal">
          <span>Sous-total</span>
          <span id="rigo-drawer-total"></span>
        </div>
        <div class="rigo-drawer-shipping-note">Livraison en point relais dès <?php echo rigo_shipping_from_label(); ?></div>
        <a href="/checkout/" class="rigo-drawer-checkout-btn">Commander →</a>
        <a href="/cart/" class="rigo-drawer-cart-link">Voir le panier complet</a>
      </div>
    </aside>

    <style>
    #rigo-cart-overlay {
      position: fixed; inset: 0; z-index: var(--rigo-z-overlay);
      background: rgba(42,29,15,.45);
      backdrop-filter: blur(2px);
      opacity: 0; pointer-events: none;
      transition: opacity .32s ease;
    }
    #rigo-cart-overlay.is-open { opacity: 1; pointer-events: all; }

    #rigo-cart-drawer {
      position: fixed; top: 0; right: 0; bottom: 0;
      width: min(400px, 92vw);
      background: var(--rigo-cream);
      z-index: var(--rigo-z-drawer);
      display: flex; flex-direction: column;
      transform: translateX(100%);
      transition: transform .35s cubic-bezier(.22,.61,.36,1);
      box-shadow: -8px 0 48px rgba(42,29,15,.15);
      border-left: 2px solid var(--rigo-border);
    }
    #rigo-cart-drawer.is-open { transform: translateX(0); }
    #rigo-cart-drawer[aria-hidden="true"] { visibility: hidden; }
    #rigo-cart-drawer.is-open[aria-hidden="false"] { visibility: visible; }

    .rigo-drawer-head {
      display: flex; align-items: center; justify-content: space-between;
      padding: 20px 20px 16px;
      border-bottom: 1px solid var(--rigo-border);
      background: #fff;
      flex-shrink: 0;
    }
    .rigo-drawer-title {
      font-family: var(--rigo-serif);
      font-size: 22px; font-weight: 700;
      color: var(--rigo-ink); margin: 0;
    }
    .rigo-drawer-close {
      width: 44px; height: 44px;
      display: grid; place-items: center;
      border-radius: 50%; border: 0;
      background: transparent; cursor: pointer;
      color: var(--rigo-ink-soft);
      transition: background .18s;
    }
    .rigo-drawer-close:hover { background: var(--rigo-cream-warm); }
    .rigo-drawer-close svg { width: 18px; height: 18px; }

    .rigo-drawer-items { flex: 1; overflow-y: auto; padding: 16px 20px; }
    .rigo-drawer-loading { color: var(--rigo-muted); font-size: 14px; text-align: center; padding: 32px 0; }

    .rigo-drawer-item {
      display: grid;
      grid-template-columns: 60px 1fr auto;
      gap: 12px; align-items: start;
      padding: 14px 0;
      border-bottom: 1px solid var(--rigo-border);
    }
    .rigo-drawer-item:last-child { border-bottom: 0; }
    .rigo-drawer-item-img {
      width: 60px; height: 60px; border-radius: 10px;
      object-fit: cover; background: var(--rigo-cream-warm);
    }
    .rigo-drawer-item-img-placeholder {
      width: 60px; height: 60px; border-radius: 10px;
      background: var(--rigo-border); display: grid; place-items: center;
      font-size: 22px;
    }
    .rigo-drawer-item-name {
      font-weight: 700; font-size: 14px; color: var(--rigo-ink); line-height: 1.4;
      margin-bottom: 4px;
    }
    .rigo-drawer-item-qty { font-size: 13px; color: var(--rigo-muted); }
    .rigo-drawer-item-price { font-weight: 800; font-size: 15px; color: var(--rigo-action); }

    .rigo-drawer-empty {
      text-align: center; padding: 48px 20px;
      color: var(--rigo-muted);
    }
    .rigo-drawer-empty svg { width: 48px; height: 48px; margin-bottom: 12px; opacity: .4; }
    .rigo-drawer-empty p { font-size: 15px; margin: 0 0 16px; }
    .rigo-drawer-empty a {
      display: inline-block; padding: 10px 20px;
      background: var(--rigo-action); color: #fff; border-radius: 9999px;
      font-weight: 800; font-size: 14px;
      text-decoration: none;
    }

    .rigo-drawer-footer {
      padding: 16px 20px 24px;
      border-top: 2px solid var(--rigo-border);
      background: #fff;
      flex-shrink: 0;
    }
    .rigo-drawer-subtotal {
      display: flex; justify-content: space-between; align-items: baseline;
      font-weight: 800; font-size: 16px; color: var(--rigo-ink);
      margin-bottom: 4px;
    }
    #rigo-drawer-total { color: var(--rigo-action); }
    .rigo-drawer-shipping-note {
      font-size: 12px; color: var(--rigo-green-dark); font-weight: 700;
      margin-bottom: 14px;
    }


    .rigo-drawer-cart-link {
      display: block; text-align: center;
      font-size: 13px; color: var(--rigo-ink-soft);
      text-decoration: underline;
    }
    </style>

    <script>
    (function() {
      var drawer = document.getElementById('rigo-cart-drawer');
      var overlay = document.getElementById('rigo-cart-overlay');
      var itemsEl = document.getElementById('rigo-drawer-items');
      var footerEl = document.getElementById('rigo-drawer-footer');
      var totalEl = document.getElementById('rigo-drawer-total');
      if (!drawer) return;

      var opener = null;

      function isOpen() { return drawer.classList.contains('is-open'); }

      function openDrawer() {
        drawer.classList.add('is-open');
        drawer.setAttribute('aria-hidden', 'false');
        overlay.classList.add('is-open');
        document.body.style.overflow = 'hidden';
        loadCart();
        setTimeout(function() { drawer.querySelector('.rigo-drawer-close').focus(); }, 100);
      }

      function closeDrawer() {
        if (!isOpen()) return;
        drawer.classList.remove('is-open');
        drawer.setAttribute('aria-hidden', 'true');
        overlay.classList.remove('is-open');
        document.body.style.overflow = '';
        if (opener && document.contains(opener)) opener.focus();
        opener = null;
      }

      overlay.addEventListener('click', closeDrawer);
      drawer.querySelector('.rigo-drawer-close').addEventListener('click', closeDrawer);
      document.addEventListener('keydown', function(e) {
        if (!isOpen()) return;
        if (e.key === 'Escape') { closeDrawer(); return; }
        // Piège de focus : Tab boucle dans le tiroir
        if (e.key === 'Tab') {
          var items = Array.prototype.filter.call(
            drawer.querySelectorAll('a[href], button:not([disabled])'),
            function(el) { return el.offsetParent !== null; }
          );
          if (!items.length) return;
          var first = items[0], last = items[items.length - 1];
          if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
          else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
        }
      });

      async function loadCart() {
        itemsEl.innerHTML = '<div class="rigo-drawer-loading">Chargement…</div>';
        footerEl.style.display = 'none';
        try {
          var r = await fetch('/wp-json/wc/store/v1/cart?_=' + Date.now(), {credentials:'include', cache:'no-store'});
          var data = await r.json();
          renderCart(data);
        } catch(e) {
          itemsEl.innerHTML = '<div class="rigo-drawer-loading">Impossible de charger le panier.</div>';
        }
      }

      function renderCart(data) {
        var items = data.items || [];
        if (items.length === 0) {
          itemsEl.innerHTML = '<div class="rigo-drawer-empty">' +
            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 4h3l2.6 13.3a2 2 0 0 0 2 1.7h8.4a2 2 0 0 0 2-1.6L22 8H6"/><circle cx="10" cy="21" r="1.2"/><circle cx="18" cy="21" r="1.2"/></svg>' +
            '<p>Votre panier est vide.</p>' +
            '<a href="/shop/">Voir les jeux</a></div>';
          footerEl.style.display = 'none';
          return;
        }

        var html = '';
        items.forEach(function(item) {
          var img = (item.images && item.images[0]) ? '<img class="rigo-drawer-item-img" src="' + item.images[0].thumbnail + '" alt="' + item.name + '" loading="lazy">' : '<div class="rigo-drawer-item-img-placeholder">📦</div>';
          var price = item.totals && item.totals.line_total ? (parseInt(item.totals.line_total, 10) / 100).toFixed(2).replace('.', ',') + '&nbsp;€' : '';
          html += '<div class="rigo-drawer-item">' + img +
            '<div><div class="rigo-drawer-item-name">' + item.name + '</div><div class="rigo-drawer-item-qty">Qté&nbsp;: ' + item.quantity + '</div></div>' +
            '<div class="rigo-drawer-item-price">' + price + '</div></div>';
        });
        itemsEl.innerHTML = html;

        if (data.totals && data.totals.total_price) {
          var total = (parseInt(data.totals.total_price, 10) / 100).toFixed(2).replace('.', ',') + '&nbsp;€';
          totalEl.innerHTML = total;
          footerEl.style.display = 'block';
        }
      }

      // Ouverture : uniquement l'icône panier de l'en-tête (ou un élément marqué
      // data-rigo-cart-open). L'ancien sélecteur attrapait tout lien vers /cart/ et
      // tout aria-label contenant « panier » : « Retirer … du panier », « Retour au
      // panier », « Ajouter au panier »… ouvraient le tiroir à la place de leur action.
      // Sur le panier et la commande, l'icône mène simplement à la page panier.
      var onCartPages = document.body.classList.contains('woocommerce-cart')
                     || document.body.classList.contains('woocommerce-checkout');
      document.addEventListener('click', function(e) {
        if (onCartPages) return;
        var trigger = e.target.closest('.site-header a.cart, [data-rigo-cart-open]');
        if (!trigger) return;
        e.preventDefault();
        opener = trigger;
        openDrawer();
      });

      // Après un ajout au panier, c'est le tiroir de confirmation
      // (includes/upsell-drawer-post-add-to-cart.php) qui s'ouvre, pas celui-ci :
      // les deux s'ouvraient l'un sur l'autre.
    })();
    </script>
    <?php
}, 100);
