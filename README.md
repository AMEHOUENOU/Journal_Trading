# TradeLog — Journal de trading en ligne

Journal de trading multi-comptes pour suivre, analyser et améliorer ses performances de trading. Construit avec Laravel + Blade.

## Fonctionnalités

- **Authentification multi-utilisateurs** (Laravel Breeze)
- **Multi-comptes** de trading (prop firm, perso, etc.) par utilisateur
- **Gestion des trades** : symbole, direction, entrée/sortie, SL/TP, taille de position, statut de clôture (SL touché, TP touché, manuel, breakeven)
- **Calcul automatique** du ratio R:R à la sauvegarde
- **Screenshots multiples** par trade (galerie de captures d'écran)
- **Stratégies détaillées** : description, règles d'entrée/sortie, statistiques par stratégie (win rate, profit factor)
- **Tags** pour catégoriser rapidement les trades
- **Import/Export CSV** des trades
- **Journal quotidien** (notes libres par jour)
- **Dashboard global** avec courbe d'équité, win rate, profit factor, R:R moyen (tous comptes confondus)
- **Calendrier économique** en temps réel (CPI, NFP, FOMC, etc.) via le flux public ForexFactory

## Stack technique

- **Backend** : Laravel 13
- **Frontend** : Blade + Tailwind CSS
- **Auth** : Laravel Breeze
- **Base de données** : PostgreSQL
- **PHP** : 8.3

## Installation

### Prérequis

- PHP 8.3+
- Composer
- Node.js 18+ / npm
- PostgreSQL

### Étapes

1. Cloner le dépôt
```bash
git clone https://github.com/TON-USERNAME/journal-trading.git
cd journal-trading
```

2. Installer les dépendances PHP
```bash
composer install
```

3. Installer les dépendances JS et builder les assets
```bash
npm install
npm run build
```

4. Copier le fichier d'environnement et générer la clé d'application
```bash
cp .env.example .env
php artisan key:generate
```

5. Configurer la base de données dans `.env`

   
6. Lancer les migrations
```bash
php artisan migrate
```

7. Créer le lien symbolique pour le storage (screenshots)
```bash
php artisan storage:link
```

8. Lancer le serveur
```bash
php artisan serve
```

L'application est accessible sur `http://127.0.0.1:8000`.

## Structure des données

- `users` — comptes utilisateurs
- `accounts` — comptes de trading (multi-comptes par utilisateur)
- `trades` — trades individuels, liés à un compte
- `tags` — stratégies (nom, description, règles d'entrée/sortie)
- `tag_trade` — relation many-to-many entre trades et stratégies
- `trade_photos` — captures d'écran liées à un trade (multi-photos)
- `daily_notes` — journal quotidien par utilisateur

## Roadmap

- [ ] Analyse de données avancée (Python/pandas) — détection de patterns, corrélations horaires
- [ ] Alertes de dépassement de drawdown
- [ ] Dashboard public partageable en lecture seule
- [ ] API REST pour connexion mobile/bot

## Licence

© 2026 Komi Amehouenou. Tous droits réservés. Ce code est visible publiquement à titre de portfolio, mais n'est pas sous licence libre d'utilisation.
