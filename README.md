```markdown
# restaurant-App

This is a Fullstack PHP project for managing a restaurant's website and backend system, including a dashboard for admins and a customer-facing website with menu, cart, checkout, and reservations.

## Project Structure

```
├── public/
│   ├── admin/               # Dashboard, user/menu/orders management, reports
│   ├── customer/            # Menu, cart, checkout, reservations, order tracking
│   ├── assets/              # CSS, JS, images, invoices
│
├── app/
│   ├── config/              # Database, env, constants
│   ├── includes/            # Auth, header, footer, helpers, CSRF
│   ├── classes/             # User, MenuItem, Order, Reservation, Payment, Inventory
│   ├── lib/                 # Stripe, PHPMailer, external libraries
│   ├── scripts/             # DB schema, seed data, cron jobs, backups
│
├── views/
│   ├── admin/               # Admin pages
│   ├── customer/            # Customer pages
│   ├── errors/              # 403, 404, 500 error pages
│
├── README.md                # Project documentation

```

## Installation


## Features

- **Admin Dashboard**: Manage users, menus, orders, and generate reports.
- **Customer Menu**: Browse the menu, add items to the cart, and proceed to checkout.
- **Reservations**: Customers can book a table for dining.
- **Order Tracking**: Customers can track the status of their orders.
- **Payments**: Integrated with Stripe for processing payments.
- **Inventory Management**: Admins can manage and track the inventory.
```
