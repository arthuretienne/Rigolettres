# Recap session — Rigolettres.fr

**Date** : 2026-04-19
**Objectif** : Site e-commerce WordPress/WooCommerce pour la grand-mère d'Arthur qui vend des jeux de société. Ultra simple à utiliser pour elle, livraison facilitée via Boxtal.

---

## ✅ Ce qui est fait

### Infrastructure
- **Hébergement** : Hostinger (plan à choisir → **Business** recommandé pour les sauvegardes quotidiennes + CDN)
- **Domaine** : `rigolettres.fr` (déjà acheté sur Hostinger)
- **SSL HTTPS** : Activé via Hostinger → Sécurité → SSL + redirection forcée
- **WordPress** : Installé via Hostinger Auto Installer

### Authentification & MCP
- **Application Password WordPress** créé (nom : `claude-mcp`)
  - Utilisateur : `aetiennea@gmail.com`
  - Password : `plKg G0zW cVAy tU2g VsdX Hp6X` ⚠️ (sensible — à révoquer si partagé)
- **Plugin WordPress MCP** (Automattic v0.2.5) installé + activé sur le site
- **Toggles MCP activés** : MCP functionality, Create Tools, Update Tools, Delete Tools, REST API CRUD (EXPERIMENTAL)
- **Skip** : WordPress Features Adapter (nécessite wp-feature-api qui est déprécié)
- **MCP côté Claude Code** : ajouté dans `~/.claude.json` (**pas** dans `~/.claude/settings.json` — l'extension VSCode lit `~/.claude.json`)

### Config MCP dans `~/.claude.json`

```json
"wordpress": {
  "command": "npx",
  "args": ["-y", "@automattic/mcp-wordpress-remote@latest"],
  "env": {
    "WP_API_URL": "https://rigolettres.fr/",
    "WP_API_USERNAME": "aetiennea@gmail.com",
    "WP_API_PASSWORD": "plKg G0zW cVAy tU2g VsdX Hp6X"
  }
}
```

### Plugins installés et actifs (via MCP, endpoint POST `/wp/v2/plugins`)
| Plugin | Version | Rôle |
|---|---|---|
| WooCommerce | 10.7.0 | Moteur e-commerce |
| WooCommerce Stripe Gateway | 10.5.3 | Paiement CB |
| WooCommerce PayPal Payments | 4.0.2 | Paiement PayPal |
| WPForms Lite | 1.10.0.4 | Formulaire contact |
| WP Mail SMTP | 4.8.0 | Emails fiables |
| Complianz RGPD | 7.4.5 | Bandeau cookies + pages légales |
| Boxtal Connect | 2.0.0 | **Livraison multi-transporteurs** (Colissimo, Mondial Relay, Chronopost) — choisi à la place du plugin Colissimo Officiel (pas sur WP repo) |
| LiteSpeed Cache | 7.8.1 | Performance (pré-installé par Hostinger) |
| WordPress MCP | 0.2.5 | Canal de pilotage IA |

### Thème
- **Blocksy** installé + activé via `/wc-admin/onboarding/themes/install` + `/wc-admin/onboarding/themes/activate`

---

## ⏳ Ce qui reste à faire

1. **Configurer WooCommerce** (en cours)
   - Pays : France
   - Devise : EUR
   - TVA française (selon statut juridique de la grand-mère)
   - Adresse de la boutique
2. **Créer les pages** : Accueil, À propos, Contact, CGV, Mentions légales, Politique confidentialité (Complianz peut générer les pages légales auto)
3. **Structure produits** : catégories de jeux de société + premiers produits
4. **Configurer Boxtal Connect** : créer compte Boxtal + lier API
5. **Configurer Stripe + PayPal** : créer comptes marchands + coller clés API
6. **Créer le rôle Gestionnaire simplifié** pour la grand-mère :
   - Utiliser le rôle natif `shop_manager` de WooCommerce
   - Installer **Admin Menu Editor** pour masquer tout sauf Commandes / Produits / Clients
   - Rédiger un mini-guide PDF visuel pour elle

---

## 🔴 Infos manquantes à demander à Arthur

### Boutique
1. **Nom boutique** : "Rigolettres" ou autre ?
2. **Description/tagline**
3. **Adresse physique** (obligatoire Woo + mentions légales)
4. **Téléphone** de contact
5. **Email** de contact boutique

### Juridique
6. **Statut grand-mère** : auto-entrepreneur / micro-entreprise / SARL ?
7. **N° SIRET**
8. **TVA** : assujettie ou pas ?

### Produits
9. **Type de jeux** : classiques / modernes / enfants / cartes ?
10. **Nb produits** au lancement
11. **Gamme de prix**

### Visuel
12. **Ambiance** : vintage chaleureux / moderne / minimaliste ?
13. **Couleurs** principales

---

## 📚 Infos techniques utiles pour la prochaine session

### Tools MCP disponibles sous `mcp__wordpress__*`
- `get_site_info` — infos site, plugins, thèmes, users
- `list_api_functions` — liste tous les endpoints REST (⚠️ 63k chars, passer par fichier)
- `get_function_details` — détails d'un endpoint
- `run_api_function` — exécute n'importe quel endpoint REST (GET/POST/PATCH/DELETE)
- `wp_get_media_file` — récupère média

### Endpoints clés découverts
- `POST /wp/v2/plugins` → installer + activer plugins (body: `{"slug": "xxx", "status": "active"}`)
- `POST /wc-admin/onboarding/themes/install` → installer thème
- `POST /wc-admin/onboarding/themes/activate` → activer thème
- `GET/POST /wc/v3/settings` → config WooCommerce
- `GET/POST /wc/v3/wc_stripe/settings` → config Stripe
- `GET/POST /wc/v3/wc_paypal/settings` → config PayPal

### Note importante
- `~/.claude/settings.json` → **PAS** lu par l'extension VSCode pour les MCP
- `~/.claude.json` → **LE** fichier lu par l'extension VSCode
- Après modification : `Cmd+Shift+P` → "Developer: Reload Window" (ou Cmd+Q sur VSCode)

### Décision architecture prise
- Option A (WordPress + WooCommerce) retenue contre Shopify (budget) et Vercel headless (overkill).
- Boxtal > plugin Colissimo Officiel car pas de contrat La Poste Pro à signer.

---

## 🚀 Prompt de reprise pour nouvelle session

```
Je reprends le projet Rigolettres.fr — site e-commerce WordPress/WooCommerce
pour ma grand-mère qui vend des jeux de société.

Lis d'abord RECAP_SESSION.md dans ce dossier pour le contexte complet.

Le MCP WordPress est configuré et connecté. WooCommerce + Blocksy + Stripe +
PayPal + Boxtal + Complianz sont installés. Il faut maintenant :
1. Que je te donne les infos boutique/juridique/produits manquantes
   (section "Infos manquantes à demander à Arthur")
2. Configurer WooCommerce avec ces infos
3. Créer les pages (accueil, légales, contact)
4. Créer catégories + premiers produits
5. Configurer Boxtal + Stripe + PayPal
6. Simplifier l'interface admin pour ma grand-mère

Commence par me redemander les infos manquantes, je t'envoie tout d'un coup.
```
