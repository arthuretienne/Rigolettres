# Rigolettres.fr — Contexte projet

> ## 🎯 À LIRE EN PREMIER À CHAQUE SESSION
>
> **[→ audit.md](audit.md)** est le **fichier source de progression** vers le niveau "e-commerce premium 50 k€".
>
> **Au démarrage de toute session, Claude doit :**
> 1. Ouvrir [audit.md](audit.md) et lire **le journal de session + les checkboxes** pour savoir ce qui est déjà fait.
> 2. **Ne jamais refaire** un item coché `[x]` — proposer amélioration plutôt.
> 3. **Mettre à jour audit.md** dès qu'une feature est livrée :
>    - cocher la case `[ ]` → `[x]`
>    - ajouter un commentaire `— YYYY-MM-DD : livré par …`
>    - ajouter une entrée dans le **Journal de session** (en haut du fichier)
> 4. **Ajouter tout nouveau manque** identifié dans la bonne section de l'audit.
>
> C'est la single source of truth pour coordonner les sessions et atteindre le niveau "site 50 k€".

Voir [RECAP_SESSION.md](RECAP_SESSION.md) pour l'état technique (hébergement, plugins installés, MCP).
Ce fichier-ci capte le **qui / quoi / pourquoi** — pour que toute nouvelle session comprenne le besoin avant de toucher au code.

---

## Qui

- **Brigitte Étienne** — grand-mère d'Arthur, **orthophoniste libérale à Mamers (72600) depuis 1978**. Études de médecine à Rouen (1963-67) puis école d'orthophonie de Tours (1969-71). Enseignante à l'école d'orthophonie de Tours depuis 1996, directrice de mémoires depuis 1998, formatrice langage oral depuis 2002. Mère de 7 enfants. Spécialisée enfants dyslexiques / dysorthographiques.
- **Entreprise Rigolettres** créée en **2011** (effectif : 1).
- **Arthur ETIENNE** (proprio du repo) — développeur du projet. Email WordPress admin : `aetiennea@gmail.com`. Sert aussi d'**email de contact boutique provisoire** (à remplacer plus tard).
- **Albéric** — **fils de Brigitte / oncle d'Arthur**, en charge de la **gestion de projet** côté famille. Peut rédiger des articles de blog et gérer l'admin WordPress à la place de Brigitte si besoin.
- **Flo** — peut aussi aider au traitement des commandes (identité précise à confirmer).

## Quoi (produits)

**Rigolettres n'est PAS une boutique de "jeux de société" générique.** C'est une gamme ciblée :

1. **Jeux éducatifs pour l'apprentissage de la lecture & de la langue française** — conçus à la main par Brigitte pour ses patients, puis édités. Plusieurs sous-gammes identifiées :
   - **RIGOLETTRES** (gamme originale) — apprentissage lecture syllabique. Ex. connu : **PATO LE CHIEN** (syllabes à 2 lettres).
   - **RIGOLOVERBES** — apprentissage conjugaison. Ex. connu : **"RIGOLOVERBES : PASSE SIMPLE"** (vu sur packaging photographié).
   - D'autres sous-gammes possibles (à confirmer avec Brigitte).
   - Caractéristiques communes : enfants 5–12 ans (CP–CM2), parties ~15 min, jeu modulable (règles enfants / règles grands), se joue en famille, mesure de progrès (course contre la montre 1 min), fabriqué en France (imprimeur Auffret-Plessix à Mamers).
2. **Livres de grammaire française** — 2 ouvrages issus des tableaux pédagogiques de Brigitte.

Méthode pédagogique : **syllabique**, ludique, correspondance son/lettre.

### Univers graphique des jeux (mascottes)

4 personnages récurrents présents sur tous les packagings :
- **LUNA** — chat blanc aux oreilles grises.
- **ZOÉ** — fille en robe rose, cheveux bruns.
- **PATO** — chien blanc aux oreilles brunes, cœur rouge sur le torse. **Mascotte principale / logo du site**.
- **THÉO** — garçon en t-shirt rayé rouge/blanc.

Ces personnages sont des atouts marketing forts (mémorisables, sympathiques, réutilisables partout). Logo Pato provisoire stocké dans [assets/logo-pato-provisoire.png](assets/logo-pato-provisoire.png) — version vectorielle HD à récupérer.

## Pourquoi (objectifs)

- Faire connaître la gamme au grand public (au-delà du bouche-à-oreille orthophonistes).
- Vendre en ligne (l'ancien site Wix `rigolettres.com` a été fermé — coût, ergonomie, lenteur, pas de paiement en ligne).
- Objectif 6 mois post-lancement : **200 visiteurs/mois, 8 commandes/mois**.
- Business plan : panier moyen ~30–70 €, 70 commandes an 1.

## Pour qui (cibles)

Par ordre d'importance :
1. **Orthophonistes** (prescripteurs — argument : "par une consœur").
2. **Parents** d'enfants 5–10 ans (surtout en difficulté de lecture).
3. **Instituteurs(rices)** CP–CM2.
4. **Grands-parents** cherchant un cadeau utile.

## Contraintes UX fortes

### Côté admin (grand-mère)
- Brigitte est **débutante informatique**. Arthur peut la former, Albéric peut aussi gérer l'admin à sa place.
- Le stock est chez **elle (son domicile)** — elle gère seule le réapprovisionnement, pas d'entrepôt externe.
- Traitement des commandes : **Brigitte, Albéric ou Flo** (selon disponibilité).
- Exigence clé : interface admin **réduite à l'essentiel** — Commandes / Produits / Clients. Tout le reste caché (Admin Menu Editor).
- Livrer un **mini-guide PDF visuel** pour les opérations courantes (traiter commande, imprimer étiquette, marquer expédié).
- Zéro jargon technique. Zéro menu superflu.

### Côté site (visiteur)
- Navigation **simple et intuitive** (menu fixe, fil d'ariane).
- Pas de moteur de recherche interne nécessaire.
- Mise en avant forte de **la légitimité de Brigitte** (orthophoniste 25 ans) — c'est l'argument de conversion principal.
- PageSpeed > 40 (objectif du cahier des charges). À viser plus haut (> 80) sur Business Hostinger + LiteSpeed.
- Langue : **français uniquement**.

## Identité visuelle

- **Logo** : mascotte **PATO** (chien au cœur rouge). Version provisoire détourée auto : [assets/logo-pato-provisoire.png](assets/logo-pato-provisoire.png). **À remplacer** par un fichier vectoriel HD fourni par Brigitte/imprimeur.
- **Wordmark "Rigolettres"** : lettres multicolores (rouge, jaune, vert, bleu, violet) en typo manuscrite — à récupérer en vectoriel.
- **Maquette de référence** : [hero.png](hero.png) (maquette grossière faite par Arthur) — sert de base pour la direction artistique : chaleureux, illustré, fond crème, accents bleu ciel + vert tendre, typographies manuscrites sur titres.
- **Tension DA** à arbitrer : maquette d'Arthur (vintage chaleureux adulte) vs packaging officiel (enfantin très coloré). **Parti pris retenu** : DA hybride — fond crème + typo douce de la maquette, wordmark multicolore + mascottes du packaging. Voir [design-system.md](design-system.md).
- **Système de design** détaillé : [design-system.md](design-system.md) (tokens couleurs, typos, spacing).
- **Produire un rendu pro qui convertit** : utiliser le skill `frontend-design` pour toute création de page/composant.

## Arborescence cible (à confirmer)

- **Accueil** — qui est Brigitte + produits phares + preuve sociale.
- **Boutique** (WooCommerce) — catégories : Jeux / Livres.
- **Fiche produit** — photos, règles du jeu, niveau scolaire (CP/CE1/CE2/CM1/CM2), âge, durée, nombre de joueurs, objectif pédagogique.
- **À propos / Brigitte** — histoire, parcours orthophoniste, méthode.
- **La méthode Rigolettres** — pédagogie syllabique expliquée (SEO fort).
- **Blog** — articles sur la lecture, la méthode syllabique, parutions presse. Rédigé par Albéric + Brigitte.
- **Contact** — formulaire WPForms.
- **Compte client** — historique commandes, adresses.
- **Pages légales** (générées par Complianz) — CGV, mentions légales, confidentialité, cookies.

## SEO — mots-clés prioritaires (d'après recherche existante)

Volume mensuel indiqué entre parenthèses.

**Grammaire / jeux scolaires** (cœur de gamme) :
- faire de la grammaire au cp (170), grammaire cp (140)
- jeu grammaire ce1 (70), jeu grammaire ce2 (50), jeu grammaire cm1 (110), jeu de grammaire (50)
- jeu de conjugaison (590), jeu de conjugaison à imprimer (320), jeu conjugaison cm1 (110)

**Dyslexie / dysorthographie** (autorité Brigitte) :
- dyslexie dysorthographie (2400), dyslexique dysorthographique (880)
- dysorthographie test (590), dysorthographie exemple (320)
- jeu dyslexie (30), jeu de société pour dyslexique (20)

Stratégie : pages méthode + blog pour capter le long-tail "dyslexie", tunnel de vente vers fiches produits.

## Ce qui sert de référence / inspiration

- PDF `Alphaludic-bdef-opti.pdf` dans `ressources/` — éditeur similaire (orthophonie + ludique).
- Plaquette presse existante (article Le Mans maville.com).
- Plaquette `docplayer.fr/74185327` (avis spécialistes cabinet).
- Critique positive sur `orthomalin.com/communaute/critiques/materiel/rigolettres-ndeg1`.

## Présence numérique actuelle à traiter

- **Ancien site** `rigolettres.com` : **pas d'héritage à reprendre** (pas de 301, pas de contenu à migrer).
- **Facebook** `rigolettres.etienne` : **on ne garde pas** (ne pas lier depuis le site).
- **Annonce Gens de Confiance** existante (f8357d1) — source de descriptifs produits réutilisables.
- **Articles presse** (Le Mans maville.com, Orthomalin) : **à relayer** sur le site (page presse ou blog).

## Stack technique (résumé — détails dans RECAP_SESSION.md)

WordPress + WooCommerce + Blocksy + Stripe + PayPal + **Boxtal Connect** (Colissimo/Mondial Relay/Chronopost) + Complianz RGPD + WPForms + WP Mail SMTP + LiteSpeed Cache. MCP WordPress Automattic v0.2.5 piloté depuis `~/.claude.json` (pas `settings.json`).

## Architecture code — Blocksy Child Theme (RÈGLE ABSOLUE)

> **Toute modification PHP/CSS/JS permanente doit aller dans le child theme, jamais dans Code Snippets.**

### Structure du child theme

```
blocksy-child/
├── style.css            # Design system complet (tokens, typo, layout, WC, chrome, mega-menu)
├── functions.php        # Enqueue parent+child + Google Fonts + auto-require includes/
└── includes/            # Un fichier = un module fonctionnel
    ├── universal-header-footer-chrome.php  ← header sticky + mega-menu + mobile menu
    ├── quiz-aide-au-choix.php
    ├── product-page-cro.php
    └── ... (un fichier par feature)
```

- **Nouveau module** → nouveau fichier `includes/nom-du-module.php` (chargé automatiquement par `functions.php`)
- **CSS** → dans `style.css` (sections numérotées, tokens `--rigo-*` en variables)
- **Code Snippets** → réservé aux one-shots ponctuels (patch DB, flush, purge) — jamais pour du code permanent

### Déploiement child theme

1. Modifier les fichiers localement dans `blocksy-child/`
2. Uploader via **Hostinger hPanel → File Manager → `public_html/wp-content/themes/blocksy-child/`**
3. **LiteSpeed → Toolbox → Purge All**

## Règles de travail pour Claude

- **[audit.md](audit.md) est le tracker vivant du projet** — lire au démarrage, cocher les cases au fil de l'eau, ajouter une entrée dans le journal de session à chaque passage. C'est ce qui permet la continuité entre sessions.
- **Langue des échanges** : français (c'est un projet français + grand-mère francophone).
- **Avant toute modif MCP destructive** (delete, bulk update, plugin disable) : confirmer avec Arthur.
- **Photos produit** : originaux HEIC dans `ressources/Site rigolettres/Photos Rigolettres/` — convertir en WebP/JPG optimisé (max 1600px, < 300 Ko). **La plupart devront être refaites proprement** (shooting dédié à prévoir) — les actuelles dépannent pour lancer.
- **Vidéos** : aucune pour l'instant (laisser l'emplacement prévu mais ne pas l'afficher tant que vide).
- **Questions en suspens** : le fichier [questions-en-suspens.md](questions-en-suspens.md) centralise tout ce qu'on attend de Brigitte (SIRET, TVA, prix exacts, téléphone, adresse…). Consulter avant de figer CGV, mentions légales, fiches produits.
- **Ton des contenus** : chaleureux, pédagogique, famille — pas "corporate" ni "startup". L'expertise orthophoniste est le levier de confiance.
- **Ne pas inventer de produits, prix, règles** : toujours demander à Arthur ou vérifier dans `ressources/Site rigolettres/Descriptifs Rigolettres.docx`.
- **Admin simplifié pour Brigitte** : chaque fois qu'une fonctionnalité ajoute un menu/écran admin, se poser la question "Brigitte va le voir ? est-ce que ça l'aide ou la perd ?".
