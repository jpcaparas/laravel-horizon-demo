# Laravel Horizon Demo

![laravel-horizon](http://i.imgur.com/US6hC7Ll.jpg)

Laravel Horizon provides an easy-to-use, interactive GUI to monitor and interact with Redis queues.

Horizon was announced at Laracon US 2017 in NYC. It requires Laravel 5.5 to run (which is currently in beta).

## Quick start

1. Clone this project.

1. Run `php composer install`.

1. Sign up for a [free Mailtrap account](https://mailtrap.io/) and copy API credentials. We'll use Mailtrap to **spoof outgoing emails** using their demo inbox:

    ![Mailtrap demo inbox](http://i.imgur.com/LjQqnGdl.png)

1. Enter values for these directives on your `.env` file:
  - `APP_KEY` &rarr; Run `php artisan key:generate` to easily generate a base64-encoded key.
  - `MAIL_*` &rarr; Enter Mailtrap credentials.
  - `REDIS_*` &rarr; A standard Redis installation usually does not require you to change values.
  
1. Run `php artisan config:cache` to use the values set above.

1. Create a blank `database.sqlite` under your `./database` directory.

1. Run `php artisan migrate --seed` to create a user table and seed it with dummy user information (e.g. name, email).

1. Run `composer dump-autoload` to clear the PHP class cache.

1. Open a new shell prompt and run `php artisan serve` to serve the web application on the foreground using PHP's built in web server.

    Note: On macOS, it's easier to use [Laravel Valet](https://laravel.com/docs/5.4/valet).

1. Open a new shell prompt and run `php artisan horizon` on the foreground and view the Horizon dashboard at `http://[app-host]/horizon`.

    ![horizon-cli](http://i.imgur.com/lh00VWzl.png)

1. By visiting `http://[app-host]/queues/fetch-star-wars-entity?repeat=1&user_id=1`, you can now test Horizon by creating a job that fetches a random Star Wars entity from the [unofficial Star Wars public API](http://swapi.co/) and subsequently sends a notification email.

    ![fetch-star-wars-entity-url](http://i.imgur.com/s9ZYw3Ll.png)
    
    ![fetch-star-wars-entity-result](http://i.imgur.com/Nd2y3A8l.jpg)

    Two async jobs are actually dispatched when you hit the URL above: (a) one that fetches from the Star Wars API and (b) another one that sends a notification email.
    
    ![two-jobs](http://i.imgur.com/g7IrLGjl.png)
  
    Note: You can increase the number of requests sent (and conversely, resulting email notifications) by increasing the `repeat` query parameter to a higher number -- say, for example, _100_. Just be aware that if using a service _other than Mailtrap's demo inbox_, you might get **flagged for sending spam**.

## Requirements

- PHP v7.1
- Redis v3.x

## Attributions

This wouldn't be possible without being granted a role as Software Developer at Pixel Fusion, an award-winning product development company at Parnell, Auckland.
