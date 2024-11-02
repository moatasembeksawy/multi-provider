## Multi Providers
A Laravel-based API that processes and filters transaction data from multiple payment providers. The system is designed to be easily scalable for adding new data providers.

## Features 
- Combines transaction data from multiple providers (DataProviderX, DataProviderY)
- Flexible filtering system supporting.
- Easily extendable for additional providers.

## Technology

- PHP 8.3
- Laravel 11.x
- cerbero/json-parser (for efficient JSON file processing)
- PHPUnit for testing
- Docker
- Composer


## Setup

1. Start the Docker containers:
```bash
docker-compose up -d
```

2. Install dependencies:
```bash
docker exec -it multi-providers composer install
```

## API Endpoints

### Get Users
```
GET /api/v1/users
```

### Available Filters

1. Filter by Provider:
```
GET /api/v1/users?provider=DataProviderX
```

2. Filter by Status:
```
GET /api/v1/users?statusCode=authorised
```
Available status: authorised, decline, refunded

3. Filter by Balance Range:
```
GET /api/v1/users?balanceMin=10&balanceMax=100
```

4. Filter by Currency:
```
GET /api/v1/users?currency=USD
```

5. Combined Filters:
```
GET /api/v1/users?provider=DataProviderX&statusCode=authorised&balanceMin=10&balanceMax=100&currency=USD
```
## Architecture

1. **Service Pattern**: Used to separate business logic from controllers
2. **Strategy Pattern**: For handling different data providers
3. **Pipeline Pattern**: For Flexible filter implementation
4. **Streaming Parser**: For Uses cerbero/json-parser for memory-efficient processing

## Testing

Run the test suite:
```bash
docker exec -it multi-providers php artisan test
```

### example for Data Provider
```bash
[
    {
      "parentAmount": 100,
      "Currency": "USD",
      "parentEmail": "parent1@providerX.com",
      "statusCode": 1,
      "registerationDate": "2023-01-01",
      "parentIdentification": "uuid1"
    },
    {
      "parentAmount": 200,
      "Currency": "USD",
      "parentEmail": "parent2@providerX.com",
      "statusCode": 2,
      "registerationDate": "2023-01-02",
      "parentIdentification": "uuid2"
    }
]
```