# Sample Laravel Project with Tests
This is a part of [Laravel Test Guideline](https://github.com/framgia/laravel-test-guideline) Project, made by Framgia Vietnam.

## Installation

### Docker
- Copy file `.env.docker.example` to `.env`
- Run `docker-compose up -d` command
- Enter the `laravel_test_examples_workspace` container
```
docker exec -ti laravel_test_examples_workspace /bin/bash
```
- Install project dependencies
```
composer install
yarn
```
- Run migration
```
php artisan migrate
php artisan migrate --database=mysql_test
```
- Check the service online at http://0.0.0.0:8000/
