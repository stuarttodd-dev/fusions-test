# Laravel Template

Thanks for forking this Laravel 12 + Inertia + Vue 3 starter—your batteries-included foundation for clean, repeatable projects.

<p align="center">
  <img src="https://halfshellstudios.co.uk/assets/images/primary-logo.png" alt="Half Shell Studios Logo" width="220" />
</p>

<p align="center">
  <img src="http://stuart-todd.co.uk/theme/assets/img/profile2.jpg" alt="Stuart Todd" width="160" style="border-radius: 50%;" />
</p>

**Need the backstory?** Check out the creator’s CV here: [halfshellstudios.co.uk/cv.pdf](https://halfshellstudios.co.uk/cv.pdf)  
**Portfolio & contact:** [halfshellstudios.co.uk](https://halfshellstudios.co.uk)

---

## Why Fork This Template?
- Fully dockerised (Laravel Sail) with PHP 8.4 + Node 22.x available inside the container.
- Inertia SPA stack preconfigured (Vue 3, Ziggy, Tailwind, Vite).
- Testing story out of the box: Pest, Jest, Playwright, plus coverage support.
- Opinionated quality gate: PHPCS, PHPMD, PHPStan, Rector, Pint.
- CI ready: GitHub Actions runs the exact Composer scripts used locally.
- Developer UX extras: Telescope, Debugbar, custom scripts, seeded demo account.

---

## Quick Start
1. Ensure Docker Engine is installed and running.  
   - macOS / Windows: [Download Docker Desktop](https://www.docker.com/products/docker-desktop/) and follow the installer prompts.  
   - Linux: install via your package manager or follow the [Docker Engine setup guide](https://docs.docker.com/engine/install/).  
   - After installation, launch Docker Desktop (or start the Docker service) so containers can boot.
2. Fork this repository (recommended) or clone it directly.
3. Copy the environment file: `cp .env.example .env`
4. Install PHP dependencies: `composer install`
5. Build the Sail images: `docker-compose build`
6. Boot the stack: `docker-compose up -d`
7. Run database migrations and seeders: `docker-compose exec web php artisan migrate:fresh --seed`
8. Install front-end packages: `docker-compose exec web npm install`
9. Start the dev server (Vite + Laravel): `docker-compose exec web npm run dev`
10. Visit the application at http://127.0.0.1:8008/

Default login: **hello@halfshellstudios.co.uk** / **password**

**Helpful commands**
- Clear routes (e.g. after tweaking Jetstream): `docker-compose exec web php artisan route:clear`
- Node.js is already available inside the `web` container (Node 22.x); no host installation is necessary.

---

### Composer Scripts
Common tooling is bundled behind Composer scripts for quick access:

- `composer tests` &mdash; run the full automated test suite.
- `composer test:coverage` &mdash; run the Pest suite with code coverage (`XDEBUG_MODE=coverage` required). Append `-- --coverage-html=storage/logs/coverage` to emit an HTML report.
- `composer standards:check` &mdash; execute `phpcs`, `phpmd`, `phpstan`, and a Rector dry-run.
- `composer standards:fix` &mdash; apply automated code-style fixes via `phpcbf` and Rector.
- Individual checks are also exposed (`composer phpcs`, `composer phpstan`, `composer rector`, etc.).

You can run these locally or inside the container, e.g. `docker-compose exec web composer standards:check`.

---

## Toolchain Overview

| Area            | Tooling / Highlights                                         |
|-----------------|--------------------------------------------------------------|
| Core Framework  | Laravel 12, Jetstream (Inertia + Vue)                        |
| Front-end       | Vue 3, Vite 7, TailwindCSS 3, Ziggy, Axios                   |
| PHP Quality     | Pest (100% coverage pre-configured), PHPStan, PHPMD, PHPCS, Rector, Pint, Telescope |
| JS Testing      | Jest (unit), Playwright (E2E)                                |
| Monitoring      | Sentry, Laravel Debugbar                                     |
| Dev Experience  | Sail (Docker), Telescope, Debugbar, seeded demo account      |

---

## Continuous Integration
- GitHub Actions workflow (`.github/workflows/ci.yml`) mirrors the local Composer scripts.
- Every push / PR runs:
  - `composer tests` (builds assets, provisions SQLite testing DB, executes Pest)
  - `composer standards:check` (PHPCS, PHPMD, PHPStan, Rector dry-run)
- Node dependencies are installed via `npm ci` inside the workflow to run the compiled assets required by the test suite.
- Use the template as-is to keep your branches clean, or extend the pipeline for staging/prod deploys.

---

### Laravel Pint
This template comes bundled with Laravel Pint.
https://laravel.com/docs/12.x/pint

**To run**
`docker-compose exec web ./vendor/bin/pint`

**To run against a specific folder**
i.e for **app/Models** `docker-compose exec web ./vendor/bin/pint app/Models`

### Laravel Jetstream
Jetstream is installed with the Inertia stack by default.
https://jetstream.laravel.com/introduction.html

To regenerate the scaffolding, run:
`docker-compose exec web php artisan jetstream:install inertia --dark`

### Database Access
Connect with any SQL client (TablePlus, DataGrip, MySQL Workbench, psql, etc.) using the credentials defined in your `.env` file. By default the Sail stack exposes:

- **Host:** `127.0.0.1`
- **MySQL Port:** `3306`
- **Database:** value of `DB_DATABASE` (default `laravel`)
- **Username:** value of `DB_USERNAME` (default `sail`)
- **Password:** value of `DB_PASSWORD` (default `password`)

#### GUI tools (e.g. TablePlus)
1. Install the client (TablePlus is available at [tableplus.com](https://tableplus.com/)).
2. Create a new **MySQL** connection.
3. Enter the host, port, database, username, and password listed above (or customised values from `.env`).
4. Save and connect to browse tables, manage data, and run queries.

#### Command line
```
docker-compose exec mysql mysql -u"$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE"
```

Swap `mysql` for `psql` if you enable the bundled PostgreSQL service.

### Tests
- PHP feature/unit tests: `docker-compose exec web composer tests`
- PHP coverage (requires Xdebug): `docker-compose exec web composer test:coverage` (alias: `composer coverage`)
- Jest unit tests: `docker-compose exec web npm run test:unit`
- Playwright E2E: `docker-compose exec web npm run test:e2e`
- Playwright HTML report: `docker-compose exec web npm run test:e2e -- --project=chromium --reporter=html && npx playwright show-report`
- Playwright UI mode: `docker-compose exec web npm run test:e2e:ui`

---

## Next Steps After Forking
1. Update `composer.json` (name, description, namespaces if needed).
2. Update environment defaults and secrets (`.env`, `.env.example`).
3. Tailor Jetstream scaffolding or Inertia pages to your product.
4. Wire CI/CD secrets for Sentry, production DB, etc.
5. Start building on a clean, consistent foundation.

