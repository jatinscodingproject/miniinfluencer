# Mini Influencer Watchlist

A Laravel + React + TypeScript application for tracking Instagram profiles and storing historical snapshots of profile metrics.

## Tech Stack

### Backend

* Laravel 12
* PostgreSQL
* Redis
* Queue Workers
* Inertia.js

### Frontend

* React
* TypeScript
* Tailwind CSS

### Infrastructure

* Redis Queue
* Scheduler
* Apify Integration

---

## Features

### Profile Watchlist

Users can:

* Add Instagram usernames
* View tracked profiles
* Search profiles
* Filter by status
* View profile details

### Profile Metrics

The system tracks:

* Followers Count
* Following Count
* Posts Count
* Bio
* Profile Picture

### Snapshot History

Every refresh creates a historical snapshot.

Snapshots allow tracking follower growth over time.

---

## Architecture

### Service Abstraction

The application uses a provider pattern.

```php
ProfileProviderInterface
```

Implementations:

```php
MockProfileProvider
ApifyService
```

This allows switching between mock and real providers without changing business logic.

---

### Background Jobs

Profile refreshes are processed asynchronously using:

```php
FetchProfileJob
```

Benefits:

* Non-blocking UI
* Retry support
* Better scalability

---

### Queue System

Redis is used as the queue backend.

Run worker:

```bash
php artisan queue:work
```

---

### Scheduler

Profiles are automatically refreshed using:

```bash
php artisan profiles:refresh
```

Scheduler:

```bash
php artisan schedule:work
```

---

### Concurrency Protection

PostgreSQL advisory locks prevent duplicate refreshes:

```sql
pg_try_advisory_lock()
```

This ensures only one refresh job can process a profile at a time.

---

### Circuit Breaker

Implemented using Redis.

Purpose:

* Prevent continuous failures against external APIs
* Automatically pause requests after repeated failures

---

### Token Bucket

Implemented using Redis.

Purpose:

* Rate limiting
* Prevent API overuse

---

### Webhook Security

Implemented:

* HMAC signature verification
* Replay attack protection
* Redis nonce storage

Endpoint:

```text
POST /webhooks/apify
```

---

## Installation

Clone repository:

```bash
git clone <repository-url>
```

Install dependencies:

```bash
composer install

npm install
```

Create environment:

```bash
cp .env.example .env
```

Generate key:

```bash
php artisan key:generate
```

Configure:

```env
DB_CONNECTION=pgsql

REDIS_HOST=127.0.0.1

QUEUE_CONNECTION=redis

APIFY_TOKEN=your_token
```

Run migrations:

```bash
php artisan migrate
```

Start application:

```bash
php artisan serve
```

Frontend:

```bash
npm run dev
```

Queue worker:

```bash
php artisan queue:work
```

Scheduler:

```bash
php artisan schedule:work
```

---

## Health Check

```text
GET /healthz
```

Returns application health information.

---

## Error Pages

Custom pages:

* 404 Not Found
* 500 Internal Server Error

Implemented using Inertia.js.

---

## API Provider

Current implementation supports:

### Mock Provider

Used for local development.

### Apify Provider

Used for production integrations.

---

## Time Spent

Approximately: 6 hours

---

## Future Improvements

* Automated testing
* Metrics graphs
* WebSocket updates
* Docker support
* Monitoring dashboard

---

## Author

Jatin Dixit

