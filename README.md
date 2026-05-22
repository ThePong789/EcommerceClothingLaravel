# 🛍️ LuxeStore — Laravel E-Commerce

A full-featured e-commerce application built with Laravel 11, featuring a beautiful storefront, customer accounts, and separate Admin & Staff dashboards.

---

## ✨ Features

### 🛒 Storefront (Customer-facing)
- **Home page** — Hero banner, featured products, categories, blog previews, testimonials
- **Shop** — Product listing with category filter, search, price sort, pagination
- **Product detail** — Size selector, add to cart, star ratings, reviews, related products
- **Shopping cart** — Real-time quantity update, remove items, order summary
- **Checkout** — Saved/new shipping addresses, multiple payment methods (COD, bank transfer, card)
- **Order tracking** — Visual status tracker (pending → processing → shipped → delivered)
- **Blog** — Posts with author, tags, sidebar recent posts
- **Reviews** — Customers can rate & review products (pending admin approval)
- **Profile** — Edit info, change password, manage saved addresses

### 🔐 Role-Based Access
| Role | Access |
|------|--------|
| **Admin** | Full dashboard: users, products, categories, orders, reviews, blog |
| **Staff** | Orders management, review approvals, product view |
| **Customer** | Shop, cart, checkout, orders, profile |

### 🖥️ Admin Dashboard
- KPI stats — total users, products, orders, revenue, pending orders, low stock
- Recent orders table with status & payment info
- Top-selling products chart
- Pending reviews queue
- Full CRUD: products (with sizes/pricing), categories, users, blog posts
- Order status management
- Review approve/delete

### 👔 Staff Dashboard
- Pending/processing/shipped order counts
- Order management & status updates
- Review approval queue
- Product stock overview

---

## 📦 Tech Stack
- **Backend**: Laravel 11 (PHP 8.2+)
- **Database**: MySQL (compatible with PostgreSQL/SQLite)
- **Frontend**: Blade templates with vanilla CSS (no build step required)
- **Icons**: Font Awesome 6
- **Fonts**: Google Fonts (Playfair Display + DM Sans)

---

## 🚀 Installation

### Prerequisites
- PHP 8.2+
- Composer
- MySQL 8.0+ (or MariaDB 10.3+)
- Node.js (optional, for Vite asset compilation)

### Step-by-step Setup

**1. Clone or extract the project**
```bash
cd /your/projects/folder
```

**2. Install PHP dependencies**
```bash
composer install
```

**3. Copy environment file**
```bash
cp .env.example .env
```

**4. Generate application key**
```bash
php artisan key:generate
```

**5. Configure your database in `.env`**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=luxestore
DB_USERNAME=root
DB_PASSWORD=your_password
```

**6. Create the database**
```sql
CREATE DATABASE luxestore CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**7. Run migrations**
```bash
php artisan migrate
```

**8. Seed the database** (demo data + default accounts)
```bash
php artisan db:seed
```

**9. Create storage symlink** (for uploaded images)
```bash
php artisan storage:link
```

**10. Start the development server**
```bash
php artisan serve
```

Visit: **http://localhost:8000**

---

## 🔑 Default Accounts

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@luxestore.com | password |
| **Staff** | staff@luxestore.com | password |
| **Customer** | john@example.com | password |
| **Customer** | sara@example.com | password |

---

## 📁 Project Structure

```
ecommerce-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── BlogController.php
│   │   │   │   ├── CategoryController.php
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── OrderController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   ├── ReviewController.php
│   │   │   │   └── UserController.php
│   │   │   ├── Staff/
│   │   │   │   └── DashboardController.php
│   │   │   ├── AuthController.php
│   │   │   ├── CartController.php
│   │   │   ├── HomeController.php
│   │   │   ├── OrderController.php
│   │   │   ├── ProfileController.php
│   │   │   └── ReviewController.php
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php
│   │       └── StaffMiddleware.php
│   └── Models/
│       ├── Blog.php, BlogDetail.php
│       ├── Cart.php, CartItem.php
│       ├── Category.php
│       ├── Order.php, OrderItem.php
│       ├── Payment.php
│       ├── Product.php
│       ├── Review.php
│       ├── Role.php
│       ├── Shipping.php
│       ├── Size.php
│       └── User.php
├── database/
│   ├── migrations/          # 4 migration files
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/views/
│   ├── layouts/
│   │   ├── app.blade.php    # Public layout
│   │   └── admin.blade.php  # Admin/Staff layout
│   ├── home/                # index, about, contact
│   ├── auth/                # login, register
│   ├── shop/                # index, show
│   ├── cart/                # index
│   ├── checkout/            # index
│   ├── orders/              # index, show
│   ├── profile/             # index
│   ├── blog/                # index, show
│   ├── admin/               # dashboard + CRUD views
│   └── staff/               # dashboard
└── routes/
    └── web.php
```

---

## 🗄️ Database Schema

| Table | Description |
|-------|-------------|
| `roles` | Admin, Staff, Customer |
| `users` | All users with role_id |
| `categories` | Product categories |
| `sizes` | XS, S, M, L, XL, shoe sizes |
| `products` | Product catalog |
| `product_size` | Pivot: price & stock per size |
| `cart` | One cart per user |
| `cart_items` | Items in cart |
| `shipping` | Saved delivery addresses |
| `orders` | Customer orders |
| `order_items` | Products in an order |
| `payment` | Payment record per order |
| `blog` | Blog author info |
| `blog_details` | Blog post content |
| `reviews` | Product reviews with approval |

---

## 🎨 UI Highlights
- Responsive design — works on mobile, tablet, desktop
- Sticky navigation with cart badge
- Luxury black & gold color palette
- Playfair Display headings + DM Sans body
- Zero build-step required — pure CSS, no Tailwind compilation needed
- Admin sidebar with active state highlighting
- Interactive modals for add/edit (no page reload)
- Image preview before upload
- Star rating widget in reviews

---

## ⚙️ Key Artisan Commands

```bash
# Run migrations fresh with seed
php artisan migrate:fresh --seed

# Create storage symlink
php artisan storage:link

# Clear all caches
php artisan optimize:clear

# List all routes
php artisan route:list
```

---

## 📝 Notes
- Product images are stored in `storage/app/public/products/`
- Blog images are stored in `storage/app/public/blogs/`
- Category images are stored in `storage/app/public/categories/`
- Run `php artisan storage:link` to make uploaded files accessible from the web
- The contact form is purely frontend (no backend handler) — wire up as needed
- Reviews require admin/staff approval before appearing on the storefront

---

## 📄 License
MIT License — free to use and modify for personal or commercial projects.
