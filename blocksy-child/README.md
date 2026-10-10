# Blocksy Child — Rigolettres

Thème enfant de Blocksy. **Tout le code permanent du site vit ici** (jamais dans Code Snippets).

## Structure

```
blocksy-child/
├── style.css               # Design system : tokens, pont Blocksy, base, boutons, formulaires,
│                           #   layout, en-tête/mega-menu, pied de page, boutique, fiche produit,
│                           #   panier/commande/compte, accessibilité (sommaire en tête de fichier)
├── assets/css/home.css     # Sections de la page d'accueil (chargé sur l'accueil uniquement)
├── functions.php           # Enqueue parent + enfant, Google Fonts, auto-require de includes/
└── includes/               # Un fichier = un module (chargé automatiquement)
```

## Règles du design system

- **Tokens** : toutes les couleurs, tailles, espacements, rayons, ombres et z-index sont des
  variables `--rigo-*` déclarées en section 1 de `style.css`. Pas de hex en dur ailleurs.
- **Pont Blocksy** (section 2) : les variables du thème parent (`--theme-palette-color-*`,
  `--theme-button-*`, conteneur…) sont redéfinies à partir des tokens. Ce que Blocksy et
  WooCommerce génèrent hérite donc du design system sans surcharge composant par composant.
- **Polices** : Fraunces (titres h1-h2), Nunito (texte, interface, h3-h6), Caveat (wordmark,
  surtitres, signature). Rien d'autre.
- **Couleur d'action** : `--rigo-action` (bleu ciel assombri, 5.3:1 sur blanc). Le vert est
  réservé à la réussite / disponibilité, jamais aux boutons.
- **Boutons** : un composant, deux variantes (pleine `.btn-primary`, contour `.btn-ghost`).
  Les classes propres aux modules y sont rattachées en section 4.
- **Points de rupture** : 640 px (mobile), 768 px (tablette), 1024 px (navigation desktop).

## Contenu hérité

Les pages Accueil, Panier, Commande et Mon compte contenaient leur propre `<style>` en base.
`includes/contenu-nettoyage-css-herite.php` les retire au rendu (sans toucher à la base) ;
le style est servi par ce thème. Désactiver ce module restaure l'ancien rendu.

## Déploiement

Push sur `main` → `.github/workflows/deploy-theme.yml` : lint PHP, rsync vers Hostinger,
purge LiteSpeed. Une erreur de syntaxe PHP bloque le déploiement.
