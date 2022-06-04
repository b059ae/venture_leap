# Description
Very simple Symfony app modeling an audit log that allows for (via a JSON API):
- Creation of an event (timestamp, type, details)
- Creation of a type ( Base type should be like “info”, “warning”, or “error”)
- Retrieval of all events with a filter on type (preferably with pagination)
- Plan the API to have a version number

# Installation
* PHP 8.1

```git clone git@github.com:b059ae/venture_leap.git && cd venture_leap```
```composer install``` 

Specify DB connection in .env file. The easiest way is to use sqlite.
```touch var/database.sqlite```

Run migrations
```php bin/console doctrine:migrations:migrate```

Load data fixtures 
```php bin/console doctrine:fixtures:load```

# Tests
Prepare test database

```touch var/database_test.sqlite```
```php bin/console doctrine:migrations:migrate --env=test```
```php bin/console doctrine:fixtures:load --env=test```

```php bin/phpunit```

# Run server
How to install Symfony CLI [https://symfony.com/download](https://symfony.com/download)
```symfony server:start```

# Endpoints
GET http://127.0.0.1:8000/v1/events # Get all events, default limit is 10
GET http://127.0.0.1:8000/v1/events?limit=5 # Get first 5 events
GET http://127.0.0.1:8000/v1/events?limit=5&offset=5 # Get events from 6 to 10
GET http://127.0.0.1:8000/v1/events?type=info # Get events filtered by type "info"

POST http://127.0.0.1:8000/v1/types # Create new type
Content-Type: application/json

{
"name": "debug"
}


POST http://127.0.0.1:8000/v1/events # Create new event
Content-Type: application/json

{
"details": "text",
"type": "info"
}