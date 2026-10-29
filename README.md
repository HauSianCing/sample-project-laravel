# Employee Registration System

A sample employee management web app built with **Laravel 13** (PHP 8.3+).
Employees log in with their employee ID, then register, search, edit, inactivate and
delete other employees, import employees in bulk from Excel, and export search results
to Excel or PDF. The interface is available in **English** and **Myanmar**.

## Features

- **Login / logout** with employee ID and password (session based, password hashed with bcrypt)
- **Employee list** with search by ID, code, name and email, and pagination (20 per page)
- **Register** an employee with an optional photo (JPG/PNG, up to 10 MB); employee IDs are generated automatically starting from `10001`
- **Bulk register from Excel**: download the `employee_registration_form.xlsx` template, fill it in and upload it (up to 100 rows). Every row is validated first; nothing is saved unless all rows are valid
- **Detail and edit** screens, including replacing the photo
- **Inactivate / activate** employees (soft delete) and **delete** them permanently; you can't inactivate or delete your own account while logged in, and inactivated employees are logged out
- **Download search results** as Excel (`.xlsx`) or PDF
- **Language switch** between English (`en`) and Myanmar (`mm`)

## Tech stack

| Part | Used |
| --- | --- |
| Framework | Laravel 13, PHP 8.3+ |
| Database | MySQL (SQLite works too, and is used for tests) |
| Excel | [maatwebsite/excel](https://docs.laravel-excel.com) 3.1 (PhpSpreadsheet) |
| PDF | [mPDF](https://mpdf.github.io) 8 |
| Frontend | Blade, Bootstrap 5 and jQuery (from CDN, no build step) |

## Getting started

Requirements: PHP 8.3+ with the `gd`, `mbstring`, `zip`, `pdo_mysql` (or `pdo_sqlite`) extensions, Composer 2, and MySQL.

```bash
git clone https://github.com/HauSianCing/sample-project-laravel.git
cd sample-project-laravel

composer install
cp .env.example .env
php artisan key:generate
```

Create a database (default name `employee_system`) and set `DB_*` in `.env`, then:

```bash
php artisan migrate --seed
php artisan serve
```

Open http://localhost:8000 and log in with the seeded account:

| Employee ID | Password |
| --- | --- |
| `10001` | `11111111` |

> Change or remove this account before using the app anywhere public.

Uploaded photos are stored in `public/photo/`, so the web server needs write access to that folder.

### Using SQLite instead of MySQL

```bash
touch database/database.sqlite
```

and in `.env` set `DB_CONNECTION=sqlite` and remove the other `DB_*` lines.

## Running the tests

```bash
php artisan test
```

The tests use an in-memory SQLite database and cover login/logout, search, registration
(with and without a photo), editing, inactivate/activate, delete, Excel import, and the
Excel/PDF downloads.

## Project structure

```
app/
├── Classes/DBTransaction.php        # base class: runs process() inside a DB transaction
├── DBTransactions/                  # save / update / delete operations for employees and photos
├── Exports/                         # Excel template and search-result exports
├── Http/
│   ├── Controllers/                 # Employee, Excel, PDF and language controllers
│   ├── Middleware/                  # employee login check (empCheck), language (lang)
│   └── Requests/                    # form validation
├── Imports/EmployeeExcelImport.php  # bulk registration from Excel
├── Interfaces/ + Repositories/      # data access (bound in RepositoryServiceProvider)
└── Models/                          # Employee, EmployeeUpload
lang/en, lang/mm                     # UI translations
resources/views/employees/           # Blade screens
```

## Main routes

| Method | URL | Purpose |
| --- | --- | --- |
| GET | `/login` | Login screen |
| POST | `/logout` | Log out |
| GET | `/employees/lists` | List and search employees |
| GET | `/employees/register` | Register form |
| GET | `/employees/register/excel-registers` | Excel bulk registration |
| GET | `/employees/show/{id}` | Employee detail |
| GET | `/employees/edit/{id}` | Edit form |
| GET | `/employees/excel-downloads` | Search results as Excel |
| GET | `/employees/pdf-search-downloads` | Search results as PDF |

## Author

Hau Sian Cing

## License

MIT
