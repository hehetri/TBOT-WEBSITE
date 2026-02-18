# TBOT 2011 Reconstruction (PHP edition)

## Conversion summary
- Entire site was migrated from `index.html` to `index.php` preserving the same folder-based URLs.
- Internal references to `index.html` were updated to `index.php`.
- Main page login now posts to local backend: `auth/login.php`.
- Main page registration buttons now point to `register.php`.

## Database configuration
File: `config.php`

```php
$host = "127.0.0.1";
$user = "bout";
$pass = "202040pp";
$db   = "bout_evolution";
```

## Implemented backend pages
- `auth/login.php`
  - Validates `user_id` + `passw` from forms.
  - Authenticates against table `users`.
  - Supports `password_verify` (Argon hash) and plain fallback comparison.
  - Updates `last_ip` after successful login.
- `register.php`
  - Implements user registration with validation.
  - Inserts into `users (username, password, email, last_ip)`.
  - Hashes password with `PASSWORD_ARGON2I`.
- `ranks/index.php`
  - Uses live SQL queries for level/exp/stats/guild rankings.
  - Filters GM/Admin (`position >= 150` and `[GM]%` names).

## Missing archived pages
Placeholder pages were kept in PHP format with notice:
> This page was not archived in 2011.

## Run locally
```bash
php -S 0.0.0.0:8000 -t .
```
