# Locality - Travel Experience Sharing Platform

**Locality** is a web platform that connects travelers and locals around the world, acting as a digital "local friend" in any destination. The application allows users to share and discover real-world experiences through photos and comments pinned to an interactive map.

## 🌟 Features

### Core Functionalities
- **Explore**: Browse posts with search, trending, and random discovery
- **Nearby**: Interactive map showing nearby locations with dynamic loading
- **Journeys**: Create and share multi-stop travel routes with detailed nodes
- **People**: Browse user profiles and manage your own profile
- **Posts**: Create, edit, and delete location-based posts with images
- **Voting System**: "Known" / "Didn't Know" voting for posts
- **Multi-language Support**: English, Simplified Chinese (zh-CN), Traditional Chinese (zh-TW)

### User Features
- User authentication (login/register)
- Profile management with bio, home city, and avatar
- Create and manage posts with location mapping
- Create and manage journeys with multiple nodes
- Soft delete for posts and journeys (data retention)
- Image uploads for posts, journeys, and profiles

## 🛠️ Technology Stack

- **Backend**: PHP 8.2+ with Laravel 12
- **Frontend**: Blade templates with Bootstrap 5
- **Database**: SQLite (default) or MySQL/PostgreSQL
- **Maps**: Leaflet.js with OpenStreetMap
- **Storage**: Local filesystem (configurable for S3)

## 📋 Requirements

- PHP 8.2 or higher
- Composer
- Node.js and NPM (for frontend assets)
- SQLite (default) or MySQL/PostgreSQL
- Web server (Apache/Nginx) or PHP built-in server

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone https://github.com/yourusername/locality.git
cd locality/locality-php
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Configure Database

**Option A: SQLite (Default - Recommended for Development)**
```bash
# Create SQLite database file
touch database/database.sqlite
```

**Option B: MySQL/PostgreSQL**
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=locality
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Run Migrations
```bash
php artisan migrate
```

### 6. Seed Database (Optional - for demo data)
```bash
php artisan db:seed --class=PostJourneySeeder
```

### 7. Create Storage Link
```bash
php artisan storage:link
```

### 8. Build Frontend Assets
```bash
npm run build
# Or for development with hot reload:
npm run dev
```

### 9. Start Development Server
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## 🗄️ Database Structure

### Main Tables
- **users**: User accounts with profile information
- **posts**: Location-based posts with images
- **post_photos**: Multiple images per post
- **journeys**: Travel journey/routes
- **journey_nodes**: Individual stops in a journey
- **tags**: Post tags for categorization
- **votes**: User votes on posts (known/unknown)
- **bookmarks**: Saved posts (prepared for future use)
- **follows**: User following relationships (prepared for future use)

### Database Migrations
All migrations are in `database/migrations/`. Run migrations with:
```bash
php artisan migrate
```

## 📁 Project Structure

```
locality-php/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # Application controllers
│   │   └── Middleware/       # Custom middleware
│   └── Models/              # Eloquent models
├── config/                  # Configuration files
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/             # Database seeders
├── public/                  # Public assets
├── resources/
│   ├── lang/                # Language files
│   └── views/               # Blade templates
├── routes/
│   └── web.php              # Web routes
└── storage/                 # File storage
```

## 🌍 Localization

The application supports multiple languages:
- English (en)
- Simplified Chinese (zh-CN)
- Traditional Chinese (zh-TW)

Language files are located in `resources/lang/{locale}/messages.php`.

Switch language via URL parameter: `?lang=en` or `?lang=zh-CN`

## 🗺️ Map Integration

The application uses Leaflet.js with OpenStreetMap for interactive maps:
- Post location selection
- Nearby posts display
- Journey route visualization

No API key required for OpenStreetMap.

## 🔐 Security Features

- CSRF protection on all forms
- Authentication middleware
- Authorization checks for edit/delete operations
- Password hashing (bcrypt)
- Soft deletes for data retention
- Input validation on all forms

## 📝 Testing

### Using SQLite (Current Test Environment)
The default database configuration uses SQLite, which is perfect for testing:
- No database server setup required
- Fast and lightweight
- Easy to reset: delete `database/database.sqlite` and run migrations again

### Run Tests
```bash
php artisan test
```

## 🚢 Deployment

### Production Checklist
1. Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`
2. Run `php artisan config:cache`
3. Run `php artisan route:cache`
4. Run `php artisan view:cache`
5. Set up proper database (MySQL/PostgreSQL recommended)
6. Configure web server (Apache/Nginx)
7. Set up file storage (consider S3 for production)
8. Configure proper file permissions for `storage/` and `bootstrap/cache/`

### Environment Variables for Production
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
DB_CONNECTION=mysql
# ... other production settings
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👤 Author

Your Name - [Your GitHub](https://github.com/yourusername)

## 🙏 Acknowledgments

- Laravel Framework
- Bootstrap
- Leaflet.js
- OpenStreetMap contributors