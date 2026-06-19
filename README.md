# CritCrafter v3

Character creation and management app for the LOSS tabletop RPG system.

## Stack

- **Laravel** 13
- **Filament** 5
- **MySQL** 8.0
- **PHP** 8.3
- **TailwindCSS** + Vite
- **DDEV** for local development

## Local Setup

**Requirements:** [DDEV](https://ddev.com)

```bash
ddev start
ddev exec php artisan migrate
```

App is available at `https://critcrafter.ddev.site`.

## Useful Commands

```bash
ddev exec php artisan <command>
ddev exec composer <command>
ddev exec npm <command>
```
