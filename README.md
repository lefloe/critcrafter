# Critcrafter v2

A Laravel v12 project with Filament v4.1 for the LOSS RPG management.

## Setup

### Requirements

- Docker & DDEV
- PHP 8.4
- MySQL

### DDEV

```bash
ddev start
ddev composer install
ddev artisan migrate
ddev artisan serve
