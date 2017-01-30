# Streams

Laravel Stream made for Laravel 5.3.

## Usages

Clone the repository

Next create a new database and add your database credentials to your .env file:

```
DB_HOST=localhost
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret
```

Add the Streams service provider to the `config/app.php` file in the `providers` array:

```php
'providers' => [
    // Laravel Framework Service Providers...
    //...
    
    // Package Service Providers
    RAD\Streams\StreamsServiceProvider::class,
    // ...
    
    // Application Service Providers
    // ...
],
```

Lastly, we can install streams. You can do this either with or without dummy data.
The dummy data will include 1 admin account (if no users already exists), 1 demo page, 4 demo posts, 2 categories and 7 settings.

To install Streams without dummy simply run

```bash
php artisan streams:install
```

If you prefer installing it with dummy run

```bash
php artisan streams:install --with-dummy
```

Start up a local development server with `php artisan serve` And, visit [http://localhost:8000/admin](http://localhost:8000/admin).

If you did go ahead with the dummy data, a user should have been created for you with the following login credentials:

>**email:** `admin@admin.com`   
>**password:** `password`

NOTE: Please note that a dummy user is **only** created if there are no current users in your database.

If you did not go with the dummy user, you may wish to assign admin priveleges to an existing user.
This can easily be done by running this command:

```bash
php artisan streams:admin your@email.com
```

If you did not install the dummy data and you wish to create a new admin user you can pass the `--create` flag, like so:

```bash
php artisan streams:admin your@email.com --create
```

Will be prompted for the users name and password.
