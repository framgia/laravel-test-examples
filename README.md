# Sample Laravel Project with Tests
This is a part of [Laravel Test Guideline](https://github.com/framgia/laravel-test-guideline) Project, made by Framgia Vietnam.

## Build Status
### Scrutinizer CI [![Build Status](https://scrutinizer-ci.com/g/framgia/laravel-test-examples/badges/build.png?b=master&s=5b4fedc7b4500c5d5956a10e21565129fd0e293e)](https://scrutinizer-ci.com/g/framgia/laravel-test-examples/build-status/master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/framgia/laravel-test-examples/badges/quality-score.png?b=master&s=0c6a0e4051bf536d3715489e79383732b4a863bf)](https://scrutinizer-ci.com/g/framgia/laravel-test-examples/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/framgia/laravel-test-examples/badges/coverage.png?b=master&s=0e8dfdea281465431818f2401965668527478098)](https://scrutinizer-ci.com/g/framgia/laravel-test-examples/?branch=master)
### Travis CI [![Build Status](https://travis-ci.org/framgia/laravel-test-examples.svg?branch=master)](https://travis-ci.org/framgia/laravel-test-examples)
### Circle CI [![Build Status](https://circleci.com/gh/framgia/laravel-test-examples/tree/master.svg?style=shield)](https://circleci.com/gh/framgia/laravel-test-examples)
### Framgia CI [![Build Status](http://ci.framgia.vn/api/badges/framgia/laravel-test-examples/status.svg)](http://ci.framgia.vn/framgia/laravel-test-examples)

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
- Generate application encryption key
```
php artisan key:generate
```
- Check the service online at http://0.0.0.0:8000/

## Integrate with CI services
- For [Scrutinizer CI](http://scrutinizer-ci.com/) integration, please refer [.scrutinizer.yml](./.scrutinizer.yml) configuration file.
- For [Travis CI](https://travis-ci.org) integration, please refer [.travis.yml](./.travis.yml). Moreover, Travis CI only support Mysql 5.6 by default, that causes the migration to be [failed](https://github.com/laravel/framework/issues/17508). Therefore, the [.travis.install-mysql-5.7.sh](./.travis.install-mysql-5.7.sh) file is also included too, to replace the default 5.6 version with the newest 5.7 version.
- For [Circle CI](https://circleci.com/), please refer [.circleci/config.yml](./.circleci/config.yml) file.
- For [Framgia CI](https://github.com/framgia/ci-service-document), please refer [.drone.yml](./.drone.yml) and [.framgia-ci.yml](./.framgia-ci.yml) files.
