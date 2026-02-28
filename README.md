# Austrian Holidays Calendar

A minimal, single-page web application that displays all Austrian public holidays in a full-year calendar view. Built with Laravel and Livewire — no JavaScript frameworks required.

## Features

- Full year calendar grid (January–December) with weeks starting on Monday
- All 13 Austrian public holidays highlighted with tooltips showing descriptions
- "Next upcoming holiday" banner with countdown in the page header
- Responsive layout (1–4 columns depending on screen size)
- Dark mode support
- Today's date visually emphasised

## Requirements

- PHP >= 8.2
- Composer
- Node.js & npm

## Installation

```bash
git clone <repository-url>
cd calendar

composer install
npm install

cp .env.example .env
php artisan key:generate
```

## Running Locally

```bash
# Build frontend assets
npm run build

# Start the development server
php artisan serve
```

For live CSS rebuilding during development:

```bash
npm run dev
```

Then open [http://localhost:8000](http://localhost:8000).

## Packages Used

| Package | Purpose |
|---|---|
| [Laravel](https://laravel.com/) (v12) | PHP web framework |
| [Livewire](https://livewire.laravel.com/) (v4) | Reactive server-rendered UI components |
| [spatie/holidays](https://github.com/spatie/holidays) | Public holiday data source (country: AT) |
| [Tailwind CSS](https://tailwindcss.com/) (v4) | Utility-first CSS framework |
| [Alpine.js](https://alpinejs.dev/) | Lightweight JS for tooltips (bundled with Livewire) |

## License

MIT
