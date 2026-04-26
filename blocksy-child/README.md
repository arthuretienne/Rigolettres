# Blocksy Child — Rigolettres

Thème enfant qui remplace les snippets `rigo-ds-v2` (48), `rigo-ds-v2-patch` (50) et `rigo-ds-v2-wc` (53).

## Installation (Hostinger File Manager)

1. **hPanel Hostinger → File Manager → `public_html/wp-content/themes/`**
2. Créer le dossier `blocksy-child/`
3. Uploader les 2 fichiers : `style.css` + `functions.php`
4. **Admin WP → Apparence → Thèmes** → activer **Blocksy Child Rigolettres**
5. **LiteSpeed → Toolbox → Purge All**

## Migration depuis les snippets

Une fois le child theme actif et le rendu confirmé :
- Désactiver les snippets : 48, 50, 53 (et tout `rigo-ds-v2-*`)
- Garder l'autre logique métier (marquee, sections home, trust strip, etc.) dans Code Snippets — c'est toujours leur place.

## Structure

```
blocksy-child/
├── style.css        # Tokens + design system (sans !important, cascade naturelle)
├── functions.php    # Enqueue parent+child + Google Fonts
└── README.md        # Ce fichier
```

## Avantages vs snippets

- CSS dans un vrai fichier minifiable par LiteSpeed
- Versioning auto via `filemtime()` → cache busté à chaque modif
- Fini la guerre de spécificité (`!important` partout) — la cascade Blocksy joue normalement
- Versionnable Git
- Survit à un déplacement du site (alors qu'un snippet vit dans la DB)
