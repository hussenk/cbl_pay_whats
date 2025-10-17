Docker usage for this Laravel project

Quick start:

1. Build and start containers:

   docker-compose up -d --build

2. Install composer dependencies (if not already installed inside image):

   docker-compose run --rm app composer install

3. Create `.env` (copy from `.env.example`) and set DB connection to:

   DB_HOST=db
   DB_DATABASE=cbl_pay
   DB_USERNAME=cbl
   DB_PASSWORD=secret

4. Run migrations:

   docker-compose exec app php artisan migrate

5. Open the app at: http://localhost:8080

phpMyAdmin is available at http://localhost:8181 (user: root / password: secret)

Notes:
- The `app` service uses PHP-FPM and mounts the project as a volume so code changes are immediate.
- For production you'll want to optimize the Dockerfile (no composer in image, build assets, environment variables securely managed).
