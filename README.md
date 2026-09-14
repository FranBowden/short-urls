# Short Urls

A Laravel application for shortening URLs with expiry control and live visit tracking

[View Website Here](https://sh-url.com)

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

<img width="1728" height="966" alt="image" src="https://github.com/user-attachments/assets/064a36d2-0da4-471c-ad33-6718f1cac67c" />

<img width="1728" height="935" alt="image" src="https://github.com/user-attachments/assets/f6e7d481-643d-46ba-a4ff-a27869506633" />

<img width="1710" height="935" alt="image" src="https://github.com/user-attachments/assets/00d7b388-3912-4d39-befc-ff9b4b9221e8" />

<img width="1728" height="935" alt="image" src="https://github.com/user-attachments/assets/62c3d13b-ac6e-4efc-be85-c92a9071cc41" />
