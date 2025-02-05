# restaurant-app

├── public/
│   ├── admin/        # Dashboard, user/menu/orders management, reports
│   ├── customer/     # Menu, cart, checkout, reservations, order tracking
│   ├── assets/       # CSS, JS, images, invoices
│
├── app/
│   ├── config/       # Database, env, constants
│   ├── includes/     # Auth, header, footer, helpers, CSRF
│   ├── classes/      # User, MenuItem, Order, Reservation, Payment, Inventory
│   ├── lib/          # Stripe, PHPMailer, external libraries
│   ├── scripts/      # DB schema, seed data, cron jobs, backups
│
├── views/
│   ├── admin/        # Admin pages
│   ├── customer/     # Customer pages
│   ├── errors/       # 403, 404, 500 pages
│
├── .htaccess         # Security + clean URLs
├── README.md         # Project documentation