<?php
/**
 * [Rigolettres] Trust badges + Free shipping bar
 *
 * 1. Trust badges sur fiches produit (sous le bouton ATC)
 * 2. Free shipping progress bar dans le mini-cart et la page panier
 *
 * Source : audit.md Phase C + Sprint 1
 * Scope : front-end
 */

// ── 1. Trust badges sur fiche produit ─────────────────────────────────────
add_action('woocommerce_after_add_to_cart_button', function () {
    ?>
    <div class="rigo-trust-badges">
      <div class="rigo-badge">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        <span>Paiement sécurisé</span>
      </div>
      <div class="rigo-badge">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        <span>Expédié sous 48 h</span>
      </div>
      <div class="rigo-badge">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        <span>Fabriqué en France</span>
      </div>
      <div class="rigo-badge">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
        <span>Créé par une orthophoniste</span>
      </div>
    </div>
    <style>
    .rigo-trust-badges {
      display: flex; flex-wrap: wrap; gap: 10px 18px;
      margin: 18px 0 8px;
      padding: 14px 16px;
      background: #F7F4ED;
      border-radius: 14px;
      border: 1px solid #E7E2D5;
    }
    .rigo-badge {
      display: flex; align-items: center; gap: 7px;
      font-size: 12.5px; font-weight: 700;
      color: #4B5563;
    }
    .rigo-badge svg { width: 16px; height: 16px; flex-shrink: 0; stroke: #8BC84B; }
    </style>
    <?php
}, 25);

// ── 2. Free shipping progress bar ─────────────────────────────────────────
add_action('wp_footer', function () {
    if (!is_cart() && !is_checkout() && !is_product()) return;
    $threshold = 60;
    ?>
    <div id="rigo-ship-bar-wrap" style="display:none">
      <div id="rigo-ship-bar-msg"></div>
      <div class="rigo-ship-track"><div class="rigo-ship-fill" id="rigo-ship-fill"></div></div>
    </div>
    <style>
    #rigo-ship-bar-wrap {
      background: #EEF7DE; border-bottom: 1px solid #C8E69A;
      padding: 9px 20px; text-align: center;
      position: sticky; top: 0; z-index: 200;
    }
    #rigo-ship-bar-msg { font-size: 13px; font-weight: 700; color: #3a2913; margin-bottom: 6px; }
    .rigo-ship-track {
      max-width: 280px; height: 6px; border-radius: 99px;
      background: #C8E69A; margin: 0 auto; overflow: hidden;
    }
    .rigo-ship-fill { height: 100%; background: #8BC84B; border-radius: 99px; transition: width .5s ease; }
    </style>
    <script>
    (function() {
      var wrap = document.getElementById('rigo-ship-bar-wrap');
      var msg  = document.getElementById('rigo-ship-bar-msg');
      var fill = document.getElementById('rigo-ship-fill');
      if (!wrap) return;
      var threshold = <?php echo $threshold; ?>;

      async function updateBar() {
        try {
          var r = await fetch('/wp-json/wc/store/v1/cart', {credentials:'include', cache:'no-store'});
          var data = await r.json();
          var total = data.totals ? parseInt(data.totals.total_items, 10) / 100 : 0;
          var items = data.items_count || 0;
          if (items === 0) { wrap.style.display = 'none'; return; }
          wrap.style.display = 'block';
          var pct = Math.min(100, Math.round((total / threshold) * 100));
          fill.style.width = pct + '%';
          if (total >= threshold) {
            msg.innerHTML = '🎉 <strong>Livraison offerte !</strong> Profitez-en.';
          } else {
            var missing = (threshold - total).toFixed(2).replace('.', ',');
            msg.innerHTML = 'Plus que <strong>' + missing + '&nbsp;€</strong> pour la livraison offerte !';
          }
        } catch(e) { wrap.style.display = 'none'; }
      }

      updateBar();
      // Re-check after add-to-cart
      var origFetch = window.fetch;
      window.fetch = function() {
        var url = String(arguments[0] || '');
        var p = origFetch.apply(this, arguments);
        if (url.includes('cart/add-item') || url.includes('cart/remove-item')) {
          p.then(function(r) { if (r.ok) setTimeout(updateBar, 600); });
        }
        return p;
      };
    })();
    </script>
    <?php
}, 20);
