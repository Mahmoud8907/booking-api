# Booking API

A RESTful Appointment Booking API built with Laravel.

This project provides a backend API for managing services and appointments, with authentication, authorization, validation, booking conflict prevention, appointment cancellation, API Resources, pagination, filtering, and automated feature tests.

## Features

* User registration and login
* Authentication using Laravel Sanctum
* Token-based API authentication
* Role-based authorization
* Admin-only service creation
* Service listing and filtering
* Pagination
* Appointment booking
* Prevention of double booking
* Appointment cancellation
* Policy-based authorization
* Form Request validation
* API Resources
* Service classes for business logic
* Database transactions
* Custom exceptions
* Feature testing with Pest

## Technologies

* PHP
* Laravel
* MySQL / MariaDB
* Laravel Sanctum
* Pest
* Docker
* phpMyAdmin
* Git & GitHub
* Postman

## Project Structure

The project follows Laravel's standard structure with separation of responsibilities:

* Controllers — Handle HTTP requests and coordinate the application flow.
* Form Requests — Handle request validation.
* Models — Represent database tables and relationships.
* API Resources — Control API response structure.
* Policies — Handle authorization rules.
* Services — Contain business logic.
* Exceptions — Handle business-related errors.
* Feature Tests — Test the API behavior and business rules.

## Authentication

The API uses Laravel Sanctum for token-based authentication.

### Register

**POST /api/register**

Example request:

```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

### Login

**POST /api/login**

Example request:

```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

The login response returns an authentication token.

For protected endpoints, send the token using:

**Authorization: Bearer TOKEN**

### Logout

**POST /api/logout**

Requires authentication.

## Services

Services represent the available services that users can book.

### Get Services

**GET /api/services**

Supports:

* Pagination
* Search
* Minimum price
* Maximum price

Examples:

**GET /api/services?search=doctor**

**GET /api/services?min_price=100&max_price=500**

### Get Single Service

**GET /api/services/{service}**

### Create Service

**POST /api/services**

This endpoint requires:

* Authentication
* Admin authorization

Regular users receive:

**403 Forbidden**

Successful creation returns:

**201 Created**

## Appointments

Authenticated users can create and manage their appointments.

### Create Appointment

**POST /api/appointments**

Example request:

```json
{
    "service_id": 1,
    "appointment_date": "2026-10-20 15:00:00"
}
```

The API validates the service and appointment date before creating the appointment.

### Preventing Double Booking

The application prevents two active appointments from being created for the same service and time.

If the requested time is already booked, the API returns:

**409 Conflict**

### Get My Appointments

**GET /api/appointments**

Returns the authenticated user's appointments with pagination.

### Get Appointment

**GET /api/appointments/{appointment}**

Users can only view their own appointments.

Unauthorized access returns:

**403 Forbidden**

### Cancel Appointment

**PATCH /api/appointments/{appointment}/cancel**

Users can only cancel their own appointments.

If the appointment is already cancelled, the API returns:

**409 Conflict**

## Authorization

The project uses multiple authorization mechanisms:

### Admin Middleware

Admin-only actions are protected using middleware.

For example, only administrators can create services.

### Policies

Appointment ownership is handled using `AppointmentPolicy`.

A user can only:

* View their own appointment
* Cancel their own appointment

## Validation

The project uses Laravel Form Requests to validate incoming API data.

Examples include:

* RegisterRequest
* LoginRequest
* StoreServiceRequest
* StoreAppointmentRequest

Invalid data returns:

**422 Unprocessable Entity**

## HTTP Status Codes

The API uses meaningful HTTP status codes:

| Status | Meaning            |
| ------ | ------------------ |
| 200    | Request successful |
| 201    | Resource created   |
| 401    | Unauthenticated    |
| 403    | Forbidden          |
| 409    | Conflict           |
| 422    | Validation error   |

## Testing

The project includes feature tests covering authentication, authorization, appointment booking, and cancellation.

Run the test suite with:

**php artisan test**

Current test suite:

**13 tests passed**

The tests use an in-memory SQLite database, so the application's development database is not affected by the test suite.

## Database

The application uses MySQL/MariaDB for development.

The main database entities are:

* Users
* Services
* Appointments

Relationships:

* User hasMany Appointments
* Service hasMany Appointments
* Appointment belongsTo User
* Appointment belongsTo Service

## Installation

Clone the repository and install the dependencies.

**composer install**

Copy the environment file:

**cp .env.example .env**

Generate the application key:

**php artisan key:generate**

Configure the database in `.env`.

Run migrations:

**php artisan migrate**

Start the Laravel application using your preferred local Laravel environment.

The API will then be available under:

**/api**

## API Testing

The API can be tested using Postman.

Recommended flow:

1. Register a user.
2. Login and receive an authentication token.
3. Use the token as a Bearer Token.
4. Create or browse services.
5. Book an appointment.
6. Test appointment ownership and cancellation.
7. Test double-booking prevention.

## Business Logic

The main business rules implemented in the project include:

* Only authenticated users can create appointments.
* Users can only access their own appointments.
* Only admins can create services.
* A service cannot be booked twice at the same time while an active appointment exists.
* Cancelled appointments cannot be cancelled again.
* Validation is handled before business logic is executed.
* Appointment creation is handled inside a database transaction.

## License

This project is open-sourced under the MIT License.
