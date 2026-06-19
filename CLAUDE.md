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
