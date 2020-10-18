## Running App for Locally

The api is based on laravel and hence it requires php and composer to be installed.
For running the app locally, docker is used and hence needs to be installed too.
Ports 8000 and 4300 should be free on the local or can be changed in the `docker-compose.yml`
The env.example file has already been filled with DB connection details for convenience

-   Clone repo: `git clone https://github.com/dragoste17/movies-api.git`
-   Run `cd movies-api`
-   Run `composer install`
-   Run `cp .env.example .env`
-   Run `php artisan key:generate`
-   Run `docker-compose up -d`
