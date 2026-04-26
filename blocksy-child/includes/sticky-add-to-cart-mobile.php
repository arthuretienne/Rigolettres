<?php
/**
 * Migré depuis Code Snippet #15 : [Rigolettres] Sticky Add-to-Cart mobile
 * Description : Barre ATC fixe en bas sur mobile — fiches produit WooCommerce. Source: audit.md Phase D.1
 */

if (!defined('ABSPATH')) exit;

/**
 * [Rigolettres] Sticky Add-to-Cart mobile (fiche produit)
 *
 * Affiche une barre fixe en bas de l'écran sur les fiches produit WooCommerce
 * (mobile uniquement — masquée sur desktop). Apparaît quand l'ATC natif
 * sort du viewport, disparaît quand l'utilisateur arrive au footer.
 *
 * Source : audit.md Phase D.1
 * Déployé via Code Snippets (scope=front-end)
 */

// HTML sticky bar
add_action('wp_footer', function () {
    if (!is_product()) return;
    global $product;
    if (!$product || !$product->is_purchasable() || !$product->is_in_stock()) return;

    $name = esc_html($product->get_name());
    $price = wc_price($product->get_price());
    $product_id = $product->get_id();
    $nonce = wp_create_nonce('wc_store_api');
    ?>
    <div id="rigo-sticky-atc" role="region" aria-label="Ajouter au panier" style="display:none">
      <div class="rigo-sticky-inner">
        <div class="rigo-sticky-info">
          <span class="rigo-sticky-name"><?php echo $name; ?></span>
          <span class="rigo-sticky-price"><?php echo $price; ?></span>
        </div>
        <button class="rigo-sticky-btn" data-product-id="<?php echo $product_id; ?>" data-nonce="<?php echo $nonce; ?>" type="button">
          Ajouter au panier
        </button>
      </div>
    </div>

    <style>
    #rigo-sticky-atc {
      position: fixed; bottom: 0; left: 0; right: 0; z-index: 9999;
      background: #fff;
      border-top: 2px solid #E7E2D5;
      box-shadow: 0 -4px 20px rgba(31,41,55,.12);
      padding: 12px 16px 16px;
      transform: translateY(100%);
      transition: transform .32s cubic-bezier(.22,.61,.36,1);
      will-change: transform;
      /* Mobile only */
      display: none;
    }
    @media (max-width: 767px) {
      #rigo-sticky-atc { display: block; }
    }
    #rigo-sticky-atc.is-visible { transform: translateY(0); }
    .rigo-sticky-inner {
      display: flex; align-items: center; justify-content: space-between;
      gap: 12px; max-width: 600px; margin: 0 auto;
    }
    .rigo-sticky-info { display: flex; flex-direction: column; gap: 2px; min-width: 0; }
    .rigo-sticky-name {
      font-family: "Nunito", sans-serif;
      font-weight: 800; font-size: 14px;
      color: #1F2937;
      white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .rigo-sticky-price {
      font-family: "Nunito", sans-serif;
      font-weight: 700; font-size: 15px;
      color: #27B4E5;
    }
    .rigo-sticky-btn {
      flex-shrink: 0;
      background: #27B4E5; color: #fff;
      font-family: "Nunito", sans-serif;
      font-weight: 800; font-size: 14px;
      border: 0; border-radius: 9999px;
      padding: 12px 20px;
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(39,180,229,.4);
      transition: background .2s, transform .15s, box-shadow .2s;
      white-space: nowrap;
    }
    .rigo-sticky-btn:hover { background: #1E92BC; transform: translateY(-1px); box-shadow: 0 6px 16px rgba(39,180,229,.5); }
    .rigo-sticky-btn:active { transform: scale(.97); }
    .rigo-sticky-btn.is-loading { opacity: .7; pointer-events: none; }
    .rigo-sticky-btn.is-added { background: #8BC84B; }
    </style>

    <script>
    (function() {
      var bar = document.getElementById('rigo-sticky-atc');
      if (!bar) return;

      // Show/hide based on native ATC button visibility
      var nativeAtc = document.querySelector('.single_add_to_cart_button, .cart button[type="submit"]');
      var footer = document.querySelector('.site-footer, footer');
      var showing = false;

      function update() {
        if (!nativeAtc) return;
        var rect = nativeAtc.getBoundingClientRect();
        var footerRect = footer ? footer.getBoundingClientRect() : {top: 9999};
        var nativeVisible = rect.bottom > 0 && rect.top < window.innerHeight;
        var footerVisible = footerRect.top < window.innerHeight - 60;
        var shouldShow = !nativeVisible && !footerVisible;
        if (shouldShow !== showing) {
          showing = shouldShow;
          bar.classList.toggle('is-visible', showing);
        }
      }

      window.addEventListener('scroll', update, {passive: true});
      window.addEventListener('resize', update, {passive: true});
      setTimeout(update, 400);

      // Add to cart via WooCommerce Store API
      var btn = bar.querySelector('.rigo-sticky-btn');
      if (!btn) return;
      btn.addEventListener('click', async function() {
        if (btn.classList.contains('is-loading')) return;
        btn.classList.add('is-loading');
        btn.textContent = 'Ajout…';

        var pid = parseInt(btn.getAttribute('data-product-id'), 10);
        try {
          // Get nonce first
          var nonceRes = await fetch('/wp-json/wc/store/v1/cart', {credentials:'include', cache:'no-store'});
          var apiNonce = nonceRes.headers.get('Nonce') || nonceRes.headers.get('nonce') || nonceRes.headers.get('X-WC-Store-API-Nonce') || '';
          var headers = {'Content-Type':'application/json', 'Accept':'application/json'};
          if (apiNonce) headers['Nonce'] = apiNonce;

          var res = await fetch('/wp-json/wc/store/v1/cart/add-item', {
            method: 'POST', credentials: 'include', headers: headers,
            body: JSON.stringify({id: pid, quantity: 1})
          });
          if (!res.ok) throw new Error('HTTP ' + res.status);
          btn.classList.remove('is-loading');
          btn.classList.add('is-added');
          btn.textContent = 'Ajouté ✓';
          // Sync cart count on main page
          var cartCountEls = document.querySelectorAll('[data-cart-count]');
          var cartRes = await fetch('/wp-json/wc/store/v1/cart', {credentials:'include', cache:'no-store'});
          var cartData = await cartRes.json();
          var count = cartData.items_count || 0;
          cartCountEls.forEach(function(el) { el.textContent = String(count); });
          setTimeout(function() {
            btn.classList.remove('is-added');
            btn.textContent = 'Ajouter au panier';
          }, 2200);
        } catch(e) {
          console.error('[rigo sticky atc]', e);
          btn.classList.remove('is-loading');
          btn.textContent = 'Réessayer';
          setTimeout(function() { btn.textContent = 'Ajouter au panier'; }, 2000);
        }
      });
    })();
    </script>
    <?php
}, 99);
