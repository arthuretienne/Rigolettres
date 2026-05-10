<?php
/**
 * ⚠️ ONE-SHOT — Configuration initiale plugins sprint 9
 *
 * Configure deux plugins fraîchement installés :
 *  1. PDF Invoices & Packing Slips for WooCommerce (WP Overnight)
 *  2. Customer Reviews for WooCommerce (CusRev — alternative à Judge.me
 *     qui a fermé son plugin WC en août 2025)
 *
 * Comportement :
 *  - S'exécute une SEULE fois (option DB rigo_plugins_configured_v1).
 *  - Set les options les plus critiques avec les coordonnées Brigitte.
 *  - Les ajustements fins (couleurs, logo facture) restent à faire via
 *    wp-admin → WooCommerce → PDF Invoices, ou wp-admin → Reviews.
 *
 * À supprimer après run réussi : ce fichier doit être retiré au sprint+1
 * (le flag DB empêche un second run, mais le fichier ne sert plus à rien).
 */

if (!defined('ABSPATH')) exit;

add_action('wp_loaded', function () {
    if (get_option('rigo_plugins_configured_v1') === 'done') return;
    if (defined('DOING_AJAX') && DOING_AJAX) return;
    if (defined('REST_REQUEST') && REST_REQUEST && !current_user_can('manage_options')) return;

    // ── 1. PDF Invoices & Packing Slips ────────────────────────────────────
    if (class_exists('WPO_WCPDF') || function_exists('wcpdf_get_invoice')) {
        // Réglages généraux : A4, format date FR, devise EUR à droite (FR usage)
        $general = get_option('wpo_wcpdf_settings_general', []);
        $general = array_merge((array) $general, [
            'paper_size'         => 'a4',
            'paper_orientation'  => 'portrait',
            'dateformat'         => 'd-m-Y',
            'currency_format'    => '{price}{symbol}',
            'extended_currency_symbol' => 'yes',
            'shop_name'          => ['default' => 'Rigolettres'],
            'shop_address'       => "Brigitte Étienne · Rigolettres\n14 chemin de la Cour du Bois\n72600 Saint-Rémy-des-Monts\n\nCabinet : Maison Médicale Maine Saosnois\nPlace Caillaux, 72600 Mamers\n\ncontact@rigolettres.fr · 06 80 40 96 18",
        ]);
        update_option('wpo_wcpdf_settings_general', $general, false);

        // Réglages facture : auto-attach aux emails "processing" + "completed",
        // numérotation séquentielle préfixée RIGO-, mentions FR au pied de page.
        $invoice = get_option('wpo_wcpdf_documents_settings_invoice', []);
        $invoice = array_merge((array) $invoice, [
            'enabled'                => 'yes',
            'attach_to_email_ids'    => [
                'customer_processing_order' => 1,
                'customer_completed_order'  => 1,
                'customer_invoice'          => 1,
            ],
            'display_number'         => 'invoice_number',
            'display_date'           => 'invoice_date',
            'number_format'          => [
                'prefix'  => 'RIGO-[invoice_year]-',
                'suffix'  => '',
                'padding' => 4,
            ],
            'reset_number_yearly'    => 'yes',
            'next_invoice_number'    => 1,
            'invoice_number_column'  => 'yes',
            'my_account_buttons'     => 'available',
            'invoice_show_payment_method' => 'yes',
            'invoice_show_customer_notes' => 'yes',
            'footer'                 => "SIRET : 314 253 030 00055 · N° ADELI : 72 9 100 180\nTVA non applicable, article 293 B du Code général des impôts\nMédiation conso : https://ec.europa.eu/consumers/odr/",
        ]);
        update_option('wpo_wcpdf_documents_settings_invoice', $invoice, false);

        // Bon de livraison : actif aussi (utile pour Brigitte côté expédition)
        $packing_slip = get_option('wpo_wcpdf_documents_settings_packing-slip', []);
        $packing_slip = array_merge((array) $packing_slip, [
            'enabled'             => 'yes',
            'my_account_buttons'  => 'never',
        ]);
        update_option('wpo_wcpdf_documents_settings_packing-slip', $packing_slip, false);
    }

    // ── 2. Customer Reviews for WooCommerce (CusRev / ivole) ───────────────
    // Stockage en options préfixées `ivole_*`. Set les plus critiques.
    update_option('ivole_enable',              'yes', false);  // Relance email auto
    update_option('ivole_delay',               7,     false);  // 7 jours après livraison
    update_option('ivole_when',                'completed', false);
    update_option('ivole_email_from',          'contact@rigolettres.fr', false);
    update_option('ivole_email_from_name',     'Rigolettres', false);
    update_option('ivole_email_subject',       'Comment se passe la lecture avec votre jeu Rigolettres ?', false);
    update_option('ivole_email_replyto',       'contact@rigolettres.fr', false);
    update_option('ivole_form_position',       'after-comments', false);
    update_option('ivole_show_qna',            'yes', false);   // Questions & Réponses
    update_option('ivole_form_show_attachment','yes', false);   // Photos avec avis
    update_option('ivole_show_email_consent',  'yes', false);   // RGPD
    update_option('ivole_review_form_text',
        "Votre avis aide d'autres parents et orthophonistes à choisir le bon jeu. Merci de prendre 30 secondes pour partager votre expérience.",
        false
    );
    // Coupon de remerciement (optionnel — Brigitte décidera si elle veut activer)
    update_option('ivole_coupon',         'no',  false);

    // ── 3. Emails WooCommerce — couleurs Rigolettres ────────────────────────
    // Tokens design system du child theme (voir style.css section 1).
    update_option('woocommerce_email_base_color',           '#5C8E2E', false); // vert (header band)
    update_option('woocommerce_email_background_color',     '#FBF8F1', false); // cream (fond extérieur)
    update_option('woocommerce_email_body_background_color','#FFFFFF', false); // paper (corps)
    update_option('woocommerce_email_text_color',           '#2A1D0F', false); // ink
    // Logo dans le header email : on utilise le PNG Pato déjà uploadé
    update_option('woocommerce_email_header_image',
        home_url('/wp-content/uploads/2026/04/logo-pato-provisoire.png'),
        false
    );
    // Texte de pied de page : signature Brigitte + mentions légales courtes
    $footer_text = "<strong>Rigolettres</strong> · jeux et livres pour apprendre à lire<br>"
                 . "Brigitte Étienne · orthophoniste à Mamers depuis 1978<br>"
                 . "<a href=\"" . esc_url(home_url('/')) . "\">rigolettres.fr</a> · "
                 . "<a href=\"mailto:contact@rigolettres.fr\">contact@rigolettres.fr</a><br>"
                 . "<small>SIRET 314 253 030 00055 · TVA non applicable, art. 293 B du CGI</small>";
    update_option('woocommerce_email_footer_text', $footer_text, false);

    update_option('rigo_plugins_configured_v1', 'done', false);
    update_option('rigo_plugins_configured_v1_at', current_time('mysql'), false);

    if (function_exists('do_action')) {
        do_action('litespeed_purge_all');
    }
}, 999);
