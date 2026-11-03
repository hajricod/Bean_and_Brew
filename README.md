# Bean & Brew

Bean & Brew is a small PHP coffee-shop website with a product catalog, customer accounts, a shopping cart, checkout, and admin tools for managing menu items.

## Features

- Browse featured drinks and pastries, filter the menu by category, and view product details.
- Register and log in to a customer account.
- Add products to a session-based cart, remove items, or clear the cart
- Submit an order and view its confirmation.
- Manage menu items from the admin area, including uploading product images.
- Use a responsive interface built with locally bundled Bootstrap 5 assets.

## Requirements

- PHP 8.x
- PHP PDO with the SQLite PDO driver (`pdo_sqlite`)
- A web server with PHP support. PHP's built-in development server is sufficient for local development.

No Composer or npm installation is required; Bootstrap and the cart JavaScript are included in `public/assets/`.

## Run locally

From the project root, start PHP's development server:

```powershell
php -S localhost:8000 -t public
```

Open [http://localhost:8000](http://localhost:8000) in your browser.

The application stores its SQLite database at `includes/database.sqlite`. This database file is included in the project. Keep the `includes/` directory alongside `public/`; the PHP pages load shared configuration and database access from there.

For product image uploads, PHP must allow file uploads, and `public/assets/images/` must be writable by the PHP process.

## Accounts and admin access

Create a regular customer account from the **Register** page. There are no default admin credentials. To grant admin access, first register an account, then update that user's role in the SQLite database using an authorized local environment. For example, with Python's standard SQLite module:

```python
import sqlite3

database = "includes/database.sqlite"
email = "admin@example.com"  # Replace with the registered account's email.

with sqlite3.connect(database) as connection:
    cursor = connection.execute(
        "UPDATE users SET role = 'admin' WHERE email = ?",
        (email,),
    )
    if cursor.rowcount != 1:
        raise RuntimeError("Expected to update exactly one registered account.")
```

After changing the role, log out and log back in. Admin users can open the **Admin Dashboard** from the account menu.

## Project structure

```text
includes/
  config.php                 Session, SQLite connection, and shared helpers
  database.sqlite            Application database
  header.php                 Shared navigation and site styles
  footer.php                 Shared footer and JavaScript includes
public/
  index.php                  Home page
  menu.php                   Product catalog and category filters
  product.php                Product details
  register.php, login.php    Customer account pages
  cart.php                   Shopping cart
  checkout.php               Order submission
  order_success.php          Order confirmation
  admin_dashboard.php        Admin overview
  admin_menu.php             Add and remove menu items
  admin_edit_item.php        Edit a menu item
  assets/                    Images, cart script, and local Bootstrap files
```

## Main routes

- `index.php` — Home
- `menu.php` — Menu catalog
- `cart.php` — Shopping cart
- `checkout.php` — Checkout
- `contact.php` and `about.php` — Shop information
- `admin_dashboard.php` — Admin dashboard (admin role required)

## Development checks

Run PHP syntax checks on the public pages from the project root:

```powershell
Get-ChildItem public -Filter *.php | ForEach-Object { php -l $_.FullName }
```

The built-in PHP server is intended for local development, not production hosting.
