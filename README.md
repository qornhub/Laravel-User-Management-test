# Laravel Developer Assessment Test

A Laravel-based user management system developed as part of Assessment.

The application provides:

- Admin authentication and authorization
- User CRUD management
- User status filtering
- Pagination
- Soft deletion
- Bulk user deletion
- Excel export

---

## Tech Stack

| Technology | Version / Details |
|---|---|
| Laravel | 12.69.3 |
| PHP | 8.4.26 |
| Composer | 2.10.3 |
| MySQL | 8.4 |
| Node.js | 24.11.1 |
| npm | 11.6.2 |
| Vite | 7.3.6 |
| Laravel Breeze | Blade |
| PHPUnit | Laravel testing framework |
| Tailwind CSS | Frontend styling |
| Laravel Excel | `maatwebsite/excel` |
| Docker | Containerized development environment |

---

## Features

### Admin Web Interface

Authenticated administrators can:

- View users
- Create users
- Edit users
- Delete users
- Bulk delete users
- Filter users by status
- Navigate users using pagination
- Export users to Excel

### Authentication & Authorization

The web administration area is protected by:

1. Laravel authentication
2. Custom admin authorization middleware

Only users with `is_admin = true` can access the admin user-management functions.

Non-admin authenticated users receive an HTTP `403 Forbidden` response.

### User Fields

Each user contains:

- ID
- Name
- Email
- Phone Number
- Password
- Status
- Admin status
- Deleted timestamp
- Created timestamp
- Updated timestamp

---

# API Documentation

The REST API is intentionally public based on the assessment requirement that the API be accessible by anyone.

The API uses Laravel API Resources to control the response structure and prevent sensitive fields such as passwords from being returned.

## API Base URL

For local development:

```text
http://localhost:8000/api
```

---

## API Endpoints

| Method | Endpoint | Description |
|---|---|---|
| `POST` | `/api/users` | Create a user |
| `GET` | `/api/users` | List users |
| `GET` | `/api/users/{id}` | Get a user |
| `DELETE` | `/api/users/{id}` | Soft delete a user |
| `DELETE` | `/api/users/bulk` | Bulk soft delete users |

---

## Create User

### Request

```http
POST /api/users
Content-Type: application/json
```

### Example Request Body

```json
{
    "name": "John",
    "email": "john@example.com",
    "phone_number": "0123456789",
    "password": "password123",
    "password_confirmation": "password123",
    "status": "active"
}
```

### Example Response

```json
{
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "phone_number": "0123456789",
        "status": "active",
        "deleted_at": null,
        "created_at": "2026-10-05T10:00:00.000000Z",
        "updated_at": "2026-10-05T10:00:00.000000Z"
    }
}
```

### Response Status

```text
201 Created
```

---

## List Users

### Request

```http
GET /api/users
```

### Status Filtering

Users can be filtered by status:

```http
GET /api/users?status=active
```

or:

```http
GET /api/users?status=inactive
```

### Pagination

The API returns 10 users per page.

Example:

```http
GET /api/users?page=2
```

### Example Response

```json
{
    "data": [
        {
            "id": 1,
            "name": "John",
            "email": "john@example.com",
            "phone_number": "0123456789",
            "status": "active",
            "deleted_at": null,
            "created_at": "2026-10-05T10:00:00.000000Z",
            "updated_at": "2026-10-05T10:00:00.000000Z"
        }
    ],
    "links": {
        "first": "http://localhost:8000/api/users?page=1",
        "last": "http://localhost:8000/api/users?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "per_page": 10,
        "to": 1,
        "total": 1
    }
}
```

---

## Get User

### Request

```http
GET /api/users/1
```

### Example Response

```json
{
    "data": {
        "id": 1,
        "name": "John",
        "email": "john@example.com",
        "phone_number": "0123456789",
        "status": "active",
        "deleted_at": null,
        "created_at": "2026-10-05T10:00:00.000000Z",
        "updated_at": "2026-10-05T10:00:00.000000Z"
    }
}
```

---

## Delete User

### Request

```http
DELETE /api/users/1
```

The user is soft deleted rather than permanently removed from the database.

### Response Status

```text
204 No Content
```

---

## Bulk Delete Users

### Request

```http
DELETE /api/users/bulk
Content-Type: application/json
```

### Example Request Body

```json
{
    "user_ids": [1, 2, 3]
}
```

### Example Response

```json
{
    "message": "Selected users deleted successfully."
}
```

### Response Status

```text
200 OK
```

---

# Validation

Laravel Form Requests are used for user creation and updates.

## Create User Validation

The following validation rules are applied:

- `name` is required and must be a string
- `email` is required and must be a valid email address
- `email` must be unique
- `phone_number` is required
- `phone_number` must be unique
- `password` is required
- Password must contain at least 5 characters
- Password confirmation is required
- `status` must be either `active` or `inactive`

## Update User Validation

The update request uses the same validation rules while allowing the current user's existing email and phone number to remain unchanged.

---

# Security

The application implements several security measures.

## Authentication

Laravel Breeze provides authentication for the admin web interface.

## Authorization

A custom `admin` middleware ensures that only users with:

```text
is_admin = true
```

can access the administration area.

Unauthenticated users are redirected to login, while authenticated non-admin users receive:

```text
403 Forbidden
```

## Password Security

Passwords are hashed using Laravel's built-in password hashing mechanism.

Passwords are also hidden from serialized User model output.

## Mass Assignment Protection

The User model uses an explicit `$fillable` list to control which attributes can be mass assigned.

## CSRF Protection

Laravel's built-in CSRF protection is used for web form requests.

## Input Validation

User input is validated through Laravel Form Requests before database operations.

## Database Constraints

Email and phone number have database-level unique constraints.

## API Data Exposure

Laravel API Resources control which user fields are exposed by the API.

Passwords are never returned through the API.

---

# Excel Export

Administrators can export users from the admin dashboard.

The export is implemented using:

```text
maatwebsite/excel
```

The exported Excel file contains:

- ID
- Name
- Email
- Phone Number
- Status
- Created At

The export uses `FromQuery` so that users are retrieved through a database query instead of loading the entire dataset into memory at once.

---

# Database Design

The application uses MySQL 8.4.

The `users` table contains:

| Column | Description |
|---|---|
| `id` | Primary key |
| `name` | User name |
| `email` | Unique email address |
| `phone_number` | Unique phone number |
| `password` | Hashed password |
| `status` | `active` or `inactive` |
| `is_admin` | Indicates administrator access |
| `deleted_at` | Soft delete timestamp |
| `created_at` | Creation timestamp |
| `updated_at` | Last update timestamp |

Soft deletes are implemented using Laravel's `SoftDeletes` feature.

---

# Testing

The project includes PHPUnit feature tests covering the main assessment requirements.

## User Management Tests

The web application tests cover:

- Admin dashboard access
- User creation
- Duplicate email validation
- User updates
- Soft deletion
- Bulk deletion
- Status filtering
- Unauthenticated access protection
- Non-admin authorization

## API Tests

The API tests cover:

- User creation
- User listing
- Pagination
- Status filtering
- User details
- Soft deletion
- Bulk deletion
- Duplicate email validation

## Run Assessment Tests

Run the focused assessment tests with:

```bash
docker compose exec app php artisan test --filter='User(Management|Api)Test'
```

The current focused test suite contains:

```text
UserManagementTest
UserApiTest
```

All assessment tests pass.

## Run All Tests

To run the complete PHPUnit test suite:

```bash
docker compose exec app php artisan test
```

---

# Project Setup

The application supports two development approaches:

1. Docker
2. Local PHP/MySQL installation without Docker

---

# Option 1: Docker Setup

Docker is the recommended setup because it provides a consistent PHP and MySQL environment.

## Requirements

Install:

- Docker Desktop
- Git

No local PHP, Composer, Node.js, or MySQL installation is required when using the Docker environment.

## 1. Clone the Repository

```bash
git clone <YOUR_GITHUB_REPOSITORY_URL>
cd laravel-test
```

## 2. Configure Environment

Copy the example environment file:

```bash
cp .env.example .env
```

Configure the database connection in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_test
DB_USERNAME=laravel
DB_PASSWORD=laravel
```

When Laravel runs inside Docker, `DB_HOST` must be:

```text
mysql
```

and `DB_PORT` must be:

```text
3306
```

The host machine uses port `3308` to access the MySQL container externally.

## 3. Build and Start Containers

```bash
docker compose up -d --build
```

Check that the containers are running:

```bash
docker compose ps
```

## 4. Generate Application Key

```bash
docker compose exec app php artisan key:generate
```

## 5. Run Database Migrations

```bash
docker compose exec app php artisan migrate
```

## 6. Seed the Admin Account

```bash
docker compose exec app php artisan db:seed --class=AdminUserSeeder
```

## 7. Install Frontend Dependencies

```bash
docker compose exec app npm install
```

## 8. Build Frontend Assets

```bash
docker compose exec app npm run build
```

## 9. Start / Access the Application

The Laravel application runs on:

```text
http://localhost:8000
```

Open the following URL in a browser:

```text
http://localhost:8000
```

## Docker Useful Commands

### Start containers

```bash
docker compose up -d
```

### Stop containers

```bash
docker compose down
```

### Rebuild containers

```bash
docker compose up -d --build
```

### View application logs

```bash
docker compose logs -f app
```

### Enter the application container

```bash
docker compose exec app bash
```

### Run Artisan commands

```bash
docker compose exec app php artisan <command>
```

### Run PHPUnit

```bash
docker compose exec app php artisan test
```

---

# Option 2: Local Setup Without Docker

Docker is recommended, but the project can also be run directly on a local PHP/MySQL/Node.js environment.

## Requirements

Install the following:

- PHP 8.4+
- Composer 2.10+
- MySQL 8.4+
- Node.js 24+
- npm 11+

Verify the installed versions:

```bash
php -v
```

```bash
composer --version
```

```bash
mysql --version
```

```bash
node -v
```

```bash
npm -v
```

## 1. Clone the Repository

```bash
git clone <YOUR_GITHUB_REPOSITORY_URL>
cd laravel-test
```

## 2. Install PHP Dependencies

```bash
composer install
```

## 3. Configure Environment

Copy the environment file:

```bash
cp .env.example .env
```

Update the database settings according to the local MySQL installation.

Example:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_test
DB_USERNAME=laravel
DB_PASSWORD=your_mysql_password
```

The database must exist before running the migrations.

Create the database in MySQL:

```sql
CREATE DATABASE laravel_test;
```

## 4. Generate Application Key

```bash
php artisan key:generate
```

## 5. Run Migrations

```bash
php artisan migrate
```

## 6. Seed the Admin Account

```bash
php artisan db:seed --class=AdminUserSeeder
```

## 7. Install Frontend Dependencies

```bash
npm install
```

## 8. Build Frontend Assets

```bash
npm run build
```

## 9. Start Laravel

```bash
php artisan serve
```

The application will normally be available at:

```text
http://127.0.0.1:8000
```

---

# Demo Admin Account

The seeded development administrator is:

```text
Email: admin@gmail.com
Password: 12345
```

This account is intended for local assessment/demo purposes.

For production deployments, the default credentials should be replaced with secure credentials.

---

# Design Choices

## Admin Authorization

A simple `is_admin` boolean flag was used instead of a full role and permission package.

This was chosen because the assessment only requires administrator access control and a simple boolean keeps the implementation lightweight and easy to maintain.

## API Authentication

The API is intentionally unauthenticated because the assessment explicitly requires the REST API to be accessible by anyone.

The admin web interface remains protected by authentication and authorization.

## Soft Deletes

Users are soft deleted instead of permanently removed.

This preserves the database record and allows the application to retain historical information.

## Pagination

User listings use pagination with 10 records per page.

This avoids loading a potentially large number of users into memory during normal listing operations.

## Status Filtering

Users can be filtered using the `status` parameter:

```text
active
inactive
```

The same filtering capability is available in both the web interface and API.

## API Resources

Laravel API Resources are used to control the structure of API responses.

This also prevents sensitive model attributes such as passwords from being exposed.

## Excel Export

The Excel export uses a database query through Laravel Excel's `FromQuery` concern.

This approach is more memory-efficient than loading the entire user collection before generating the export.

## Form Requests

Dedicated Form Request classes are used for user creation and updates.

This keeps validation logic separate from the controller and makes the controller code easier to maintain.

---

# Assumptions

The following assumptions were made during implementation:

1. Only administrators should manage users through the web interface.
2. The REST API is public because the assessment requires it to be accessible by anyone.
3. User email addresses must be unique.
4. User phone numbers must be unique.
5. User deletion is implemented as a soft delete.
6. Valid user statuses are `active` and `inactive`.
7. Passwords must be confirmed when creating a user.
8. Passwords must be at least 5 characters long.
9. The admin dashboard is the primary interface for user management.
10. Pagination is set to 10 users per page.
11. The API does not expose passwords.
12. The default seeded administrator account is intended only for local assessment/demo use.

---

