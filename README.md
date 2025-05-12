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
      "email": "test@demo.com",
      "password": "12345678"
  }

### 2. Ensure PHP 8.1+
    Check PHP version:
    php -v

### 3. Install Dependencies
    composer install
     
### (3) if any issue in composer install then use command
    composer install --ignore-platform-reqs

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

## ✅ 5. 📄 Email Documentation
Update your README:

    ### 📬 Email Notifications

    - On borrow and return, users receive confirmation emails.
    - Email configured via `.env` using SMTP.
    - Templates located in `resources/views/emails/`.

    ### 🪵 Event Logging

    - Each borrow and return event is logged in `storage/logs/laravel.log`.


## ✅  📧 Mail Configuration in .env
Update your .env file with mail credentials. Example using Gmail SMTP:

MAIL_MAILER=smtp
    MAIL_HOST=smtp.gmail.com
    MAIL_PORT=587
    MAIL_USERNAME=your_email@gmail.com
    MAIL_PASSWORD=your_app_password   # Use App Password if 2FA enabled
    MAIL_ENCRYPTION=tls
    MAIL_FROM_ADDRESS=your_email@gmail.com
    MAIL_FROM_NAME="Library API"

## 📚 Book Management
    POST /api/create-book – Add new book
    POST /api/update-book – Update book
    POST /api/delete-book – Delete book
    GET /api/book-list – List all books
    POST /api/books-borrow – Borrow a book
    POST /api/books-return – Return a book
    GET /api/my-borrow-book – List your borrowed books

 ## 📑 API Documentation
   - Swagger UI reads this JSON and shows a nice web interface where you can:
    - View all available API endpoints
    - See request/response formats
    - Execute live API calls (if enabled)

    ---

    ### 🛠 How to Regenerate Documentation

    Whenever you update your API annotations or routes, run:

    php artisan l5-swagger:generate

  ### 📎 Access Link
    Once running, visit:
    Swagger UI is available at:
    http://localhost:8000/api/documentation

  ### 🧪 Running Automated Tests
    This project includes automated tests to ensure the API functions correctly. To run the tests, execute the following command:

    php artisan test


    Example Tests:
    User Registration: Ensure users can register successfully.
    User Login: Ensure users can login and receive a valid token.
    Book Borrowing/Returning: Test book borrowing and returning logic for members.
    Book CRUD: Ensure that only admins can create, update, and delete books.

