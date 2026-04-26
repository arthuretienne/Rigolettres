<?php
/**
 * [Rigolettres] Thank you page personnalisée
 *
 * Enrichit la page de confirmation commande WooCommerce (/checkout/order-received/) :
 * — Message chaleureux signé Brigitte (ton famille, pas corporate)
 * — Infos clés : numéro commande, email, délai expédition
 * — Étapes visuelles : Commande reçue → Brigitte prépare → Expédition
 * — CTA vers la boutique (upsell doux)
 * — Invitation à l'avis (post-achat J+7, placeholder)
 * — Masque le titre WC par défaut "Commande reçue"
 *
 * Scope : front-end
 * Priority : 60
 */

// ── 1. Injection HTML avant le contenu WC ────────────────────────────────
add_action('woocommerce_before_thankyou', function ($order_id) {
    $order = wc_get_order($order_id);
    if (!$order) return;

    $first_name   = $order->get_billing_first_name();
    $order_number = $order->get_order_number();
    $email        = $order->get_billing_email();
    $total        = wc_price($order->get_total());
    $items        = $order->get_items();
    $item_count   = count($items);
    ?>
    <div class="rigo-ty-hero">
        <div class="rigo-ty-hero-icon" aria-hidden="true">
            <svg viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="40" cy="40" r="40" fill="#F0FBE6"/>
                <path d="M24 40l12 12 20-24" stroke="#68a033" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="rigo-ty-hero-text">
            <h1 class="rigo-ty-title">Merci<?php echo $first_name ? ', ' . esc_html($first_name) . ' !' : ' !' ?></h1>
            <p class="rigo-ty-subtitle">
                Votre commande <strong>#<?php echo esc_html($order_number); ?></strong> est confirmée.<br>
                Un email de confirmation a été envoyé à <strong><?php echo esc_html($email); ?></strong>.
            </p>
        </div>
    </div>

    <!-- Étapes visuelles -->
    <div class="rigo-ty-steps">
        <div class="rigo-ty-step rigo-ty-step-done">
            <div class="rigo-ty-step-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <span>Commande<br>reçue</span>
        </div>
        <div class="rigo-ty-step-line"></div>
        <div class="rigo-ty-step rigo-ty-step-active">
            <div class="rigo-ty-step-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
            </div>
            <span>Brigitte<br>prépare</span>
        </div>
        <div class="rigo-ty-step-line"></div>
        <div class="rigo-ty-step rigo-ty-step-pending">
            <div class="rigo-ty-step-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 5v3h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            </div>
            <span>Expédié<br>sous 48 h</span>
        </div>
    </div>

    <!-- Message Brigitte -->
    <div class="rigo-ty-brigitte">
        <div class="rigo-ty-brigitte-avatar" aria-hidden="true">
            <svg viewBox="0 0 60 60" fill="none">
                <circle cx="30" cy="30" r="30" fill="#F7E9D8"/>
                <circle cx="30" cy="23" r="11" fill="#E8C99A"/>
                <ellipse cx="30" cy="48" rx="17" ry="14" fill="#E8C99A"/>
            </svg>
        </div>
        <blockquote class="rigo-ty-brigitte-quote">
            <p>« Je prépare personnellement chaque commande depuis mon domicile à Mamers. Votre enfant va adorer découvrir les syllabes avec Pato ! N'hésitez pas à me contacter si vous avez des questions sur comment utiliser le jeu en séance. »</p>
            <footer>— <strong>Brigitte Étienne-Camillerapp</strong>, orthophoniste · Mamers (72)</footer>
        </blockquote>
    </div>

    <?php
}, 5);

// ── 2. CTA "Continuer mes achats" en bas ─────────────────────────────────
add_action('woocommerce_thankyou', function ($order_id) {
    ?>
    <div class="rigo-ty-footer-cta">
        <p class="rigo-ty-footer-text">Vous souhaitez offrir un autre jeu, ou découvrir la gamme livres ?</p>
        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="rigo-ty-shop-btn">
            ← Retour à la boutique
        </a>
    </div>
    <?php
}, 20);

// ── 3. CSS ────────────────────────────────────────────────────────────────
add_action('wp_head', function () {
    if (!is_wc_endpoint_url('order-received')) return;
    ?>
    <style id="rigo-ty-css">
    /* Masquer le titre WC par défaut */
    .woocommerce-order .entry-title,
    .woocommerce-order h1.page-title { display: none !important; }

    /* ── Hero ── */
    .rigo-ty-hero {
        display: flex;
        align-items: flex-start;
        gap: 20px;
        padding: 32px;
        background: linear-gradient(135deg, #F0FBE6 0%, #EEF7DE 100%);
        border: 1.5px solid #C5E09B;
        border-radius: 20px;
        margin-bottom: 28px;
    }
    .rigo-ty-hero-icon svg { width: 72px; height: 72px; flex-shrink: 0; }
    .rigo-ty-title {
        font-family: "Kalam", cursive;
        font-size: 32px;
        color: #2D5A1B;
        margin: 0 0 8px;
        line-height: 1.2;
    }
    .rigo-ty-subtitle {
        font-family: "Nunito", sans-serif;
        font-size: 15px;
        color: #4B5563;
        line-height: 1.6;
        margin: 0;
    }
    @media (max-width: 600px) {
        .rigo-ty-hero { flex-direction: column; padding: 22px 18px; }
        .rigo-ty-title { font-size: 26px; }
    }

    /* ── Étapes ── */
    .rigo-ty-steps {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0;
        margin-bottom: 28px;
        padding: 24px;
        background: #fff;
        border: 1.5px solid #E7E2D5;
        border-radius: 16px;
    }
    .rigo-ty-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        flex: 1;
        max-width: 120px;
        font-family: "Nunito", sans-serif;
        font-size: 12.5px;
        font-weight: 700;
        color: #9CA3AF;
        text-align: center;
        line-height: 1.4;
    }
    .rigo-ty-step-icon {
        width: 40px; height: 40px;
        border-radius: 50%;
        background: #F3F4F6;
        display: flex; align-items: center; justify-content: center;
        border: 2px solid #E5E7EB;
    }
    .rigo-ty-step-icon svg { width: 18px; height: 18px; stroke: #9CA3AF; }
    .rigo-ty-step-line {
        flex: 1;
        height: 2px;
        background: #E5E7EB;
        margin: 0 4px;
        align-self: flex-start;
        margin-top: 19px;
    }
    .rigo-ty-step-done .rigo-ty-step-icon {
        background: #F0FBE6; border-color: #68a033;
    }
    .rigo-ty-step-done .rigo-ty-step-icon svg { stroke: #68a033; }
    .rigo-ty-step-done { color: #2D5A1B; }
    .rigo-ty-step-active .rigo-ty-step-icon {
        background: #FFF3CD; border-color: #F59E0B;
        animation: rigo-ty-pulse 2s ease-in-out infinite;
    }
    .rigo-ty-step-active .rigo-ty-step-icon svg { stroke: #D97706; }
    .rigo-ty-step-active { color: #92400E; }
    @keyframes rigo-ty-pulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(245,158,11,0); }
        50% { box-shadow: 0 0 0 6px rgba(245,158,11,.18); }
    }
    @media (max-width: 480px) {
        .rigo-ty-steps { padding: 16px 12px; }
        .rigo-ty-step { font-size: 11px; max-width: 80px; }
        .rigo-ty-step-icon { width: 32px; height: 32px; }
        .rigo-ty-step-icon svg { width: 14px; height: 14px; }
    }

    /* ── Message Brigitte ── */
    .rigo-ty-brigitte {
        display: flex;
        align-items: flex-start;
        gap: 18px;
        padding: 24px 28px;
        background: #FBF8F1;
        border: 1.5px solid #E7E2D5;
        border-radius: 16px;
        margin-bottom: 28px;
    }
    .rigo-ty-brigitte-avatar svg { width: 60px; height: 60px; flex-shrink: 0; border-radius: 50%; }
    .rigo-ty-brigitte-quote {
        margin: 0;
        font-family: "Nunito", sans-serif;
    }
    .rigo-ty-brigitte-quote p {
        font-size: 14.5px;
        color: #4B5563;
        line-height: 1.75;
        font-style: italic;
        margin: 0 0 10px;
    }
    .rigo-ty-brigitte-quote footer {
        font-size: 13px;
        color: #9CA3AF;
    }
    @media (max-width: 600px) {
        .rigo-ty-brigitte { flex-direction: column; padding: 18px; }
    }

    /* ── CTA footer ── */
    .rigo-ty-footer-cta {
        text-align: center;
        padding: 28px 20px;
        border-top: 1.5px solid #E7E2D5;
        margin-top: 20px;
    }
    .rigo-ty-footer-text {
        font-family: "Nunito", sans-serif;
        font-size: 15px;
        color: #6B7280;
        margin-bottom: 14px;
    }
    .rigo-ty-shop-btn {
        display: inline-block;
        font-family: "Nunito", sans-serif;
        font-weight: 700;
        font-size: 14px;
        color: #1F2937;
        border: 1.5px solid #E7E2D5;
        border-radius: 9999px;
        padding: 11px 24px;
        text-decoration: none;
        transition: background 200ms, color 200ms;
    }
    .rigo-ty-shop-btn:hover {
        background: #1F2937;
        color: #fff;
    }
    </style>
    <?php
}, 60);
