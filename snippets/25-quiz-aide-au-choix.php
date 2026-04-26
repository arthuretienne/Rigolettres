<?php
/**
 * [Rigolettres] Quiz "Aide au choix" — différenciateur CRO
 *
 * Mini-quiz 3 questions → recommandation 1 match parfait + 2 alternatives.
 * Benchmarks : Typology, Oura Ring, Mejuri, Bonne Gueule (quiz morpho).
 *
 * UX :
 * - Floating CTA "Quel jeu pour mon enfant ?" (desktop bottom-left, mobile ajusté)
 * - Également déclenchable depuis n'importe quel lien href="#rigo-quiz" ou .rigo-quiz-trigger
 * - Modal plein écran avec progress bar 3 étapes
 * - Questions : Niveau scolaire / Défi principal / Contexte de jeu
 * - Résultat : hero match + 2 alternatives, CTA "Voir le jeu" + "Ajouter au panier"
 *
 * Logique de matching : poids par produit selon réponses.
 * Produits mappés par ID WC (à ajuster si nouveaux produits).
 *
 * Scope : front-end
 * Priority : 48 (après design system, avant thank-you)
 */

// ── Helper : récupère un résumé produit pour le quiz (cache transient) ────
function rigo_quiz_product_summary($product_id) {
    if (!function_exists('wc_get_product')) return null;
    $product = wc_get_product($product_id);
    if (!$product) return null;

    return [
        'id'     => $product_id,
        'name'   => $product->get_name(),
        'url'    => get_permalink($product_id),
        'price'  => wp_strip_all_tags($product->get_price_html()),
        'image'  => wp_get_attachment_image_url($product->get_image_id(), 'woocommerce_thumbnail')
                    ?: wc_placeholder_img_src('woocommerce_thumbnail'),
        'short'  => wp_strip_all_tags($product->get_short_description()),
        'stock'  => $product->is_in_stock(),
    ];
}

add_action('wp_footer', function () {
    // Exclure cart / checkout / admin / fiche produit (risque doublon avec ATC)
    if (is_admin() || is_cart() || is_checkout() || is_product()) return;

    // IDs produits (vérifiés via REST /wc/v3/products au 2026-04-22)
    $products = [
        'pato'          => rigo_quiz_product_summary(28), // Rigolettres N°1 Pato — CP
        'sons'          => rigo_quiz_product_summary(29), // Rigolettres N°2 Sons — CE1-CE2
        'rigoloverbes'  => rigo_quiz_product_summary(30), // Rigoloverbes — CM1-6e
        'grammaire1'    => rigo_quiz_product_summary(31), // Grammaire N1 — CE1-CM1
        'grammaire2'    => rigo_quiz_product_summary(32), // Grammaire N2 — CM1-6e
    ];
    $products = array_filter($products); // supprime les null

    if (count($products) < 3) return; // protection : quiz inutile si < 3 produits
    ?>

    <!-- ── Trigger flottant ───────────────────────────────────────────────── -->
    <button type="button" class="rigo-quiz-fab rigo-quiz-trigger" aria-label="Ouvrir l'aide au choix">
        <span class="rigo-quiz-fab-icon" aria-hidden="true">🎯</span>
        <span class="rigo-quiz-fab-label">Quel jeu pour mon enfant&nbsp;?</span>
    </button>

    <!-- ── Modal quiz ─────────────────────────────────────────────────────── -->
    <div class="rigo-quiz-modal" id="rigo-quiz" role="dialog" aria-modal="true" aria-labelledby="rigo-quiz-title" hidden>
        <div class="rigo-quiz-backdrop" data-rigo-quiz-close></div>

        <div class="rigo-quiz-box" role="document">
            <button type="button" class="rigo-quiz-close" data-rigo-quiz-close aria-label="Fermer le quiz">×</button>

            <!-- Progress bar -->
            <div class="rigo-quiz-progress" aria-hidden="true">
                <div class="rigo-quiz-progress-bar" id="rigo-quiz-progress-bar"></div>
            </div>

            <!-- Step 1 : Niveau scolaire -->
            <div class="rigo-quiz-step" data-step="1" id="rigo-quiz-step-1">
                <span class="rigo-quiz-step-num">Étape 1 / 3</span>
                <h2 id="rigo-quiz-title">En quelle classe est votre enfant&nbsp;?</h2>
                <p class="rigo-quiz-sub">Nous adaptons la recommandation à son niveau scolaire actuel.</p>
                <div class="rigo-quiz-options" role="radiogroup" aria-label="Niveau scolaire">
                    <button type="button" class="rigo-quiz-option" data-q="niveau" data-v="cp">
                        <span class="rigo-quiz-option-emoji">🐶</span>
                        <span class="rigo-quiz-option-title">CP</span>
                        <span class="rigo-quiz-option-meta">5-7 ans · débute la lecture</span>
                    </button>
                    <button type="button" class="rigo-quiz-option" data-q="niveau" data-v="ce1">
                        <span class="rigo-quiz-option-emoji">🐱</span>
                        <span class="rigo-quiz-option-title">CE1</span>
                        <span class="rigo-quiz-option-meta">6-8 ans · lecture en cours</span>
                    </button>
                    <button type="button" class="rigo-quiz-option" data-q="niveau" data-v="ce2">
                        <span class="rigo-quiz-option-emoji">📚</span>
                        <span class="rigo-quiz-option-title">CE2</span>
                        <span class="rigo-quiz-option-meta">7-9 ans · consolide</span>
                    </button>
                    <button type="button" class="rigo-quiz-option" data-q="niveau" data-v="cm1">
                        <span class="rigo-quiz-option-emoji">✏️</span>
                        <span class="rigo-quiz-option-title">CM1</span>
                        <span class="rigo-quiz-option-meta">9-10 ans · grammaire</span>
                    </button>
                    <button type="button" class="rigo-quiz-option" data-q="niveau" data-v="cm2">
                        <span class="rigo-quiz-option-emoji">🎓</span>
                        <span class="rigo-quiz-option-title">CM2 / 6ème</span>
                        <span class="rigo-quiz-option-meta">10-12 ans · approfondit</span>
                    </button>
                    <button type="button" class="rigo-quiz-option" data-q="niveau" data-v="autre">
                        <span class="rigo-quiz-option-emoji">🌱</span>
                        <span class="rigo-quiz-option-title">Autre</span>
                        <span class="rigo-quiz-option-meta">Adulte ou FLE</span>
                    </button>
                </div>
            </div>

            <!-- Step 2 : Défi principal -->
            <div class="rigo-quiz-step" data-step="2" id="rigo-quiz-step-2" hidden>
                <span class="rigo-quiz-step-num">Étape 2 / 3</span>
                <h2>Quel est son défi principal&nbsp;?</h2>
                <p class="rigo-quiz-sub">Le point sur lequel il ou elle bute le plus en ce moment.</p>
                <div class="rigo-quiz-options rigo-quiz-options-wide" role="radiogroup" aria-label="Défi principal">
                    <button type="button" class="rigo-quiz-option" data-q="defi" data-v="lecture">
                        <span class="rigo-quiz-option-emoji">🔤</span>
                        <span class="rigo-quiz-option-title">Décoder les syllabes</span>
                        <span class="rigo-quiz-option-meta">ma, le, pi… la lecture de base</span>
                    </button>
                    <button type="button" class="rigo-quiz-option" data-q="defi" data-v="sons">
                        <span class="rigo-quiz-option-emoji">🎵</span>
                        <span class="rigo-quiz-option-title">Confondre les sons complexes</span>
                        <span class="rigo-quiz-option-meta">ou / an / oi / in…</span>
                    </button>
                    <button type="button" class="rigo-quiz-option" data-q="defi" data-v="grammaire">
                        <span class="rigo-quiz-option-emoji">📖</span>
                        <span class="rigo-quiz-option-title">Grammaire &amp; orthographe</span>
                        <span class="rigo-quiz-option-meta">Accords, classes de mots, règles</span>
                    </button>
                    <button type="button" class="rigo-quiz-option" data-q="defi" data-v="conjugaison">
                        <span class="rigo-quiz-option-emoji">⏳</span>
                        <span class="rigo-quiz-option-title">Conjugaison</span>
                        <span class="rigo-quiz-option-meta">Passé simple, terminaisons</span>
                    </button>
                    <button type="button" class="rigo-quiz-option" data-q="defi" data-v="general">
                        <span class="rigo-quiz-option-emoji">🌟</span>
                        <span class="rigo-quiz-option-title">Progrès général</span>
                        <span class="rigo-quiz-option-meta">Pas de difficulté spécifique</span>
                    </button>
                </div>
            </div>

            <!-- Step 3 : Contexte -->
            <div class="rigo-quiz-step" data-step="3" id="rigo-quiz-step-3" hidden>
                <span class="rigo-quiz-step-num">Étape 3 / 3</span>
                <h2>Dans quel contexte va-t-il l'utiliser&nbsp;?</h2>
                <p class="rigo-quiz-sub">Cela nous aide à recommander le bon format (jeu / livre de référence).</p>
                <div class="rigo-quiz-options rigo-quiz-options-wide" role="radiogroup" aria-label="Contexte d'usage">
                    <button type="button" class="rigo-quiz-option" data-q="contexte" data-v="famille">
                        <span class="rigo-quiz-option-emoji">👨‍👩‍👧</span>
                        <span class="rigo-quiz-option-title">En famille, le soir ou week-end</span>
                        <span class="rigo-quiz-option-meta">On veut jouer ensemble</span>
                    </button>
                    <button type="button" class="rigo-quiz-option" data-q="contexte" data-v="autonome">
                        <span class="rigo-quiz-option-emoji">📘</span>
                        <span class="rigo-quiz-option-title">En autonomie ou révision</span>
                        <span class="rigo-quiz-option-meta">Devoirs, consultation d'une règle</span>
                    </button>
                    <button type="button" class="rigo-quiz-option" data-q="contexte" data-v="pro">
                        <span class="rigo-quiz-option-emoji">🩺</span>
                        <span class="rigo-quiz-option-title">Cabinet d'orthophonie ou classe</span>
                        <span class="rigo-quiz-option-meta">Usage professionnel</span>
                    </button>
                    <button type="button" class="rigo-quiz-option" data-q="contexte" data-v="cadeau">
                        <span class="rigo-quiz-option-emoji">🎁</span>
                        <span class="rigo-quiz-option-title">C'est pour offrir</span>
                        <span class="rigo-quiz-option-meta">Cadeau utile, qualité exigée</span>
                    </button>
                </div>
            </div>

            <!-- Résultat -->
            <div class="rigo-quiz-step rigo-quiz-result" data-step="4" id="rigo-quiz-result" hidden>
                <span class="rigo-quiz-step-num">Votre recommandation</span>
                <h2>Le jeu parfait pour votre situation</h2>
                <p class="rigo-quiz-sub">Sélection validée par Brigitte, orthophoniste depuis 25 ans.</p>

                <div class="rigo-quiz-hero-match" id="rigo-quiz-hero">
                    <!-- Injecté en JS -->
                </div>

                <div class="rigo-quiz-alts">
                    <h3>Aussi adaptés&nbsp;:</h3>
                    <div class="rigo-quiz-alts-grid" id="rigo-quiz-alts-grid">
                        <!-- Injecté en JS -->
                    </div>
                </div>

                <div class="rigo-quiz-actions">
                    <button type="button" class="rigo-quiz-restart">← Refaire le quiz</button>
                    <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="rigo-quiz-see-all">Voir toute la boutique</a>
                </div>
            </div>

        </div>
    </div>

    <!-- Données produits (JSON pour le JS) -->
    <script type="application/json" id="rigo-quiz-products">
    <?php echo wp_json_encode($products); ?>
    </script>

    <style id="rigo-quiz-css">
    /* ── Floating CTA ────────────────────────────────────────────────────── */
    .rigo-quiz-fab {
        position: fixed;
        bottom: calc(24px + env(safe-area-inset-bottom, 0px));
        left: 16px;
        z-index: 750;
        display: flex;
        align-items: center;
        gap: 8px;
        background: #F7F4ED;
        color: #374151;
        border: 2px solid #E7E2D5;
        border-radius: 9999px;
        padding: 10px 18px 10px 14px;
        font-family: "Nunito", sans-serif;
        font-size: 13px;
        font-weight: 700;
        box-shadow: 0 4px 16px rgba(0,0,0,.12);
        cursor: pointer;
        transition: transform 200ms, box-shadow 200ms, background 200ms;
        white-space: nowrap;
    }
    .rigo-quiz-fab:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,.18);
        background: #fff;
    }
    .rigo-quiz-fab-icon { font-size: 18px; line-height: 1; }
    @media (max-width: 600px) {
        .rigo-quiz-fab {
            bottom: calc(140px + env(safe-area-inset-bottom, 0px)); /* au-dessus sticky ATC */
            font-size: 12px;
            padding: 8px 14px 8px 10px;
        }
        .rigo-quiz-fab-label { max-width: 140px; overflow: hidden; text-overflow: ellipsis; }
    }

    /* ── Modal ───────────────────────────────────────────────────────────── */
    .rigo-quiz-modal {
        position: fixed;
        inset: 0;
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        animation: rigoQuizFade 200ms ease-out;
    }
    .rigo-quiz-modal[hidden] { display: none !important; }
    @keyframes rigoQuizFade {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .rigo-quiz-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(31, 41, 55, .72);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
    }
    .rigo-quiz-box {
        position: relative;
        background: #fff;
        border-radius: 16px;
        padding: 40px 36px 28px;
        max-width: 720px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 24px 64px rgba(0,0,0,.32);
        font-family: "Nunito", sans-serif;
        color: #374151;
        animation: rigoQuizSlide 280ms cubic-bezier(.2,.8,.2,1);
    }
    @keyframes rigoQuizSlide {
        from { transform: translateY(24px); opacity: 0; }
        to   { transform: translateY(0);    opacity: 1; }
    }
    .rigo-quiz-close {
        position: absolute;
        top: 14px;
        right: 14px;
        width: 32px;
        height: 32px;
        border: 0;
        background: #F7F4ED;
        border-radius: 50%;
        font-size: 20px;
        line-height: 1;
        cursor: pointer;
        color: #6B7280;
        transition: background 150ms, transform 150ms;
    }
    .rigo-quiz-close:hover { background: #E7E2D5; transform: rotate(90deg); }

    /* Progress */
    .rigo-quiz-progress {
        height: 4px;
        background: #F7F4ED;
        border-radius: 2px;
        margin: 0 0 28px;
        overflow: hidden;
    }
    .rigo-quiz-progress-bar {
        height: 100%;
        width: 33%;
        background: linear-gradient(90deg, #27B4E5, #68a033);
        border-radius: 2px;
        transition: width 300ms cubic-bezier(.2,.8,.2,1);
    }

    /* Step */
    .rigo-quiz-step[hidden] { display: none !important; }
    .rigo-quiz-step-num {
        display: inline-block;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .12em;
        color: #27B4E5;
        margin-bottom: 8px;
    }
    .rigo-quiz-box h2 {
        font-family: "Kalam", cursive;
        font-size: 28px;
        font-weight: 700;
        color: #2D2420;
        margin: 0 0 8px;
        line-height: 1.2;
    }
    .rigo-quiz-sub {
        font-size: 14px;
        color: #6B7280;
        margin: 0 0 24px;
    }

    /* Options */
    .rigo-quiz-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 10px;
    }
    .rigo-quiz-options-wide {
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    }
    .rigo-quiz-option {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
        padding: 16px;
        background: #F7F4ED;
        border: 2px solid #E7E2D5;
        border-radius: 12px;
        cursor: pointer;
        text-align: left;
        transition: all 180ms;
        font-family: inherit;
    }
    .rigo-quiz-option:hover {
        background: #fff;
        border-color: #27B4E5;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(39, 180, 229, .15);
    }
    .rigo-quiz-option.selected {
        background: #E8F6FD;
        border-color: #27B4E5;
    }
    .rigo-quiz-option-emoji { font-size: 22px; line-height: 1; }
    .rigo-quiz-option-title {
        font-weight: 800;
        font-size: 14px;
        color: #2D2420;
    }
    .rigo-quiz-option-meta {
        font-size: 12px;
        color: #9CA3AF;
    }

    /* Résultat */
    .rigo-quiz-hero-match {
        display: grid;
        grid-template-columns: 140px 1fr;
        gap: 20px;
        background: linear-gradient(135deg, #FFF8E6, #F7F4ED);
        border: 2px solid #FBCF33;
        border-radius: 14px;
        padding: 20px;
        margin: 20px 0 24px;
        position: relative;
    }
    .rigo-quiz-hero-match::before {
        content: "🏆 Match idéal";
        position: absolute;
        top: -12px;
        left: 20px;
        background: #FBCF33;
        color: #5C4000;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .08em;
        padding: 4px 12px;
        border-radius: 20px;
    }
    .rigo-quiz-hero-img {
        width: 140px;
        height: 140px;
        border-radius: 10px;
        background: #fff;
        object-fit: cover;
    }
    .rigo-quiz-hero-info { display: flex; flex-direction: column; gap: 6px; min-width: 0; }
    .rigo-quiz-hero-name {
        font-family: "Kalam", cursive;
        font-size: 22px;
        font-weight: 700;
        color: #2D2420;
        margin: 0;
    }
    .rigo-quiz-hero-short {
        font-size: 13px;
        color: #4B5563;
        line-height: 1.5;
        margin: 0;
    }
    .rigo-quiz-hero-price {
        font-size: 18px;
        font-weight: 800;
        color: #68a033;
    }
    .rigo-quiz-hero-ctas {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 8px;
    }
    .rigo-quiz-btn-primary,
    .rigo-quiz-btn-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 18px;
        border-radius: 9999px;
        font-size: 13px;
        font-weight: 800;
        text-decoration: none;
        transition: all 180ms;
        border: 0;
        cursor: pointer;
        font-family: inherit;
    }
    .rigo-quiz-btn-primary {
        background: #68a033;
        color: #fff;
    }
    .rigo-quiz-btn-primary:hover {
        background: #588a28;
        color: #fff;
        transform: translateY(-1px);
    }
    .rigo-quiz-btn-secondary {
        background: transparent;
        border: 1.5px solid #27B4E5;
        color: #27B4E5;
    }
    .rigo-quiz-btn-secondary:hover {
        background: #27B4E5;
        color: #fff;
    }

    .rigo-quiz-alts h3 {
        font-family: "Nunito", sans-serif;
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #9CA3AF;
        margin: 20px 0 12px;
    }
    .rigo-quiz-alts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
    }
    .rigo-quiz-alt {
        display: flex;
        gap: 12px;
        align-items: center;
        padding: 12px;
        background: #F7F4ED;
        border: 1.5px solid #E7E2D5;
        border-radius: 10px;
        text-decoration: none;
        transition: all 180ms;
    }
    .rigo-quiz-alt:hover {
        background: #fff;
        border-color: #27B4E5;
        transform: translateY(-2px);
    }
    .rigo-quiz-alt-img {
        width: 56px;
        height: 56px;
        border-radius: 8px;
        object-fit: cover;
        flex-shrink: 0;
    }
    .rigo-quiz-alt-info { min-width: 0; }
    .rigo-quiz-alt-name {
        font-size: 13px;
        font-weight: 700;
        color: #2D2420;
        margin: 0 0 2px;
        line-height: 1.3;
    }
    .rigo-quiz-alt-price {
        font-size: 13px;
        color: #68a033;
        font-weight: 700;
    }

    /* Actions bas */
    .rigo-quiz-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 24px;
        padding-top: 18px;
        border-top: 1px solid #E7E2D5;
        flex-wrap: wrap;
        gap: 10px;
    }
    .rigo-quiz-restart {
        background: transparent;
        border: 0;
        color: #6B7280;
        font-family: inherit;
        font-size: 13px;
        cursor: pointer;
        padding: 6px 0;
        font-weight: 600;
    }
    .rigo-quiz-restart:hover { color: #27B4E5; text-decoration: underline; }
    .rigo-quiz-see-all {
        color: #27B4E5;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
    }
    .rigo-quiz-see-all:hover { text-decoration: underline; }

    /* Mobile */
    @media (max-width: 600px) {
        .rigo-quiz-box { padding: 32px 20px 20px; border-radius: 14px; }
        .rigo-quiz-box h2 { font-size: 22px; }
        .rigo-quiz-hero-match { grid-template-columns: 100px 1fr; padding: 16px; gap: 14px; }
        .rigo-quiz-hero-img { width: 100px; height: 100px; }
        .rigo-quiz-hero-name { font-size: 18px; }
    }
    </style>

    <script id="rigo-quiz-js">
    (function () {
        'use strict';

        // Récupère le JSON produits
        var productsJson = document.getElementById('rigo-quiz-products');
        if (!productsJson) return;
        var PRODUCTS = {};
        try { PRODUCTS = JSON.parse(productsJson.textContent); } catch (e) { return; }

        var modal     = document.getElementById('rigo-quiz');
        var bar       = document.getElementById('rigo-quiz-progress-bar');
        var steps     = Array.prototype.slice.call(modal.querySelectorAll('.rigo-quiz-step'));
        var answers   = {};

        // Matrice de scoring : chaque produit reçoit des points selon les réponses.
        // Le produit au score max = match idéal, les 2 suivants = alternatives.
        var WEIGHTS = {
            // { question.value: { productKey: score } }
            'niveau.cp':            { pato: 10, sons: 3, grammaire1: 1 },
            'niveau.ce1':           { sons: 10, pato: 5, grammaire1: 6 },
            'niveau.ce2':           { sons: 7, grammaire1: 9, pato: 2 },
            'niveau.cm1':           { grammaire1: 8, rigoloverbes: 9, grammaire2: 6 },
            'niveau.cm2':           { grammaire2: 10, rigoloverbes: 8, grammaire1: 4 },
            'niveau.autre':         { grammaire2: 7, grammaire1: 6, rigoloverbes: 4 },

            'defi.lecture':         { pato: 10, sons: 6 },
            'defi.sons':            { sons: 10, pato: 4 },
            'defi.grammaire':       { grammaire1: 8, grammaire2: 9, rigoloverbes: 3 },
            'defi.conjugaison':     { rigoloverbes: 10, grammaire2: 6, grammaire1: 3 },
            'defi.general':         { pato: 3, sons: 3, grammaire1: 4, grammaire2: 3, rigoloverbes: 3 },

            'contexte.famille':     { pato: 3, sons: 3, rigoloverbes: 3 },
            'contexte.autonome':    { grammaire1: 3, grammaire2: 3 },
            'contexte.pro':         { pato: 2, sons: 2, grammaire1: 3, grammaire2: 3, rigoloverbes: 2 },
            'contexte.cadeau':      { pato: 3, sons: 2, rigoloverbes: 2, grammaire1: 2 }
        };

        function openModal() {
            modal.hidden = false;
            document.body.style.overflow = 'hidden';
            // Focus 1er bouton
            var first = modal.querySelector('.rigo-quiz-option');
            if (first) first.focus();
            if (window.gtag) window.gtag('event', 'quiz_open', { event_category: 'rigo_quiz' });
        }
        function closeModal() {
            modal.hidden = true;
            document.body.style.overflow = '';
            // Reset
            answers = {};
            showStep(1);
            Array.prototype.forEach.call(modal.querySelectorAll('.rigo-quiz-option.selected'), function (el) {
                el.classList.remove('selected');
            });
        }
        function showStep(n) {
            steps.forEach(function (s) {
                s.hidden = parseInt(s.dataset.step, 10) !== n;
            });
            var totalSteps = 3;
            var pct = Math.min(100, (n / (totalSteps + 1)) * 100);
            if (n === 4) pct = 100;
            bar.style.width = pct + '%';
        }

        function computeResult() {
            var scores = {};
            Object.keys(PRODUCTS).forEach(function (k) { scores[k] = 0; });

            Object.keys(answers).forEach(function (q) {
                var key = q + '.' + answers[q];
                var w = WEIGHTS[key];
                if (!w) return;
                Object.keys(w).forEach(function (p) {
                    if (scores[p] !== undefined) scores[p] += w[p];
                });
            });

            // Tri décroissant
            var sorted = Object.keys(scores).sort(function (a, b) {
                return scores[b] - scores[a];
            });

            return sorted.slice(0, 3).map(function (k) { return PRODUCTS[k]; }).filter(Boolean);
        }

        function renderResult() {
            var top3 = computeResult();
            if (top3.length === 0) return;

            var hero = top3[0];
            var heroEl = document.getElementById('rigo-quiz-hero');
            heroEl.innerHTML =
                '<img class="rigo-quiz-hero-img" src="' + esc(hero.image) + '" alt="' + esc(hero.name) + '" loading="lazy" />' +
                '<div class="rigo-quiz-hero-info">' +
                    '<h3 class="rigo-quiz-hero-name">' + esc(hero.name) + '</h3>' +
                    '<p class="rigo-quiz-hero-short">' + esc(truncate(hero.short, 140)) + '</p>' +
                    '<span class="rigo-quiz-hero-price">' + hero.price + '</span>' +
                    '<div class="rigo-quiz-hero-ctas">' +
                        '<a href="' + esc(hero.url) + '" class="rigo-quiz-btn-primary">Voir le jeu →</a>' +
                        '<a href="' + esc(hero.url) + '?add-to-cart=' + hero.id + '" class="rigo-quiz-btn-secondary" data-quiz-atc="' + hero.id + '">Ajouter au panier</a>' +
                    '</div>' +
                '</div>';

            var altsEl = document.getElementById('rigo-quiz-alts-grid');
            altsEl.innerHTML = top3.slice(1).map(function (p) {
                return '<a href="' + esc(p.url) + '" class="rigo-quiz-alt">' +
                        '<img class="rigo-quiz-alt-img" src="' + esc(p.image) + '" alt="' + esc(p.name) + '" loading="lazy" />' +
                        '<div class="rigo-quiz-alt-info">' +
                            '<h4 class="rigo-quiz-alt-name">' + esc(p.name) + '</h4>' +
                            '<span class="rigo-quiz-alt-price">' + p.price + '</span>' +
                        '</div>' +
                    '</a>';
            }).join('');

            if (window.gtag) window.gtag('event', 'quiz_complete', {
                event_category: 'rigo_quiz',
                event_label: hero.name,
                value: hero.id
            });
        }

        function truncate(s, n) {
            if (!s) return '';
            return s.length > n ? s.slice(0, n - 1).trimEnd() + '…' : s;
        }
        function esc(s) {
            return String(s == null ? '' : s)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        // ── Events ──────────────────────────────────────────────────────────
        document.addEventListener('click', function (e) {
            // Triggers
            if (e.target.closest('.rigo-quiz-trigger, [href="#rigo-quiz"]')) {
                e.preventDefault();
                openModal();
                return;
            }
            // Close
            if (e.target.closest('[data-rigo-quiz-close]')) {
                closeModal();
                return;
            }
            // Option select
            var opt = e.target.closest('.rigo-quiz-option');
            if (opt) {
                var q = opt.dataset.q;
                var v = opt.dataset.v;
                answers[q] = v;

                // Marque visuel
                Array.prototype.forEach.call(
                    opt.parentNode.querySelectorAll('.rigo-quiz-option'),
                    function (o) { o.classList.remove('selected'); }
                );
                opt.classList.add('selected');

                // Progression avec petit délai (feedback visuel)
                setTimeout(function () {
                    if (q === 'niveau')    showStep(2);
                    else if (q === 'defi') showStep(3);
                    else if (q === 'contexte') { showStep(4); renderResult(); }
                }, 220);
                return;
            }
            // Restart
            if (e.target.closest('.rigo-quiz-restart')) {
                answers = {};
                Array.prototype.forEach.call(modal.querySelectorAll('.rigo-quiz-option.selected'), function (el) {
                    el.classList.remove('selected');
                });
                showStep(1);
                return;
            }
        });

        // ESC pour fermer
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !modal.hidden) closeModal();
        });
    })();
    </script>
    <?php
}, 48);
