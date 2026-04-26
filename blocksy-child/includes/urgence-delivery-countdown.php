<?php
/**
 * Migré depuis Code Snippet #32 : [Rigolettres] 21 — Urgence delivery countdown
 * Description : Countdown avant 14h sur fiche produit : expédié aujourd'hui / demain / lundi selon jour+heure Paris
 */

if (!defined('ABSPATH')) exit;

/**
 * [Rigolettres] Urgence delivery — countdown expédition J
 *
 * Affiche sur fiche produit (en stock) :
 * "Commandez avant 14h00 → expédié aujourd'hui !"
 * avec compte à rebours dynamique jusqu'à 14h.
 * Après 14h : "Commandez maintenant → expédié demain matin"
 * Le week-end : "Commandez ce week-end → expédié lundi"
 *
 * Jours ouvrés configurables. Fuseau Europe/Paris.
 *
 * Scope : front-end
 * Priority : 38
 */

add_action('woocommerce_after_add_to_cart_button', function () {
    global $product;
    if (!$product || !$product->is_in_stock()) return;

    // Calcul PHP côté serveur (fuseau Paris)
    $tz       = new DateTimeZone('Europe/Paris');
    $now      = new DateTime('now', $tz);
    $weekday  = (int) $now->format('N'); // 1=lundi, 7=dimanche
    $hour     = (int) $now->format('G');
    $minute   = (int) $now->format('i');
    $cutoff_h = 14; // 14h00

    $is_weekend = ($weekday >= 6);
    $past_cutoff = ($hour >= $cutoff_h);

    // Calcule les secondes restantes avant 14h (si avant 14h en semaine)
    $seconds_left = 0;
    if (!$is_weekend && !$past_cutoff) {
        $cutoff = clone $now;
        $cutoff->setTime($cutoff_h, 0, 0);
        $seconds_left = $cutoff->getTimestamp() - $now->getTimestamp();
    }

    // Label expédition
    if ($is_weekend) {
        $day_name   = $weekday === 6 ? 'samedi' : 'dimanche';
        $ship_label = 'Commandez ce ' . $day_name . ' — expédié <strong>lundi matin</strong> 📦';
        $show_timer = false;
    } elseif ($past_cutoff) {
        $ship_label = 'Commandez maintenant — expédié <strong>demain matin</strong> 📦';
        $show_timer = false;
    } else {
        $ship_label = 'Commandez avant <strong>14h00</strong> — expédié <strong>aujourd\'hui</strong> ! 🚀';
        $show_timer = true;
    }
    ?>
    <div class="rigo-urgence-wrap" id="rigo-urgence-wrap">
        <div class="rigo-urgence-icon" aria-hidden="true">🚚</div>
        <div class="rigo-urgence-text">
            <span class="rigo-urgence-label"><?php echo $ship_label; ?></span>
            <?php if ($show_timer): ?>
            <span class="rigo-urgence-timer" id="rigo-urgence-timer" aria-live="polite">
                Plus que <strong id="rigo-urgence-hms">--:--:--</strong> pour commander
            </span>
            <?php endif; ?>
        </div>
    </div>

    <style id="rigo-urgence-css">
    .rigo-urgence-wrap {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin: 14px 0 6px;
        padding: 12px 16px;
        background: #FFF8E6;
        border: 1.5px solid #FBCF33;
        border-radius: 10px;
        font-family: "Nunito", sans-serif;
        font-size: 13.5px;
        color: #7C5800;
        line-height: 1.5;
    }
    .rigo-urgence-icon { font-size: 18px; flex-shrink: 0; margin-top: 1px; }
    .rigo-urgence-text { display: flex; flex-direction: column; gap: 3px; }
    .rigo-urgence-label strong { color: #5C4000; }
    .rigo-urgence-timer { font-size: 12.5px; color: #9CA3AF; }
    .rigo-urgence-timer strong { color: #D97706; font-variant-numeric: tabular-nums; }
    </style>

    <?php if ($show_timer): ?>
    <script id="rigo-urgence-js">
    (function () {
        var el = document.getElementById('rigo-urgence-hms');
        if (!el) return;
        var target = <?php echo (int)$seconds_left; ?>; // secondes restantes au chargement de page
        var start  = Math.floor(Date.now() / 1000);

        function update() {
            var elapsed  = Math.floor(Date.now() / 1000) - start;
            var remaining = target - elapsed;
            if (remaining <= 0) {
                // Après 14h → changer le message
                var wrap = document.getElementById('rigo-urgence-wrap');
                if (wrap) {
                    wrap.innerHTML = '<div class="rigo-urgence-icon" aria-hidden="true">🚚</div>'
                        + '<div class="rigo-urgence-text"><span class="rigo-urgence-label">'
                        + 'Commandez maintenant — expédié <strong>demain matin</strong> 📦</span></div>';
                }
                return;
            }
            var h = Math.floor(remaining / 3600);
            var m = Math.floor((remaining % 3600) / 60);
            var s = remaining % 60;
            el.textContent = pad(h) + ':' + pad(m) + ':' + pad(s);
            setTimeout(update, 1000);
        }
        function pad(n) { return n < 10 ? '0' + n : String(n); }
        update();
    })();
    </script>
    <?php endif; ?>
    <?php
}, 40);
