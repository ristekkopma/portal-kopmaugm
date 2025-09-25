# Copilot Instructions for portal-kopma.net

## Project Overview
This is a Laravel-based web application for managing a cooperative portal. The codebase follows Laravel conventions but includes custom domain logic and integrations.

## Architecture & Key Components
- **app/Models/**: Eloquent models for core entities (e.g., `Member`, `User`, `Transaction`, `Wallet`).
- **app/Http/Controllers/**: Handles HTTP requests, business logic, and data flow between models and views.
- **app/Imports/**: Custom importers for bulk data operations (e.g., `MemberImport`, `TransactionImport`).
- **app/Enums/**: Domain-specific enums for statuses, roles, and types.
- **app/Filament/**: Filament admin panel customizations (components, pages, resources, widgets).
- **app/Observers/**: Model observers for side effects (e.g., updating related data on changes).
- **resources/views/**: Blade templates for UI rendering.
- **routes/web.php**: Main route definitions for web endpoints.

## Developer Workflows
- **Local Development**: Use XAMPP for local server and database. Entry point is `public/index.php`.
- **Build Assets**: Run `npm install` then `npm run build` for frontend assets (uses Vite & Tailwind).
- **Testing**: Run `php artisan test` for PHPUnit tests. Test files are in `tests/Feature/` and `tests/Unit/`.
- **Database Migrations**: Use `php artisan migrate` to apply migrations. Seed with `php artisan db:seed`.
- **Debugging**: Use Laravel's built-in logging (`storage/logs/`) and exception handling. Filament provides admin UI for data inspection.

## Project-Specific Patterns
- **Enums**: All domain enums are in `app/Enums/` and used for type safety in models and controllers.
- **Imports**: Bulk data importers are in `app/Imports/` and typically invoked via custom console commands or Filament actions.
- **Observers**: Business logic side effects (e.g., updating points, cycles) are handled in `app/Observers/`.
- **Filament Customization**: Admin panel features are extended in `app/Filament/` (see `Pages/`, `Resources/`, `Widgets/`).
- **Role Management**: User roles and permissions are managed via enums and Filament resources.

## Integration Points
- **External Packages**: Key dependencies include Filament (admin panel), Maatwebsite Excel (imports), and Laravel core packages.
- **Frontend**: Uses Vite for asset bundling and Tailwind CSS for styling.
- **Localization**: Language files are in `lang/` (e.g., `id.json`).

## Examples
- To add a new member import type, create a new class in `app/Imports/` and register it in the relevant controller or Filament resource.
- To extend admin UI, add a new page or widget in `app/Filament/Pages/` or `app/Filament/Widgets/`.
- To add a new enum, define it in `app/Enums/` and update model attributes and validation logic accordingly.

## References
- [Laravel Documentation](https://laravel.com/docs)
- Filament Admin: [https://filamentphp.com/docs/2.x/admin/panels](https://filamentphp.com/docs/2.x/admin/panels)
- Maatwebsite Excel: [https://docs.laravel-excel.com/3.1/](https://docs.laravel-excel.com/3.1/)

---
_If any section is unclear or missing important project-specific details, please provide feedback to improve these instructions._
