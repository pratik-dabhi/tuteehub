# API Documentation

This document provides details about the available API endpoints, request payloads, response formats, data types, and authentication requirements.

---

## **Authentication Endpoints**

### 1. Register User

**URL:** `POST /api/users/register`
**Description:** Register a new user.

**Request Payload:**

| Field    | Type   | Description       | Example                                     |
| -------- | ------ | ----------------- | ------------------------------------------- |
| name     | string | Name              | John Doe                                    |
| email    | string | Email             | [john@example.com](mailto:john@example.com) |
| password | string | Password          | Pass@123                                    |
| mobile   | string | Mobile number     | 1234567890                                  |
| address  | string | Address of user   | 123 Main St                                 |

```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "Pass@123",
  "mobile": "1234567890",
  "address": "123 Main St"
}
```

**Response (Success):**

```json
{
  "status": "success",
  "message": "User registered successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    },
    "token": "sanctum_token_here"
  }
}
```

**Example (cURL):**

```bash
curl -X POST http://127.0.0.1:8000/api/users/register \
-H "Content-Type: application/json" \
-d '{"name":"John Doe","email":"john@example.com","password":"Pass@123","mobile":"1234567890","address":"123 Main St"}'
```

---

### 2. Login

**URL:** `POST /api/login`
**Description:** Login a user and generate an API token.

**Request Payload:**

| Field    | Type   | Description | Example                                     |
| -------- | ------ | ----------- | ------------------------------------------- |
| email    | string | User email  | [john@example.com](mailto:john@example.com) |
| password | string | Password    | Pass@123                                    |

```json
{
  "email": "john@example.com",
  "password": "Pass@123"
}
```

**Response (Success):**

```json
{
  "status": "success",
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    },
    "token": "sanctum_token"
  }
}
```

**Example (cURL):**

```bash
curl -X POST http://127.0.0.1:8000/api/login \
-H "Content-Type: application/json" \
-d '{"email":"john@example.com","password":"Pass@123"}'
```

---

## **Subscription Endpoints** *(Authenticated)*

> All subscription routes require the header:
> `Authorization: Bearer {token}`

### 3. Subscribe

**URL:** `POST /api/subscription/subscribe`
**Description:** Subscribe a user to a plan.

**Request Payload:**

| Field | Type   | Description       | Example |
| ----- | ------ | ----------------- | ------- |
| plan  | string | Subscription plan | Basic   |

```json
{
  "plan": "Basic"
}
```

**Response (Success):**

```json
{
  "status": "success",
  "message": "Subscription successful",
  "data": {
    "plan": "Basic",
    "started_at": "2025-09-21 08:06:17",
    "status": "active"
  }
}
```

**Example (cURL):**

```bash
curl -X POST http://127.0.0.1:8000/api/subscription/subscribe \
-H "Authorization: Bearer sanctum_token_here" \
-H "Content-Type: application/json" \
-d '{"plan":"Basic"}'
```

---

### 4. Subscription Status

**URL:** `GET /api/subscription/status`
**Description:** Get the current subscription status of the authenticated user.

**Response (Success):**

```json
{
  "status": "success",
  "data": {
    "plan": "Basic",
    "calls_per_minute": 3,
    "started_at": "2025-09-21 08:06:17",
    "status": "active"
  }
}
```

**Response (Error):**

```json
{
  "status": "error",
  "message": "No active subscription"
}
```

**Example (cURL):**

```bash
curl -X GET http://127.0.0.1:8000/api/subscription/status \
-H "Authorization: Bearer sanctum_token_here"
```

---

### 5. Cancel Subscription

**URL:** `POST /api/subscription/cancel`
**Description:** Cancel the current subscription.

**Response (Success):**

```json
{
  "data": [],
  "status": "success",
  "code": 200,
  "message": "Subscription cancelled successfully",
  "toast": true
}
```

**Example (cURL):**

```bash
curl -X POST http://127.0.0.1:8000/api/subscription/cancel \
-H "Authorization: Bearer sanctum_token_here"
```

---

## **User Endpoints** *(Authenticated + Active Subscription)*

> Routes below require:
>
> * `Authorization: Bearer {token}`
> * Active subscription
> * Throttling (`throttle:subscription`)

### 6. Get User by ID

**URL:** `GET /api/users/{id}`
**Description:** Retrieve user details by ID.

| Parameter | Type    | Description    | Example |
| --------- | ------- | -------------- | ------- |
| id        | integer | ID of the user | 1       |

**Response (Success):**

```json
{
  "status": "success",
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "created_at": "2025-01-01 10:00:00"
  }
}
```

**Example (cURL):**

```bash
curl -X GET http://127.0.0.1:8000/api/users/1 \
-H "Authorization: Bearer sanctum_token_here"
```

**Response (Error):**

```json
{
  "status": "error",
  "message": "User not found"
}
```

**Response (Throttle Limit Exceeded):**

```json
{
  "status": "error",
  "message": "API rate limit exceeded for your subscription plan."
}
```
