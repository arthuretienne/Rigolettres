<?php
/**
 * [Rigolettres] Footer trust strip — 5 piliers de réassurance
 *
 * Bandeau inséré juste avant le footer : Fabriqué FR / Paiement / Retours /
 * Orthophoniste / Livraison offerte.
 * Utilise JS pour s'insérer avant l'élément <footer> existant.
 *
 * Scope : front-end
 * Priority : 40
 */

add_action('wp_footer', function () {
    ?>
    <section class="rigo-trust-strip" aria-label="Engagements Rigolettres" style="display:none">
        <div class="rigo-trust-strip-inner">

            <div class="rigo-trust-item">
                <div class="rigo-trust-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="1" y="4" width="22" height="16" rx="2"/>
                        <line x1="1" y1="10" x2="23" y2="10"/>
                    </svg>
                </div>
                <div class="rigo-trust-text">
                    <strong>Fabriqué en France 🇫🇷</strong>
                    <span>Imprimé à Mamers, Sarthe</span>
                </div>
            </div>

            <div class="rigo-trust-item">
                <div class="rigo-trust-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                </div>
                <div class="rigo-trust-text">
                    <strong>Paiement sécurisé</strong>
                    <span>CB · PayPal · Virement</span>
                </div>
            </div>

            <div class="rigo-trust-item">
                <div class="rigo-trust-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.21"/>
                    </svg>
                </div>
                <div class="rigo-trust-text">
                    <strong>Retours 30 jours</strong>
                    <span>Satisfait ou remboursé</span>
                </div>
            </div>

            <div class="rigo-trust-item">
                <div class="rigo-trust-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                </div>
                <div class="rigo-trust-text">
                    <strong>Créé par une orthophoniste</strong>
                    <span>25 ans d'expertise · méthode syllabique</span>
                </div>
            </div>

            <div class="rigo-trust-item">
                <div class="rigo-trust-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 5v3h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                    </svg>
                </div>
                <div class="rigo-trust-text">
                    <strong>Livraison offerte dès 60 €</strong>
                    <span>Colissimo &amp; Mondial Relay</span>
                </div>
            </div>

        </div>
    </section>

    <style id="rigo-trust-strip-css">
    .rigo-trust-strip {
        background: #F7F4ED;
        border-top: 1px solid #E7E2D5;
        border-bottom: 1px solid #E7E2D5;
        padding: 28px 20px;
    }
    .rigo-trust-strip-inner {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px 40px;
    }
    .rigo-trust-item {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 180px;
        flex: 1 1 180px;
        max-width: 240px;
    }
    .rigo-trust-icon {
        flex-shrink: 0;
        width: 42px; height: 42px;
        background: #fff;
        border: 1.5px solid #E7E2D5;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
    }
    .rigo-trust-icon svg {
        width: 20px; height: 20px;
        stroke: #68a033;
    }
    .rigo-trust-text {
        display: flex; flex-direction: column; gap: 2px;
    }
    .rigo-trust-text strong {
        font-family: Nunito, sans-serif;
        font-size: 13.5px;
        font-weight: 800;
        color: #3a2913;
        line-height: 1.3;
    }
    .rigo-trust-text span {
        font-family: Nunito, sans-serif;
        font-size: 12px;
        color: #6B7280;
        line-height: 1.4;
    }
    @media (max-width: 640px) {
        .rigo-trust-strip-inner { gap: 16px 24px; }
        .rigo-trust-item { min-width: 140px; max-width: none; flex-basis: calc(50% - 12px); }
    }
    </style>

    <script id="rigo-trust-strip-js">
    (function () {
        var strip = document.querySelector('.rigo-trust-strip');
        if (!strip) return;
        strip.style.display = '';
        var footer = document.querySelector('footer.site-footer, footer#colophon, footer[class*="footer"], .ct-footer, #footer');
        if (footer) {
            footer.parentNode.insertBefore(strip, footer);
        }
    })();
    </script>
    <?php
}, 40);
