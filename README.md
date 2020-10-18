## Running App for Locally

The API is based on Laravel.
For running the app locally, Docker is used and hence needs to be installed along with Docker Compose.
Ports 8000 and 4300 should be free on the local or can be changed in the `docker-compose.yml`
The `.env.example` file has already been filled with DB connection details for convenience

-   Clone repo: `git clone https://github.com/dragoste17/movies-api.git`
-   Run `cd movies-api`
-   Attach a shell to the `movies-api-php` container and run the following commands in that shell
    -   `composer install`
    -   `cp .env.example .env`
    -   `php artisan key:generate`
-   Run `docker-compose up -d` in a separate terminal
-   Again attach a shell to the `movies-api-php` container or use the previous one and run
    -   `php artisan migrate:fresh --seed`
