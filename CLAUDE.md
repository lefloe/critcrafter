# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
# Initial setup
composer setup

# Development (starts server, queue, log viewer, and Vite concurrently)
composer dev

# Run all tests
composer test

# Run a single test file or filter
php artisan test --filter=TestClassName
php artisan test tests/Feature/SomeTest.php

# Code formatting (Laravel Pint)
./vendor/bin/pint

# Frontend assets
npm run dev     # watch mode
npm run build   # production build

# Database
php artisan migrate
php artisan migrate:fresh --seed
```

## Architecture

**Critcrafter v2** is a Laravel 12 + Filament 4 application for managing LOSS RPG (German tabletop RPG) character sheets.

### Multi-Panel Structure

Filament is configured with three separate panels, each with its own provider in `app/Providers/Filament/`:

- **Admin Panel** (`/admin`) — user management, admin-only access (`role === 'admin'`)
- **Player Panel** (`/player`) — character and equipment management for regular users
- **GM Panel** (`/gm`) — game master view, set as the default panel

Each panel auto-discovers its resources from the corresponding directory under `app/Filament/{Admin,Player,Gm}/`.

### Domain Models

- **User** — has a `role` field (`admin`/`user`), implements `FilamentUser` to control panel access
- **Character** — belongs to User, soft-deleted, many-to-many with Equipment; stores RPG stats and JSON arrays (racial traits, class abilities, skills, lore, etc.)
- **Equipment** — belongs to User, many-to-many with Character; stores weapon/armor/shield attributes and JSON extension arrays
- **CharacterEquipment** — pivot model for the Character↔Equipment relationship with slot data

### Filament Resource Organization

Each Filament resource separates its UI concerns into dedicated classes:

- `CharacterForm` — form schema for create/edit
- `CharacterInfolist` — display schema for view pages
- `CharactersTable` — table columns and filters for the list page

Resources scope queries to the authenticated user's own records (`where('user_id', auth()->id())`).

### Key Files

- `app/Providers/Filament/` — panel registration and configuration
- `app/Filament/Player/Resources/Characters/` — main character resource with form/table/infolist split
- `app/Helpers/` — `limitClassabilitiesHelper.php` and `equipmentextensionshelper.php`
- `database/migrations/` — schema for characters (80+ columns) and equipment tables

### Testing

Tests use an in-memory SQLite database (configured in `phpunit.xml`). Feature tests are under `tests/Feature/`, unit tests under `tests/Unit/`.
