<?php
/**
 * Migré depuis Code Snippet #34 : [Rigolettres] 23 — CGV RGPD checkout + contact FAB
 * Description : Checkbox CGV obligatoire au checkout (validation serveur) + bouton flottant WhatsApp/email mobile
 */

if (!defined('ABSPATH')) exit;

/**
 * [Rigolettres] Checkout RGPD checkbox + WhatsApp / contact flottant mobile
 *
 * 1. Checkbox RGPD "J'accepte les CGV" au checkout (obligatoire, bloque la commande)
 * 2. Bouton flottant WhatsApp / email sur mobile (hors pages admin + checkout)
 *    → numéro ou email provisoire (à remplacer par vrai n° Brigitte)
 *
 * ⚠️ Action Arthur : remplacer RIGO_CONTACT_TEL par le vrai n° de Brigitte
 *    (ex: "33612345678") — actuellement = '' (bouton caché si vide)
 *
 * Scope : front-end
 * Priority : 30
 */

define('RIGO_CONTACT_TEL', '');          // ex: '33612345678' (format international sans +)
define('RIGO_CONTACT_EMAIL', 'aetiennea@gmail.com'); // email boutique provisoire

// ── 1. Checkbox CGV RGPD au checkout ────────────────────────────────────
add_action('woocommerce_review_order_before_submit', function () {
    $cgv_url = get_privacy_policy_url() ?: '#';
    ?>
    <div class="rigo-cgv-wrap">
        <label class="rigo-cgv-label" for="rigo_cgv_accept">
            <input type="checkbox" id="rigo_cgv_accept" name="rigo_cgv_accept" value="1" required>
            <span>
                J'ai lu et j'accepte les
                <a href="/conditions-generales-de-vente/" target="_blank" rel="noopener">Conditions générales de vente</a>
                et la
                <a href="<?php echo esc_url($cgv_url); ?>" target="_blank" rel="noopener">Politique de confidentialité</a>.
                <em>(obligatoire)</em>
            </span>
        </label>
    </div>
    <style id="rigo-cgv-css">
    .rigo-cgv-wrap {
        margin: 16px 0;
        padding: 14px 16px;
        background: #F7F4ED;
        border: 1.5px solid #E7E2D5;
        border-radius: 10px;
    }
    .rigo-cgv-label {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-family: "Nunito", sans-serif;
        font-size: 13.5px;
        color: #4B5563;
        line-height: 1.6;
        cursor: pointer;
    }
    .rigo-cgv-label input[type="checkbox"] {
        width: 18px !important;
        height: 18px !important;
        min-width: 18px;
        border: 2px solid #E7E2D5 !important;
        border-radius: 4px !important;
        margin-top: 2px;
        flex-shrink: 0;
        accent-color: #68a033;
        cursor: pointer;
    }
    .rigo-cgv-label a {
        color: #68a033;
        text-decoration: underline;
    }
    .rigo-cgv-label em { color: #9CA3AF; font-size: 12px; }
    </style>
    <?php
}, 10);

// Validation serveur de la checkbox
add_action('woocommerce_checkout_process', function () {
    if (empty($_POST['rigo_cgv_accept'])) {
        wc_add_notice(
            __('Veuillez accepter nos Conditions générales de vente pour passer votre commande.', 'rigolettres'),
            'error'
        );
    }
});

// ── 2. Bouton flottant contact (WhatsApp / email) ─────────────────────────
add_action('wp_footer', function () {
    if (is_checkout() || is_cart() || is_admin()) return;

    $tel   = RIGO_CONTACT_TEL;
    $email = RIGO_CONTACT_EMAIL;
    if (!$tel && !$email) return;

    $href  = $tel
        ? 'https://wa.me/' . preg_replace('/\D/', '', $tel) . '?text=' . rawurlencode('Bonjour ! J\'ai une question sur les jeux Rigolettres.')
        : 'mailto:' . $email . '?subject=' . rawurlencode('Question sur les jeux Rigolettres');
    $label = $tel ? 'Contacter par WhatsApp' : 'Nous contacter par email';
    $icon  = $tel
        ? '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.089.537 4.05 1.475 5.759L0 24l6.389-1.454A11.935 11.935 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 0 1-5.009-1.373l-.359-.214-3.723.847.862-3.63-.234-.373A9.78 9.78 0 0 1 2.182 12C2.182 6.58 6.58 2.182 12 2.182S21.818 6.58 21.818 12 17.42 21.818 12 21.818z"/></svg>'
        : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>';
    ?>
    <a href="<?php echo esc_url($href); ?>"
       class="rigo-contact-fab"
       aria-label="<?php echo esc_attr($label); ?>"
       <?php echo $tel ? 'target="_blank" rel="noopener"' : ''; ?>>
        <?php echo $icon; ?>
        <span class="rigo-contact-fab-label">Une question ?</span>
    </a>

    <style id="rigo-fab-css">
    .rigo-contact-fab {
        display: flex;
        align-items: center;
        gap: 8px;
        position: fixed;
        bottom: calc(80px + env(safe-area-inset-bottom, 0px)); /* au-dessus du sticky ATC */
        right: 16px;
        background: <?php echo $tel ? '#25D366' : '#27B4E5'; ?>;
        color: #fff;
        border-radius: 9999px;
        padding: 10px 16px 10px 12px;
        text-decoration: none;
        font-family: "Nunito", sans-serif;
        font-size: 13px;
        font-weight: 700;
        box-shadow: 0 4px 16px rgba(0,0,0,.20);
        z-index: 800;
        transition: transform 200ms, box-shadow 200ms;
        white-space: nowrap;
    }
    .rigo-contact-fab:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,.25);
        color: #fff;
    }
    .rigo-contact-fab svg { width: 20px; height: 20px; flex-shrink: 0; }
    .rigo-contact-fab-label { line-height: 1; }

    /* Masquer le label sur très petits écrans */
    @media (max-width: 360px) { .rigo-contact-fab-label { display: none; } }

    /* Sur desktop → coin inférieur droit standard, sans décalage sticky ATC */
    @media (min-width: 769px) {
        .rigo-contact-fab { bottom: 24px; right: 24px; }
    }

    /* Cacher sur checkout (trop distrayant) */
    .woocommerce-checkout .rigo-contact-fab,
    .woocommerce-cart .rigo-contact-fab { display: none; }
    </style>
    <?php
}, 30);
