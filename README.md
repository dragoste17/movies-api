## Running App for Locally

The API is based on Laravel.
For running the app locally, Docker is used and hence needs to be installed along with Docker Compose.
Ports 8000 and 4300 should be free on the local or can be changed in the `docker-compose.yml`.
The `.env.example` file has already been filled with DB connection details for convenience.
Both the Client API and the In-house (internal) API are housed in the same project for convenience.

-   Clone repo: `git clone https://github.com/dragoste17/movies-api.git`
-   Run `cd movies-api`
-   Attach a shell to the `movies-api-php` container and run the following commands in that shell
    -   `composer install`
    -   `cp .env.example .env`
    -   `php artisan key:generate`
-   Run `docker-compose up -d` in a separate terminal
-   Again attach a shell to the `movies-api-php` container or use the previous one and run
    -   `php artisan migrate:fresh --seed`

## Manual Testing

For manual testing of Internal API try the following endpoints:

-   http://localhost:8000/api/movies?apiKey=e3miDEbMi8ri6MKG2wAI&searchQuery=si
-   http://localhost:8000/api/movies/2?apiKey=e3miDEbMi8ri6MKG2wAI

## Automated Feature Testing

For feature testing, a separate db is used so as not to interfere with the original db

-   Attach a shell to the `movies-api-php` container or use the previous one and run
    -   `php artisan test`
