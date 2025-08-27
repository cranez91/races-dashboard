# Races Dashboard

A Laravel 10 + Vue 3 application for managing participants in athletic races (5K & 10K), with filtering, inline editing, real-time updates, dark mode, and keyboard shortcuts.

## Features

- **Paginated Data Dashboard**: View participants with server-side pagination and multiple filters (race and category).
- **Inline Editing with Optimistic Updates**: Edit participant data directly in table rows. Changes are reflected instantly with error handling.
- **Dark Mode Support**: User preference is persisted.
- **Keyboard Shortcuts**: Navigate and perform common dashboard actions efficiently.
- **Authentication**: Users must register and log in to access the dashboard.
- **Seeder Example**: Default admin user seeded for development/testing.

## Tech Stack

- **Backend**: Laravel 10
- **Frontend**: Vue 3 + Inertia.js
- **State Management**: Pinia
- **Database**: PostgreSQL 15
- **Testing**: PestPHP
- **Styling**: TailwindCSS 2.x
- **Additional**: SweetAlert2 for notifications

## Setup Instructions

### Requirements

- PHP 8.2+
- Composer
- Node.js 20+
- PostgreSQL 15
- NPM or Yarn

### Installation

1. Clone the repository:
```bash
git clone https://github.com/cranez91/races-dashboard.git
cd races-dashboard
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install JavaScript dependencies:
```bash
npm install
```

4. Copy .env.example and configure database credentials:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Run migrations and seed default admin:
```bash
php artisan migreate --seed
```

7. Build frontend assets:
```bash
npm run dev
```

8. Start Laravel development server:
```bash
php artisan serve
```

9. Access the app at http://localhost:8000.

### Testing
Run tests using Pest in the testing environment:
```bash
php artisan test --env=testing
```

### Default Admin User
- Email: admin@curotec.com
- Password: passwordCuro123

### Folder Structure Highlights
- app/Models - Eloquent models
- app/Http/Controllers - Controllers for API and UI
- resources/js/Pages - Vue pages
- resources/js/Layouts - Layout components
- resources/js/stores - Pinia stores
- database/factories - Model factories
- database/seeders - Seeders

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
