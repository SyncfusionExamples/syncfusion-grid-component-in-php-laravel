# Syncfusion JavaScript Grid with Laravel and MySQL

A lightweight, production-ready pattern for binding **MySQL** data to a **Syncfusion JavaScript Grid** via **Laravel API**. The sample supports complete CRUD (Insert, Read, Update, Delete), server-side filtering, searching, sorting, and paging using **DataManager + UrlAdaptor** with Laravel Eloquent models and Blade templates.

## Key Features

- **MySQL ↔ Laravel**: Native PDO MySQL driver + migrations-driven schema
- **Syncfusion JavaScript Grid**: Built-in paging, sorting, filtering, searching, and editing via CDN
- **Full CRUD**: Add, edit, and delete directly from the grid
- **Server-side Data Operations**: Read/search/filter/sort/page handled by Laravel API
- **Blade Templates**: Server-side templating with Syncfusion components
- **RESTful API**: JSON responses with pagination metadata

## Prerequisites

| Requirement | Version | Purpose |
|-------------|---------|---------|
| **PHP** | 8.1 or higher | Laravel runtime environment |
| **Laravel** | 10.x or 11.x | PHP web application framework |
| **Composer** | 2.0+ | PHP package manager for dependencies |
| **MySQL** | 8.0 or higher | Database server |

---

## Quick Start

### 1) Clone the Repository

```bash
git clone https://github.com/SyncfusionExamples/syncfusion-grid-component-in-laravel
cd syncfusion-grid-component-in-laravel
```

### 2) Install Dependencies

```bash
composer install
```

### 3) Configure Database

Create a new MySQL database:

```bash
mysql -u root -p
CREATE DATABASE students_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

Update `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=students_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4) Run Migrations & Seed Data

```bash
php artisan migrate:fresh --seed
```

This creates the `students` table and seeds 1000 sample records.

### 5) Start Laravel Server

```bash
php artisan serve
```

Access the application at **`http://localhost:8000`**

---

## Project Structure

| File/Folder | Purpose |
|-------------|---------|
| `app/Models/Student.php` | Eloquent model for student data |
| `app/Http/Controllers/ServerController.php` | API controller handling CRUD operations |
| `database/migrations/` | Database schema definitions |
| `database/seeders/DatabaseSeeder.php` | Sample data seeder (1000 records) |
| `routes/api.php` | API routes for DataManager |
| `routes/web.php` | Web routes for Blade templates |
| `resources/views/grid.blade.php` | Blade template with Syncfusion Grid |
| `config/cors.php` | CORS configuration |

---

## API Endpoints

All endpoints receive POST requests and return JSON with `result` and `count` fields:

| Endpoint | Purpose |
|----------|---------|
| `POST /api/read` | Fetch records with paging, filtering, sorting, and searching |
| `POST /api/insert` | Insert new record |
| `POST /api/update` | Update existing record |
| `POST /api/remove` | Delete record |

**Example Response:**
```json
{
  "result": [
    { "id": 1, "FirstName": "John", "LastName": "Doe", "Email": "john@example.com", "Course": "Banking" }
  ],
  "count": 1000
}
```

---

## Grid Features

### Add a Record
1. Click **Add** in the grid toolbar
2. Fill out fields (First Name, Last Name, Email, Course)
3. Click **Save** → Creates record via `POST /api/insert`

### Edit a Record
1. Select a row → Click **Edit**
2. Modify fields → Click **Update**
3. Record updated via `POST /api/update`

### Delete a Record
1. Select a row → Click **Delete**
2. Confirm deletion → Record deleted via `POST /api/remove`

### Search / Filter / Sort
- Use the **Search** box to match across columns
- Use column filter icons for specific filtering
- Click column headers to sort (server-side sorting)

---

## Configuration

### Database Connection (`.env`)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=students_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

### CORS Configuration (`config/cors.php`)
```php
'allowed_origins' => ['*'],        // For development only
'allowed_methods' => ['*'],
'allowed_headers' => ['*'],
```

For production, replace `'*'` with your actual domain.

---

## Troubleshooting

| Issue | Solution |
|-------|----------|
| **MySQL Connection Error** | Verify MySQL is running, check `.env` credentials, ensure `students_db` exists |
| **CORS Blocked** | Enable CORS middleware, set `'allowed_origins' => ['*']` in `config/cors.php` |
| **Grid Not Loading Data** | Check browser Network tab for API response, verify DataManager URLs match routes |
| **Migration Failed** | Run `php artisan migrate:fresh` to reset, check SQL syntax in migrations |
| **Blade Template Not Found** | Verify `resources/views/grid.blade.php` exists, check web route in `routes/web.php` |
| **Syncfusion CDN Not Loading** | Check browser console, ensure internet connection, verify CDN URLs are correct |
| **Validation/422 Errors** | Ensure required fields are provided, verify field names match database columns |

---

## Technology Stack

- **Backend**: Laravel 10/11, PHP 8.1+
- **Database**: MySQL 8.0+
- **Frontend**: Vanilla JavaScript + Syncfusion Grid (CDN)
- **Templates**: Blade (Laravel)
- **API**: RESTful JSON endpoints

---

## Documentation References

- [Syncfusion JavaScript Grid Documentation](https://ej2.syncfusion.com/javascript/documentation/grid/getting-started)
- [Syncfusion Grid + Laravel Integration](https://ej2.syncfusion.com/documentation/grid/connecting-to-backends/laravel-server)
- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Eloquent ORM](https://laravel.com/docs/11.x/eloquent)

---