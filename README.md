<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>


## Auth Endpoints

### Register

**Endpoint:** `api/auth/register`
**HTTP method:** `POST`
**Route type:** Public route

**Request:**

```json
{
  "name": "Ada Vendor",
  "email": "ada@example.com",
  "phone_number": "08000000000",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "vendor"
}
```

Rules: `name` required (max 255). `email` required, valid, unique. `phone_number` optional (max 20). `password` required (min 8) and must match `password_confirmation`. `role` required, either `buyer` or `vendor`. Buyers automatically receive an empty cart.

**Response `201`:**

```json
{
  "success": true,
  "message": "Registration successful.",
  "data": {
    "user": {
      "id": 2,
      "name": "Ada Vendor",
      "email": "ada@example.com",
      "phone_number": "08000000000",
      "role": "vendor",
      "created_at": "2026-11-17T10:15:30.000000Z"
    },
    "token": ""
  }
}
```

**Errors:** `422` for invalid data, a duplicate email, or a `role` other than `buyer` / `vendor`.

---

### Login

**Endpoint:** `api/auth/login`
**HTTP method:** `POST`
**Route type:** Public route

**Request:**

```json
{
  "email": "ada@example.com",
  "password": "password123"
}
```

**Response `200`:**

```json
{
  "success": true,
  "message": "Login successful.",
  "data": {
    "user": {
      "id": 2,
      "name": "Ada Vendor",
      "email": "ada@example.com",
      "phone_number": "08000000000",
      "role": "vendor",
      "created_at": "2026-11-17T10:15:30.000000Z"
    },
    "token": ""
  }
}
```

**Errors:** `401` with `"Invalid email or password."`.

---

### Logout

**Endpoint:** `api/auth/logout`
**HTTP method:** `POST`
**Route type:** Authenticated route (any logged-in user)

**Request:** no body.

```json
{}
```

**Response `200`:**

```json
{
  "success": true,
  "message": "Logged out.",
  "data": null
}
```

Revokes only the token used for this request.

---

### Current user

**Endpoint:** `api/auth/me`
**HTTP method:** `GET`
**Route type:** Authenticated route (any logged-in user)

**Request:** no body.

```json
{}
```

**Response `200`:**

```json
{
  "success": true,
  "message": "Success",
  "data": {
    "id": 2,
    "name": "Ada Vendor",
    "email": "ada@example.com",
    "phone_number": "08000000000",
    "role": "vendor",
    "created_at": "2026-11-17T10:15:30.000000Z"
  }
}
```

---

## Public Endpoints

### List all stores

**Endpoint:** `api/stores`
**HTTP method:** `GET`
**Route type:** Public route

**Request (query parameters, optional):**

```json
{
  "page": 1
}
```

**Response `200`:** paginated, 20 per page, sorted alphabetically by name.

```json
{
  "success": true,
  "message": "Stores retrieved successfully.",
  "data": [
    {
      "id": 1,
      "name": "Ada Fashion",
      "category": "Fashion",
      "location": "Ikeja, Lagos",
      "description": "Ankara and modern wear",
      "created_at": "2026-11-17T10:20:00.000000Z",
      "updated_at": "2026-11-17T10:20:00.000000Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 20,
    "total": 1,
    "last_page": 1,
    "next_page_url": null,
    "prev_page_url": null
  }
}
```

---

### Get one store

**Endpoint:** `api/stores/{name}` (for example `api/stores/Ada%20Fashion`)
**HTTP method:** `GET`
**Route type:** Public route

**Request:** no body. The store is found by its **name** (URL-encoded, not case-sensitive).

```json
{}
```

**Response `200`:**

```json
{
  "success": true,
  "message": "Success",
  "data": {
    "id": 1,
    "name": "Ada Fashion",
    "category": "Fashion",
    "location": "Ikeja, Lagos",
    "description": "Ankara and modern wear",
    "created_at": "2026-11-17T10:20:00.000000Z",
    "updated_at": "2026-11-17T10:20:00.000000Z"
  }
}
```

**Errors:** `404` if no store has that name.

---

### List one store's products

**Endpoint:** `api/stores/{name}/products`
**HTTP method:** `GET`
**Route type:** Public route

**Request (query parameters, optional):**

```json
{
  "page": 1
}
```

**Response `200`:** paginated, 20 per page, sorted by product name. The `store` block is left out because you are already viewing that store.

```json
{
  "success": true,
  "message": "Store products retrieved successfully.",
  "data": [
    {
      "id": 1,
      "name": "Ankara Shirt",
      "description": "Handmade, size M",
      "price": "5000.00",
      "unit_available": 20,
      "in_stock": true,
      "created_at": "2026-11-17T10:25:00.000000Z",
      "updated_at": "2026-11-17T10:25:00.000000Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 20,
    "total": 1,
    "last_page": 1,
    "next_page_url": null,
    "prev_page_url": null
  }
}
```

**Errors:** `404` if no store has that name.

---

### List all products

**Endpoint:** `api/products`
**HTTP method:** `GET`
**Route type:** Public route

**Request (query parameters, all optional and combinable):**

```json
{
  "search": "ankara",
  "category": "Fashion",
  "page": 1
}
```


**Response `200`:** paginated, 20 per page, sorted by name.

```json
{
  "success": true,
  "message": "Products retrieved successfully.",
  "data": [
    {
      "id": 1,
      "name": "Ankara Shirt",
      "description": "Handmade, size M",
      "price": "5000.00",
      "unit_available": 20,
      "in_stock": true,
      "store": {
        "id": 1,
        "name": "Ada Fashion",
        "category": "Fashion",
        "location": "Ikeja, Lagos",
        "description": "Ankara and modern wear",
        "created_at": "2026-11-17T10:20:00.000000Z",
        "updated_at": "2026-11-17T10:20:00.000000Z"
      },
      "created_at": "2026-11-17T10:25:00.000000Z",
      "updated_at": "2026-11-17T10:25:00.000000Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 20,
    "total": 1,
    "last_page": 1,
    "next_page_url": null,
    "prev_page_url": null
  }
}
```

A search or category with no matches returns `200` with an empty `data` list.

---

### Get one product

**Endpoint:** `api/products/{id}`
**HTTP method:** `GET`
**Route type:** Public route

**Request:** no body.

```json
{}
```

**Response `200`:**

```json
{
  "success": true,
  "message": "Success",
  "data": {
    "id": 1,
    "name": "Ankara Shirt",
    "description": "Handmade, size M",
    "price": "5000.00",
    "unit_available": 20,
    "in_stock": true,
    "store": {
      "id": 1,
      "name": "Ada Fashion",
      "category": "Fashion",
      "location": "Ikeja, Lagos",
      "description": "Ankara and modern wear",
      "created_at": "2026-11-17T10:20:00.000000Z",
      "updated_at": "2026-11-17T10:20:00.000000Z"
    },
    "created_at": "2026-11-17T10:25:00.000000Z",
    "updated_at": "2026-11-17T10:25:00.000000Z"
  }
}
```

**Errors:** `404` if the product does not exist.

---

## Vendor Endpoints

Every vendor route requires `Authorization: Bearer <token>` and the `vendor` role.

### View my store

**Endpoint:** `api/vendor/store`
**HTTP method:** `GET`
**Route type:** Vendor route

**Request:** no body.

```json
{}
```

**Response `200`:**

```json
{
  "success": true,
  "message": "Success",
  "data": {
    "id": 1,
    "name": "Ada Fashion",
    "category": "Fashion",
    "location": "Ikeja, Lagos",
    "description": "Ankara and modern wear",
    "created_at": "2026-11-17T10:20:00.000000Z",
    "updated_at": "2026-11-17T10:20:00.000000Z"
  }
}
```

**Errors:** `404` with `"You have not created a store yet."`.

---

### Create my store

**Endpoint:** `api/vendor/store`
**HTTP method:** `POST`
**Route type:** Vendor route

**Request:**

```json
{
  "name": "Ada Fashion",
  "category": "Fashion",
  "location": "Ikeja, Lagos",
  "description": "Ankara and modern wear"
}
```

Rules: `name` required (max 255, unique, cannot contain `/`). `category` required, one of the allowed categories (see [Store object](#store-object)). `location` required (max 255). `description` optional (max 1000).

**Response `201`:**

```json
{
  "success": true,
  "message": "Store created successfully.",
  "data": {
    "id": 1,
    "name": "Ada Fashion",
    "category": "Fashion",
    "location": "Ikeja, Lagos",
    "description": "Ankara and modern wear",
    "created_at": "2026-11-17T10:20:00.000000Z",
    "updated_at": "2026-11-17T10:20:00.000000Z"
  }
}
```

**Errors:** `403` with `"You already have a store."` (one store per vendor). `422` for invalid data or a name that is already taken.

---

### Update my store

**Endpoint:** `api/vendor/store`
**HTTP method:** `PUT` or `PATCH`
**Route type:** Vendor route

**Request:** send **only the fields you want to change**. Any field you send follows the same rules as creation. `description` can be set to `null` to clear it.

```json
{
  "location": "Victoria Island, Lagos"
}
```

**Response `200`:**

```json
{
  "success": true,
  "message": "Store updated successfully.",
  "data": {
    "id": 1,
    "name": "Ada Fashion",
    "category": "Fashion",
    "location": "Victoria Island, Lagos",
    "description": "Ankara and modern wear",
    "created_at": "2026-11-17T10:20:00.000000Z",
    "updated_at": "2026-11-17T12:00:00.000000Z"
  }
}
```

**Errors:** `404` if you have no store yet. `422` for invalid data.

---

### Delete my store

**Endpoint:** `api/vendor/store`
**HTTP method:** `DELETE`
**Route type:** Vendor route

**Request:** no body.

```json
{}
```

**Response `200`:**

```json
{
  "success": true,
  "message": "Store deleted successfully.",
  "data": null
}
```


**Errors:** `409` with `"This store has sales and cannot be deleted."` once any item has been sold from the store. `404` if you have no store.

---

### List my products

**Endpoint:** `api/vendor/products`
**HTTP method:** `GET`
**Route type:** Vendor route

**Request (query parameters, optional):**

```json
{
  "page": 1
}
```

**Response `200`:** paginated, 20 per page, sorted by name.

```json
{
  "success": true,
  "message": "Products retrieved successfully.",
  "data": [
    {
      "id": 1,
      "name": "Ankara Shirt",
      "description": "Handmade, size M",
      "price": "5000.00",
      "unit_available": 20,
      "in_stock": true,
      "created_at": "2026-11-17T10:25:00.000000Z",
      "updated_at": "2026-11-17T10:25:00.000000Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 20,
    "total": 1,
    "last_page": 1,
    "next_page_url": null,
    "prev_page_url": null
  }
}
```

**Errors:** `404` with `"You have not created a store yet."`.

---

### Add a product

**Endpoint:** `api/vendor/products`
**HTTP method:** `POST`
**Route type:** Vendor route

**Request:**

```json
{
  "name": "Ankara Shirt",
  "description": "Handmade, size M",
  "price": 5000,
  "unit_available": 20
}
```

Rules: `name` required (max 255). `description` optional (max 2000). `price` required, between 0.01 and 99,999,999.99. `unit_available` required, a whole number of 0 or more.

**Response `201`:**

```json
{
  "success": true,
  "message": "Product created successfully.",
  "data": {
    "id": 1,
    "name": "Ankara Shirt",
    "description": "Handmade, size M",
    "price": "5000.00",
    "unit_available": 20,
    "in_stock": true,
    "created_at": "2026-11-17T10:25:00.000000Z",
    "updated_at": "2026-11-17T10:25:00.000000Z"
  }
}
```

**Errors:** `403` with `"Create your store before adding products."`. `422` for invalid data.

---

### View one of my products

**Endpoint:** `api/vendor/products/{id}`
**HTTP method:** `GET`
**Route type:** Vendor route

**Request:** no body.

```json
{}
```

**Response `200`:**

```json
{
  "success": true,
  "message": "Success",
  "data": {
    "id": 1,
    "name": "Ankara Shirt",
    "description": "Handmade, size M",
    "price": "5000.00",
    "unit_available": 20,
    "in_stock": true,
    "created_at": "2026-11-17T10:25:00.000000Z",
    "updated_at": "2026-11-17T10:25:00.000000Z"
  }
}
```

**Errors:** `403` if the product belongs to another vendor. `404` if it does not exist.

---

### Update one of my products

**Endpoint:** `api/vendor/products/{id}`
**HTTP method:** `PUT` or `PATCH`
**Route type:** Vendor route

**Request:** send **only the fields you want to change**, using the same rules as creation.

```json
{
  "price": 5500
}
```

**Response `200`:**

```json
{
  "success": true,
  "message": "Product updated successfully.",
  "data": {
    "id": 1,
    "name": "Ankara Shirt",
    "description": "Handmade, size M",
    "price": "5500.00",
    "unit_available": 20,
    "in_stock": true,
    "created_at": "2026-11-17T10:25:00.000000Z",
    "updated_at": "2026-11-17T12:10:00.000000Z"
  }
}
```

**Errors:** `403` if the product belongs to another vendor. `422` for invalid data.

---

### Delete one of my products

**Endpoint:** `api/vendor/products/{id}`
**HTTP method:** `DELETE`
**Route type:** Vendor route

**Request:** no body.

```json
{}
```

**Response `200`:**

```json
{
  "success": true,
  "message": "Product deleted successfully.",
  "data": null
}
```

**Errors:** `403` if the product belongs to another vendor.

---

### List items I must fulfil

**Endpoint:** `api/vendor/orders`
**HTTP method:** `GET`
**Route type:** Vendor route

**Request (query parameters, optional):**

```json
{
  "status": "pending",
  "page": 1
}
```


```json
{
  "success": true,
  "message": "Orders retrieved successfully.",
  "data": [
    { "...": "Vendor order object" }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 20,
    "total": 1,
    "last_page": 1,
    "next_page_url": null,
    "prev_page_url": null
  }
}
```

**Errors:** `422` for an invalid `status`.

---

### View one item to fulfil

**Endpoint:** `api/vendor/orders/{id}`
**HTTP method:** `GET`
**Route type:** Vendor route

**Request:** no body.

```json
{}
```

**Response `200`:**

```json
{
  "success": true,
  "message": "Success",
  "data": { "...": "Vendor order object" }
}
```

**Errors:** `403` if the item belongs to another vendor. `404` if it does not exist.

---

### Update an item's status

**Endpoint:** `api/vendor/orders/{id}/status`
**HTTP method:** `PATCH`
**Route type:** Vendor route

**Request:**

```json
{
  "status": "processing"
}
```

**Response `200`:**

```json
{
  "success": true,
  "message": "Order status updated.",
  "data": { "...": "Vendor order object, with the new status" }
}
```


**Errors:**

- `403` if the item belongs to another vendor.
- `422` with `"This order is pending. The next status must be processing."` when a step is skipped, repeated, or reversed.
- `422` with `"This order has already been delivered and cannot be changed."`.

---

### Earnings summary

**Endpoint:** `api/vendor/earnings`
**HTTP method:** `GET`
**Route type:** Vendor route

**Request:** no body.

```json
{}
```

**Response `200`:**

```json
{
  "success": true,
  "message": "Earnings summary retrieved successfully.",
  "data": {
    "balance": "9000.00",
    "total_earned": "9000.00",
    "total_withdrawn": "0.00",
    "pending_earnings": "4500.00",
    "sales": {
      "gross_sales": "10000.00",
      "commission_deducted": "1000.00",
      "net_earnings": "9000.00"
    },
    "orders_by_status": {
      "pending": 1,
      "processing": 0,
      "shipped": 0,
      "delivered": 1
    }
  }
}
```



Only **delivered** items count towards `balance` and `total_earned`.

---

## Buyer Endpoints

Every buyer route requires `Authorization: Bearer <token>` and the `buyer` role.

### View my cart

**Endpoint:** `api/cart`
**HTTP method:** `GET`
**Route type:** Buyer route

**Request:** no body.

```json
{}
```

**Response `200`:**

```json
{
  "success": true,
  "message": "Cart retrieved successfully.",
  "data": { "...": "Cart object" }
}
```

---

### Add a product to my cart

**Endpoint:** `api/cart/items`
**HTTP method:** `POST`
**Route type:** Buyer route

**Request:**

```json
{
  "product_id": 1,
  "quantity": 2
}
```

Rules: `product_id` required, must exist. `quantity` required, 1 to 1000. Adding a product that is already in the cart **increases** that line's quantity (one line per product).

**Response `200`:**

```json
{
  "success": true,
  "message": "Item added to cart.",
  "data": { "...": "Cart object, updated" }
}
```

**Errors:**

- `422` with `"\"Ankara Dress\" is out of stock."`.
- `422` with `"Only 20 unit(s) of \"Ankara Shirt\" are available."` when the total quantity in your cart would exceed the stock.
- `422` if `product_id` does not exist or `quantity` is invalid.

---

### Change a cart line's quantity

**Endpoint:** `api/cart/items/{id}`
**HTTP method:** `PUT`
**Route type:** Buyer route

**Request:**

```json
{
  "quantity": 3
}
```

`quantity` is required (1 to 1000) and **sets** the quantity exactly. To remove a line, use the remove endpoint.

**Response `200`:**

```json
{
  "success": true,
  "message": "Cart updated.",
  "data": { "...": "Cart object, updated" }
}
```

**Errors:** `403` if the line is in someone else's cart. `422` for an invalid quantity or one that exceeds the stock.

---

### Remove a cart line

**Endpoint:** `api/cart/items/{id}`
**HTTP method:** `DELETE`
**Route type:** Buyer route

**Request:** no body.

```json
{}
```

**Response `200`:**

```json
{
  "success": true,
  "message": "Item removed from cart.",
  "data": { "...": "Cart object, updated" }
}
```

**Errors:** `403` if the line is in someone else's cart.

---

### Empty my cart

**Endpoint:** `api/cart`
**HTTP method:** `DELETE`
**Route type:** Buyer route

**Request:** no body.

```json
{}
```

**Response `200`:**

```json
{
  "success": true,
  "message": "Cart cleared.",
  "data": {
    "id": 1,
    "items": [],
    "items_count": 0,
    "total_quantity": 0,
    "total": "0.00"
  }
}
```

---

### Checkout

**Endpoint:** `api/checkout`
**HTTP method:** `POST`
**Route type:** Buyer route

**Request:**

```json
{
  "delivery_address": "12 Allen Avenue, Ikeja, Lagos"
}
```

Rules: `delivery_address` required, 10 to 500 characters.

Response `201`

```json
{
  "success": true,
  "message": "Order placed successfully.",
  "data": {
    "id": 1,
    "total_price": "13500.00",
    "delivery_address": "12 Allen Avenue, Ikeja, Lagos",
    "payment_status": "paid",
    "items": [
      {
        "id": 1,
        "product_id": 1,
        "product_name": "Ankara Shirt",
        "store": "Ada Fashion",
        "quantity": 2,
        "price": "5000.00",
        "line_total": "10000.00",
        "status": "pending"
      },
      {
        "id": 2,
        "product_id": 3,
        "product_name": "Phone Charger",
        "store": "Chidi Gadgets",
        "quantity": 1,
        "price": "3500.00",
        "line_total": "3500.00",
        "status": "pending"
      }
    ],
    "created_at": "2026-11-17T11:00:00.000000Z"
  }
}
```

**Errors:**

- `422` with `"Your cart is empty."`.
- `422` with the stock problems listed under `errors.cart`, for example `"Only 1 unit(s) of \"Phone Charger\" are available, but you have 3 in your cart."`.
- `422` if `delivery_address` is missing or too short.

---

### List my orders

**Endpoint:** `api/orders`
**HTTP method:** `GET`
**Route type:** Buyer route

**Request (query parameters, optional):**

```json
{
  "page": 1
}
```

**Response `200`:** paginated, newest first, 20 per page. Each entry is an [Order object](#order-object-buyers-view).

```json
{
  "success": true,
  "message": "Orders retrieved successfully.",
  "data": [
    { "...": "Order object" }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 20,
    "total": 1,
    "last_page": 1,
    "next_page_url": null,
    "prev_page_url": null
  }
}
```

---

### View one of my orders

**Endpoint:** `api/orders/{id}`
**HTTP method:** `GET`
**Route type:** Buyer route

**Request:** no body.

```json
{}
```

**Response `200`:**

```json
{
  "success": true,
  "message": "Success",
  "data": { "...": "Order object" }
}
```

**Errors:**`403` if the order belongs to another buyer. `404` if it does not exist.




## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
"# market-square" 
