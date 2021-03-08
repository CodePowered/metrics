# Metrics

## Description
Implementation of consuming an API assignment.

## Libraries / Dependencies
Some external libraries are being used:
- `guzzlehttp/guzzle` - to send API requests and retrieve responses,
- `symfony/property-info` - to resolve mapping type of items in `array` properties,
- `symfony/serializer` - to serialize request objects and deserialize response content,
- `symfony/yaml` - to configure custom object mapping.

Libraries used only for development:
- `phpunit/phpunit` - to test code,
- `roave/security-advisories` - to ensures that your application doesn't have installed dependencies with known security vulnerabilities.
