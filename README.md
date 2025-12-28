# Complaints Management System

A CodeIgniter 4 based application for managing student complaints and feedback in an educational environment.

## Authors
*   **طه فيصل الهادي عبدالله**
*   **احمد صالع علي مفتاح**
*   **عبدالرحمن محمد الساسي**

## Test Accounts
You can use the following accounts to test the system with different roles:

### 1. Administrator
*   **Email:** `admin@example.com`
*   **Password:** `admin123`

### 2. Student
*   **Email:** `ahmed@student.com`
*   **Password:** `student123`

## Server Requirements
PHP version 8.1 or higher is required, with the following extensions installed:
- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

## Installation & Setup
1.  Clone the repository.
2.  Copy `env` to `.env` and configure your database settings.
3.  Run `composer update`.
4.  Run migrations and seeds:
    ```bash
    php spark migrate
    php spark db:seed UserSeeder
    ```
5.  Serve the application:
    ```bash
    php spark serve
    ```
