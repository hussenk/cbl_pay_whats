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

Note: The MySQL service maps container port 3306 to host port 3307 by default to avoid conflicts with any locally running MySQL instance. If you'd like to use host port 3306, update `docker-compose.yml` and ensure nothing else is listening on 3306.

4. Run migrations:

   docker-compose exec app php artisan migrate

5. Open the app at: http://localhost:8080

phpMyAdmin is available at http://localhost:8181 (user: root / password: secret)

Notes:
- The `app` service uses PHP-FPM and mounts the project as a volume so code changes are immediate.
- For production you'll want to optimize the Dockerfile (no composer in image, build assets, environment variables securely managed).

HTTPS (local development)
-------------------------
This setup supports HTTPS by mounting certificate files into `./docker/certs` and exposing host port `8129`.

Create certs (recommended: mkcert)

1. Install mkcert (https://github.com/FiloSottile/mkcert).
2. Run:

   mkcert -install
   mkcert -key-file docker/certs/privkey.pem -cert-file docker/certs/fullchain.pem localhost 127.0.0.1

Alternatively create self-signed certs with openssl:

   mkdir -p docker/certs
   openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
     -keyout docker/certs/privkey.pem -out docker/certs/fullchain.pem \
     -subj "/CN=localhost"

Then start the stack:

   docker-compose up -d --build

Open the site at:

   http://localhost:8128 (if using non-standard HTTP mapping)
   https://localhost:8129 (if using non-standard HTTPS mapping)

