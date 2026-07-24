# Enterprise Vendor Registration Portal

This is a complete, production-ready Laravel 11 application for an Enterprise Vendor Registration Portal.

## Features

- **Vendor Registration**: A streamlined public-facing form for vendors to submit their details.
- **Admin Dashboard**: A secure portal for administrators to manage vendor registrations.
- **Vendor Management**: View details, approve, reject, or delete vendor registrations.
- **Data Export**: Export vendor lists to CSV, Excel, and PDF formats.

## Prerequisites

- PHP >= 8.2
- Composer
- MySQL Database

## Installation & Deployment

1. **Install Dependencies**
   Run composer to install all required PHP packages (including Laravel framework, DomPDF, and Excel).
   ```bash
   composer install
   ```

2. **Environment Configuration**
   Copy the example environment file and set up your database connection:
   ```bash
   cp .env.example .env
   ```
   *Update your `.env` file with your `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD`.*

3. **Application Key**
   Generate a unique application key:
   ```bash
   php artisan key:generate
   ```

4. **Database Migration & Seeding**
   Run the migrations to create the tables, and seed the initial admin account:
   ```bash
   php artisan migrate --seed
   ```
   *The default admin account credentials are:*
   - **Email:** admin@vendorconnect.com
   - **Password:** password

5. **Storage Link**
   Create a symbolic link to ensure uploaded vendor files (ID cards, logos) are publicly accessible:
   ```bash
   php artisan storage:link
   ```

6. **Serve the Application**
   For local development:
   ```bash
   php artisan serve
   ```
   For production, configure your web server (Nginx/Apache) to point to the `public/` directory.

## Design

The UI is built using standard HTML, vanilla CSS (no Tailwind), and JavaScript, adhering strictly to the Kinetic Enterprise design system.
