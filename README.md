![Logo](https://cis-security.co.uk/wp-content/uploads/2023/02/cis-logo-blue.png)

# SHEQ Management System


A **Laravel-based Safety, Health, Environment, and Quality (SHEQ) Management System** designed to help organisations manage compliance, documentation, incidents, audits, and operational processes within a single central platform.

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.x-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![MySQL](https://img.shields.io/badge/MySQL-MariaDB-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://www.mysql.com)

📖 [Documentation](https://cissecurityltd.sharepoint.com/:f:/s/cis-shared-docs/IgD3G8f06Q-wSIq2HmpPYBZUAXfTsaDteXeq8rXrE-J2ZYY?e=mQT2o5) · 🚀 [Live Demo](https://dev.sms-cis-security.co.uk/login) · 🐛 [Report a Bug](https://forms.monday.com/forms/d8037e786c063e00bf2182f1e4d67a5b?r=use1)

---

## Overview

Built with **Laravel 12** using **Blade templates**, the system follows modern Laravel development practices including:

- Database migrations for version-controlled schema management
- Environment-based configuration via `.env`
- Automated testing with PHPUnit
- Structured, repeatable deployment processes

---

## Tech Stack

| Layer     | Technology                         |
|-----------|------------------------------------|
| Backend   | Laravel 12, PHP 8.2+               |
| Database  | MySQL / MariaDB                    |
| Frontend  | Blade Templates, TailwindCSS       |
| Dev Tools | Composer, PHPUnit, Laravel Artisan |

---

## Getting Started

### Prerequisites

- PHP 8.2+
- Composer
- Node.js & npm
- MySQL or MariaDB
- A web server (Apache / Nginx) or Laravel's built-in development server

### Installation

**1. Clone the repository**

```bash
git clone https://github.com/CIS-SecurityLTD/CIS-SHEQ.git
cd sheq-management-system
```

**2. Install PHP dependencies**

```bash
composer install
```

**3. Install Node dependencies**

```bash
npm install
```

**4. Set up your environment**

```bash
cp .env.example .env
php artisan key:generate
```

**5. Configure your database in `.env`**

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

**6. Run database migrations**

```bash
php artisan migrate
```

**7. Start the development server**

```bash
npm run dev
php artisan serve
```

The application will be available at **http://localhost:8000**

---

## Database

All schema changes are managed through **Laravel migrations**, ensuring a consistent and reproducible database state across all environments.

```bash
# Run pending migrations
php artisan migrate

# Rebuild the database from scratch
php artisan migrate:fresh
```

---

## Running Tests

Tests are located in the `tests/` directory. Run the full test suite with:

```bash
php artisan test
```

---

## Deployment

The application is currently deployed via **FTP to shared hosting**.

> **Note:** Node.js is only required to build frontend assets. It does not need to be installed on the production server — only the compiled output from `npm run build` needs to be uploaded.

**1.** Upload project files to the server

**2.** Install production PHP dependencies

```bash
composer install --no-dev --optimize-autoloader
```

**3.** Build frontend assets (TailwindCSS / Vite)

```bash
npm ci
npm run build
```

**4.** Run database migrations

```bash
php artisan migrate --force
```

**5.** Cache configuration, routes, and views for performance

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**6.** Optimise Laravel for production

```bash
php artisan optimize
```

**7.** Ensure the following directories are writable by the web server

```
storage/
bootstrap/cache/
```

---

## Environment Variables

All configuration is handled through the `.env` file. Key variables include:

```env
# Application
APP_NAME=
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=

# Database
DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

> **Note:** Never commit your `.env` file to version control. Ensure it is listed in `.gitignore`.

---

## Support

For support or development queries, please contact **CIS Security Ltd** or raise an issue within this repository.

---

## License

This is an internal project and is not currently licensed for external distribution.
