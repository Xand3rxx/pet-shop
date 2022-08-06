# Buckhill Pet Shop API

This project was created based on Buckhill's Pet Shop API requirements.

## About Pet Shop API

The Pet Shop API offers the required HTTP request methods and endpoints to meet the demands of a FE team creating a suitable UI. This web application features the following features based on the User Story:

1. Admin endpoint.
2. User endpoint (CRUD).
3. Forgot/reset password.
4. Main page endpoint.

## Pet Shop API Development Procedures

1. CD into the application root directory with your command prompt/terminal/git bash.

2. Run `cp .env.example .env`.

3. Inside `.env` file, setup database, mail and other configurations.

4. Run `composer install`.

5. Run `php artisan key:generate` command.

6. Run `php artisan migrate:fresh --seed` command.

7. Run `php artisan serve` command.

## Login credentials

Administrator Account
Email: `admin@buckhill.co.uk`
Password: `admin`

User Account
You can get a new or existing user email from the `admin/user-listing` endpoint.
Password: `userpassword`
