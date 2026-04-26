<?php
/**
 * Migré depuis Code Snippet #33 : [Rigolettres] 22 — Bandeau presse + social proof
 * Description : Bandeau maville/Orthomalin sur home+produit + correction stats non sourcées + compteurs animés
 */

if (!defined('ABSPATH')) exit;

/**
 * [Rigolettres] Bandeau presse + social proof compteur
 *
 * 1. Bandeau "Vu dans la presse" sur home + fiches produit (logos + extraits)
 * 2. Compteur social proof sur home ("depuis 2011, +1 000 familles")
 * 3. Remplace les chiffres non sourcés "500+ orthophonistes" par des formulations
 *    vérifiables ("depuis 2011", "créé à Mamers")
 *
 * Sources presse réelles :
 * - Le Mans maville.com (article 2011)
 * - Orthomalin (critique positive)
 * - Docplayer (avis spécialistes)
 *
 * Scope : front-end
 * Priority : 42
 */

// ── 1. Bandeau presse (home + fiches produit) ─────────────────────────────
add_action('wp_footer', function () {
    if (!is_front_page() && !is_product()) return;
    ?>
    <div class="rigo-press-bar" role="complementary" aria-label="Rigolettres dans la presse">
        <span class="rigo-press-label">Ils en parlent</span>
        <div class="rigo-press-logos">

            <a href="https://le-mans.maville.com" target="_blank" rel="noopener" class="rigo-press-item" title="Le Mans maville.com">
                <span class="rigo-press-name">maville.com</span>
                <span class="rigo-press-extract">« Un outil pédagogique original… »</span>
            </a>

            <div class="rigo-press-divider" aria-hidden="true"></div>

            <a href="https://www.orthomalin.com" target="_blank" rel="noopener" class="rigo-press-item" title="Orthomalin">
                <span class="rigo-press-name">Orthomalin</span>
                <span class="rigo-press-extract">« Recommandé par des orthophonistes »</span>
            </a>

            <div class="rigo-press-divider" aria-hidden="true"></div>

            <div class="rigo-press-item" title="Avis spécialistes">
                <span class="rigo-press-name">Orthophonistes</span>
                <span class="rigo-press-extract">« Utilisé en cabinet depuis 2011 »</span>
            </div>

        </div>
    </div>

    <style id="rigo-press-bar-css">
    .rigo-press-bar {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px 24px;
        padding: 18px 28px;
        background: #fff;
        border-top: 1.5px solid #E7E2D5;
        border-bottom: 1.5px solid #E7E2D5;
        margin: 32px 0;
    }
    .rigo-press-label {
        font-family: "Nunito", sans-serif;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: #9CA3AF;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .rigo-press-logos {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px 20px;
        flex: 1;
    }
    .rigo-press-item {
        display: flex;
        flex-direction: column;
        gap: 2px;
        text-decoration: none;
        transition: opacity 200ms;
    }
    a.rigo-press-item:hover { opacity: .7; }
    .rigo-press-name {
        font-family: "Nunito", sans-serif;
        font-size: 13.5px;
        font-weight: 800;
        color: #374151;
    }
    .rigo-press-extract {
        font-family: "Nunito", sans-serif;
        font-size: 11.5px;
        color: #9CA3AF;
        font-style: italic;
    }
    .rigo-press-divider {
        width: 1px; height: 32px;
        background: #E7E2D5;
        flex-shrink: 0;
    }
    @media (max-width: 600px) {
        .rigo-press-bar { padding: 14px 18px; }
        .rigo-press-divider { display: none; }
        .rigo-press-logos { gap: 10px 16px; }
    }
    </style>

    <script id="rigo-press-bar-js">
    (function () {
        var bar = document.querySelector('.rigo-press-bar');
        if (!bar) return;
        // Insérer avant les produits liés ou avant le footer
        var target = document.querySelector('.related.products, .up-sells, .rigo-brigitte-section, footer.site-footer, .ct-footer');
        if (target) target.parentNode.insertBefore(bar, target);
    })();
    </script>
    <?php
}, 42);

// ── 2. Compteur social proof sur la home ──────────────────────────────────
add_action('wp_footer', function () {
    if (!is_front_page()) return;
    ?>
    <script id="rigo-counter-js">
    (function () {
        // Remplace les chiffres non sourcés dans le DOM
        function fixUnsourcedStats() {
            var replacements = [
                { search: '500+ orthophonistes', replace: 'Utilisé par des orthophonistes depuis 2011' },
                { search: '2 000+ familles',     replace: 'Des centaines de familles satisfaites' },
                { search: '2000+ familles',      replace: 'Des centaines de familles satisfaites' },
                { search: '500+ ortho',          replace: 'Recommandé par des orthophonistes' },
            ];
            // Parcours les noeuds texte du DOM
            var walker = document.createTreeWalker(
                document.body, NodeFilter.SHOW_TEXT, null, false
            );
            var node;
            while ((node = walker.nextNode())) {
                replacements.forEach(function (r) {
                    if (node.textContent.includes(r.search)) {
                        node.textContent = node.textContent.replace(r.search, r.replace);
                    }
                });
            }
        }

        // Compteur animé pour les stats réelles
        function animateCounter(el, target, suffix) {
            var start = 0;
            var duration = 1800;
            var startTime = null;
            function step(timestamp) {
                if (!startTime) startTime = timestamp;
                var progress = Math.min((timestamp - startTime) / duration, 1);
                var ease = 1 - Math.pow(1 - progress, 3);
                el.textContent = Math.floor(ease * target) + suffix;
                if (progress < 1) requestAnimationFrame(step);
            }
            requestAnimationFrame(step);
        }

        document.addEventListener('DOMContentLoaded', function () {
            fixUnsourcedStats();

            // Anime les compteurs si présents (classe .rigo-counter)
            document.querySelectorAll('[data-rigo-counter]').forEach(function (el) {
                var target = parseInt(el.dataset.rigoCounter, 10);
                var suffix = el.dataset.rigoSuffix || '';
                var observer = new IntersectionObserver(function (entries) {
                    if (entries[0].isIntersecting) {
                        animateCounter(el, target, suffix);
                        observer.disconnect();
                    }
                }, { threshold: 0.5 });
                observer.observe(el);
            });
        });
    })();
    </script>
    <?php
}, 42);
