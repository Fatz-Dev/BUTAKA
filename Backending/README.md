# Butaka (Buku Tamu Kantor) API

Butaka is a modern Office Guest Book application built with Laravel 12. It facilitates the management of visitors, feedback, and receptionist operations in an office environment. This repository contains the backend API source code.

## Features

-   **Authentication**: Secure login and logout mechanism using Laravel Sanctum.
-   **Roles**:
    -   **Admin**: Manages receptionists and oversees the entire system.
    -   **Receptionist**: Manages guests and front-desk operations.
-   **Guest Management**: Check-in and checkout process for office visitors.
-   **Feedback System**: Collects feedback from visitors.
-   **Dashboard**: Provides statistics and insights (Admin/Receptionist views).

## Technology Stack

-   **Framework**: [Laravel 12](https://laravel.com)
-   **Authentication**: [Laravel Sanctum](https://laravel.com/docs/sanctum)
-   **Database**: MySQL / MariaDB

## Prerequisites

Before you begin, ensure you have met the following requirements:

-   PHP >= 8.2
-   Composer
-   MySQL or MariaDB

## Installation

Follow these steps to set up the project locally:

1.  **Clone the repository**

    ```bash
    git clone https://github.com/Fatz-Dev/BUTAKA.git
    cd BUTAKA/Backend
    ```

2.  **Install PHP dependencies**

    ```bash
    composer install
    ```

3.  **Setup Environment File**

    Copy the example environment file and configure it:

    ```bash
    cp .env.example .env
    ```

4.  **Generate Application Key**

    ```bash
    php artisan key:generate
    ```

5.  **Configure Database**

    Open `.env` file and set your database credentials:

    ```ini
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=bukutamu
    DB_USERNAME=root
    DB_PASSWORD=
    ```

6.  **Run Migrations and Seeders**

    Setup the database tables and populate them with initial data:

    ```bash
    php artisan migrate --seed
    ```

7.  **Serve the Application**

    ```bash
    php artisan serve
    ```

    The API will be available at `http://localhost:8000`.

## API Documentation

### Authentication

| Method | Endpoint      | Description                                 |
| :----- | :------------ | :------------------------------------------ |
| `POST` | `/api/login`  | User login                                  |
| `POST` | `/api/logout` | User logout (Requires Auth)                 |
| `GET`  | `/api/me`     | Get authenticated user info (Requires Auth) |

### Receptionists (Admin Only)

| Method   | Endpoint                  | Description                       |
| :------- | :------------------------ | :-------------------------------- |
| `GET`    | `/api/receptionists`      | List all receptionists            |
| `POST`   | `/api/receptionists`      | Create a new receptionist         |
| `GET`    | `/api/receptionists/{id}` | Get specific receptionist details |
| `PUT`    | `/api/receptionists/{id}` | Update receptionist details       |
| `DELETE` | `/api/receptionists/{id}` | Delete a receptionist             |

### Guests

| Method   | Endpoint           | Description           |
| :------- | :----------------- | :-------------------- |
| `GET`    | `/api/guests`      | List all guests       |
| `POST`   | `/api/guests`      | Check-in a new guest  |
| `GET`    | `/api/guests/{id}` | Get guest details     |
| `PUT`    | `/api/guests/{id}` | Update guest details  |
| `DELETE` | `/api/guests/{id}` | Delete a guest record |

### Feedback

| Method   | Endpoint             | Description       |
| :------- | :------------------- | :---------------- |
| `GET`    | `/api/feedback`      | List all feedback |
| `DELETE` | `/api/feedback/{id}` | Delete feedback   |

### Dashboard

| Method | Endpoint         | Description              |
| :----- | :--------------- | :----------------------- |
| `GET`  | `/api/dashboard` | Get dashboard statistics |

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
