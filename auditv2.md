# 🎨 Audit V2 — Direction artistique & refonte design

> Volet **DA / UI / brand premium** complémentaire à [audit.md](audit.md) (qui suit le scoring fonctionnel/business 50 k€).
> Cet audit V2 isole les chantiers spécifiquement **visuels** qui restent à mener pour passer du look "site WordPress soigné" → "marque éditrice premium type Maison du Pastel / Typology / Jeux Djeco".
>
> **Date d'ouverture** : 2026-04-25 — sortie du sprint 7 (10 pages pilier publiées + chrome universel).
> **Score DA actuel estimé** : 55-60 % du niveau "marque éditrice premium". Beaucoup de fondations sont là (palette crème, header/footer cohérents, mascottes), mais on accumule trop d'incohérences fines + assets bricolés.

---

## 🎯 Objectif final

Avoir **un seul univers visuel** lisible en 2 secondes — quand un orthophoniste ou un parent atterrit sur la home, il doit ressentir : **artisanal, chaleureux, sérieux, conçu par une experte, fait avec soin**. Pas "site WordPress générique avec mascottes collées".

Référents visuels à étudier :
- **Maison du Pastel** (artisanal + autorité de l'expert)
- **Typology** (rigueur typographique + crème + soin du détail)
- **Jeux Djeco / Lilliputiens** (univers enfantin + premium parent)
- **Bonne Maman** (typographie manuscrite + chaleur)
- **Mr. Boddington's Studio** (illustration + sérénité)

---

## 1. 🅰️ Typographies

**Constat** : on utilise actuellement Kalam (manuscrite, wordmark) + Nunito (sans-serif body) + Caveat ponctuellement. Trois polices manuscrites cohabitent par moments → manque de hiérarchie. Kalam fait "billet de blog" plus que "marque éditrice".

- [ ] **Adopter une typo serif éditoriale** pour titres : **Fraunces** (variable, expressive, gratuite Google Fonts) ou **Instrument Serif** (très chaleureuse, plus posée). Donne immédiatement un cachet "édition" + "expertise".
- [ ] **Garder Nunito** en body (lisibilité enfants/parents OK, déjà familière).
- [ ] **Limiter la manuscrite à 1 usage symbolique** : wordmark + éventuellement pull quotes Brigitte. Bannir Caveat des sous-titres.
- [ ] **Échelle typographique cohérente** : définir 6 tailles (display / h1 / h2 / h3 / body / small) avec ratio 1.250 (major third). Documenter dans [design-system.md](design-system.md).
- [ ] **Hiérarchie de poids** : 800 display, 700 h1-h2, 600 h3, 400 body, 600 strong. Plus de italique fantaisie.

---

## 2. 🎨 Palette & couleurs

**Constat** : palette de base posée (#F7F4ED crème + #68a033 vert + #27B4E5 bleu ciel + #3a2913 brun) mais on utilise localement 2-3 variations qui créent du bruit (#8BC84B vs #68a033, #2872fa vs #27B4E5).

- [ ] **Verrouiller 5 tokens couleur max** dans une variable CSS root (`--rigo-cream`, `--rigo-ink`, `--rigo-green`, `--rigo-sky`, `--rigo-accent`) + 2 gris (border, muted text).
- [ ] **Bannir les hex en dur** dans les snippets — passer aux variables.
- [ ] **Améliorer le contraste body** : actuellement #6B7280 sur crème = passe AA mais limite. Foncer à #4B5563.
- [ ] **Définir un fond "secondaire"** légèrement plus chaud (#FAF7F0) pour alterner les sections de la home et créer du rythme sans fatiguer l'œil.
- [ ] **Rationaliser les "couleurs mascottes"** (rouge cœur Pato, rose robe Zoé, rayé Théo, gris Luna) → utilisées comme accents seulement, jamais en surface.

---

## 3. 🦆 Assets visuels — logo, mascottes, packagings

**Constat — c'est le chantier le plus douloureux**. Le logo Pato est un PNG détouré au lasso, pas vectoriel. Le wordmark "Rigolettres" multicolore est reconstitué en CSS (Kalam + dégradé). Les mascottes n'existent qu'en photos de packaging compressées.

- [ ] 🔴 **Récupérer fichiers vectoriels HD chez Auffret-Plessix** (imprimeur Mamers) : logo Pato, wordmark, 4 mascottes, illustrations packaging. **Action Arthur/Brigitte**, cf. [questions-en-suspens.md](questions-en-suspens.md).
- [ ] 🔴 **Refaire le wordmark proprement en SVG** une fois les sources reçues — soit reconstitué fidèle au packaging, soit version "édition" plus discrète pour le header.
- [ ] **Créer des illustrations dérivées** des 4 mascottes pour les hero sections : Pato qui lit, Luna sur un coussin, Théo qui réfléchit, Zoé qui rit. Investir dans 1 illustrateur·rice freelance (~600-1200 € pour 6-8 illus) — ROI immédiat sur la home, pages méthode et pages pilier.
- [ ] **Photos packaging détourées sur fond crème** plutôt que sur fond blanc carton (bake les transparences propres).
- [ ] **Patterns / textures fines** : papier kraft léger, lignes seyes très subtiles en arrière-plan de certaines sections — clin d'œil pédagogique sans être lourd.

---

## 4. 📸 Photographie produit & lifestyle

**Constat** : photos actuelles = HEIC d'iPhone retraitées. Cadrages serrés sur boîte, pas de mise en situation, lumière inégale. Pour un site qui doit déclencher l'achat à 30-130 €, c'est rédhibitoire.

- [ ] 🔴 **Shooting pro complet** (~1 200-1 800 € pour la journée) :
  - Packaging produit sur fond crème uniforme (5-7 angles par jeu) — boîte fermée, ouverte, contenu étalé, détail carte, vue 3/4.
  - **Lifestyle famille** : enfant 7 ans qui joue avec un parent ou grand-parent à table, lumière naturelle, ambiance maison de campagne (correspond à l'identité Mamers + Brigitte).
  - **Portrait Brigitte** (intérieur cabinet ou bureau créatif) + plans serrés mains qui dessinent une carte = preuve "je conçois moi-même".
  - **Plans détail** : carton, finition impression, mascottes en gros plan.
- [ ] **Briefing au photographe** : référence Maison du Pastel (palette claire, contraste doux, cadrages aérés).
- [ ] **Format final** : WebP ≤ 200 Ko en cartes / ≤ 400 Ko en hero, AVIF en fallback.

---

## 5. 🖼️ Layout & composition

**Constat** : home posée mais sections trop denses, peu de respiration. Pages pilier excellentes en contenu mais aspect "long article" sans rythme visuel.

- [ ] **Grille verticale rythmée** : alterner sections fond crème / fond crème chaud / blanc — créer un battement visuel.
- [ ] **Augmenter le whitespace vertical** entre sections (96-120 px desktop / 64 px mobile) — c'est le marqueur n°1 d'un site "premium" vs "amateur".
- [ ] **Sections visuelles régulières dans les pages pilier** : actuellement 80 % texte. Insérer un visuel illustration ou photo tous les ~600 mots, plus une citation Brigitte stylisée tous les ~1 000 mots.
- [ ] **Hero home** : repenser. Aujourd'hui mascotte + wordmark + slogan. À enrichir avec sous-pitch chiffré ("Conçu en 1978 par une orthophoniste, fabriqué à 10 km du cabinet"), bouton primaire fort, badges trust en sous-titre, mini-galerie produits visible sans scroll (3 boîtes juxtaposées).
- [ ] **Cards produit** sur boutique : harmoniser ratio image (3:4), prix typo serif + ancien prix barré sur les packs, badge "fabriqué à Mamers" coin haut-droit.

---

## 6. 🛒 Fiche produit premium

**Constat** : fiche produit fonctionnelle mais générique WooCommerce avec quelques surcouches. Pour vendre 30-130 €, il faut un layout type "Aesop / La Cerise sur le Gâteau".

- [ ] **Layout 2 colonnes asymétrique** : galerie sticky 60 % gauche, infos 40 % droite avec ATC sticky pendant le scroll.
- [ ] **Galerie scroll** : 5-7 visuels par produit (cf. §4), zoom au clic, navigation clavier.
- [ ] **Bloc "Pourquoi ce jeu ?" signé Brigitte** (citation longue + signature manuscrite + ADELI).
- [ ] **Tableau caractéristiques visuel** avec icônes (âge / niveau / joueurs / durée / origine / contenu boîte).
- [ ] **Section "Ils l'utilisent en cabinet"** (avis orthophonistes — quand collectés).
- [ ] **Vidéo règle du jeu** intégrée (60 secondes Brigitte qui explique les règles avec une vraie boîte) — facteur conversion énorme sur produits jeu.
- [ ] **Bloc "Souvent acheté avec"** = packs auto-suggérés pour faire grimper l'AOV.
- [ ] **FAQ produit** : déjà présente, à harmoniser visuellement avec la FAQ pages pilier (mêmes styles d'accordéon).

---

## 7. ✨ Motion & micro-interactions

**Constat** : zéro motion. Le site est statique, ce qui le fait paraître "vieux" en 2026.

- [ ] **Fade-in au scroll** sur sections home (Intersection Observer + transform translateY 16px → 0 + opacity 0 → 1, 600 ms).
- [ ] **Hover cards produit** : légère élévation (translateY -4px + shadow) + zoom image 1.03.
- [ ] **Bouton primaire** : animation au hover (gradient slide ou flèche qui glisse).
- [ ] **Add-to-cart** : flying image animation (image produit qui voyage vers icône panier) + count badge qui pulse.
- [ ] **Header au scroll** : déjà géré (snippet 17), à raffiner (logo qui rétrécit légèrement, ombre douce).
- [ ] **Loader entre pages** : barre de progression fine en haut (style YouTube/NProgress) plutôt que page blanche.
- [ ] **Transitions sur tabs / accordions** : easing custom (cubic-bezier 0.4, 0, 0.2, 1) plutôt que linear par défaut.

---

## 8. 📱 Mobile-first refinement

**Constat** : mobile fonctionne mais plusieurs frictions (sticky ATC chevauche FAB quiz, header un peu lourd, espacements).

- [ ] **Bottom nav iOS-style** alternatif au menu burger pour les pages clés (Boutique / Méthode / Panier / Compte) — UX e-commerce 2026 standard.
- [ ] **Resserrer les paddings hero mobile** + agrandir le CTA principal (min 56 px height, fond plein vert, ombre douce).
- [ ] **Galerie produit mobile** : swipe horizontal pleine largeur avec dots (style Instagram).
- [ ] **Mobile typography scale** : actuellement même ratio que desktop, à compresser (16/18/22/28/36 plutôt que 16/20/26/36/48).
- [ ] **Z-index audit complet** : sticky ATC, FAB quiz, menu mobile, popup exit-intent → tester collisions.

---

## 9. 🧹 Chrome WC à nettoyer

**Constat** : malgré l'override design system (snippets 24, 27), il reste des incrustations Blocksy + WooCommerce qui cassent l'identité.

- [ ] **Notifications WC** ("✓ ajouté au panier") : rebrand intégral (fond crème, bordure verte, typo serif, icône custom).
- [ ] **Page Mon Compte** : toujours 100 % template Blocksy, à habiller (sidebar custom avec mascottes, contenu typé serif).
- [ ] **Checkout layout** : passer en checkout 1-page épuré type Shopify/Stripe, pas la grille 2 colonnes WC bricolée.
- [ ] **Messages d'erreur** WC traduits + rebrandés (couleur, icône).
- [ ] **Page de confirmation commande** : déjà custom (snippet 19), à pousser plus loin (illustration Brigitte qui prépare le colis, étapes timeline animées).
- [ ] **Email transactionnels** : actuellement template WP par défaut. Refondre avec entête wordmark + signature Brigitte + footer trust.

---

## 10. 🏷️ Système d'icônes

**Constat** : on mixe SVG inline ad hoc, emojis (🇫🇷, ✓), Dashicons, Heroicons. Manque de cohérence.

- [ ] **Choisir 1 set d'icônes** et s'y tenir : **Phosphor Icons** (style chaleureux, regular weight) ou **Lucide** (sobre, premium). Bannir emojis sauf 🇫🇷 stratégique.
- [ ] **Stroke uniforme** (1.8 ou 2 px), couleur via `currentColor`.
- [ ] **Tailles cohérentes** : 16/20/24 — pas d'icône 22 ni 18 baladeur.

---

## 11. 🔗 Continuité brand entre touchpoints

- [ ] **Favicon** : actuellement placeholder. Créer favicon.ico + apple-touch-icon depuis le Pato vectoriel HD (cf. §3). Snippet 20 attend déjà l'asset.
- [ ] **Open Graph image** : OG par défaut OK (snippet 6), mais générer 1 OG par catégorie + 1 OG par pack (template Figma à templater).
- [ ] **Page 404** : à customiser avec illustration mascotte perdue + CTA retour boutique (au lieu du template Blocksy par défaut).
- [ ] **Page de maintenance / coming-soon** (au cas où) : préparer un visuel cohérent.
- [ ] **Carton / packaging d'envoi** (sticker Pato + mot manuscrit imprimé "Merci, Brigitte" inséré dans chaque colis) — pas du web mais ça boucle l'expérience marque, à proposer à Brigitte.

---

## 12. 📐 Design system documentation

- [ ] **Compléter [design-system.md](design-system.md)** avec : typographies (échelle, ratios, line-heights), tokens couleur, spacing (4/8/16/24/32/48/64/96), shadows (3 niveaux), border-radius (4/8/16), motion (durations + easings).
- [ ] **Storybook minimaliste** ou page `/styleguide/` cachée avec tous les composants assemblés (boutons, cards, forms, alerts, tabs) — outil de référence pour ne plus diverger.
- [ ] **Snippets CSS partagés** : extraire les variables dans un snippet "core variables" ré-injecté partout, plutôt que dupliquer hex/rem dans chaque snippet.

---

## 🚦 Priorisation suggérée

Si on devait phaser le travail DA :

**Sprint A — Avant lancement officiel (P0)**
1. §3 logo + wordmark vectoriels (bloque tout)
2. §4 shooting photo pro (bloque conversion)
3. §6 fiche produit premium (bloque AOV)
4. §1 bascule typo serif éditoriale
5. §11 favicon + OG par catégorie + 404 customisée

**Sprint B — Mois 1 post-launch (P1)**
6. §5 layout home + rythme sections
7. §2 verrouillage palette + tokens
8. §7 motion & micro-interactions
9. §9 chrome WC nettoyage (mon-compte, checkout, emails)

**Sprint C — Mois 2-3 (P2)**
10. §3 illustrations custom (commande illustrateur)
11. §10 set d'icônes unifié
12. §12 documentation design system
13. §8 mobile bottom nav + raffinements
