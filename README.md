# PHP Login System (PHP + MySQL + Bootstrap 5)

## Setup
1. Install XAMPP/Laragon (Apache + MySQL) and start both.
2. Copy this `session` folder into `htdocs` (XAMPP) or `www` (Laragon).
3. Import `login_system.sql` via phpMyAdmin (or `mysql -u root < login_system.sql`).
4. Edit credentials in `db.php` if yours differ from the defaults.
5. Open http://localhost/session/ → click **Register** → create an account → log in.

## Files
| File | Purpose |
|------|---------|
| `db.php` | PDO connection + session start |
| `login_system.sql` | Creates the database and `users` table |
| `index.php` | Login page |
| `register.php` | Sign-up page |
| `dashboard.php` | Protected dashboard with account stats and searchable users table |
| `profile.php` | Protected profile details and profile editor |
| `settings.php` | Protected appearance and password settings |
| `home.php` | Redirects to `dashboard.php` |
| `includes/header.php` | Shared page header, styles, and theme controls |
| `includes/sidebar.php` | Shared navigation with the current page highlighted |
| `includes/footer.php` | Shared page footer and theme persistence script |
| `includes/auth.php` | Shared login guard and current-user lookup |
| `logout.php` | Ends the session |

## Security touches
- Passwords hashed with `password_hash()` / checked with `password_verify()`
- PDO prepared statements (prevents SQL injection)
- `htmlspecialchars()` on all output (prevents XSS)
- `session_regenerate_id()` on login
- Dashboard, profile, and settings redirect guests to the login page
- Profile and password forms validate a session CSRF token
- The selected light/dark appearance is saved in browser `localStorage`
