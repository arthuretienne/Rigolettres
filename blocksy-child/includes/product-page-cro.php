<?php
/**
 * Migré depuis Code Snippet #26 : [Rigolettres] 15 — Product page CRO
 * Description : Guarantee badge 30j + icônes paiement + section Brigitte + FAQ accordéon sur fiche produit
 */

if (!defined('ABSPATH')) exit;

/**
 * [Rigolettres] Fiche produit CRO — Sprint 3
 *
 * 1. Guarantee badge "Satisfait ou remboursé 30 jours" sous les trust badges
 * 2. Icônes moyens de paiement (CB / Visa / MC / PayPal) sous le CTA
 * 3. FAQ accordéon : détecte la section FAQ dans la description produit
 *    et la transforme en accordéon animé
 * 4. Section "L'avis de l'orthophoniste" (bio courte Brigitte) sur chaque fiche
 *
 * Scope : front-end
 * Priority : 45
 */

// ── 1. Guarantee badge + icônes paiement ───────────────────────────────────
add_action('woocommerce_after_add_to_cart_button', function () {
    ?>
    <div class="rigo-guarantee-row">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        <span><strong>Satisfait ou remboursé 30 jours</strong> — retour simple, sans justification</span>
    </div>

    <div class="rigo-payment-row" aria-label="Moyens de paiement acceptés">
        <span class="rigo-payment-label">Paiement sécurisé :</span>
        <!-- Visa -->
        <svg class="rigo-pay-icon" viewBox="0 0 780 500" aria-label="Visa" role="img"><rect width="780" height="500" rx="40" fill="#1A1F71"/><path d="M294 340l35-216h56l-35 216h-56zm250-212c-11-4-28-9-49-9-54 0-92 29-93 70-1 30 27 47 47 57 21 10 28 17 28 26 0 14-17 20-32 20-21 0-33-3-51-11l-7-3-8 47c13 6 37 11 62 11 57 0 94-28 94-72 0-24-14-42-46-57-19-9-31-15-31-25 0-8 10-17 32-17 18 0 31 4 41 8l5 2 8-47zm143-4h-42c-13 0-23 4-28 17l-80 199h57l11-32h70l7 32h50l-45-216zm-67 141l22-62 6-17 3 16 10 63h-41zm-410-141l-53 147-6-29c-10-32-41-66-75-83l49 181h58l86-216h-59z" fill="#fff"/><path d="M167 124h-88l-1 4c68 17 113 59 132 108l-19-97c-3-12-12-15-24-15z" fill="#F9A533"/></svg>
        <!-- Mastercard -->
        <svg class="rigo-pay-icon" viewBox="0 0 780 500" aria-label="Mastercard" role="img"><rect width="780" height="500" rx="40" fill="#252525"/><circle cx="289" cy="250" r="160" fill="#EB001B"/><circle cx="491" cy="250" r="160" fill="#F79E1B"/><path d="M390 125.9a160 160 0 0 1 0 248.2 160 160 0 0 1 0-248.2z" fill="#FF5F00"/></svg>
        <!-- PayPal -->
        <svg class="rigo-pay-icon" viewBox="0 0 780 500" aria-label="PayPal" role="img"><rect width="780" height="500" rx="40" fill="#fff" stroke="#ddd" stroke-width="2"/><path d="M511 156c0 66-45 95-119 95h-30l-22 130h-61l55-330h96c52 0 81 28 81 105z" fill="#253B80"/><path d="M411 201c48 0 77-21 77-72 0-30-17-47-52-47h-42l-25 119h42z" fill="#253B80"/><path d="M631 156c0 66-45 95-119 95h-30l-22 130h-61l55-330h96c52 0 81 28 81 105z" fill="#179BD7"/><path d="M531 201c48 0 77-21 77-72 0-30-17-47-52-47h-42l-25 119h42z" fill="#179BD7"/></svg>
        <!-- CB générique -->
        <svg class="rigo-pay-icon rigo-pay-cb" viewBox="0 0 780 500" aria-label="Carte bancaire" role="img"><rect width="780" height="500" rx="40" fill="#F3F3F3" stroke="#ddd" stroke-width="2"/><rect x="60" y="160" width="280" height="180" rx="10" fill="#D4A017"/><rect x="60" y="220" width="280" height="60" fill="#C49010"/><text x="390" y="290" font-family="Arial" font-size="80" font-weight="bold" fill="#333" text-anchor="middle" dominant-baseline="middle">CB</text></svg>
    </div>
    <?php
}, 35);

// ── 2. Section Brigitte sur chaque fiche produit ───────────────────────────
add_action('woocommerce_after_single_product_summary', function () {
    ?>
    <div class="rigo-brigitte-section">
        <div class="rigo-brigitte-inner">
            <div class="rigo-brigitte-avatar" aria-hidden="true">
                <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="50" cy="50" r="50" fill="#F7F4ED"/>
                    <circle cx="50" cy="38" r="18" fill="#E8C99A"/>
                    <ellipse cx="50" cy="80" rx="28" ry="22" fill="#E8C99A"/>
                    <path d="M32 58 Q50 72 68 58" stroke="#C9A070" stroke-width="2" fill="none"/>
                </svg>
            </div>
            <div class="rigo-brigitte-content">
                <p class="rigo-brigitte-label">✦ L'avis de l'orthophoniste</p>
                <p class="rigo-brigitte-quote">« Ce jeu est né de mes consultations : chaque règle, chaque carte, chaque niveau a été testé avec des enfants réels. L'objectif est simple — que l'enfant joue, rit, et sans s'en rendre compte, apprenne à lire. »</p>
                <p class="rigo-brigitte-sig">— <strong>Brigitte Étienne</strong>, orthophoniste à Mamers depuis 1978</p>
            </div>
        </div>
    </div>
    <?php
}, 15);

// ── 3. FAQ accordéon (via JS) ──────────────────────────────────────────────
add_action('wp_footer', function () {
    if (!is_product()) return;
    ?>
    <script id="rigo-faq-accordion">
    (function () {
        // Cherche une section FAQ dans la description longue
        // Patterns : <h3>FAQ</h3> ou <h2>Foire aux questions</h2> + paires question/réponse
        var desc = document.querySelector('.woocommerce-product-details__short-description, .woocommerce-Tabs-panel--description');
        if (!desc) desc = document.querySelector('#tab-description');
        if (!desc) return;

        // Trouve le heading FAQ
        var headings = desc.querySelectorAll('h2, h3, h4');
        var faqHeading = null;
        headings.forEach(function(h) {
            var t = h.textContent.trim().toLowerCase();
            if (t.includes('faq') || t.includes('foire') || t.includes('questions fréquentes')) {
                faqHeading = h;
            }
        });
        if (!faqHeading) return;

        // Collecte les paires question/réponse qui suivent le heading
        var items = [];
        var current = faqHeading.nextElementSibling;
        while (current) {
            var tag = current.tagName;
            if (tag === 'H2' || tag === 'H3' || tag === 'H4') {
                // Nouvelle question
                var question = current.textContent.trim();
                var answerEl = current.nextElementSibling;
                var answerHTML = '';
                if (answerEl && (answerEl.tagName === 'P' || answerEl.tagName === 'UL')) {
                    answerHTML = answerEl.outerHTML;
                    answerEl.remove();
                }
                items.push({ question: question, answer: answerHTML });
                var toRemove = current;
                current = current.nextElementSibling;
                toRemove.remove();
                continue;
            }
            // Stop si on rencontre un HR ou un bloc non-question
            if (tag === 'HR') break;
            current = current.nextElementSibling;
        }

        if (items.length === 0) return;

        // Construit l'accordéon
        var wrap = document.createElement('div');
        wrap.className = 'rigo-faq-accordion';
        var title = document.createElement('h3');
        title.className = 'rigo-faq-title';
        title.textContent = '❓ Questions fréquentes';
        wrap.appendChild(title);

        items.forEach(function(item, i) {
            var entry = document.createElement('div');
            entry.className = 'rigo-faq-item';
            entry.innerHTML =
                '<button class="rigo-faq-q" aria-expanded="false" aria-controls="rigo-faq-a-' + i + '">' +
                    '<span>' + item.question + '</span>' +
                    '<svg class="rigo-faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>' +
                '</button>' +
                '<div class="rigo-faq-a" id="rigo-faq-a-' + i + '" hidden>' +
                    item.answer +
                '</div>';
            wrap.appendChild(entry);
        });

        // Remplace le heading FAQ par l'accordéon
        faqHeading.parentNode.insertBefore(wrap, faqHeading);
        faqHeading.remove();

        // Logique ouverture/fermeture
        wrap.addEventListener('click', function(e) {
            var btn = e.target.closest('.rigo-faq-q');
            if (!btn) return;
            var panel = btn.nextElementSibling;
            var isOpen = btn.getAttribute('aria-expanded') === 'true';
            // Fermer tous
            wrap.querySelectorAll('.rigo-faq-q').forEach(function(b) {
                b.setAttribute('aria-expanded', 'false');
                b.nextElementSibling.hidden = true;
            });
            if (!isOpen) {
                btn.setAttribute('aria-expanded', 'true');
                panel.hidden = false;
            }
        });
    })();
    </script>
    <?php
}, 50);

// ── 4. CSS fiche produit CRO ───────────────────────────────────────────────
add_action('wp_head', function () {
    if (!is_product()) return;
    ?>
    <style id="rigo-product-cro-css">
    /* ── Guarantee badge ── */
    .rigo-guarantee-row {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        margin: 12px 0 6px;
        padding: 11px 14px;
        background: #F0FBE6;
        border: 1.5px solid #C5E09B;
        border-radius: 10px;
        font-family: Nunito, sans-serif;
        font-size: 13px;
        color: #2D5A1B;
        line-height: 1.5;
    }
    .rigo-guarantee-row svg {
        width: 18px; height: 18px;
        stroke: #68a033;
        flex-shrink: 0;
        margin-top: 1px;
    }

    /* ── Payment icons row ── */
    .rigo-payment-row {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px 10px;
        margin: 10px 0 4px;
    }
    .rigo-payment-label {
        font-family: Nunito, sans-serif;
        font-size: 11.5px;
        color: #9CA3AF;
        font-weight: 600;
        margin-right: 4px;
    }
    .rigo-pay-icon {
        height: 26px;
        width: auto;
        border-radius: 4px;
        border: 1px solid #E5E7EB;
        display: inline-block;
        vertical-align: middle;
    }

    /* ── Section Brigitte ── */
    .rigo-brigitte-section {
        margin: 40px 0;
        background: linear-gradient(135deg, #FBF8F0 0%, #F3EDD8 100%);
        border: 1.5px solid #E7E2D5;
        border-radius: 16px;
        overflow: hidden;
    }
    .rigo-brigitte-inner {
        max-width: 900px;
        margin: 0 auto;
        padding: 32px 28px;
        display: flex;
        align-items: flex-start;
        gap: 24px;
    }
    .rigo-brigitte-avatar {
        flex-shrink: 0;
        width: 80px; height: 80px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid #E7E2D5;
    }
    .rigo-brigitte-avatar svg { width: 100%; height: 100%; }
    .rigo-brigitte-label {
        font-family: Kalam, cursive;
        font-size: 13px;
        color: #68a033;
        font-weight: 700;
        margin: 0 0 8px;
        text-transform: uppercase;
        letter-spacing: .06em;
    }
    .rigo-brigitte-quote {
        font-family: Nunito, sans-serif;
        font-size: 15px;
        color: #3a2913;
        line-height: 1.7;
        font-style: italic;
        margin: 0 0 10px;
    }
    .rigo-brigitte-sig {
        font-family: Nunito, sans-serif;
        font-size: 13px;
        color: #6B7280;
        margin: 0;
    }
    @media (max-width: 600px) {
        .rigo-brigitte-inner { flex-direction: column; padding: 22px 18px; }
        .rigo-brigitte-avatar { width: 60px; height: 60px; }
    }

    /* ── FAQ accordéon ── */
    .rigo-faq-accordion {
        margin: 32px 0;
    }
    .rigo-faq-title {
        font-family: Kalam, cursive;
        font-size: 22px;
        color: #3a2913;
        margin: 0 0 16px;
    }
    .rigo-faq-item {
        border: 1.5px solid #E7E2D5;
        border-radius: 10px;
        margin-bottom: 8px;
        overflow: hidden;
        background: #fff;
    }
    .rigo-faq-q {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 14px 18px;
        background: none;
        border: none;
        cursor: pointer;
        text-align: left;
        font-family: Nunito, sans-serif;
        font-size: 15px;
        font-weight: 700;
        color: #3a2913;
        transition: background .15s;
    }
    .rigo-faq-q:hover { background: #F7F4ED; }
    .rigo-faq-q[aria-expanded="true"] { background: #F7F4ED; }
    .rigo-faq-chevron {
        width: 18px; height: 18px;
        flex-shrink: 0;
        stroke: #68a033;
        transition: transform .25s ease;
    }
    .rigo-faq-q[aria-expanded="true"] .rigo-faq-chevron {
        transform: rotate(180deg);
    }
    .rigo-faq-a {
        padding: 0 18px 16px;
        font-family: Nunito, sans-serif;
        font-size: 14.5px;
        color: #4B5563;
        line-height: 1.7;
    }
    .rigo-faq-a p { margin: 0; }
    </style>
    <?php
}, 45);
