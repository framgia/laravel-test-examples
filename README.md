# Sample Laravel Project with Tests
This is a part of [Laravel Test Guideline](https://github.com/framgia/laravel-test-guideline) Project, made by Framgia Vietnam.

## Build Status
- Scrutinizer [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/framgia/laravel-test-examples/badges/quality-score.png?b=master&s=0c6a0e4051bf536d3715489e79383732b4a863bf)](https://scrutinizer-ci.com/g/framgia/laravel-test-examples/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/framgia/laravel-test-examples/badges/coverage.png?b=master&s=0e8dfdea281465431818f2401965668527478098)](https://scrutinizer-ci.com/g/framgia/laravel-test-examples/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/framgia/laravel-test-examples/badges/build.png?b=master&s=5b4fedc7b4500c5d5956a10e21565129fd0e293e)](https://scrutinizer-ci.com/g/framgia/laravel-test-examples/build-status/master)
- Framgia CI [![Build Status](http://ci.framgia.vn/api/badges/framgia/laravel-test-examples/status.svg)](http://ci.framgia.vn/framgia/laravel-test-examples)
- Travis CI
- Circle CI

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
