Run:
```bash
docker compose up -d
```

Install dependencies:
```bash
docker compose exec php composer install
```

Run tests:
```bash
docker compose exec php vendor/bin/phpunit tests/
```