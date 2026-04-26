<?php
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
        <div class="rigo-drawer-shipping-note">Livraison offerte dès 60 €</div>
        <a href="/checkout/" class="rigo-drawer-checkout-btn">Commander →</a>
        <a href="/cart/" class="rigo-drawer-cart-link">Voir le panier complet</a>
      </div>
    </aside>

    <style>
    #rigo-cart-overlay {
      position: fixed; inset: 0; z-index: 9998;
      background: rgba(31,41,55,.45);
      backdrop-filter: blur(2px);
      opacity: 0; pointer-events: none;
      transition: opacity .32s ease;
    }
    #rigo-cart-overlay.is-open { opacity: 1; pointer-events: all; }

    #rigo-cart-drawer {
      position: fixed; top: 0; right: 0; bottom: 0;
      width: min(400px, 92vw);
      background: #FBF8F1;
      z-index: 9999;
      display: flex; flex-direction: column;
      transform: translateX(100%);
      transition: transform .35s cubic-bezier(.22,.61,.36,1);
      box-shadow: -8px 0 48px rgba(31,41,55,.15);
      border-left: 2px solid #E7E2D5;
    }
    #rigo-cart-drawer.is-open { transform: translateX(0); }
    #rigo-cart-drawer[aria-hidden="true"] { visibility: hidden; }
    #rigo-cart-drawer.is-open[aria-hidden="false"] { visibility: visible; }

    .rigo-drawer-head {
      display: flex; align-items: center; justify-content: space-between;
      padding: 20px 20px 16px;
      border-bottom: 1px solid #E7E2D5;
      background: #fff;
      flex-shrink: 0;
    }
    .rigo-drawer-title {
      font-family: "Kalam", cursive;
      font-size: 22px; font-weight: 700;
      color: #1F2937; margin: 0;
    }
    .rigo-drawer-close {
      width: 36px; height: 36px;
      display: grid; place-items: center;
      border-radius: 50%; border: 0;
      background: transparent; cursor: pointer;
      color: #4B5563;
      transition: background .18s;
    }
    .rigo-drawer-close:hover { background: #F7EFDD; }
    .rigo-drawer-close svg { width: 18px; height: 18px; }

    .rigo-drawer-items { flex: 1; overflow-y: auto; padding: 16px 20px; }
    .rigo-drawer-loading { color: #9CA3AF; font-size: 14px; text-align: center; padding: 32px 0; }

    .rigo-drawer-item {
      display: grid;
      grid-template-columns: 60px 1fr auto;
      gap: 12px; align-items: start;
      padding: 14px 0;
      border-bottom: 1px solid #E7E2D5;
    }
    .rigo-drawer-item:last-child { border-bottom: 0; }
    .rigo-drawer-item-img {
      width: 60px; height: 60px; border-radius: 10px;
      object-fit: cover; background: #F7EFDD;
    }
    .rigo-drawer-item-img-placeholder {
      width: 60px; height: 60px; border-radius: 10px;
      background: #E7E2D5; display: grid; place-items: center;
      font-size: 22px;
    }
    .rigo-drawer-item-name {
      font-weight: 700; font-size: 14px; color: #1F2937; line-height: 1.4;
      margin-bottom: 4px;
    }
    .rigo-drawer-item-qty { font-size: 13px; color: #9CA3AF; }
    .rigo-drawer-item-price { font-weight: 800; font-size: 15px; color: #27B4E5; }

    .rigo-drawer-empty {
      text-align: center; padding: 48px 20px;
      color: #9CA3AF;
    }
    .rigo-drawer-empty svg { width: 48px; height: 48px; margin-bottom: 12px; opacity: .4; }
    .rigo-drawer-empty p { font-size: 15px; margin: 0 0 16px; }
    .rigo-drawer-empty a {
      display: inline-block; padding: 10px 20px;
      background: #27B4E5; color: #fff; border-radius: 9999px;
      font-weight: 800; font-size: 14px;
      text-decoration: none;
    }

    .rigo-drawer-footer {
      padding: 16px 20px 24px;
      border-top: 2px solid #E7E2D5;
      background: #fff;
      flex-shrink: 0;
    }
    .rigo-drawer-subtotal {
      display: flex; justify-content: space-between; align-items: baseline;
      font-weight: 800; font-size: 16px; color: #1F2937;
      margin-bottom: 4px;
    }
    #rigo-drawer-total { color: #27B4E5; }
    .rigo-drawer-shipping-note {
      font-size: 12px; color: #8BC84B; font-weight: 700;
      margin-bottom: 14px;
    }
    .rigo-drawer-checkout-btn {
      display: block; text-align: center;
      background: #27B4E5; color: #fff;
      font-family: "Nunito", sans-serif;
      font-weight: 800; font-size: 16px;
      padding: 14px; border-radius: 9999px;
      text-decoration: none; margin-bottom: 10px;
      box-shadow: 0 6px 16px rgba(39,180,229,.4);
      transition: background .2s, transform .15s;
    }
    .rigo-drawer-checkout-btn:hover { background: #1E92BC; transform: translateY(-1px); }
    .rigo-drawer-cart-link {
      display: block; text-align: center;
      font-size: 13px; color: #4B5563;
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

      function openDrawer() {
        drawer.classList.add('is-open');
        drawer.setAttribute('aria-hidden', 'false');
        overlay.classList.add('is-open');
        document.body.style.overflow = 'hidden';
        loadCart();
        setTimeout(function() { drawer.querySelector('.rigo-drawer-close').focus(); }, 100);
      }

      function closeDrawer() {
        drawer.classList.remove('is-open');
        drawer.setAttribute('aria-hidden', 'true');
        overlay.classList.remove('is-open');
        document.body.style.overflow = '';
      }

      overlay.addEventListener('click', closeDrawer);
      drawer.querySelector('.rigo-drawer-close').addEventListener('click', closeDrawer);
      document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeDrawer(); });

      async function loadCart() {
        itemsEl.innerHTML = '<div class="rigo-drawer-loading">Chargement…</div>';
        footerEl.style.display = 'none';
        try {
          var r = await fetch('/wp-json/wc/store/v1/cart', {credentials:'include', cache:'no-store'});
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

      // Open on header cart icon click
      document.addEventListener('click', function(e) {
        var cartLink = e.target.closest('a[href*="/cart/"], a[href*="/panier/"], .cart, [aria-label*="panier" i], [aria-label*="Panier"]');
        if (cartLink && !cartLink.closest('#rigo-cart-drawer')) {
          e.preventDefault();
          openDrawer();
        }
      });

      // Open after successful add-to-cart
      document.addEventListener('wc-blocks-cart-added-item', openDrawer);

      // Hook into the custom add-to-cart events from sticky bar and home cards
      var origFetch = window.fetch;
      window.fetch = function() {
        var url = arguments[0];
        var promise = origFetch.apply(this, arguments);
        if (typeof url === 'string' && url.includes('cart/add-item')) {
          promise.then(function(res) {
            if (res.ok) {
              // Small delay to let cart count update first
              setTimeout(openDrawer, 300);
            }
          });
        }
        return promise;
      };
    })();
    </script>
    <?php
}, 100);
