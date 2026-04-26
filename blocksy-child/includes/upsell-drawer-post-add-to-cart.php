<?php
/**
 * Migré depuis Code Snippet #29 : [Rigolettres] 18 — Upsell drawer post add-to-cart
 * Description : Drawer latéral après ajout panier : confirmation + suggestion cross-sell + CTA checkout
 */

if (!defined('ABSPATH')) exit;

/**
 * [Rigolettres] Upsell drawer — après ajout panier
 *
 * Quand un produit est ajouté au panier (via AJAX ou form), affiche
 * un mini-drawer latéral avec :
 * — Confirmation "✓ Pato le Chien ajouté !"
 * — Suggestion d'un produit complémentaire (cross-sell WC ou fallback liste)
 * — CTA "Finaliser ma commande" → /checkout/
 * — CTA "Continuer mes achats" → ferme le drawer
 *
 * S'appuie sur les cross-sells configurés dans WC (sinon liste fallback).
 * NE remplace PAS le side-cart (snippet 07), s'affiche en plus au-dessus.
 *
 * Scope : front-end
 * Priority : 55
 */

// ── 1. Données cross-sells injectées en JS (côté PHP) ─────────────────────
add_action('wp_footer', function () {
    if (!is_product() && !is_shop() && !is_product_category() && !is_front_page()) return;

    // Récupère les cross-sells de chaque produit pour les passer en JS
    $cross_sell_map = [];
    $products = wc_get_products(['status' => 'publish', 'limit' => 20]);
    foreach ($products as $product) {
        $ids = $product->get_cross_sell_ids();
        if (!empty($ids)) {
            $suggestions = [];
            foreach (array_slice($ids, 0, 1) as $id) {
                $p = wc_get_product($id);
                if ($p && $p->is_in_stock()) {
                    $img = wp_get_attachment_image_url($p->get_image_id(), 'thumbnail');
                    $suggestions[] = [
                        'id'    => $p->get_id(),
                        'name'  => $p->get_name(),
                        'price' => wc_price($p->get_price()),
                        'img'   => $img ?: wc_placeholder_img_src('thumbnail'),
                        'url'   => get_permalink($p->get_id()),
                        'atc'   => $p->add_to_cart_url(),
                    ];
                }
            }
            if (!empty($suggestions)) {
                $cross_sell_map[$product->get_id()] = $suggestions[0];
            }
        }
    }

    // Fallback global : dernier produit ajouté recommande le prochain dans la gamme
    $all_products = wc_get_products(['status' => 'publish', 'limit' => 10, 'orderby' => 'menu_order']);
    $fallback_suggestions = [];
    foreach ($all_products as $p) {
        $img = wp_get_attachment_image_url($p->get_image_id(), 'thumbnail');
        $fallback_suggestions[] = [
            'id'    => $p->get_id(),
            'name'  => $p->get_name(),
            'price' => wc_price($p->get_price()),
            'img'   => $img ?: wc_placeholder_img_src('thumbnail'),
            'url'   => get_permalink($p->get_id()),
            'atc'   => $p->add_to_cart_url(),
        ];
    }

    ?>
    <script id="rigo-upsell-data">
    window.rigoUpsellData = {
        crossSells: <?php echo wp_json_encode($cross_sell_map); ?>,
        allProducts: <?php echo wp_json_encode($fallback_suggestions); ?>,
        checkoutUrl: <?php echo wp_json_encode(wc_get_checkout_url()); ?>,
        cartUrl: <?php echo wp_json_encode(wc_get_cart_url()); ?>
    };
    </script>
    <?php
}, 55);

// ── 2. HTML + CSS + JS du drawer ──────────────────────────────────────────
add_action('wp_footer', function () {
    ?>
    <!-- Upsell drawer -->
    <div id="rigo-upsell-overlay" aria-hidden="true"></div>
    <div id="rigo-upsell-drawer" role="dialog" aria-modal="true" aria-label="Produit ajouté" aria-hidden="true">

        <!-- Header -->
        <div class="rigo-ud-header">
            <span class="rigo-ud-check">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
            </span>
            <span class="rigo-ud-added-label">Ajouté au panier !</span>
            <button class="rigo-ud-close" aria-label="Fermer">&#10005;</button>
        </div>

        <!-- Produit ajouté -->
        <div class="rigo-ud-added-product">
            <img id="rigo-ud-product-img" src="" alt="" width="64" height="64"/>
            <div>
                <p id="rigo-ud-product-name" class="rigo-ud-pname"></p>
                <p id="rigo-ud-product-price" class="rigo-ud-pprice"></p>
            </div>
        </div>

        <!-- Suggestion -->
        <div id="rigo-ud-suggest" class="rigo-ud-suggest" style="display:none">
            <p class="rigo-ud-suggest-label">✨ Les familles ajoutent aussi…</p>
            <div class="rigo-ud-suggest-product">
                <img id="rigo-ud-suggest-img" src="" alt="" width="60" height="60"/>
                <div class="rigo-ud-suggest-info">
                    <p id="rigo-ud-suggest-name" class="rigo-ud-pname"></p>
                    <p id="rigo-ud-suggest-price" class="rigo-ud-pprice"></p>
                </div>
                <button id="rigo-ud-suggest-atc" class="rigo-ud-suggest-btn" type="button">
                    + Ajouter
                </button>
            </div>
        </div>

        <!-- CTAs -->
        <div class="rigo-ud-actions">
            <a id="rigo-ud-checkout" href="/checkout/" class="rigo-ud-btn-primary">
                Finaliser ma commande →
            </a>
            <button class="rigo-ud-btn-secondary rigo-ud-close">
                Continuer mes achats
            </button>
        </div>

    </div>

    <style id="rigo-upsell-css">
    #rigo-upsell-overlay {
        display: none;
        position: fixed; inset: 0;
        background: rgba(31,41,55,.35);
        z-index: 9998;
        opacity: 0;
        transition: opacity 280ms ease;
        backdrop-filter: blur(2px);
    }
    #rigo-upsell-overlay.rigo-ud-open { display: block; opacity: 1; }

    #rigo-upsell-drawer {
        position: fixed;
        top: 0; right: 0;
        width: min(420px, 100vw);
        height: 100dvh;
        background: #fff;
        z-index: 9999;
        transform: translateX(100%);
        transition: transform 320ms cubic-bezier(.4,0,.2,1);
        display: flex;
        flex-direction: column;
        box-shadow: -8px 0 40px rgba(31,41,55,.14);
        border-left: 1.5px solid #E7E2D5;
        overflow-y: auto;
        font-family: "Nunito", sans-serif;
    }
    #rigo-upsell-drawer.rigo-ud-open { transform: translateX(0); }

    /* Header */
    .rigo-ud-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 18px 20px;
        border-bottom: 1.5px solid #E7E2D5;
        background: #F0FBE6;
    }
    .rigo-ud-check {
        width: 28px; height: 28px;
        background: #68a033;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .rigo-ud-check svg { width: 14px; height: 14px; stroke: #fff; }
    .rigo-ud-added-label {
        flex: 1;
        font-weight: 800;
        font-size: 15px;
        color: #2D5A1B;
    }
    .rigo-ud-close {
        background: none; border: none; cursor: pointer;
        font-size: 18px; color: #9CA3AF;
        padding: 4px 6px;
        border-radius: 6px;
        transition: color 200ms, background 200ms;
        line-height: 1;
    }
    .rigo-ud-close:hover { color: #1F2937; background: #F3F4F6; }

    /* Produit ajouté */
    .rigo-ud-added-product {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 20px;
        border-bottom: 1.5px solid #E7E2D5;
    }
    .rigo-ud-added-product img {
        width: 64px; height: 64px;
        object-fit: cover;
        border-radius: 10px;
        border: 1.5px solid #E7E2D5;
        flex-shrink: 0;
    }
    .rigo-ud-pname {
        font-weight: 700;
        font-size: 14.5px;
        color: #1F2937;
        margin: 0 0 4px;
        line-height: 1.4;
    }
    .rigo-ud-pprice {
        font-weight: 800;
        font-size: 15px;
        color: #68a033;
        margin: 0;
    }
    .rigo-ud-pprice del { color: #9CA3AF; font-weight: 400; font-size: 13px; margin-right: 4px; }

    /* Suggestion */
    .rigo-ud-suggest {
        padding: 18px 20px;
        border-bottom: 1.5px solid #E7E2D5;
        background: #FBF8F1;
    }
    .rigo-ud-suggest-label {
        font-size: 12.5px;
        font-weight: 800;
        color: #9CA3AF;
        text-transform: uppercase;
        letter-spacing: .06em;
        margin: 0 0 12px;
    }
    .rigo-ud-suggest-product {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #fff;
        border: 1.5px solid #E7E2D5;
        border-radius: 12px;
        padding: 12px;
    }
    .rigo-ud-suggest-product img {
        width: 56px; height: 56px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #E7E2D5;
        flex-shrink: 0;
    }
    .rigo-ud-suggest-info { flex: 1; min-width: 0; }
    .rigo-ud-suggest-btn {
        flex-shrink: 0;
        font-family: "Nunito", sans-serif;
        font-weight: 800;
        font-size: 13px;
        background: #68a033;
        color: #fff;
        border: none;
        border-radius: 9999px;
        padding: 8px 14px;
        cursor: pointer;
        transition: background 200ms;
        white-space: nowrap;
    }
    .rigo-ud-suggest-btn:hover { background: #5a8c2b; }
    .rigo-ud-suggest-btn.loading { opacity: .7; pointer-events: none; }

    /* CTAs */
    .rigo-ud-actions {
        margin-top: auto;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .rigo-ud-btn-primary {
        display: block;
        text-align: center;
        font-family: "Nunito", sans-serif;
        font-weight: 800;
        font-size: 15px;
        background: #68a033;
        color: #fff;
        border: none;
        border-radius: 9999px;
        padding: 14px 24px;
        text-decoration: none;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(104,160,51,.28);
        transition: background 200ms;
    }
    .rigo-ud-btn-primary:hover { background: #5a8c2b; color: #fff; }
    .rigo-ud-btn-secondary {
        display: block;
        width: 100%;
        font-family: "Nunito", sans-serif;
        font-weight: 700;
        font-size: 14px;
        background: transparent;
        color: #4B5563;
        border: 1.5px solid #E7E2D5;
        border-radius: 9999px;
        padding: 12px 24px;
        cursor: pointer;
        transition: background 200ms, color 200ms;
    }
    .rigo-ud-btn-secondary:hover { background: #F3F4F6; color: #1F2937; }

    @media (max-width: 480px) {
        #rigo-upsell-drawer { width: 100vw; }
    }
    </style>

    <script id="rigo-upsell-js">
    (function () {
        var data     = window.rigoUpsellData || {};
        var drawer   = document.getElementById('rigo-upsell-drawer');
        var overlay  = document.getElementById('rigo-upsell-overlay');
        if (!drawer || !overlay) return;

        var elImg     = document.getElementById('rigo-ud-product-img');
        var elName    = document.getElementById('rigo-ud-product-name');
        var elPrice   = document.getElementById('rigo-ud-product-price');
        var elSuggest = document.getElementById('rigo-ud-suggest');
        var elSImg    = document.getElementById('rigo-ud-suggest-img');
        var elSName   = document.getElementById('rigo-ud-suggest-name');
        var elSPrice  = document.getElementById('rigo-ud-suggest-price');
        var elSBtn    = document.getElementById('rigo-ud-suggest-atc');

        var currentSuggestId   = null;
        var currentSuggestAtc  = null;

        // ── Ouvrir le drawer ──────────────────────────────────────────────
        function openDrawer(productId) {
            // Infos produit ajouté (via WC store API)
            fetch('/wp-json/wc/store/v1/cart', { credentials: 'include', cache: 'no-store' })
                .then(function (r) { return r.json(); })
                .then(function (cart) {
                    // Cherche le produit dans le panier
                    var item = (cart.items || []).find(function (i) {
                        return String(i.id) === String(productId);
                    }) || (cart.items || [])[0];

                    if (item) {
                        elImg.src = (item.images && item.images[0] && item.images[0].thumbnail) || '';
                        elImg.alt = item.name || '';
                        elName.textContent = item.name || '';
                        elPrice.innerHTML  = item.prices ? formatPrice(item.prices.price) : '';
                    }

                    // Suggestion cross-sell
                    var suggest = (data.crossSells || {})[productId];
                    if (!suggest && data.allProducts) {
                        // Fallback : un produit différent de celui ajouté
                        suggest = data.allProducts.find(function (p) {
                            return String(p.id) !== String(productId);
                        });
                    }

                    if (suggest) {
                        elSImg.src    = suggest.img || '';
                        elSImg.alt    = suggest.name || '';
                        elSName.textContent = suggest.name || '';
                        elSPrice.innerHTML  = suggest.price || '';
                        currentSuggestId   = suggest.id;
                        currentSuggestAtc  = suggest.atc;
                        elSuggest.style.display = '';
                    } else {
                        elSuggest.style.display = 'none';
                    }
                })
                .catch(function () {});

            drawer.setAttribute('aria-hidden', 'false');
            overlay.setAttribute('aria-hidden', 'false');
            drawer.classList.add('rigo-ud-open');
            overlay.classList.add('rigo-ud-open');
            document.body.style.overflow = 'hidden';
        }

        // ── Fermer ────────────────────────────────────────────────────────
        function closeDrawer() {
            drawer.classList.remove('rigo-ud-open');
            overlay.classList.remove('rigo-ud-open');
            setTimeout(function () {
                overlay.style.display = '';
                drawer.setAttribute('aria-hidden', 'true');
                overlay.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
                overlay.style.display = '';
            }, 320);
        }

        overlay.addEventListener('click', closeDrawer);
        drawer.querySelectorAll('.rigo-ud-close').forEach(function (btn) {
            btn.addEventListener('click', closeDrawer);
        });

        // ── Ajouter suggestion au panier ──────────────────────────────────
        if (elSBtn) {
            elSBtn.addEventListener('click', function () {
                if (!currentSuggestId) return;
                elSBtn.classList.add('loading');
                elSBtn.textContent = '…';

                fetch('/wp-json/wc/store/v1/cart/add-item', {
                    method: 'POST',
                    credentials: 'include',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: currentSuggestId, quantity: 1 })
                })
                .then(function (r) { return r.json(); })
                .then(function () {
                    elSBtn.textContent = '✓ Ajouté !';
                    elSBtn.style.background = '#27B4E5';
                    setTimeout(function () {
                        elSBtn.classList.remove('loading');
                        window.location.href = data.checkoutUrl || '/checkout/';
                    }, 800);
                })
                .catch(function () {
                    elSBtn.classList.remove('loading');
                    elSBtn.textContent = '+ Ajouter';
                });
            });
        }

        // ── Intercepter add-to-cart WooCommerce ──────────────────────────
        // Méthode 1 : WC fragments (event jQuery)
        if (typeof jQuery !== 'undefined') {
            jQuery(document).on('added_to_cart', function (e, fragments, cart_hash, $button) {
                var pid = $button ? $button.data('product_id') : null;
                // Délai léger pour laisser le side-cart se fermer s'il s'ouvre
                setTimeout(function () { openDrawer(pid); }, 150);
            });
        }

        // Méthode 2 : patch fetch natif (WC Blocks)
        var _origFetch = window.fetch;
        window.fetch = function () {
            var url = String(arguments[0] || '');
            var p   = _origFetch.apply(this, arguments);
            if (url.includes('/cart/add-item') || url.includes('wc-ajax=add_to_cart')) {
                p.then(function (response) {
                    if (response.ok) {
                        var body = arguments[1] || {};
                        try {
                            var bodyStr = typeof body === 'string' ? body : JSON.stringify(body);
                            var parsed  = JSON.parse(bodyStr);
                            setTimeout(function () { openDrawer(parsed.id || null); }, 200);
                        } catch (err) {
                            setTimeout(function () { openDrawer(null); }, 200);
                        }
                    }
                }).catch(function () {});
            }
            return p;
        };

        // Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeDrawer();
        });

        // ── Helper prix ───────────────────────────────────────────────────
        function formatPrice(cents) {
            var val = (parseInt(cents, 10) / 100).toFixed(2).replace('.', ',');
            return val + '&nbsp;€';
        }
    })();
    </script>
    <?php
}, 55);
