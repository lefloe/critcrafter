# CritCrafter v3 — Claude Instructions

## Git
- This directory contains an existing git repository with history (`legacy/v1`, `legacy/v2`, tags `v1.0.0`/`v2.2.0`). **NEVER run `git init`.**
- Active development branch: `v3` (orphan branch — no parent commits, clean slate)
- Do not carry over architectural assumptions from v2 (different Filament panel structure, old Character/Equipment schema). The LOSS ruleset changed significantly; the data model is being redesigned.

## Stack
- **PHP** 8.3
- **Laravel** 13
- **Filament** 5 — single panel (`AdminPanelProvider`) for now; architecture TBD
- **Database** MySQL 8.0
- **Frontend** TailwindCSS, Vite, Node.js 18
- **Local dev** DDEV (`ddev.site`)

## Local Development
- Start: `ddev start`
- Artisan: `ddev exec php artisan <command>`
- Composer: `ddev exec composer <command>`
- URL: `https://critcrafter.ddev.site`
- DB credentials: host `db`, database/user/password all `db`

## Database
- Connection: MySQL (not SQLite)
- Tests use SQLite `:memory:` via `phpunit.xml` — this is intentional, do not change it

## Deployment (Laravel Cloud)
Production runs on [Laravel Cloud](https://cloud.laravel.com) with push-to-deploy on the `v3` branch.

**Build commands** (configured in dashboard):
```
composer install --no-dev --optimize-autoloader --no-interaction
npm ci && npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Deploy commands** (configured in dashboard):
```
php artisan migrate --force
php artisan storage:link
```

**Infrastructure:**
- PHP 8.3, managed MySQL, Cloudflare R2 object storage (S3-compatible)
- Queue/cache: `database` driver (Redis can be added later when jobs exist)

**Dashboard-injected env vars** (do not hardcode):
- `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`, `AWS_BUCKET`, `AWS_ENDPOINT`, `AWS_URL`
- `APP_KEY`, `APP_ENV=production`, `APP_DEBUG=false`, `FILESYSTEM_DISK=s3`
