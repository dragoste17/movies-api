## Running App for Locally

The API is based on Laravel and MySQL.
For running the app locally, Docker is used and hence needs to be installed along with Docker Compose.
Ports 8000 and 4300 should be free on the local or can be changed in the `docker-compose.yml`.
The `.env.example` file has already been filled with DB connection details for convenience.
Both the Client API and the In-house (internal) API are housed in the same project for convenience.

-   Clone repo: `git clone https://github.com/dragoste17/movies-api.git`
-   Run `cd movies-api`
-   Run `docker-compose up -d`
-   Attach a shell to the `movies-api-php` container by using `docker exec -it {containerId} sh` and run the following commands in that shell
    -   `composer install`
    -   `cp .env.example .env`
    -   `php artisan key:generate`
    -   `php artisan migrate:fresh --seed` (This should be run after docker has finished setting up the database)

## API Consumption

Link for Postman Collection: https://www.getpostman.com/collections/91bc6563c37af1ec8f78

For manual testing of Internal API try the following endpoints:

-   http://localhost:8000/api/internal/movies?apiKey=e3miDEbMi8ri6MKG2wAI&searchQuery=si
-   http://localhost:8000/api/internal/movies/2?apiKey=e3miDEbMi8ri6MKG2wAI
-   http://localhost:8000/api/internal/movies?apiKey=e3miDEbMi8ri6MKG2wAI&movieIds%5B0%5D=8&movieIds%5B1%5D=13&movieIds%5B2%5D=17&movieIds%5B3%5D=20&movieIds%5B4%5D=34&movieIds%5B5%5D=35&movieIds%5B6%5D=56&movieIds%5B7%5D=58&movieIds%5B8%5D=92&movieIds%5B9%5D=95
-   http://localhost:8000/api/internal/movies/popular?apiKey=e3miDEbMi8ri6MKG2wAI

Default API Key has been used, will need to be changed if updated in the `.env` file.

For manual testing of the Client APIs, the `api_token` of the user needs to be added as a param.
The `api_token` can be viewed in the db after seeding and should be replaced below.

-   http://localhost:8000/api/v1/favorites?api_token=DmGIGjiJMtW2DHAqDiipKEU4Ql03t6ViRlhNECdTX26IPcIgeQsAFLUIfSFRsOS4K2Www9v4qmc5Cszs
-   POST http://localhost:8000/api/v1/favorite/1?api_token=DmGIGjiJMtW2DHAqDiipKEU4Ql03t6ViRlhNECdTX26IPcIgeQsAFLUIfSFRsOS4K2Www9v4qmc5Cszs
-   http://localhost:8000/api/v1/movies?search=si&api_token=DmGIGjiJMtW2DHAqDiipKEU4Ql03t6ViRlhNECdTX26IPcIgeQsAFLUIfSFRsOS4K2Www9v4qmc5Cszs
-   http://localhost:8000/api/v1/movie/2?api_token=DmGIGjiJMtW2DHAqDiipKEU4Ql03t6ViRlhNECdTX26IPcIgeQsAFLUIfSFRsOS4K2Www9v4qmc5Cszs

## Automated Feature Testing

For feature testing, a separate db is used so as not to interfere with the original db.
The database used for the internal api calls is the mysql one due to Laravel's design.
Only for Client API and Unit Testing an in-memory sqlite db is used.

-   Attach a shell to the `movies-api-php` container and run
    -   `php artisan test`

## Design and Assumptions

We have created the following tables

-   `movies`: to store the list of movies consumed only by the in-house API
-   `users`: to store the list of users of the client
-   `favorites`: to store the list of movies favorited by a user
-   `search_frequencies`: to store the list of searches and their frequency

There are multiple ways to define popularity of a movie.
One would be to scrape the net for references in recent news or to fetch popularity ratings from IMDB or Rotten Tomatoes.
But for simplicity we will assume that popular movies are the movies which are searched for the most.
This will be stored in the `search_frequencies` table.

The search for the movies is assumed to be based only on the name of the movie for simplicity.

We are using the MVC pattern with a MySQL database.

The API endpoints for the in-house API are hosted on a slightly modified route `/api/internal/movies` to avoid clashes in naming with the client API.
Both the endpoints are exposed as requested in the task. The `apiKey` has a default value or may be set via the `API_KEY` variable in the `.env`.
Additionally, a route to expose popular movies is created based on the search frequencies.
The searches are assumed to be case insensitive for convenience of the user.

The client API never interacts with the `movies` database directly. It only uses the endpoints exposed by the in-house API.
The `users` and `favorites` tables are the only ones used by the client API.
The functionalities to fetch favorite movies is implemented on the `/api/v1/favorites` and to favorite a movie is on `/api/v1/favorite/:id` routes.
The functionality to search for movies is implemented on `/api/v1/movies?search={query}` and get details is on `/api/v1/movie/:id` route.

The client APIs have been versioned to ensure accomodation of future changes in the payload if requested by the client.
The client APIs have also been put under the auth middleware to ensure that no unauthenticated requests are made.
