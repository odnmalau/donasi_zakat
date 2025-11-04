# 💚 Platform Donasi Zakat Online - Dokumentasi Lengkap

![Status](https://img.shields.io/badge/Status-Production%20Ready-green)
![Tests](https://img.shields.io/badge/Tests-62%2F62%20PASSING-brightgreen)
![PHP](https://img.shields.io/badge/PHP-8.4.14-blue)
![Laravel](https://img.shields.io/badge/Laravel-12.36.1-red)

Aplikasi web modern untuk penyaluran zakat yang **transparan, aman, dan mudah digunakan**. Platform ini memudahkan donatur untuk menyalurkan zakat mereka kepada penerima manfaat dengan sistem verifikasi yang ketat.

---

## 📋 Daftar Isi

- [Features](#features)
- [Tech Stack](#tech-stack)
- [System Requirements](#system-requirements)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Database Structure](#database-structure)
- [User Roles & Permissions](#user-roles--permissions)
- [Project Structure](#project-structure)
- [API Routes](#api-routes)
- [How to Use](#how-to-use)
- [Testing](#testing)
- [Deployment](#deployment)
- [Troubleshooting](#troubleshooting)

---

## ✨ Features

### 🎯 Core Features
- ✅ **Campaign Management** - Create, edit, dan publish kampanye zakat
- ✅ **Donation System** - Donatur bisa menyumbang dengan mudah
- ✅ **Verification Workflow** - Petugas verifikasi donasi & reject jika perlu
- ✅ **Distribution Tracking** - Lacak penyaluran dana ke penerima manfaat
- ✅ **Dynamic Landing Page** - CMS terintegrasi untuk manage konten tanpa code changes
- ✅ **Public Campaigns** - Halaman kampanye publik untuk donatur
- ✅ **Role-Based Access** - 4 role berbeda dengan permissions unik
- ✅ **Real-time Dashboard** - Statistics yang selalu update
- ✅ **Responsive Design** - Mobile-friendly UI

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|------------|
| **Framework** | Laravel 12.36.1 |
| **Language** | PHP 8.4.14 |
| **Database** | MariaDB / MySQL |
| **ORM** | Eloquent |
| **Admin Panel** | Filament 4.2.0 |
| **Frontend** | Tailwind CSS 4.1.16, Livewire 3.6.4 |
| **Testing** | Pest 4.1.3 |
| **Code Quality** | Laravel Pint 1.25.1 |
| **Auth** | Spatie Permission 6.23.0 |

---

## 💻 System Requirements

**Minimum:**
- PHP 8.4+
- Composer
- Node.js & npm
- MariaDB 10.3+ atau MySQL 8.0+

**Recommended:**
- PHP 8.4+
- Node.js 18+
- MariaDB 10.5+
- 4GB RAM
- SSD storage

---

## 📥 Installation

### 1. Clone & Install
```bash
git clone <repository-url>
cd donasi_zakat

# Install dependencies
composer install
npm install
```

### 2. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Database Setup
```bash
# Run migrations & seeders
php artisan migrate:seed
```

### 4. Build Assets & Run
```bash
npm run dev
php artisan serve
```

### ✅ Access Application
- **Homepage**: http://localhost:8000
- **Admin Panel**: http://localhost:8000/admin
- **Admin Email**: admin@donasi.test
- **Password**: password

---

## 🚀 Quick Start

### For Admin
1. Login ke `/admin`
2. Click **Campaigns** → **+ Create** untuk buat kampanye
3. Click **Donations** → Edit → **Verifikasi Donasi** untuk verify donasi
4. Click **Settings** → Edit konten landing page (tanpa code changes!)

### For Donatur
1. Buka homepage → Scroll ke **Kampanye Unggulan**
2. Click **Lihat Detail** pada kampanye pilihan
3. Isi form donasi & submit

---

## 🗄️ Database Structure

### Core Tables
- **Users** - User accounts dengan roles
- **Campaigns** - Kampanye zakat dengan status & target amount
- **Donations** - Donasi dengan status (pending, verified, rejected)
- **Distributions** - Penyaluran dana ke mustahik
- **Settings** - Content management untuk landing page

### Total Tables: 10+
### Migrations: 10+
### Relationships: 15+

---

## 👥 User Roles & Permissions

```
Super Admin
├── Manage Campaigns ✅
├── Verify Donations ✅
├── Manage Users ✅
├── Manage Settings ✅
└── View Reports ✅

Petugas Yayasan
├── Verify Donations ✅
├── View Campaigns ✅
├── Manage Distributions ✅
└── Generate Reports ✅

Donatur
├── Submit Donations ✅
├── View Own Donations ✅
└── View Public Campaigns ✅

Mustahik
└── View Received Distributions ✅
```

---

## 📁 Project Structure

```
donasi_zakat/
├── app/
│   ├── Filament/         # Admin panel resources
│   │   ├── Pages/        # Dashboard
│   │   ├── Resources/    # CRUD interfaces
│   │   └── Widgets/      # Dashboard widgets
│   ├── Http/Controllers/ # Controllers
│   ├── Livewire/         # Reactive components
│   └── Models/           # Eloquent models
├── database/
│   ├── migrations/       # Database schemas
│   ├── factories/        # Factory definitions
│   └── seeders/          # Database seeds
├── resources/views/
│   ├── landing/          # Landing page
│   ├── campaigns/        # Campaign pages
│   └── livewire/         # Livewire components
├── tests/
│   ├── Feature/          # Feature tests (62 tests)
│   └── Unit/             # Unit tests
├── routes/               # Web routes
└── storage/              # User uploads
```

---

## 🔗 API Routes

### Public Routes
```
GET  /                              Landing page
GET  /campaigns                      Campaign listing
GET  /campaigns/{slug}               Campaign detail
```

### Admin Routes (Protected)
```
GET    /admin                        Dashboard
GET    /admin/campaigns              Campaign list
POST   /admin/campaigns              Create campaign
PUT    /admin/campaigns/{id}         Update campaign

GET    /admin/donations              Donation list
PUT    /admin/donations/{id}         Verify/reject donation

GET    /admin/distributions          Distribution list
POST   /admin/distributions          Create distribution

GET    /admin/settings               Settings list
PUT    /admin/settings/{id}          Update setting

GET    /admin/users                  User list
```

---

## 🚀 How to Use

### Manage Content (Landing Page)
1. Go to `/admin/settings`
2. Edit any setting:
   - `hero_title` → Homepage title
   - `hero_subtitle` → Homepage subtitle
   - `about_content` → About section
   - `how_step1_title` → How it works step 1
   - etc.
3. Changes langsung terlihat di homepage! 🎉

### Create Campaign
1. Click **Campaigns** → **+ Create**
2. Fill form: title, description, images, target amount, dates
3. Set status to **Active** untuk publish
4. Save

### Verify Donation
1. Click **Donations**
2. Find donation dengan status "pending"
3. Click edit → Click **Verifikasi Donasi** (hijau)
4. Donation auto-verified & campaign collected_amount auto-updated

### Create Distribution
1. Click **Distributions** → **+ Create**
2. Select campaign, mustahik, amount
3. Save

---

## 🧪 Testing

### Test Coverage: 62/62 ✅

**Test Categories:**
- Campaign Controller Tests (14 tests)
- Livewire Component Tests (28 tests)
- Filament Resource Tests (6 tests)
- Authorization Tests (14 tests)

### Run Tests
```bash
# All tests
php artisan test

# Specific test file
php artisan test tests/Feature/DonationFlowTest.php

# With coverage (requires Xdebug)
XDEBUG_MODE=coverage php artisan test --coverage-html=/tmp/coverage
```

**Latest Result:**
```
Tests:    62 passed (135 assertions)
Duration: 15.46s
Status:   ✅ ALL PASS
```

---

## 🚀 Deployment

### Pre-Deployment Checklist
- [ ] All tests passing
- [ ] Code formatted (`vendor/bin/pint`)
- [ ] Environment configured
- [ ] Database ready
- [ ] Assets built (`npm run build`)
- [ ] SSL certificate configured

### Production Setup
```bash
# Copy production environment
cp .env.example .env.production

# Update database credentials & app settings in .env.production

# Generate key
php artisan key:generate --env=production

# Run migrations
php artisan migrate --force

# Build assets
npm run build

# Cache configuration
php artisan config:cache
php artisan route:cache
```

### Recommended Hosting
- **Shared Hosting**: Minimal setup required
- **VPS**: Full control, better performance
- **Cloud** (AWS, Google Cloud, Azure): Auto-scaling

### Web Server Configuration
**Nginx**: [See full README for Nginx config]

---

## 🔧 Troubleshooting

| Issue | Solution |
|-------|----------|
| Database table not found | Run `php artisan migrate` |
| Assets 404 errors | Run `npm run dev` or `npm run build` |
| Storage permission denied | `chmod -R 775 storage` |
| View not found | Check `resources/views/` path |
| CSRF token mismatch | `php artisan cache:clear` |

---

## 📊 Project Statistics

- **Total Tests**: 62 (100% passing)
- **Test Assertions**: 135
- **Database Tables**: 10+
- **Models**: 5
- **Controllers**: 2
- **Filament Resources**: 5
- **API Routes**: 20+
- **Lines of Code**: 2000+

---

## 📚 Documentation Files

- `README.md` - This file (complete documentation)
- `TEST_RESULTS.md` - Test execution results
- `CLAUDE.md` - Laravel Boost guidelines
- `routes/web.php` - API routes definition

---

## 📝 License

Proprietary - All Rights Reserved © 2025

---

## 🤝 Support & Contact

- **Email**: support@donasi-zakat.id
- **Issues**: GitHub Issues
- **Documentation**: See full README section

---

## 🎯 Version

**v1.0.0** - Initial Release (2025-11-04)
- ✅ Core donation system
- ✅ Campaign management
- ✅ Filament admin panel
- ✅ Dynamic landing page
- ✅ 62 comprehensive tests
- ✅ Complete documentation

---

**Status**: ✅ Production Ready | **Last Updated**: 2025-11-04 | **Maintained By**: Development Team
