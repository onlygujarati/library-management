# 📚 Laravel Library Management API

A RESTful API to manage books, users, and borrowing logic, built with Laravel 10.

---
## 🚀 Features

- User registration & login (Sanctum tokens)
- Role-based access (admin/member)
- Book CRUD (admin only)
- Borrow & return books (members)
- API documentation (Swagger)

---

## ⚙️ Setup Instructions

Follow these steps to run the application locally:

### 1. Clone the Repo

bash
git clone https://github.com/your-username/library-api.git
cd library-api

The API supports secure authentication using **Laravel Sanctum**. Upon successful registration or login, a bearer token is returned that must be included in future requests to access protected routes.

 **Register URL**: `POST /api/register`  
  Example Payload:
  json
  {
      "name": "test",
      "email": "test@demo.com",
      "password": "12345678",
      "user_type": "user",
      "status": "active"
  }

  
 **Login URL**: `POST /api/login`  
  Example Payload:
  json
  {
      "name": "test",
      "email": "test@demo.com",
      "password": "12345678",
      "user_type": "user",
      "status": "active"
  }

### 2. Ensure PHP 8.1+
    Check PHP version:
    php -v

### 3. Install Dependencies
    composer install

### 4. Create Environment File
    cp .env.example .env

### 5. Generate Application Key
    php artisan key:generate

### 6. Configure Database
Create a MySQL database named laravel, then edit the .env file
    DB_DATABASE=laravel
    DB_USERNAME=root
    DB_PASSWORD=

###  7. Run Migrations
    php artisan migrate

###  8.  Start the Application
    php artisan serve

    Visit the app at:
    http://localhost:8000

---

## 🔐 Authentication
The API supports secure authentication using Laravel Sanctum. Upon successful registration or login, you’ll receive a bearer token. Include this token in the Authorization header for protected routes:

    makefile
    Authorization: Bearer your_token_here


## 📚 Book Management
    POST /api/create-book – Add new book
    POST /api/update-book – Update book
    POST /api/delete-book – Delete book
    GET /api/book-list – List all books
    POST /api/books-borrow – Borrow a book
    POST /api/books-return – Return a book
    GET /api/my-borrow-book – List your borrowed books

📑 API Documentation
    Swagger UI is available at:
    http://localhost:8000/api/documentation