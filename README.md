Super simple weather app, reading the spec I thought that the whole project should be using Laravel, therefore I used the Blade templating engine for the first time in a long long time... probably should have used VUE as I created seporate routes for web/API

Run composer install to update the packages
Run php artisan migrate to install the tables

Created using Sail for development ( Documented here: https://laravel.com/docs/9.x#getting-started-on-macos)

OpenWeatherApp token is exposed, however I did not want to add this to the .env file so that the project can be quickly spun up

