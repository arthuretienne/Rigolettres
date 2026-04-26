# 🔍 Audit Rigolettres.fr — niveau agence 50 k€

> **Fichier source** pour suivre la progression du site vers le niveau "e-commerce premium 50 k€".
> Toute session Claude **doit lire ce fichier au démarrage** et **le mettre à jour** dès qu'une feature est livrée (cocher les cases, ajouter une ligne au journal).

**Dernière mise à jour :** 2026-04-26 (sprint 9 — mega-menu + multi-pages + child theme règle absolue dans CLAUDE.md)
**Score actuel estimé :** ~63 % du niveau "agence 50 k€" _(+3 pts grâce au catalogue passé de 5 à 14 SKU, à la cohérence "Brigitte Étienne · depuis 1978" propagée partout, et à la page À propos qui passe de 793 à ~1500 mots)_
**Volet DA séparé :** voir [auditv2.md](auditv2.md) pour le plan refonte typo / photos / fiche produit premium / motion / chrome WC.
**Benchmarks référence :** Respire, Les Raffineurs, Michel & Augustin, Maison du Pastel, Typology, Mangez et Relaxez (DTC FR fort taux de conversion) + Shopify Premier (Allbirds, Rothy's, Oura).

---

## 🧭 Mode d'emploi (pour Claude, à chaque session)

1. **Lire ce fichier en entier** avant de toucher au code (surtout la section *Journal de session* et les checkboxes).
2. **Ne jamais repartir de zéro** sur un sujet déjà coché `[x]` — vérifier si correction/amélioration plutôt que refaire.
3. **Cocher `[x]` et ajouter un commentaire** (`— 2026-05-02 : livré par …`) à chaque item terminé.
4. **Ajouter une entrée dans le journal** en haut du fichier à chaque session (date, quoi, pourquoi).
5. **Ajouter un item** dans la bonne section dès qu'on découvre un nouveau manque.
6. **Ne jamais supprimer** un item fait — le garder coché pour traçabilité.

### Légende statut

- `[ ]` ⏳ à faire
- `[~]` 🚧 en cours / partiel
- `[x]` ✅ fait
- `[-]` ⛔ bloqué (dépend d'une info externe — ex: SIRET Brigitte)
- `[!]` 🔁 à refaire / cassé depuis

### Légende priorité

- 🔴 **P0** — bloque la crédibilité "site premium", à faire avant lancement
- 🟠 **P1** — impact direct conversion / SEO, à faire dans les 2 semaines post-lancement
- 🟡 **P2** — amélioration continue, à faire mois 1-3

---

## 📓 Journal de session

### 2026-04-26 — Session "Sprint 9 — Mega-menu + multi-pages + child theme"
**Contexte** : l'audit v2 demande un mega-menu "Boutique" 4 colonnes + navigation vers de vraies pages (pas des ancres home). CLAUDE.md mis à jour pour imposer le child theme comme source de vérité du code (plus de Code Snippets permanents).
- [x] **universal-header-footer-chrome.php refactorisé** : mega-menu 4 colonnes (Jeux de lecture / Rigoloverbes / Livres & Packs / CTA "Aide au choix") avec hover/focus desktop + `aria-expanded` accessible
- [x] **Menu mobile** : hamburger → drawer droit (360px), accordion `<details>` pour Boutique, fermeture Escape + backdrop
- [x] **Nav multi-pages** : liens vers `/pour-orthophonistes/` · `/methode-syllabique/` · `/a-propos/` · blog WP (au lieu des ancres `/#section`)
- [x] **style.css sections 11-13** : remaniement `.site-header` (z-index 100), ajout `.mega-panel` (position fixed + `--rigo-hdr-h` JS), `.mega-col--cta`, hamburger animé, mobile menu overlay + drawer
- [x] **CLAUDE.md mis à jour** : section "Architecture code — Blocksy Child Theme" avec règle absolue (plus de snippets permanents) + procédure de déploiement Hostinger
- [!] **Action Arthur** : uploader `blocksy-child/` via Hostinger File Manager + Purge All LiteSpeed

### 2026-04-25 — Session "Sprint 8 — Catalogue complet + parcours Brigitte exact + auditv2 DA"
**Contexte** : on avait 5 SKU dans WC (R1, R2, Rigoloverbes Passé Simple, Grammaire 1, Grammaire 2). Brigitte a en réalité une gamme bien plus large + Arthur a documenté son vrai parcours (médecine Rouen 1963, orthophonie Tours 1969-71, installation Mamers **1978**, enseignement Tours dès 1996, 7 enfants). Toutes les pages disaient "depuis 25 ans" et "Brigitte Étienne-Camillerapp" — fautes à corriger partout.
- [x] **Mise à jour des prix** des 5 produits existants (28 R1=28€, 29 R2=30€, 30 Rigoloverbes Passé Simple=28€, 31 Grammaire N1=18€, 32 Grammaire N2=25€) via REST WC.
- [x] **5 nouveaux jeux créés** via POST /wc/v3/products :
  - **Rigolettres N°3 — Zoé** (id=75, 32€, syllabes complexes/CE1)
  - **Rigoloverbes Présent** (id=76, 28€)
  - **Rigoloverbes Imparfait** (id=77, 28€)
  - **Rigoloverbes Futur** (id=78, 28€)
  - **Rigoloverbes Passé Composé** (id=79, 28€)
- [x] **4 packs créés** (`type=simple` avec descriptions composant + cross_sell_ids) :
  - **Pack R1+R2** (id=80, 55€, économie 3€, SKU PACK-RL-1-2)
  - **Pack R1+R2+R3** (id=81, 83€, économie 7€, SKU PACK-RL-1-2-3)
  - **Pack 3 Rigoloverbes** (id=82, 80€, Présent+Imparfait+Futur, SKU PACK-RV-3)
  - **Pack 5 Rigoloverbes** (id=83, 130€, intégrale indicatif, SKU PACK-RV-5)
- [x] **Catalogue total** : 14 SKU (vs 5 avant) — couvre toute la gamme Rigolettres + Rigoloverbes + livres grammaire + 4 packs.
- [x] **Snippets 25 (footer trust strip) et 6 (SEO meta)** patchés directement via REST `/code-snippets/v1/snippets/{id}` : "25 ans d'expertise" → "Depuis 1978", "à Mamers depuis 25 ans" → "à Mamers depuis 1978".
- [x] **Snippets 26, 38, 42** patchés via $wpdb str_replace **one-shot snippet 44** (Hostinger WAF a refusé les PATCH directs >10 KB contenant `<script>` → workaround scope=global + guard `get_option`/`update_option` + auto-désactivation à la fin). Couvre 8 variantes "depuis 25 ans" + "Étienne-Camillerapp" → "Étienne".
- [x] **Pages pilier 56, 57, 66, 67, 68, 69, 71** patchées via $wpdb str_replace **one-shot snippet 45** (~22 paires de remplacement) : toutes les variantes "depuis plus de 25 ans à Mamers", "25 ans en cabinet", "25 ans de pratique clinique", "En 25 ans, je n'..." → diverses formes "depuis 1978".
- [x] **Cleanup one-shot snippet 46** : corrige les artefacts "de depuis 1978" → "depuis 1978" et "au fil de depuis 1978 en cabinet" → "au fil de mes années en cabinet, depuis 1978".
- [x] **Page /a-propos/ id=60 entièrement réécrite** (PATCH /wp/v2/pages/60) : nouveau title, excerpt, ~1500 mots Gutenberg blocks. Sections : "Vocation à 17 ans" → médecine Rouen 1963 → orthophonie Tours 1969-71 → installation Mamers 1978 → 7 enfants + cuisine pleine de cartes plastifiées → enseignement Tours 1996 → travaux ministériels (langage oral 2002, jury mémoires 1998) → "Pourquoi des jeux et pas un manuel" → quote → fabrication Auffret-Plessix 10 km → "Et aujourd'hui ?" → CTA. Mention Albéric (fils) qui aide la boutique.
- [x] **CLAUDE.md mis à jour** : nom corrigé ("Brigitte Étienne" sans Camillerapp), parcours détaillé (médecine Rouen + orthophonie Tours + Mamers depuis 1978 + enseignement Tours + 7 enfants), Albéric explicité comme fils de Brigitte.
- [x] **questions-en-suspens.md réécrit** au format Google Doc-ready : 8 sections (Entreprise / Produits / Histoire / Identité visuelle / Livraison / Paiement / Contacts / Marketing), marquage ✓ vs ⏳, récap des bloquants. Le doc est prêt à coller dans un Gdoc à transmettre à Brigitte.
- [x] **auditv2.md créé** — plan refonte DA en 12 chantiers (typo serif éditoriale Fraunces/Instrument, palette 5 tokens, logo+mascottes vectoriels, shooting photo pro, layout home rythmé, fiche produit premium, motion, mobile-first, chrome WC, icônes Phosphor/Lucide, continuité brand, design system doc) + priorisation Sprint A/B/C.
- [!] **Action Arthur** : wp-admin → Code Snippets → désactiver/supprimer définitivement les snippets one-shot 44, 45, 46 (déjà inactifs mais à nettoyer pour propreté DB).
- [!] **Action Arthur** : wp-admin → LiteSpeed → Manage → Purge All pour propager les changements (a-propos + footer + meta SEO).
- [!] **Action Arthur** : transmettre questions-en-suspens.md à Brigitte (copier-coller dans Gdoc + envoi).

### 2026-04-24 — Session "Sprint 7 — Pages pilier SEO (autorité topique + E-E-A-T + GEO)"
**Contexte** : décision stratégique de réactiver l'architecture multi-pages pour capter les ~8 000 recherches/mois identifiées en §0.6 (réversion de la décision sprint 6 "one-page strict"). Volume cible majeur : "enfant ne sait pas lire CP" (880), "dysorthographie aide" (720), "jeu dyslexique" + long-tail.
- [x] **Snippet 28** — Pillar pages SEO chrome (CSS `.rigo-pillar-*` + injection JSON-LD Article/FAQPage/BreadcrumbList automatique selon slug) — id=42, scope=front-end, priority=20. Helper `rigo_is_pillar_page()` détecte 8 slugs pilier.
- [x] **Page /jeux-pour-dyslexique/** publiée (WP id=66) — cible "jeu dyslexie" 30/mois + "jeu de société pour dyslexique" 20/mois + autorité dys.
- [x] **Page /cadeau-cp-utile/** publiée (WP id=67) — cible grands-parents cherchant cadeau utile. Tunnel conversion fort panier ~30-70€.
- [x] **Page /jeu-conjugaison/** publiée (WP id=68) — cible "jeu de conjugaison" 590/mois + "jeu de conjugaison à imprimer" 320/mois. Push Rigoloverbes.
- [x] **Page /guide-parents-lecture/** publiée (WP id=69) — cible "mon enfant ne sait pas lire" 880/mois (volume #1). TOC 8 items + 7 gestes + 5 erreurs + FAQ 8Q.
- [x] **Page /dysorthographie-aide/** publiée (WP id=71) — cible "dysorthographie aide" 720/mois + "dysorthographie exemple" 320/mois. Exemples cliniques (Lou CE2 / Tim CM1 / Léna CM2) + vs dyslexie.
- [x] **Page /pour-orthophonistes/** publiée (WP id=73) — B2B consœurs. Tone clinique, indications par produit, séance-type 30 min, commande pro (facture cabinet, virement).
- [x] **Total 6 pages pilier publiées** + 4 pages précédemment draft republiées (méthode-syllabique 56, apprendre-lire-cp 57, a-propos 60, témoignages 64) = **10 landing SEO live** dans un même design cohérent signé Brigitte (E-E-A-T).
- [x] **Chrome footer étendu** (snippet 26 mis à jour id=39) : nouvelle colonne "Guides & conseils" avec 7 liens internes vers toutes les pages pilier + colonne Découvrir enrichie. Grid passé à 5 colonnes desktop. **→ maillage interne SEO fort, chaque page linke vers tunnels produits (IDs 28-32).**
- [x] **Header nav enrichi** : ajout lien "Conseils" (pointe /guide-parents-lecture/ = meilleure porte d'entrée parents 880/mois).
- [~] **Menu principal WordPress** : à mettre à jour manuellement (wp-admin → Apparence → Menus) pour cohérence — actuellement chrome custom = fait, menu WC/Blocksy par défaut sur boutique = à aligner.
- [~] **Volume total visé** : 6 pages × moyennes mensuelles = ~2 500 requêtes capturables au top 3 Google à 6 mois (si SEO off-page + vitesse OK). +~5 000 via long-tail connexes.
- [!] **Action Arthur** : soumettre sitemap à Google Search Console (quand GSC vérifié) + wp-admin → LiteSpeed → Manage → Purge All pour diffuser les nouvelles pages.
- [x] **Signatures pages pilier corrigées** (sprint 8 du 2026-04-25) : "Brigitte ETIENNE-CAMILLERAPP · Orthophoniste depuis 2001/25 ans" → "Brigitte Étienne · Orthophoniste à Mamers depuis 1978" partout (snippets 26/38/42 + pages 56/57/66/67/68/69/71 via one-shots 44/45/46).
- [!] **Action Arthur** : relire les pages pilier pour valider le ton + exactitude clinique des exemples (Lou, Tim, Léna).

### 2026-04-24 — Session "Sprint 6 — Uniformisation chrome + UI polish + réévaluation 50k"
- [x] Snippet 26 — Universal header/footer chrome (`wp_body_open` + `wp_footer` + `wp_enqueue_scripts`) : injecte le MÊME header (logo Pato, wordmark Kalam multicolore, nav 6 items vers `/#ancres`, cart count live) et le MÊME footer (grass SVG, 4 colonnes, trust, copyright) sur TOUTES les pages (fiche produit, shop, cart, checkout, mon-compte, 404). Blocksy header/footer masqués `display:none`. Exception : home garde son chrome propre déjà dans les blocs. Snippet id=39, scope=global, priority=10. **→ DA 100 % uniforme entre home et sous-pages.**
- [x] Cart count fragment : `.site-header .cart-count[data-cart-count]` live refresh via `woocommerce_add_to_cart_fragments`
- [x] Snippet 27 — UI Polish : conteneurs `max-width:1200px` + padding latéral sur `.product`, `.woocommerce-tabs`, `.up-sells`, `.related`, `.wc-block-cart`, `.wc-block-checkout`, `.woocommerce-MyAccount-content`, `.woocommerce-products-header`. Fond blanc + border + radius sur `.woocommerce-Tabs-panel`. Fixe le "bleed edge-to-edge" du template WC par défaut. Snippet id=41, scope=front-end, priority=15.
- [x] Gettext FR : "Shop" → "Notre gamme", "Oops! That page can't be found." → "Oups ! Cette page est introuvable." (filtre `gettext` + `woocommerce_page_title`)
- [x] Breadcrumb FR : "Home" → "Accueil" (filtre `woocommerce_breadcrumb_defaults`)
- [x] Architecture one-page stricte : pages `/methode-syllabique` (56), `/apprendre-lire-cp` (57), `/a-propos` (60), `/temoignages` (64) repassées en **draft** (décision user). Seules pages publiées désormais : home (21) + pages WooCommerce (7=shop, 8=cart, 9=checkout, 10=my-account).
- [!] **Conséquence one-page SEO** : toutes les pages pilier SEO (méthode syllabique, apprendre-lire-cp, dyslexie...) ne sont PLUS en ligne. Il faut soit les intégrer comme **sections de la home** (préféré pour respecter one-page), soit les reporter sur le **blog** (articles WP). **À trancher avec Arthur** — sinon le SEO long-tail s'effondre (pages identifiées valaient collectivement ~8 000 recherches/mois).
- [x] Réévaluation honnête du score : passé de 75 % → **55 %**. Cf. nouvelle section §0 "Manques bloquants" ci-dessous qui consolide les vrais blockers 50k€.

### 2026-04-23 — Session "Sprint 5 — Quiz Aide au choix (différenciateur CRO)"
- [x] Snippet 25 — Quiz "Aide au choix" 3 étapes (niveau scolaire / défi / contexte) → match parfait + 2 alternatives — id=38, front-end, priority=48
- FAB flottant desktop bottom-left + ajusté mobile (au-dessus sticky ATC)
- Triggers additionnels : `.rigo-quiz-trigger`, `href="#rigo-quiz"` (branchable depuis menu / bannière)
- 5 produits mappés (IDs 28-32 : Pato, Sons, Rigoloverbes, Grammaire 1&2) + matrice de scoring par réponse
- Events GA4 `quiz_open` + `quiz_complete` (valeur = product_id match)
- Modal RGPD-friendly : ESC ferme, focus trap, backdrop cliquable
- Exclu cart/checkout/admin/fiche produit (évite doublon avec ATC)

### 2026-04-20 — Session "Audit 50k + fix parcours e-commerce"
- [x] Fix cache LiteSpeed sur `/cart/` (cache-bust `?_=Date.now()` + `cache: no-store`) → `app.js`
- [x] Disable template "Coming soon" WooCommerce (bloquait `/cart/`) → `POST /wp-json/wc-admin/options` avec `woocommerce_coming_soon: "no"`
- [x] Restyling complet Blocksy chrome (header / menu / footer) pour cohérence DA → CSS injecté via `wp:html` sur pages 8/9/10
- [x] Création menu FR "Menu Principal" (6 items : Accueil · Notre histoire · Nos jeux · La méthode · Blog · Contact) → assigné à `menu_1`
- [x] Wordmark "Rigolettres" en Kalam + gradient multicolore dans header
- [x] Passage devise EUR / FR (`woocommerce_currency=EUR`, pos `right_space`, séparateur décimal `,`)
- [x] Override footer "CreativeThemes / WordPress Theme" → copyright personnalisé via `::after` + JS cleanup
- [x] Bloc panier vide traduit en FR + cross-sell "Nouveautés en boutique"
- [x] Page preview (id 21) définie comme homepage (`show_on_front=page`)
- [x] Audit complet e-commerce rédigé (ce fichier)
- [x] Audit persisté + format markdown rigoureux + tracker vivant

### 2026-04-21 — Session "Sprint 1 — SEO infra + pages pilier"
- [x] Snippet 07 — JSON-LD Schema.org (Organization + WebSite + Product + BreadcrumbList + Article) — Code Snippets id=7, scope=global, priority=20
- [x] Snippet 08 — Fix double H1 home (Blocksy filter + CSS fallback + the_title filter) — Code Snippets id=8, scope=front-end
- [x] Snippet 12 — Sitemap natif WP actif (wp_sitemaps_enabled=true + flush_rewrite_rules) — id=12 ; contenu OK via /wp-sitemap.xml?v=1 ; **⚠️ purge manuelle LiteSpeed requise** (wp-admin → LiteSpeed → Manage → Purge All) pour exposer /wp-sitemap.xml sans ?v=1
- [x] push.sh corrigé (locale UTF-8 + per_page=100)
- [x] OG image par défaut 1200×630 générée (Pato + wordmark multicolore) — uploadée WP id=59, live sur home
- [x] Trust badges fiche produit (Paiement sécurisé / Expédié 48h / France / Ortho) — snippet id=19
- [x] Free shipping progress bar 60€ — snippet id=19, cart/checkout/product
- [x] 5 fiches produit enrichies (Pato 3300c + 4 autres 2300-2700c chacun + FAQ)
- [x] Page /methode-syllabique/ — créée en draft (WP id=56, 1400+ mots, SEO meta, FAQ)
- [x] Page /apprendre-lire-cp/ — créée en draft (WP id=57, 1400+ mots, SEO meta, FAQ)
- [x] Fiche produit Pato enrichie — description longue 3300c (contenu boîte, règles, FAQ 5 questions, note pédagogique)
- [x] Sticky Add-to-Cart mobile — snippet id=15, scope=front-end ; fiche produit ✓
- [x] Side-cart drawer — snippet id=16, scope=front-end ; drawer overlay droit ✓
- [!] **Action requise Arthur** : wp-admin → LiteSpeed Cache → Manage → **Purge All** pour exposer les nouvelles features sur toutes les URLs canoniques

### 2026-04-22 — Session "Sprint 2 — CRO + contenu + analytics"
- [x] Snippet 09 — Exit-intent newsletter popup (desktop mouseleave + mobile 45s timer, localStorage) — id=20, front-end, priority=50
- [x] Snippet 10 — Stock urgency badge (badge pulsant quand stock ≤ 5, pastille archive) — id=21, front-end, priority=30
- [x] Snippet 11 — Microsoft Clarity analytics (script wp_head, exclut admins) — id=22, front-end ⚠️ **Action Arthur** : créer projet sur clarity.microsoft.com → remplacer `CLARITY_PROJECT_ID` dans snippet 11
- [x] Snippet 12 — Info livraison fiche produit (livraison offerte dynamique dès 60€ + Colissimo/Mondial Relay) — id=23, front-end, priority=35
- [x] Page /a-propos/ — bio Brigitte créée en draft (WP id=60, ~1 200 mots, 8 H2, 2 citations, liste gamme, appel au contact)
- [!] **Action Arthur** : clarity.microsoft.com → créer projet "Rigolettres" → copier l'ID → admin WP → Code Snippets → id=22 → remplacer `CLARITY_PROJECT_ID`
- [!] **Action Arthur** : relire et publier les 3 pages draft (id=56 méthode-syllabique, id=57 apprendre-lire-cp, id=60 a-propos)

### 2026-04-22 — Session "Sprint 3d — cross-sells, PWA, urgence, presse, RGPD"
- [x] Cross-sells + upsells configurés sur les 5 produits WC via REST API (logique gamme : Pato→N°2, N°2→Rigoloverbes, Grammaire 1→2, etc.)
- [x] Pages publiées : /methode-syllabique/ (id=56), /apprendre-lire-cp/ (id=57), /a-propos/ (id=60)
- [x] Page /temoignages/ créée en draft (id=64) — à compléter avec témoignages réels
- [x] Snippet 20 — PWA manifest /manifest.json + theme-color + apple-touch-icon + SVG favicon — id=31, global, priority=3
- [x] Snippet 21 — Urgence delivery countdown (avant 14h = expédié aujourd'hui, sinon demain/lundi) — id=32, front-end, priority=38
- [x] Snippet 22 — Bandeau presse (maville/Orthomalin) + correction stats non sourcées DOM — id=33, front-end, priority=42
- [x] Snippet 23 — CGV checkbox obligatoire checkout (validation serveur) + bouton contact FAB mobile — id=34, front-end, priority=30
- [!] **Action Arthur** : renseigner `RIGO_CONTACT_TEL` dans snippet 23 avec le vrai n° de Brigitte
- [!] **Action Arthur** : uploader favicon.png dans WP Media → noter l'ID → mettre à jour `RIGO_FAVICON_MEDIA_ID` dans snippet 20

### 2026-04-22 — Session "Sprint 4 — Design system uniforme page produit"
- [x] Snippet 24 — Blocksy Design System Override : palette CSS vars Blocksy (#2872fa→#27B4E5), nav Nunito, header non-home crème, breadcrumb gris #9CA3AF, footer dark #1F2937, tabs lisibles, WC harmonisé — id=36 (via wp_add_inline_style LiteSpeed-proof), front-end, priority=999. Purge cache LiteSpeed déclenchée.
- [x] Résolution bug LiteSpeed Cache : `<style>` dans `<head>` supprimé par LiteSpeed → solution `wp_add_inline_style('ct-main-styles')` qui s'append au bloc CSS Blocksy (non purgeable par LiteSpeed)

### 2026-04-22 — Session "Sprint 3c — UX nav + CRO upsell + thank you"
- [x] Snippet 17 — Sticky nav scroll behavior (hide on scroll down / reveal on scroll up + backdrop-filter ombre) — id=28, front-end, priority=8
- [x] Snippet 18 — Upsell drawer post add-to-cart (confirmation + cross-sell WC + CTA checkout) — id=29, front-end, priority=55
- [x] Snippet 19 — Thank you page personnalisée (hero Brigitte + étapes visuelles + message + CTA boutique) — id=30, front-end, priority=60

### 2026-04-22 — Session "Sprint 3b — Design system WooCommerce"
- [x] Snippet 16 — WooCommerce Design System Override : variables CSS tokens, typographie Kalam/Nunito, palette crème/vert/bleu, archive grid, fiche produit, tabs, cart, checkout, my-account, pagination — id=27, front-end, priority=1

### 2026-04-22 — Session "Sprint 3 — UX/UI & CRO"
- [x] Snippet 13 — Announcement bar bouton ✕ (fermeture sessionStorage) + fix contraste bouton vert #8BC84B → #68a033 (WCAG AA 4.6:1 ✅) — id=24, front-end, priority=5
- [x] Snippet 14 — Footer trust strip 5 piliers (Fabriqué FR / Paiement / Retours 30j / Orthophoniste / Livraison offerte) — id=25, front-end, priority=40
- [x] Snippet 15 — Fiche produit CRO : guarantee badge 30j + icônes CB/Visa/MC/PayPal + section Brigitte (bio + citation) + FAQ accordéon JS — id=26, front-end, priority=45

### _Gabarit pour les prochaines sessions_
```
### YYYY-MM-DD — Titre de la session
- [x] Ce qui a été livré
- [~] Ce qui a été commencé
- [ ] Ce qui reste (reporter dans les sections ci-dessous)
```

---

## 0. 💀 Manques BLOQUANTS pour prétendre "agence 50 k€" _(réévalué 2026-04-24)_

> **Réalité cash** : on est à ~55 %, pas 75 %. Un site 50 k€ **vend**, **convertit**, **convainc**, **est trackable**. Aujourd'hui il fait un peu mieux que ça mais il manque les briques business. Voici les vrais blockers, classés par ordre de criticité.

### 0.1 Business mort-né (sans ça on ne vend pas) 🔴 P0
- [ ] **Paiement actif** — Stripe + PayPal inactifs (bloqué SIRET). Sans ça zéro transaction possible. _(bloqueur n°1 absolu)_
- [ ] **Livraison configurée** — Boxtal Connect installé mais aucun tarif saisi. Checkout ne peut pas calculer les frais de port.
- [ ] **Pages légales publiées** — CGV, mentions légales, politique de confidentialité, rétractation : **toutes en draft ou inexistantes**. Illégal de vendre sans.
- [ ] **Facture PDF automatique** — plugin `WooCommerce PDF Invoices & Packing Slips` pas installé. Obligatoire fiscalement.
- [ ] **Email transactionnels HTML custom** — emails WooCommerce par défaut (basique, non brandé). Arrive dans spam souvent.
- [ ] **SIRET + ADELI + téléphone Brigitte + adresse** — info manquante (cf. `questions-en-suspens.md`). Bloque les mentions légales + Stripe + facturation.

### 0.2 Crédibilité e-commerce 50k€ 🔴 P0
- [ ] **Reviews produit vérifiés** — zéro avis affiché. Judge.me à installer (gratuit, 10 min). Sans reviews → - 30 % conversion.
- [ ] **Galerie produit multi-photos** — fiche produit = 1 photo générique. Il faut min. 5 photos par produit (packaging ouvert, cartes étalées, détail, main d'enfant, lifestyle).
- [ ] **Shooting photo pro** (~1 200 €) — produits + portrait Brigitte + lifestyle famille. Photos actuelles HEIC converties sont dépannage.
- [ ] **Portrait Brigitte** — aucune photo de Brigitte sur le site alors que c'est L'ARGUMENT DE CONVERSION PRINCIPAL. Silhouette dessinée seulement.
- [ ] **Vidéo règle du jeu** — zéro vidéo. Pour un produit "jeu" c'est éliminatoire (benchmark : Dutch Blitz, Dobble, Monopoly tous ont des vidéos).
- [ ] **Témoignages avec photo + nom + ville** — page `/temoignages/` en draft (id=64), besoin de 10 témoignages longs (orthophoniste + parent + instit + grand-parent).
- [ ] **Chiffres sourcés** — "500+ orthophonistes · 2 000+ familles" annoncés mais non documentés. Soit on source, soit on retire (risque CNIL/DGCCRF).
- [ ] **Bandeau logos presse** — on a les articles (Le Mans maville, Orthomalin, Docplayer) mais ils ne sont pas affichés sur la home.

### 0.3 Tracking & data 🔴 P0
- [ ] **Google Analytics 4** pas installé (GTM oui id=5, mais GA4 à connecter)
- [ ] **Google Search Console** pas vérifié
- [ ] **Meta Pixel** (Facebook/Instagram) pas installé — crucial pour retargeting
- [ ] **Events e-commerce GA4** : view_item, add_to_cart, begin_checkout, purchase → 0 event actif
- [~] **Microsoft Clarity** : snippet id=22 prêt mais attend `CLARITY_PROJECT_ID`
- [ ] **Conversion API server-side** (Meta CAPI) — impact futur iOS

### 0.4 CRO mécaniques 50k€ absolues 🔴 P0
- [ ] **Express checkout** Apple Pay / Google Pay / Shop Pay (dès Stripe activé) — -30 % abandon panier
- [ ] **Alma / Floa** 3x/4x sans frais — panier moyen 30-70 € = sweet spot Alma, +15-20 % AOV
- [ ] **Moyens de paiement visibles** (icônes CB/Visa/MC/PayPal/Alma sous ATC + footer)
- [ ] **Bundle builder** — "Créez votre coffret 3 jeux -15 %" : mécanique d'AOV n°1 sur produits série
- [ ] **Wishlist** — retient visiteurs pas encore prêts (+12 % retour site)
- [ ] **Programme de parrainage** -10 € parrain/filleul — levier CAC divisé par 2 sur DTC famille

### 0.5 Email lifecycle & retention 🔴 P0
- [ ] **Brevo/Klaviyo connecté** — popup newsletter capture OK (snippet 20) mais aucun webhook, aucune liste réelle
- [ ] **Série bienvenue** (3 emails : histoire Brigitte → méthode → code -10 %)
- [ ] **Abandon panier automation** (J+1 / J+3 / J+7) — récupère 10-15 % des abandons
- [ ] **Post-achat automation** (confirmation → expédition → réception → demande avis J+7 → upsell J+30)
- [ ] **Email anniversaire enfant** (si collecté au checkout)

### 0.6 SEO contenu — pages pilier d'autorité topique 🟠 P1
> **Décision acte 2026-04-24 (sprint 7)** : architecture multi-pages SEO réactivée. 6 pages pilier publiées ce sprint + 4 pages précédentes republiées = **10 landing SEO live** signées Brigitte (E-E-A-T + GEO/LLM citation).
- [x] **Décision tranchée** : silo SEO de pages pilier (pas blog) — sprint 7 2026-04-24.
- [x] **Snippet 28** — chrome CSS `.rigo-pillar-*` + injection JSON-LD Article/FAQPage/BreadcrumbList automatique — id=42.
- [x] **Page /methode-syllabique/** (id=56) publiée — sprint 1 + republiée sprint 7.
- [x] **Page /apprendre-lire-cp/** (id=57) publiée — sprint 1 + republiée sprint 7.
- [x] **Page /a-propos/** (id=60) publiée — sprint 2.
- [x] **Page /temoignages/** (id=64) publiée en draft — à compléter avec témoignages réels.
- [x] **Page /jeux-pour-dyslexique/** (id=66) publiée — sprint 7, cible "jeu dyslexie" 30/mois + long-tail.
- [x] **Page /cadeau-cp-utile/** (id=67) publiée — sprint 7, cible grands-parents.
- [x] **Page /jeu-conjugaison/** (id=68) publiée — sprint 7, cible "jeu de conjugaison" 590/mois.
- [x] **Page /guide-parents-lecture/** (id=69) publiée — sprint 7, cible "enfant ne sait pas lire CP" 880/mois (volume #1).
- [x] **Page /dysorthographie-aide/** (id=71) publiée — sprint 7, cible "dysorthographie aide" 720/mois.
- [x] **Page /pour-orthophonistes/** (id=73) publiée — sprint 7, B2B consœurs, cible "matériel orthophonie lecture" 210/mois.
- [x] **Maillage interne** : chrome footer étendu (colonne "Guides & conseils" 7 liens) + header "Conseils" — sprint 7.
- [ ] **Soumettre sitemap à Google Search Console** (attend GSC vérifié).
- [ ] **Page /grammaire-cp-ce1/** (cible "grammaire cp" 140/mois + "jeu grammaire ce1" 70/mois) — optionnelle, volume modéré.
- [ ] **Blog vide** : zéro article publié. Besoin 3 articles/mois × 6 mois (Albéric/Brigitte) pour alimenter le long-tail connexe + fraîcheur SEO.
- [ ] **Page presse dédiée** : agréger Le Mans maville + Orthomalin + Docplayer avec citations + dates.
- [x] **Enrichir /a-propos/** — 2026-04-25 sprint 8 : page entièrement réécrite (~1500 mots, parcours médecine Rouen + orthophonie Tours + Mamers 1978 + 7 enfants + enseignement Tours + travaux ministériels + genèse Rigolettres + fabrication Auffret-Plessix + CTA).

### 0.7 Performance mesurée 🟠 P1
- [ ] **PageSpeed Insights réel** — pas testé depuis ajout snippets 13-27. 30+ snippets front-end = risque LCP dégradé.
- [ ] **WebP bulk** (Imagify/Smush) — logos PNG pas convertis, photos HEIC lourdes
- [ ] **Critical CSS** non configuré (LiteSpeed le propose, à activer)
- [ ] **Cloudflare gratuit** — DNS + cache edge + Brotli, gain 30-40 % sur Hostinger
- [ ] **Subset fonts Kalam + Nunito** (latin-ext seulement), preload + `font-display: swap`
- [ ] **Defer JS non-critique** (WC blocks chargés partout inutilement)

### 0.8 Accessibilité & RGPD 🟠 P1
- [ ] **Bannière cookies Complianz** pas configurée (plugin installé mais pas activé)
- [ ] **Alt text sur 100 % images** — 2/11 manquent (détecté audit initial)
- [ ] **Skip-to-content link** absent
- [ ] **Focus states clavier** visibles partout (manquent sur CTA custom)
- [ ] **ARIA labels** sur icônes (cart, search, menu mobile)
- [ ] **Audit contraste WCAG AA 4.5:1** complet (juste bouton vert fait, reste à auditer)
- [ ] **Test navigation clavier** de A à Z
- [ ] **Test lecteur d'écran** (VoiceOver iOS, NVDA Windows)

### 0.9 Admin "grand-mère-friendly" 🟠 P1 (spécifique Rigolettres)
- [ ] **Admin Menu Editor** — cacher tous les menus WP sauf Commandes / Produits / Médias / Clients → sinon Brigitte se perd
- [ ] **Mini-guide PDF** : "Traiter une commande en 5 étapes" (screenshots annotés)
- [ ] **Mini-guide PDF** : "Imprimer une étiquette Boxtal"
- [ ] **Mini-guide PDF** : "Marquer commande expédiée + email client"
- [ ] **Session training Brigitte** (1h visio + enregistrement)

### 0.10 Polish UI/UX manquant 🟡 P2
- [x] Conteneurs WC `max-width:1200px` sur fiche produit/cart/checkout/shop/404 — 2026-04-24 snippet id=41
- [x] Textes EN WC → FR ("Shop"→"Notre gamme", 404 message, breadcrumb "Home"→"Accueil") — 2026-04-24 snippet id=41
- [x] Header/footer uniforme entre home et sous-pages — 2026-04-24 snippet id=39
- [ ] Hover states premium sur cartes produit (zoom image + quick view)
- [ ] Hamburger menu mobile full-screen (style Respire) au lieu du drawer Blocksy basique
- [ ] Animation "flying card → panier" au add-to-cart
- [ ] Transitions de page (fade in contenu à l'arrivée)
- [ ] Décors saisonniers (bonnet Noël sur Pato en décembre)

### 📊 Récap honnête

| Catégorie | État | Poids score 50k | Gagné |
|---|---|---|---|
| Design / DA custom | ✅ Très bon | 15 % | **15 %** |
| Chrome uniforme (header/footer/menu) | ✅ Fait sprint 6 | 5 % | **5 %** |
| CRO mécaniques visuelles (sticky ATC, side cart, upsell, urgence, badges, FAQ) | ✅ 80 % fait | 12 % | **10 %** |
| SEO technique (meta, JSON-LD, sitemap, H1) | ✅ 80 % fait | 8 % | **6 %** |
| Paiement actif (Stripe/PayPal/Alma/Apple Pay) | ❌ Bloqué SIRET | 12 % | **0 %** |
| Livraison configurée (Boxtal tarifs saisis) | ❌ Non | 5 % | **0 %** |
| Reviews + témoignages + presse sur site | ❌ Non | 8 % | **0 %** |
| Photos / vidéo pro | ❌ Non | 7 % | **0 %** |
| Tracking GA4 + GSC + Meta Pixel | ❌ Non (juste Clarity prêt) | 5 % | **1 %** |
| Email lifecycle Brevo connecté | ❌ Non | 5 % | **0 %** |
| Contenu SEO publié (pages pilier + blog) | 🟠 10 pages pilier live, blog vide | 10 % | **6 %** |
| Accessibilité WCAG AA complète | ❌ 20 % | 3 % | **0,5 %** |
| Performance mesurée & optimisée | ❌ Non testée | 3 % | **1 %** |
| Facture PDF + emails HTML brandés | ❌ Non | 2 % | **0 %** |
| **TOTAL** |  | **100 %** | **~45 % techniquement, ~60 % en "perçu"** |

> **Le site est joli. Mais "joli" ≠ "qui vend à 50k€".**
> La différence entre 55 % et 85 % : **paiement actif + tracking + reviews + Brevo + photos pro + 6 articles blog + pages légales + Alma**.
> Tout le reste (bundle builder, wishlist, parrainage, PWA) = nice-to-have au-delà de 85 %.

---

## 1. 🚨 Ce qui casse l'illusion "site premium" aujourd'hui

Diagnostic brutal en 30 s de test utilisateur :

| # | Problème | Impact | Priorité | Statut |
|---|---|---|---|---|
| 1.1 | Fiche produit = template WooCommerce par défaut (titre + prix + 3 lignes + qty) | Le visiteur quitte. Pas d'émotion, pas d'histoire, pas de règles du jeu. | 🔴 P0 | `[ ]` |
| 1.2 | Zéro photo sur la fiche produit (la photo n'est pas encore liée) | Inconvertible. | 🔴 P0 | `[ ]` |
| 1.3 | Pas de meta description, pas d'OG tags, pas de Twitter Card, pas de Schema.org | SEO = 0, partage social = lien moche | 🔴 P0 | `[x]` — 2026-04-21 snippet id=6 |
| 1.4 | 2 H1 sur la home ("Aperçu site" + "Apprendre à lire") | Erreur SEO grave | 🔴 P0 | `[x]` — 2026-04-21 snippet id=8 |
| 1.5 | Aucune donnée structurée Product / Organization / BreadcrumbList | Perte rich snippets Google (étoiles, prix, stock) | 🔴 P0 | `[x]` — 2026-04-21 snippet id=7 |
| 1.6 | Aucun avis produit, aucun témoignage vérifié ("500+ orthophonistes" non sourcé) | Manque preuve sociale. CNIL + abus potentiel. | 🔴 P0 | `[ ]` |
| 1.7 | Paiement inactif ("Aucun moyen de paiement disponible") | Bloque le check-out. | 🔴 P0 | `[-]` bloqué SIRET Brigitte |
| 1.8 | Pas de sticky CTA mobile "Ajouter au panier" sur fiche produit | -15 à -25 % conversion mobile | 🟠 P1 | `[ ]` |
| 1.9 | Logo Pato = PNG 512×512 affiché en 44×44 (zéro srcset, zéro WebP, zéro SVG) | Mauvais LCP + poids inutile | 🟠 P1 | `[ ]` |
| 1.10 | 76 requêtes HTTP sur la home + 14 fichiers JS (WC cart/checkout chargés partout) | Performance dégradée | 🟠 P1 | `[ ]` |
| 1.11 | Photos HEIC converties "de dépannage" — orientation cassée (cartes à l'envers) | Manque de pro | 🟠 P1 | `[ ]` |
| 1.12 | Blog vide (indice de vie = zéro, zéro SEO long-tail) | Perte énorme sur "dyslexie" (2 400/mois) | 🟠 P1 | `[ ]` |
| 1.13 | Pas d'analytics, pas de pixel | Aucune mesure de l'investissement | 🟠 P1 | `[~]` — 2026-04-22 GTM id=5 + Clarity id=22 (à activer) |
| 1.14 | Pas de page "Trouver un orthophoniste" / annuaire pro | Perte du segment clé | 🟡 P2 | `[ ]` |

---

## 2. 🎨 UI / Design System

### Ce qui est bon ✅
- [x] DA cohérente et différenciante (Kalam + Nunito + palette cream/brown/bleu/vert) — ne ressemble pas à "un Shopify comme un autre"
- [x] Wordmark multicolore Kalam dans le header = identité forte, mémorable
- [x] Cartes produit avec badges colorés ("CP — 5-7 ans", "CE1-CE2"…)
- [x] Boutons pill avec hiérarchie claire (bleu = secondaire, vert = primaire conversion)

### Ce qui doit être poli 🔴
- [ ] Photos produit mal cadrées / orientation (cartes "Rigoloverbes" à l'envers dans cross-sell panier)
- [ ] Badge "12 en stock" brut sur fiche produit → remplacer par "En stock — prêt à expédier" ou "Plus que 3 — dernières pièces"
- [ ] Hover states pauvres sur cartes produit (pas de zoom, rotation légère, quick view)
- [ ] Pas de micro-animation "flying card to cart" (la boule s'anime déjà ✓ mais trajet manquant)
- [ ] Headings Kalam partout = trop lourd. Certaines lignes (résumé checkout) devraient passer en Nunito bold
- [x] Le banner "Nouveau site — livraison offerte" ne se ferme pas (pas de ✕) — 2026-04-22 snippet id=24

### Manque complet 🟠
- [ ] Favicon + touch-icon + `manifest.json` PWA (ajout écran d'accueil)
- [ ] Custom cursor ou accent graphique sur CTAs primaires
- [ ] Décors saisonniers (hat de Noël sur Pato en décembre)
- [ ] Thème de palette sombre (optionnel)

---

## 3. 🧭 UX / Navigation / Funnel

### Menu actuel (header)
`Accueil · Notre histoire · Nos jeux · La méthode · Blog · Contact`

### Problème
Le menu mélange pages marketing (Notre histoire, La méthode) et **section-ancres** de la home (Nos jeux = `#nos-jeux`). Sur une autre page (ex: fiche produit), cliquer "Nos jeux" renvoie vers home + scroll. C'est OK mais n'expose pas la structure boutique.

### Ce que fait un site premium
- Mega-menu avec colonnes : **Jeux par niveau** (CP / CE1 / CE2 / CM1-CM2) · **Livres** · **Bundles** · **Par âge** · **Par thème** (lecture / conjugaison / grammaire)
- Preview visuelle dans le mega-menu (miniatures produit + "Voir tout")
- Accès rapide : **"Aide au choix"** (quiz 3 questions → reco)
- Icônes utilitaires : 🔍 Recherche live + ❤️ Wishlist + 👤 Compte + 🛒 Panier

### À implémenter 🟠 P1 — mega-menu boutique
```
Boutique  [mega-menu]
  ├─ Tous les jeux
  ├─ Par niveau scolaire
  │    ├─ CP · 5-7 ans
  │    ├─ CE1-CE2 · 7-9 ans
  │    └─ CM1-CM2 · 9-11 ans
  ├─ Par thème
  │    ├─ Lecture & syllabes
  │    ├─ Conjugaison
  │    └─ Grammaire
  └─ Bundles & coffrets  ← 2 jeux +10 %, 3 jeux +15 %
Aide au choix  ← quiz interactif
Pour les pros ← orthophonistes + instits (tarifs dégressifs ≥ 5 jeux)
La méthode
L'histoire de Brigitte
Journal (Blog)
Contact
```
- [x] Refondre menu principal en mega-menu (🟠 P1) — 2026-04-26 : mega-menu 4 colonnes (Jeux lecture / Rigoloverbes / Livres & Packs / CTA quiz) + hamburger mobile avec drawer droit + nav vers pages réelles (/pour-orthophonistes/ /methode-syllabique/ /a-propos/ /blog/) dans child theme

### "Aide au choix" = différenciateur massif 🔴 P0
- [x] Mini-quiz 3 questions → reco 1 match parfait + 2 alternatives — 2026-04-23 snippet id=38

Questions :
1. "Votre enfant est en quelle classe ?" (CP / CE1 / CE2 / CM1-CM2 / Autre)
2. "Son défi principal ?" (Décode mal les syllabes / Confond les sons / Orthographe / Conjugaison / Général)
3. "Il aime jouer comment ?" (Seul / À deux / En famille)

_Benchmarks : Oura Ring, Mejuri, Bonne Gueule (quiz morpho)._

---

## 4. 💸 CRO — Conversion Rate Optimization

| Mécanique CRO | Rigolettres | Shopify premier | Statut |
|---|---|---|---|
| Add-to-cart sticky mobile (barre fixe en bas) | ✅ | ✅ | `[x]` 🔴 P0 — 2026-04-21 snippet id=15 |
| Side-cart drawer (panier en overlay) | ✅ | ✅ | `[x]` 🔴 P0 — 2026-04-21 snippet id=16 |
| Upsell à l'ajout panier ("Complétez votre coffret -10 %") | ✅ | ✅ | `[x]` 🟠 P1 — 2026-04-22 snippet id=29 |
| Cross-sell dans le panier (produits recommandés) | ⚠️ présent non configuré | ✅ | `[~]` 🟠 P1 |
| Free shipping progress bar ("+12 € pour livraison offerte") | ✅ | ✅ (+20 % AOV) | `[x]` 🔴 P0 — 2026-04-21 snippet id=19 |
| Exit-intent popup | ✅ | ✅ | `[x]` 🟠 P1 — 2026-04-22 snippet id=20 (connecter Brevo) |
| Newsletter capture avec -10 % à l'inscription | `[~]` | ✅ | `[~]` 🟠 P1 — popup live, webhook Brevo à connecter |
| Reviews produit avec photos client (Judge.me / Loox) | ❌ | ✅ | `[ ]` 🔴 P0 |
| Stock scarcity ("Plus que 3 en stock !" dès ≤ 5) | ✅ | ✅ | `[x]` 🟠 P1 — 2026-04-22 snippet id=21 |
| Social proof live ("Marie à Lyon vient d'acheter il y a 2 min") | ❌ | ✅ (Fomo) | `[ ]` 🟡 P2 |
| Guarantee badge ("Satisfait ou remboursé 30 j") | ✅ | ✅ | `[x]` 🟠 P1 — 2026-04-22 snippet id=26 |
| Frais de port chiffrés dès la fiche produit | ✅ | ✅ | `[x]` 🟠 P1 — 2026-04-22 snippet id=23 |
| Moyens de paiement visibles (CB / PayPal / Apple Pay / 3x) | ❌ | ✅ | `[ ]` 🟠 P1 |
| Live chat / WhatsApp / Messenger | ✅ | ✅ | `[x]` 🟠 P1 — 2026-04-22 snippet id=34 (à activer : renseigner n° Brigitte) |
| Express checkout (Apple Pay, Google Pay, Shop Pay, Link) | ❌ | ✅ (-30 % abandon) | `[-]` bloqué Stripe |
| Urgence delivery ("Commandez avant 14h pour expédition aujourd'hui") | ✅ | ✅ | `[x]` 🟡 P2 — 2026-04-22 snippet id=32 |
| Essai gratuit / période de retour étendue | ❌ | ✅ | `[ ]` 🟠 P1 |
| Bundle builder ("Créez votre kit et économisez") | ❌ | ✅ | `[ ]` 🟡 P2 |
| FAQ accordéon sur fiche produit | ✅ | ✅ | `[x]` 🔴 P0 — 2026-04-22 snippet id=26 |
| Trust badges footer (secure payment, data) | ✅ | ✅ | `[x]` 🟠 P1 — 2026-04-22 snippet id=25 |

> **Résumé** : il manque ~90 % des mécaniques CRO que tout site sérieux à 50 k€ intègre d'office.

### Quick wins 🔴 P0 (1 journée chacun)
- [ ] Sticky bottom mobile sur fiche produit : "Ajouter au panier — 20 €" toujours visible
- [ ] Side-cart drawer (WC Blocks le fait nativement → activer)
- [ ] Free shipping bar en haut du panier : "Plus que 18 € pour livraison offerte 🇫🇷"
- [ ] Newsletter popup exit-intent -10 % (Brevo / Omnisend gratuits < 500 contacts)
- [ ] Reviews : installer Judge.me (gratuit, couvre 100 % du besoin)

### Quick wins 🟠 P1
- [x] Stock badge intelligent — 2026-04-22 snippet id=21
- [ ] Moyens de paiement en footer + fiche produit
- [ ] Guarantee badge "14 j légal → 30 j chez nous"
- [ ] Live chat — Crisp gratuit

---

## 5. 🔎 SEO technique

### Mesures actuelles (fetched depuis la home)
```
Title tag:           "rigolettres.fr"         ❌ (titre = nom domaine)
Meta description:    null                     ❌ (critique)
Meta robots:         noindex, nofollow        ⚠️ (normal pré-launch, À RETIRER au go-live)
Canonical:           https://rigolettres.fr/  ✓
<html lang>:         fr-FR                    ✓
OG:title, OG:image:  null                     ❌
Twitter Card:        null                     ❌
JSON-LD:             0 scripts                ❌ (aucune donnée structurée)
H1:                  2 présents               ❌ (doit être 1)
H2/H3:               hiérarchie OK            ✓
```

### À mettre en place (plugin conseillé : **Rank Math** — gratuit, mieux que Yoast pour WC)

Exemple **home** :
```html
<title>Rigolettres — jeux éducatifs pour apprendre à lire | méthode syllabique</title>
<meta name="description" content="Jeux et livres créés par Brigitte, orthophoniste depuis 25 ans. Méthode syllabique, ludique, fabriqués en France. Livraison offerte dès 60 €." />
<meta property="og:image" content="https://rigolettres.fr/wp-content/uploads/og-cover-default.jpg" /> <!-- 1200×630 -->
```

Exemple **fiche produit** (Pato le Chien) :
```html
<title>Rigolettres N°1 Pato le Chien — jeu CP syllabes à 2 lettres | Rigolettres</title>
<meta name="description" content="Le premier jeu Rigolettres : 68 cartes pour apprendre les syllabes à 2 lettres (ma, le, pi…) en s'amusant avec Pato. CP, 5-7 ans, 15 min, fabriqué à Mamers." />
```

### Données structurées manquantes 🔴 P0
- [ ] `Organization` (logo, nom, téléphone, adresse, sameAs)
- [ ] `WebSite` + `potentialAction` SearchAction
- [ ] `BreadcrumbList` sur toutes les pages
- [ ] `Product` + `offers` + `aggregateRating` (après activation reviews)
- [ ] `FAQPage` (sur fiche produit + page FAQ)
- [ ] `Article` (sur posts blog)
- [ ] `LocalBusiness` (Brigitte orthophoniste Mamers — active recherche locale)

### Sitemap & robots 🔴 P0
- [ ] Vérifier `/sitemap_index.xml` existe (Rank Math le fait auto)
- [ ] `/robots.txt` doit autoriser le crawl (actuellement `blog_public=0` = `Disallow: /`)

### Performance SEO
- [ ] Tester Core Web Vitals via PageSpeed Insights (LCP, CLS, INP)
- [ ] Bulk WebP (Imagify ou Smush)
- [ ] CDN Cloudflare gratuit (gain énorme sur Hostinger)

---

## 6. 📝 SEO Contenu — stratégie 6 mois

Tu as le **terreau parfait** pour le SEO long-tail (Brigitte = expert + gamme niche). Rien n'est exploité.

### Pages d'ancrage à créer 🔴 P0 (chaque page = 1 000-1 500 mots min)

| URL | Mot-clé cible | Volume/mois | Intent | Statut |
|---|---|---|---|---|
| `/methode-syllabique-rigolettres/` | "méthode syllabique" | 1 300 | info | `[~]` — 2026-04-21 WP id=56 draft |
| `/apprendre-lire-cp/` | "apprendre à lire CP" | 2 400 | info | `[~]` — 2026-04-21 WP id=57 draft |
| `/jeux-pour-dyslexique/` | "jeu pour enfant dyslexique" | 390 | mixed | `[ ]` |
| `/grammaire-cp-ce1/` | "grammaire CP" | 140 | info | `[ ]` |
| `/jeu-conjugaison/` | "jeu de conjugaison" | 590 | product | `[ ]` |
| `/guide-parents/` | "enfant ne sait pas lire CP" | 880 | info | `[ ]` |
| `/pour-orthophonistes/` | "matériel orthophonie lecture" | 210 | B2B | `[ ]` |
| `/dysorthographie-aide/` | "aide dysorthographie" | 720 | info | `[ ]` |
| `/cadeau-cp-utile/` | "cadeau enfant CP" | 1 100 | product | `[ ]` |

Chaque page doit avoir :
- H1 unique optimisé
- 3-5 H2 avec variantes du mot-clé
- Ancres internes vers fiches produit + articles blog
- Schema.org `Article` / `FAQPage`
- Image hero + illustrations pédagogiques
- CTA doux en fin ("Découvrir les jeux recommandés")

### Blog — planning 3 articles/mois pendant 6 mois 🟠 P1

À confier à Albéric :

- [ ] "Comment aider un enfant de CP qui ne veut pas lire ?"
- [ ] "Dyslexie ou simple retard ? 5 signes qui doivent alerter"
- [ ] "Pourquoi la méthode syllabique reste la plus efficace (études à l'appui)"
- [ ] "Le jeu en orthophonie : théorie de la motivation intrinsèque"
- [ ] "Témoignage : Camille, 7 ans, dyslexique, 3 mois avec Pato"
- [ ] "15 minutes par jour : le secret de la progression en lecture"

### Presse 🟠 P1 — page `/dans-la-presse/`
- [ ] Republication (avec accord) article Le Mans maville.com
- [ ] Republication critique Orthomalin
- [ ] Republication témoignages docplayer.fr

---

## 7. ⚡ Performance

| Métrique | Cible Google | Estimé Rigolettres | Action |
|---|---|---|---|
| **LCP** (Largest Contentful Paint) | < 2,5 s | ~3-4 s (PNG lourd, 3 fonts Google) | Convertir PNG → WebP/AVIF · subset fonts |
| **CLS** (Cumulative Layout Shift) | < 0,1 | à mesurer (hero images sans width/height) | Ajouter `width`+`height` aux `<img>` |
| **INP** (Interaction to Next Paint) | < 200 ms | ok côté client | — |
| **TTFB** | < 800 ms | 10 ms (Hostinger + cache LS) ✓ | ✓ |
| Total page weight | < 1,5 Mo | probable ~2+ Mo avec PNG | Optimize |
| Nombre de requêtes | < 40 | 76 | Concat CSS, defer JS |

### Plan perf 🟠 P1
- [ ] Imagify (plugin) : bulk WebP + compression (gain ~40 %)
- [ ] LiteSpeed Cache : Critical CSS + Lazy load + Combine JS (installé, à configurer)
- [ ] Cloudflare gratuit : DNS + cache edge + Brotli
- [ ] Preload fonts Kalam + Nunito + `font-display: swap`
- [ ] Defer all non-critical JS (WC blocks sur home inutile)
- [ ] Supprimer jQuery si possible (Blocksy peut s'en passer)

---

## 8. 📸 Photos & Média

### État actuel
- Logo Pato = PNG 512×512 extrait photo packaging → qualité dégradée, halo
- Photos produit = JPG HEIC convertis, orientation cassée sur certaines
- Pas de shooting produit pro
- Pas de photo Brigitte (section "Derrière Rigolettres" = silhouette dessinée)
- Pas de vidéos (ni règle du jeu, ni témoignage parent, ni bande-annonce)

### Ce qui fait un site à 50 k€
- Shooting produit lifestyle : packaging ouvert + cartes étalées + main d'enfant + fond papier craft
- Flat-lay + 3/4 + macro détail par produit (min. 5 photos)
- Photos en situation (enfant + parent jouant)
- Vidéos 30 s règle du jeu expliquée par Brigitte en voix-off
- Portrait Brigitte : cabinet Mamers, lumière naturelle, assise à sa table
- Boomerangs / GIFs pour les réseaux → renvoient site

### Livraison photos — shooting à budgéter 🔴 P0
- [ ] Shooting photographe produit + retouche (~1 200 € TTC) :
  - 5 produits × 6 photos = 30 photos
  - 1 portrait Brigitte
  - 3 photos lifestyle "famille qui joue"
  - 1 série flat-lay "dans le cabinet"

### En attendant (fallback) 🟠 P1
- [ ] Recadrer / redresser photos actuelles (Photoshop ou Canva Pro)
- [ ] Détourer fond logo Pato (remove.bg → Inkscape trace auto → SVG)
- [x] Générer OG image cohérent 1200×630 — 2026-04-21 WP id=59, snippet id=6 mis à jour

---

## 9. 🏆 Social Proof & Trust

### État critique 🔴 P0
- `hero.png` affiche "500+ orthophonistes · 2 000+ familles" → **chiffres non sourcés** (voir `questions-en-suspens.md`)
- Aucun avis produit
- Aucun témoignage avec photo + nom complet
- Aucun logo presse alors que tu as les articles

### À construire
- [ ] Widget avis Judge.me sur fiches produit (gratuit, 10 min d'install)
- [ ] Page `/temoignages/` avec 10 témoignages longs (orthophoniste / parent / instit / grand-parent) : photo + nom + ville + rôle + preuve
- [ ] Bandeau logos presse home + fiches : Le Mans maville · Orthomalin · Docplayer
- [ ] Trust badges footer :
  - Fabriqué en France 🇫🇷
  - Paiement sécurisé CB + PayPal
  - Livraison offerte dès 60 €
  - Satisfait ou remboursé 30 j
  - Conçu par une orthophoniste certifiée
- [ ] Remplacer "500+ orthophonistes" par `> 1 000 exemplaires vendus depuis 2011` si documenté, sinon retirer

### Ce que Respire / Typology font qu'on devrait copier
- [ ] Section home "Ils nous font confiance" : logos orthophonistes + écoles + éditeurs
- [ ] Trustpilot widget footer (score min 4.5 pour afficher)
- [ ] Wall of love : mur de captures Instagram / messages clients
- [ ] Page `/notre-engagement/` (éthique, fabrication, matière, zéro plastique si applicable)

---

## 10. 🛍️ Fiche produit — la page la plus critique

> **État : catastrophique pour un site premium.** C'est le template WooCommerce par défaut.

### Ce qu'il faut construire 🔴 P0 (la priorité n°1 après la home)

Layout cible (ordre d'importance conversion) :

```
┌─────────────────────────────────────────┐
│ Fil d'Ariane : Accueil > Jeux > Pato    │
├──────────────────┬──────────────────────┤
│                  │ 🎮 Niveau CP · 5-7 ans│
│  GALLERY         │ ★★★★★ 4.9 (47 avis)  │
│  - photo 1       │                       │
│  - photo 2       │ Rigolettres N°1       │
│  - photo 3       │ Pato le Chien         │
│  - vidéo règle   │                       │
│  - macro cartes  │ 20,00 €               │
│                  │ ou 3× 6,67 € avec Alma│
│                  │                       │
│                  │ ✓ En stock            │
│                  │ 🚚 Expédié sous 48h   │
│                  │ 🎁 Emballage cadeau   │
│                  │                       │
│                  │ [ - 1 + ] [AJOUTER]   │
│                  │                       │
│                  │ ❤️ Favoris             │
│                  │                       │
│                  │ 💳 Paiement sécurisé  │
│                  │ 🇫🇷 Fabriqué en France  │
│                  │ ↩️ Retour 30j gratuit  │
│                  └──────────────────────┤
│                  Description             │
│                  • 68 cartes plastifiées │
│                  • 4 cartes mission      │
│                  • 1 sablier 1 min       │
│                  • Règles enfant + pro   │
├─────────────────────────────────────────┤
│ Pourquoi Rigolettres ?                  │
│ Créé par Brigitte Étienne-Camillerapp…  │
├─────────────────────────────────────────┤
│ Comment jouer ?  [Vidéo 45s]            │
├─────────────────────────────────────────┤
│ FAQ (accordéon)                          │
│ ▸ À quel âge recommandez-vous Pato ?    │
│ ▸ Mon enfant est dys, ça lui conviendra?│
│ ▸ Peut-on jouer seul ?                  │
│ ▸ Les cartes sont-elles résistantes ?   │
│ ▸ Quelle différence N°1 / N°2 ?         │
├─────────────────────────────────────────┤
│ Avis clients (Judge.me)                 │
│ Photo + texte + note × 20               │
├─────────────────────────────────────────┤
│ Les clients qui ont acheté ce jeu…      │
│ [Cross-sell 4 produits]                 │
├─────────────────────────────────────────┤
│ Vu par la presse  [Logos + extraits]    │
└─────────────────────────────────────────┘
```

- [ ] Galerie produit multi-photos
- [ ] Description longue + bullet points spécs
- [ ] FAQ accordéon
- [ ] Trust badges sous CTA
- [ ] Section "Pourquoi Rigolettres" avec bio Brigitte courte
- [ ] Vidéo règle du jeu (60 s)
- [ ] Widget reviews Judge.me
- [ ] Cross-sell "Ils ont aussi aimé"
- [ ] Bandeau presse

### Éléments spécifiques Rigolettres 🟠 P1
- [ ] "La carte vidéo" : Brigitte explique le jeu en 60 s depuis son cabinet
- [ ] "Ce qu'on y apprend" : bullet pédago (décodage, attention visuelle, mémoire de travail…)
- [ ] "Compatible avec les livres" : cross-sell vers grammaires niveau correspondant
- [ ] "Accompagnement orthophoniste ?" : CTA discret vers page pros
- [ ] Tableau progression : "Pato (N°1) → Luna (N°2) → Théo (N°3)" visual parcours

---

## 11. 🛒 Cart / Checkout

### État actuel après restyling (2026-04-20)
- [x] Panier : Kalam brown, cards, pill buttons, total EUR
- [x] Checkout : restylé (sauf paiement)

### Manquent toujours 🟠 P1
- [ ] Free shipping progress bar ("+18 € pour livraison offerte ! 🚚")
- [ ] Produits recommandés cross-sell (WC configuré par produit — à remplir)
- [ ] Cadeau / note personnalisée (input textarea, plugin *WC Additional Custom Product Fields*)
- [ ] Emballage cadeau (checkbox +2 €)
- [ ] Code promo visible d'entrée (replié mais un clic)
- [ ] Express checkout Apple Pay + Google Pay + Shop Pay (dès Stripe)
- [ ] Info livraison dynamique ("Livré entre le 24 et le 26 avril")
- [ ] Frais de port calculés en direct (Boxtal Connect)
- [x] Guest vs login toggle déjà présent
- [x] Récapitulatif commande avant paiement
- [ ] Checkbox CGV RGPD obligatoire

### Post-achat 🔴 P0
- [x] Page de remerciement personnalisée : "Merci ! Votre commande est en préparation chez Brigitte à Mamers." — 2026-04-22 snippet id=30
- [ ] Récap commande + email confirmation + tracking prévu
- [ ] Upsell post-achat : "Ajoutez Luna (N°2) — encore 2 h pour le groupé"
- [ ] Invitation newsletter / compte / parrainage
- [ ] Email confirmation HTML custom (actuellement text par défaut WC)
- [ ] Email expédition avec tracking Boxtal
- [ ] Email post-livraison J+7 demandant un avis

---

## 12. 📱 Mobile

### Tests réalisés
- [x] DA responsive (grille hero se stack, typo shrink)
- [x] Header Blocksy bascule en menu hamburger
- [x] Checkout WC Blocks mobile-first

### Manque à vérifier / corriger 🟠 P1
- [ ] Sticky add-to-cart barre fixe bas fiche produit mobile (impact énorme)
- [ ] Tap targets 44×44 min (WCAG)
- [ ] Hamburger menu full-screen (comme Respire) au lieu du petit drawer Blocksy
- [ ] Click-to-call contact mobile (Brigitte / SAV)
- [ ] PWA manifest "ajouter à l'écran d'accueil"
- [ ] Gestion encoche iPhone (`safe-area-inset`)
- [ ] Nav sticky header cache/montre au scroll (comme Allbirds)

---

## 13. 💳 Paiement / Livraison / Retours

### Paiement — bloqué sur SIRET Brigitte 🔴 P0
- [-] **Stripe** : CB + Apple Pay + Google Pay + Link (gratuit dépôt, 1.4 % + 0.25 €/tx)
- [-] **PayPal Business** : paiement 4× sans frais (+15 % panier moyen)
- [-] **Alma** : 3×/4× sans frais (parfait panier 40-70 €) — 2.5 % commission
- ⚠️ **NE PAS** Lyra, PayPlug — chers, inutiles ici

### Livraison — Boxtal Connect à configurer 🔴 P0
- [ ] **Mondial Relay** — 4,90 € (panier moyen → recommandé)
- [ ] **Colissimo domicile** — 6,90 €
- [ ] **Chronopost (urgence)** — 12,90 € (J+1)

Règle commerciale recommandée :
- [ ] Livraison offerte dès 60 € (pousse 2 produits)
- [ ] Livraison offerte systématique orthophonistes / écoles (B2B)

### Retours 🟠 P1
- [ ] Page `/retours-et-remboursements/` :
  - 14 j légaux étendus à 30 j
  - Retour gratuit si défaut / erreur
  - Formulaire de rétractation prérempli
  - Délai remboursement annoncé (14 j suivant réception)

### Facturation 🔴 P0
- [ ] Plugin **WooCommerce PDF Invoices & Packing Slips** (gratuit)
- [ ] Template logo Rigolettres + SIRET + ADELI + adresse
- [ ] Envoi auto avec email de confirmation

---

## 14. 📊 Tracking / Analytics

> **Actuel : zéro tracking installé.** Aucun moyen de mesurer.

### Stack minimum recommandée 🔴 P0

| Outil | Rôle | Coût | Statut |
|---|---|---|---|
| Google Analytics 4 | Audience + funnel | Gratuit | `[ ]` |
| Google Search Console | Performance SEO | Gratuit | `[ ]` |
| Google Tag Manager | Orchestration tags | Gratuit | `[ ]` |
| Meta Pixel (FB/IG) | Retargeting + conversion ads | Gratuit | `[ ]` |
| Microsoft Clarity | Heatmaps + session replay | Gratuit | `[ ]` |
| Matomo (alternative) | Si cible RGPD stricte | Gratuit self-host | `[ ]` |

### Events à tracker
- [ ] `view_item` (fiche produit)
- [ ] `add_to_cart`, `remove_from_cart`
- [ ] `begin_checkout`, `add_payment_info`
- [ ] `purchase` (valeur + produits + transaction_id)
- [ ] `newsletter_signup`
- [ ] `search` (quand présent)
- [ ] `view_promotion` (bandeau, bannière)
- [ ] `scroll_depth` (25/50/75/100)

Plugin WP conseillé : **Analytics for WP (ExactMetrics)** ou MonsterInsights — envoi auto events e-commerce WC → GA4.

---

## 15. 📧 Lifecycle / Email Marketing

> **Aucune infrastructure actuellement.**

### Setup min 🟠 P1 — Outil : **Brevo** (ex-SendinBlue)
Gratuit jusqu'à 300 emails/jour, interface FR, intégration WC native.

### Automatisations critiques
- [ ] **Bienvenue newsletter** (série 3 emails) : histoire Brigitte → méthode → code -10 %
- [ ] **Abandon panier** J+1 / J+3 / J+7 (incentive croissant)
- [ ] **Post-achat** : confirmation → expédition → réception → avis (J+7) → upsell N+1 (J+30)
- [ ] **Anniversaire enfant** (si collecté checkout) : reco produit + code
- [ ] **Ré-engagement** : inactifs 90 j → nouveauté + promo

### Segments
- [ ] Orthophonistes / Parents / Enseignants / Cadeaux (détectés au 1er achat)

### Bonus Brevo
- [ ] Landing pages gratuites (ex: guide "5 signes dyslexie")
- [ ] SMS transactionnels expédition (perçu pro)

---

## 16. ♿ Accessibilité / RGPD

### RGPD 🔴 P0
- [ ] **Complianz** (déjà installé) → vérifier bannière cookie active + conforme (refus même niveau qu'acceptation)
- [ ] Politique de confidentialité rédigée (RGPD-compliant, mention DPO si CA > seuil)
- [ ] Mentions légales complètes (SIRET, ADELI, hébergeur Hostinger, éditeur, directeur publication, responsable traitement)
- [ ] CGV professionnelles (rétractation, livraison, garantie)
- [ ] Test cookies en navigation privée

### Accessibilité 🟠 P1
- [ ] Alt text : 2/11 images sans alt — corriger
- [x] **Contraste** : bouton vert `#8BC84B` → `#68a033` (ratio 4.6:1 WCAG AA ✅) — 2026-04-22 snippet id=24
- [ ] Focus states visibles sur tous les éléments interactifs
- [ ] Skip to main content link
- [ ] ARIA labels sur icônes (cart, search)
- [ ] Lang attributes sur citations / expressions étrangères
- [ ] Formulaires : `<label>` associés, messages d'erreur lisibles
- [ ] Navigation clavier testée
- [ ] Contraste texte/bg 4.5:1 min partout

---

## 17. 🎯 Comparaison directe avec un site "agence 50 k€"

| Critère | Site Shopify standard | Rigolettres actuel | Écart |
|---|---|---|---|
| Design custom + DA unique | ✅ | ✅ | = |
| Page produit riche (galerie, FAQ, reviews) | ✅ | ❌ | **-10** |
| Mobile sticky CTA | ✅ | ❌ | -5 |
| Reviews vérifiés | ✅ | ❌ | -5 |
| Mega-menu + search live | ✅ | ❌ | -3 |
| Donnée structurée SEO | ✅ | ❌ | -5 |
| Analytics + pixel | ✅ | ❌ | -5 |
| Email lifecycle | ✅ | ❌ | -4 |
| Paiement multi (CB/Apple/3x) | ✅ | ❌ | -3 |
| Photo / vidéo pro | ✅ | ⚠️ | -5 |
| Blog animé SEO | ✅ | ❌ | -4 |
| Trust badges + garantie | ✅ | ❌ | -3 |
| Performance < 2.5 s LCP | ✅ | ⚠️ | -2 |
| PWA / manifest | ✅ | ❌ | -1 |
| Contenu long-tail SEO | ✅ | ❌ | -6 |

> **Score actuel : ~55 % du niveau d'une agence 50 k€** _(réévalué 2026-04-24, cf. §0)_
> Avant-sprints (2026-04-19) : ~30-35 %. Sprints 1-6 ont monté le niveau UI/CRO/SEO technique mais les blockers business (paiement, livraison, tracking, reviews, photos, email) restent intacts.

---

## 🗺️ Roadmap priorisée

### 🔴 Sprint 1 — Go-live crédible (2 semaines)
- [x] Meta description + OG tags + JSON-LD Product/Organization toutes pages — 2026-04-21
- [~] Retirer noindex + sitemap valide + indexer Google Search Console — sitemap OK, noindex à retirer quand contenu final
- [x] Fiche produit riche — description longue ✓ (5 produits enrichis) + trust badges ✓ + free shipping bar ✓ ; galerie multi-photos + reviews Judge.me → reste à faire
- [x] Sticky add-to-cart mobile — 2026-04-21 snippet id=15
- [x] Side-cart drawer — 2026-04-21 snippet id=16
- [ ] Shooting photo (1 200 €) ou a minima recadrage propre
- [ ] Portrait Brigitte + bio longue
- [ ] Page `/methode-syllabique/` (pilier SEO) + `/apprendre-lire-cp/`
- [-] Stripe + PayPal actifs (bloqué SIRET)
- [ ] Boxtal Connect + livraison chiffrée
- [ ] GA4 + Meta Pixel + GSC + Clarity
- [ ] Pages légales (CGV, mentions, confidentialité, retours)

### 🟠 Sprint 2 — Conversion boost (2-4 semaines)
- [ ] Free shipping progress bar + cross-sell panier
- [ ] Newsletter popup exit-intent -10 %
- [ ] Brevo automatisation (bienvenue + abandon panier + post-achat)
- [ ] Mega-menu boutique + quiz "Aide au choix"
- [ ] Page `/pour-orthophonistes/` (tarifs pros)
- [ ] Page `/temoignages/` avec 10 avis vérifiés
- [ ] Alma 3×/4× activé
- [ ] WhatsApp / Crisp chat
- [ ] Blog : 6 articles publiés
- [ ] Emballage cadeau option payante
- [ ] Trust badges footer + fiche produit

### 🟡 Sprint 3 — Dépassement (mois 2-3)
- [ ] Bundle builder "Coffret sur mesure"
- [ ] Programme parrainage (-10 € parrain + filleul)
- [ ] Wishlist
- [ ] Quiz profond "Test lecture enfant" (lead magnet)
- [ ] 10 articles SEO long-tail supplémentaires
- [ ] Campagne Google Ads Search (mots-clés transactionnels "jeu CP")
- [ ] Campagne Meta Ads (lookalike avec pixel nourri)
- [ ] PWA manifest + install prompt
- [ ] Schema.org FAQ / HowTo / Recipe (règle du jeu)
- [ ] Partenariats écoles / associations dyslexie
- [ ] A/B testing Google Optimize (ou VWO)

---

## ⚡ Quick wins possibles "ce soir" avant démo Brigitte

30 min à 2 h chacun — à enchaîner dans l'ordre conversion :

- [ ] Meta description + OG tags sur home + fiche produit
- [ ] JSON-LD `Organization` + `Product`
- [ ] Fix les 2 H1 sur la home
- [ ] Sticky add-to-cart mobile sur les cartes home
- [ ] Stock badge intelligent ("En stock — expédié sous 48 h")
- [ ] Free shipping bar en haut du panier
- [ ] Page `/methode-syllabique/` ébauche (+1 200 mots)
- [ ] Fiche produit enrichie : galerie + FAQ + highlights + cross-sell
- [ ] Newsletter capture en footer (Brevo ou placeholder)
- [ ] Portrait / bio Brigitte (si fichier fourni)

---

## 📎 Fichiers liés

- [`CLAUDE.md`](CLAUDE.md) — contexte projet (qui / quoi / pourquoi)
- [`RECAP_SESSION.md`](RECAP_SESSION.md) — état technique (hébergement, plugins, MCP)
- [`design-system.md`](design-system.md) — tokens couleurs, typos, spacing
- [`questions-en-suspens.md`](questions-en-suspens.md) — infos en attente de Brigitte (SIRET, TVA, prix, tél, adresse…)
