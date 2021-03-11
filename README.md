# Metrics

## Description
Implementation of consuming an API assignment.

## Libraries / Dependencies
Some external libraries are being used:
- `guzzlehttp/guzzle` - to send API requests and retrieve responses,
- `monolog/monolog` - to log errors,
- `psr/log` - to type hint logger interface,
- `symfony/property-info` - to resolve mapping type of items in `array` properties,
- `symfony/serializer` - to serialize request objects and deserialize response content,
- `symfony/yaml` - to configure custom object mapping.

Libraries used only for development:
- `phpunit/phpunit` - to test code,
- `roave/security-advisories` - to ensures that your application doesn't have installed dependencies with known security vulnerabilities.

## Environment variables:
- `REDIS_HOST` - to set Redis host other than `"localhost"`,
- `REDIS_PORT` - to set Redis post other than `6379`.

## Run statistics
If local system has all required services, it is enough to run `statistics.php` script from the root directory.

It can also be executing by:
1. launching services with Docker Compose `docker-compose up`,
1. and then visiting http://localhost:8001/statistics.php in a browser.

## Automated tests
Run automated tests by executing:
- `vendor/bin/phpunit`
