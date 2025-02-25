# Products API

A modern REST API application for product management built with PHP 8.

## Tech Stack

-   PHP 8.2+
-   MySQL 8.0+
-   Redis (optional for caching)
-   Composer
-   PSR-4 autoloading
-   PSR-7 HTTP interfaces
-   PSR-11 dependency container
-   PSR-12 coding standard

## Requirements

-   PHP >= 8.1
-   MySQL >= 8.0
-   Composer
-   PDO PHP Extension
-   Redis Server (optional)

## Installation

1. Clone the repository:
2. Install dependencies:
3. Copy .env.example to .env and configure:
4. Configure your database in .env file

## API Endpoints

### Products

-   `GET /api/products` - List all products
-   `GET /api/products/{id}` - Get product details
-   `POST /api/products` - Create new product
-   `PUT /api/products/{id}` - Update product
-   `DELETE /api/products/{id}` - Delete product

### Categories

-   `GET /api/categories` - List all categories
-   `GET /api/categories/{id}` - Get category details

## Query Parameters

### Pagination

-   `page` - Page number (default: 1)
-   `per_page` - Items per page (default: 10, max: 100)

### Sorting

-   `sort` - Sort field (id, name, price, stock_quantity)
-   `order` - Sort order (asc, desc)

### Filtering

-   `price_from` - Minimum price
-   `price_to` - Maximum price
-   `category_id` - Filter by category
-   `status` - Filter by status (active, inactive)
-   `stock_quantity_from` - Minimum stock quantity
-   `stock_quantity_to` - Maximum stock quantity

## Response Format

### Success Response

```json
{
    "data": {
        "items": [],
        "meta": {
            "current_page": 1,
            "per_page": 10,
            "total": 0,
            "total_pages": 0
        }
    }
}
```

### Error Response

```json
{
    "error": {
        "message": "Error message",
        "errors": {
            "field": "Error description"
        }
    }
}
```
