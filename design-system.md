# Design System — Rigolettres.fr

> Basé sur la maquette [hero.png](hero.png) + la photo du packaging officiel (voir [assets/logo-pato-source.png](assets/logo-pato-source.png)). **Document vivant** — à ajuster quand le logo définitif (vectoriel HD) est fourni par Brigitte.

## Univers de marque (découvert depuis le packaging)

- **Mascottes** : 4 personnages récurrents — **LUNA** (chat blanc aux oreilles grises), **ZOÉ** (fille en robe rose), **PATO** (chien blanc aux oreilles brunes, cœur rouge sur le torse — **mascotte principale / logo**), **THÉO** (garçon en t-shirt rayé rouge/blanc).
- **Décor récurrent** : prairie verte avec fleurs jaunes/oranges, soleil souriant, nuages bleu ciel, petite maison.
- **Wordmark "Rigolettres"** officiel : **lettres multicolores** (rouge, jaune, vert, bleu, violet), typo manuscrite légèrement irrégulière — à reproduire fidèlement.
- **Sous-gammes identifiées** : **RIGOLETTRES** (lecture syllabique, produit d'origine) et **RIGOLOVERBES** (conjugaison — ex. "Passe simple"). Possibilité d'autres sous-gammes à confirmer avec Brigitte.
- **Logo Pato provisoire** : [assets/logo-pato-provisoire.png](assets/logo-pato-provisoire.png) — extrait automatiquement du packaging photographié, qualité limitée (artefacts de détourage). **À remplacer** par un vectoriel HD dès que disponible.

## Tension à arbitrer (maquette vs packaging)

La maquette `hero.png` d'Arthur propose une DA **plus adulte, chaleureuse, vintage** (fond crème, typo Kalam). Le packaging officiel est **plus enfantin, coloré, saturé** (vert prairie, lettres arc-en-ciel).

**Recommandation** : DA hybride adulte-friendly — garder le **fond crème** et la **typo manuscrite douce** de la maquette (parce que l'acheteur est un adulte — orthophoniste, parent), MAIS réutiliser les **mascottes** et les **lettres multicolores du wordmark** comme signatures visuelles fortes. On évite le côté "trop enfant" qui décrédibiliserait l'expertise orthophoniste.

---

## Principes

1. **Chaleureux, pas enfantin.** La cible principale (orthophonistes, parents) doit sentir le sérieux professionnel. Les enfants apprécient l'illustré, mais l'acheteur est l'adulte.
2. **Artisanal, pas kitsch.** Traits au crayon, textures légères, imperfections assumées — mais pas Comic Sans, pas Papyrus, pas de surcharge d'emoji.
3. **Hiérarchie claire.** Un seul accent primaire (bleu ciel) pour les actions. Le vert et l'ocre servent pour les illustrations et micro-accents, pas pour boutons multiples.
4. **Légitimité en permanence.** "25 ans d'orthophoniste", preuve sociale, avis orthophonistes → présents sur la plupart des pages, pas uniquement en home.
5. **Lisibilité > originalité.** Body texte dans une sans-serif standard, pas de font manuscrite hors titres courts.

---

## Couleurs (tokens)

```
# Neutres (fond, texte)
--color-bg            #FBF8F1   /* crème très clair (fond principal) */
--color-bg-alt        #FFFFFF   /* cartes, inputs */
--color-ink           #1F2937   /* texte principal — presque noir chaud */
--color-ink-soft      #4B5563   /* sous-titres */
--color-muted         #9CA3AF   /* légendes, placeholders */
--color-border        #E7E2D5   /* bordures douces, tonalité crème */

# Primaire — bleu ciel (CTA, liens, focus)
--color-primary       #27B4E5   /* bouton "Découvrir les jeux" */
--color-primary-dark  #1E92BC   /* hover */
--color-primary-soft  #E3F5FC   /* backgrounds doux, badges */

# Secondaire — vert tendre (accents, nature/croissance)
--color-accent        #8BC84B   /* mot "s'amusant", colline */
--color-accent-dark   #6FA832
--color-accent-soft   #EEF7DE

# Tertiaire — ocre/caramel (chaleur, éléments décoratifs)
--color-warm          #D9A066   /* montgolfière, touches illustrées */
--color-warm-soft     #F7E9D8

# Sémantique
--color-success       #22C55E
--color-warning       #F59E0B
--color-danger        #EF4444
```

**Règle d'accent :** primary pour actions (achat, CTA, liens). Vert pour réussites/nature (message "livré", accents illustratifs). Ocre pour chaleur décorative uniquement.

---

## Typographie

Deux familles Google Fonts, gratuites, auto-hébergeables.

### Titres (display) — **Kalam** ou **Caveat Brush**
- Style manuscrit, marqueur enfant.
- Usage : H1, H2 accroches, citations courtes ("Approuvés par les pros…").
- **Ne PAS utiliser** pour paragraphes ni pour > 8 mots.
- Fallback : `"Kalam", "Comic Neue", cursive`.

### Corps (text) — **Nunito**
- Sans-serif arrondie, chaleureuse, très lisible.
- Usage : tous paragraphes, menus, formulaires, fiches produit, boutons.
- Poids disponibles : 400, 600, 700, 800.
- Fallback : `"Nunito", system-ui, -apple-system, sans-serif`.

### Échelle (base = 16px)

```
--text-xs      12px / 1.4      /* legal, footnote */
--text-sm      14px / 1.5      /* secondaire */
--text-base    16px / 1.6      /* body */
--text-lg      18px / 1.6      /* intro paragraphe */
--text-xl      22px / 1.4      /* H3 */
--text-2xl     28px / 1.3      /* H2 — Nunito 800 */
--text-3xl     36px / 1.2      /* H2 display — Kalam */
--text-4xl     48px / 1.1      /* H1 mobile */
--text-5xl     64px / 1.05     /* H1 desktop — Kalam */
```

Les gros titres display (Kalam) doivent avoir un `letter-spacing` légèrement négatif (`-0.01em`) pour rester denses.

---

## Espacement & grille

```
--space-1   4px
--space-2   8px
--space-3   12px
--space-4   16px
--space-5   24px
--space-6   32px
--space-8   48px
--space-10  64px
--space-12  96px
--space-16  128px
```

- **Conteneur max-width** : 1200px (centrage auto, padding 24px mobile / 32px desktop).
- **Grille produits** : 1 colonne mobile, 2 tablette, 3 desktop (max 4 sur très large écran).
- **Rythme vertical** entre sections : `--space-12` desktop / `--space-8` mobile.

---

## Rayons, ombres, bordures

```
--radius-sm    8px     /* inputs, badges */
--radius-md    16px    /* cartes produit, boutons */
--radius-lg    24px    /* modales, gros blocs */
--radius-full  9999px  /* pills, avatars */

--shadow-sm    0 1px 2px rgba(31, 41, 55, 0.06)
--shadow-md    0 4px 12px rgba(31, 41, 55, 0.08)
--shadow-lg    0 12px 32px rgba(31, 41, 55, 0.10)
```

Boutons primaires : `--radius-full` (comme sur la maquette), jamais d'angle droit.

---

## Boutons

**Primaire** (CTA principal — "Ajouter au panier", "Découvrir les jeux") :
- Fond `--color-primary`, texte blanc, Nunito 700, radius pill, padding `12px 24px`, shadow-sm.
- Hover : `--color-primary-dark`, léger translate-Y.

**Secondaire** (action alternative — "Notre histoire") :
- Fond transparent, bordure 1.5px `--color-ink`, texte `--color-ink`, pill, padding identique.
- Hover : fond `--color-ink`, texte blanc.

**Tertiaire / liens** : texte `--color-primary-dark`, souligné au hover.

---

## Composants illustrés récurrents

Inspirés de la maquette `hero.png` — à réutiliser pour créer une cohérence visuelle :
- **Nuages** (coton gris doux, traits irréguliers) — séparateurs de section ou décor de fond.
- **Colline verte** — bas de home, bas de CTA sections.
- **Montgolfière ocre** — élément "voyage d'apprentissage", à réutiliser avec parcimonie.

À terme, commander à un·e illustrateur·rice un **kit de 6-8 vignettes** cohérentes (animaux Rigolettres, nuages, étoiles, ballons, éléments lettres/syllabes). En attendant, utiliser SVG libres cohérents avec l'esprit (ex. [Open Peeps](https://www.openpeeps.com/), [unDraw](https://undraw.co/) en teintant).

---

## Micro-interactions

- Transitions par défaut : `200ms ease-out`.
- Survol cartes produit : légère élévation (`translateY(-2px)` + `--shadow-md`).
- Focus visible : `outline: 3px solid --color-primary-soft; outline-offset: 2px;` — **obligatoire** pour accessibilité.
- Pas d'animations fancy au scroll qui ralentissent. PageSpeed > 80 vise.

---

## Accessibilité (non négociable)

- Contraste texte principal > 4.5:1 (AA). Vérifié : `--color-ink` sur `--color-bg` = OK.
- Texte sur `--color-primary` doit être blanc (contraste 4.6:1).
- Tailles cliquables > 44×44px sur mobile.
- Labels explicites sur tous les champs (important vu la cible 60+ / débutants).
- Alt text sur toutes les illustrations décoratives (ou `aria-hidden`).

---

## Références de style (inspirations hors concurrence)

- **Topla** (toplaboardgames.com) — jeux maths pour enfants, ton pro + chaleureux.
- **Hoptoys** (hoptoys.fr) — e-commerce pédagogique français, bien structuré.
- **Alphaludic** (alphaludic-orthophonie.com) — concurrent direct, à étudier pour se différencier.

---

## À figer une fois le logo reçu (version HD/vectorielle)

- [ ] Récupérer le logo Pato au format **vectoriel** (SVG, AI, PDF) ou à défaut PNG haute résolution sur fond transparent — l'actuel [logo-pato-provisoire.png](assets/logo-pato-provisoire.png) est extrait d'une photo et a des artefacts.
- [ ] Récupérer le **wordmark "Rigolettres"** aux lettres multicolores en vectoriel (imprimeur `Auffret-Plessix Mamers` — `www.auffretplessix.com` — à contacter si Brigitte n'a pas les sources).
- [ ] Récupérer les **4 mascottes** (Luna / Zoé / Pato / Théo) en version isolée et vectorielle — utiles pour illustrer les différentes gammes, pages enfants, etc.
- [ ] Couleurs exactes du logo (ajuster le bleu ciel `#27B4E5` et les accents pour matcher les couleurs d'impression).
- [ ] Favicon 32px + 180px (apple-touch) à générer depuis le logo.
- [ ] Version monochrome du logo (footer sombre, factures, emails).
