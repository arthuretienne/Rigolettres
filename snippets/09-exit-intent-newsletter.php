<?php
/**
 * [Rigolettres] Exit-intent newsletter popup
 *
 * Popup qui s'affiche quand la souris quitte la fenêtre vers le haut
 * (exit intent desktop) ou après 45 s sur mobile. Affiché une seule fois
 * par session (localStorage). Pas de plugin nécessaire.
 *
 * Source : audit.md Sprint 2 (quick win)
 * Scope : front-end
 */
add_action('wp_footer', function () {
    if (is_checkout() || is_cart()) return;
    ?>
    <div id="rigo-popup-overlay" aria-hidden="true"></div>
    <div id="rigo-popup" role="dialog" aria-modal="true" aria-labelledby="rigo-popup-title" aria-hidden="true">
      <button class="rigo-popup-close" aria-label="Fermer" type="button">✕</button>
      <div class="rigo-popup-pato">
        <img src="<?php echo esc_url(home_url('/wp-content/uploads/2026/04/og-rigolettres-default.png')); ?>" alt="" aria-hidden="true" style="display:none">
        <svg viewBox="0 0 80 80" fill="none" aria-hidden="true">
          <circle cx="40" cy="40" r="38" fill="#FBF8F1" stroke="#E7E2D5" stroke-width="2"/>
          <text x="40" y="52" text-anchor="middle" font-size="36">🐶</text>
        </svg>
      </div>
      <p class="rigo-popup-eyebrow">La lettre de Brigitte</p>
      <h2 id="rigo-popup-title">Un conseil par mois pour aider un enfant à lire.</h2>
      <p class="rigo-popup-sub">Pas de promo agressive. Des astuces concrètes venues du cabinet, et les nouveautés Rigolettres — quand il y en a.</p>
      <form class="rigo-popup-form" id="rigo-popup-form">
        <input type="email" name="email" placeholder="votre.email@exemple.fr" required aria-label="Votre adresse email">
        <button type="submit" class="rigo-popup-btn">Je m'inscris</button>
      </form>
      <p class="rigo-popup-ok" id="rigo-popup-ok" style="display:none">🎉 Merci ! À très bientôt dans votre boîte mail.</p>
      <p class="rigo-popup-tiny">Désinscription en 1 clic. Aucun spam.</p>
    </div>

    <style>
    #rigo-popup-overlay {
      position: fixed; inset: 0; z-index: 10000;
      background: rgba(31,41,55,.55); backdrop-filter: blur(3px);
      opacity: 0; pointer-events: none;
      transition: opacity .3s ease;
    }
    #rigo-popup-overlay.is-open { opacity: 1; pointer-events: all; }

    #rigo-popup {
      position: fixed; z-index: 10001;
      top: 50%; left: 50%; transform: translate(-50%, -44%) scale(.92);
      width: min(480px, 92vw);
      background: #FBF8F1;
      border-radius: 24px;
      padding: 40px 36px 32px;
      box-shadow: 0 24px 64px rgba(31,41,55,.22), 0 4px 16px rgba(31,41,55,.1);
      border: 2px solid #E7E2D5;
      text-align: center;
      opacity: 0; pointer-events: none;
      transition: opacity .32s ease, transform .32s cubic-bezier(.22,.61,.36,1);
    }
    #rigo-popup.is-open { opacity: 1; pointer-events: all; transform: translate(-50%, -50%) scale(1); }
    #rigo-popup[aria-hidden="true"] { visibility: hidden; }
    #rigo-popup.is-open[aria-hidden="false"] { visibility: visible; }

    .rigo-popup-close {
      position: absolute; top: 14px; right: 16px;
      width: 32px; height: 32px; border-radius: 50%;
      border: 0; background: transparent; cursor: pointer;
      font-size: 16px; color: #9CA3AF;
      display: grid; place-items: center;
      transition: background .18s, color .18s;
    }
    .rigo-popup-close:hover { background: #E7E2D5; color: #1F2937; }

    .rigo-popup-pato svg { width: 64px; height: 64px; margin: 0 auto 12px; display: block; }

    .rigo-popup-eyebrow {
      font-size: 11px; font-weight: 800; letter-spacing: .1em;
      text-transform: uppercase; color: #27B4E5;
      margin: 0 0 10px;
    }
    #rigo-popup-title {
      font-family: "Kalam", cursive;
      font-size: 26px; font-weight: 700;
      color: #3a2913; margin: 0 0 12px; line-height: 1.2;
    }
    .rigo-popup-sub {
      font-size: 15px; color: #4B5563; line-height: 1.55;
      margin: 0 0 22px;
    }
    .rigo-popup-form { display: flex; flex-direction: column; gap: 10px; }
    .rigo-popup-form input[type="email"] {
      width: 100%; padding: 13px 16px;
      border: 1.5px solid #E7E2D5; border-radius: 12px;
      font-family: "Nunito", sans-serif; font-size: 15px;
      background: #fff; color: #1F2937;
      outline: none; transition: border-color .18s;
      box-sizing: border-box;
    }
    .rigo-popup-form input:focus { border-color: #27B4E5; }
    .rigo-popup-btn {
      width: 100%; padding: 14px;
      background: #27B4E5; color: #fff;
      font-family: "Nunito", sans-serif; font-weight: 800; font-size: 15px;
      border: 0; border-radius: 9999px; cursor: pointer;
      box-shadow: 0 6px 18px rgba(39,180,229,.38);
      transition: background .18s, transform .15s;
    }
    .rigo-popup-btn:hover { background: #1E92BC; transform: translateY(-1px); }
    .rigo-popup-ok { font-weight: 700; color: #8BC84B; font-size: 16px; margin: 8px 0; }
    .rigo-popup-tiny { font-size: 12px; color: #9CA3AF; margin: 12px 0 0; }
    </style>

    <script>
    (function() {
      var KEY = 'rigo_nl_dismissed';
      if (localStorage.getItem(KEY)) return;

      var overlay = document.getElementById('rigo-popup-overlay');
      var popup   = document.getElementById('rigo-popup');
      var form    = document.getElementById('rigo-popup-form');
      var okMsg   = document.getElementById('rigo-popup-ok');
      if (!popup) return;

      function open() {
        if (localStorage.getItem(KEY)) return;
        popup.classList.add('is-open');
        popup.setAttribute('aria-hidden', 'false');
        overlay.classList.add('is-open');
        document.body.style.overflow = 'hidden';
        popup.querySelector('input[type="email"]').focus();
      }
      function close() {
        popup.classList.remove('is-open');
        popup.setAttribute('aria-hidden', 'true');
        overlay.classList.remove('is-open');
        document.body.style.overflow = '';
        localStorage.setItem(KEY, '1');
      }

      overlay.addEventListener('click', close);
      popup.querySelector('.rigo-popup-close').addEventListener('click', close);
      document.addEventListener('keydown', function(e) { if (e.key === 'Escape') close(); });

      // Exit intent — desktop
      var triggered = false;
      document.addEventListener('mouseleave', function(e) {
        if (triggered || e.clientY > 20) return;
        triggered = true;
        setTimeout(open, 200);
      });

      // Mobile fallback — 45s timer
      var mobileTimer = setTimeout(function() {
        if (!triggered && window.innerWidth < 768) { triggered = true; open(); }
      }, 45000);

      // Form submit — placeholder (à connecter à Brevo/Mailchimp via webhook)
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        var email = form.querySelector('input[type="email"]').value;
        // Log pour debug — remplacer par appel API Brevo
        console.log('[Rigolettres] Newsletter signup:', email);
        form.style.display = 'none';
        okMsg.style.display = 'block';
        localStorage.setItem(KEY, '1');
        setTimeout(close, 3200);
      });
    })();
    </script>
    <?php
}, 50);
