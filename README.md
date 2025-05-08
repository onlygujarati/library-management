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


### 1. Clone the Repo
<!-- ```bash
git clone https://github.com/your-username/library-api.git
cd library-api -->

The API supports secure authentication using **Laravel Sanctum**. Upon successful registration or login, a bearer token is returned that must be included in future requests to access protected routes.

- **Register URL**: `POST /api/register`  
  Example Payload:
  ```json
  {
      "name": "test",
      "email": "test@demo.com",
      "password": "12345678",
      "user_type": "user",
      "status": "active"
  }

  
- **Login URL**: `POST /api/login`  
  Example Payload:
  ```json
  {
      "name": "test",
      "email": "test@demo.com",
      "password": "12345678",
      "user_type": "user",
      "status": "active"
  }