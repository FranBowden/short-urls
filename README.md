# Short Urls

A Laravel application for shortening URLs with expiry control and live visit tracking

[View Website Here](https://sh-url.com)

- Sign up (these can be fake creds)
- This will redirect you to the dashboard where you can create shorter url links that will redirect you 

## Features

### URL Shortening
- Paste any long URL and generate a 6-character short code
- Short links are ready to share immediately

### Expiry Control
When creating a link you can choose how long it stays active:
- No expiry
- 1 hour
- 1 day
- 1 week
- 1 month

Once a link expires, visitors are shown a friendly "This URL has expired" page instead of being redirected.

### Visit Tracking
- Every click on a short link is recorded in a `url_visits` table
- The dashboard displays a live visitor counter that updates every 5 seconds without a page reload

### Dashboard
- View all your active short URLs alongside their visit counts
- View all your expired links and how many visits they received before expiring

### Authentication
- Register, log in, and manage your account via Laravel Breeze
- Email verification supported
- Each user only sees and manages their own links

## Tech Stack

- **PHP 8.5** / **Laravel 13**
- **Laravel Breeze** — authentication scaffolding
- **Tailwind CSS** — styling
- **JS polling** — live visit counts 
