# Locality - Project Report

## 1. Reasonable Application Logic

### Core Concept
Locality is designed as a location-based social platform that bridges the gap between travelers and locals. The application logic follows a user-centric approach where:

1. **Content Creation Flow**: Users create posts about locations, which are geotagged and can be discovered by others
2. **Discovery Flow**: Users can explore content through multiple channels (search, trending, nearby map)
3. **Journey Planning**: Users can link multiple posts into cohesive travel journeys
4. **Social Interaction**: Users can vote on posts, view profiles, and follow other users (prepared for future implementation)

### Business Logic
- **Location-based Discovery**: Posts are tied to geographic coordinates, enabling map-based exploration
- **Voting System**: "Known" vs "Didn't Know" voting provides engagement metrics beyond simple likes
- **Soft Deletes**: Content deletion preserves data for potential recovery or analytics
- **Multi-language Support**: Ensures accessibility for international users

## 2. Functionalities - Software Architecture

### 2.1 Core Features

#### Post Management
- Create posts with title, description, category, location (lat/lng), city, country
- Upload multiple images per post
- Tag posts for categorization
- Edit and soft-delete own posts
- View post details with map visualization

#### Journey Management
- Create multi-node journeys with detailed information
- Link existing posts to journey nodes
- Include transportation, accommodation, and tips per node
- Visualize journey route on map
- Edit and soft-delete own journeys

#### User Management
- User registration and authentication
- Profile management (name, email, bio, home city, avatar)
- View other users' profiles and content
- Browse all users

#### Discovery Features
- **Explore**: Browse all posts with pagination
- **Search**: Filter posts by keywords and categories
- **Discover**: Trending and random post discovery
- **Nearby**: Interactive map showing nearby posts with dynamic loading

#### Voting System
- Vote "Known" or "Didn't Know" on posts
- Track vote counts per post
- Support for both authenticated users and guests (IP-based)

### 2.2 Software Architecture

#### MVC Pattern
- **Models**: Eloquent ORM models (User, Post, Journey, JourneyNode, Tag, Vote, etc.)
- **Views**: Blade templates for server-side rendering
- **Controllers**: Handle HTTP requests and business logic

#### Database Architecture
- **Relational Design**: Normalized database with foreign key relationships
- **Soft Deletes**: `deleted_at` timestamp for data retention
- **Polymorphic Relationships**: Prepared for extensibility

#### Middleware Stack
- Authentication middleware
- Locale setting middleware
- CSRF protection
- Session management

## 3. Client Side Design

### 3.1 Frontend Technologies
- **Blade Templates**: Server-side templating engine
- **Bootstrap 5**: Responsive CSS framework
- **JavaScript (Vanilla)**: For interactive features
- **Leaflet.js**: Interactive map functionality

### 3.2 User Interface Design

#### Navigation
- Fixed top navigation bar
- Language switcher
- User authentication controls
- "My Profile" quick access

#### Responsive Design
- Mobile-first approach
- Bootstrap grid system
- Responsive cards and layouts
- Touch-friendly interface

#### Interactive Features
- **Map Integration**: 
  - Click-to-select location on maps
  - Current location detection
  - Dynamic marker loading
  - Route visualization for journeys
- **Dynamic Content Loading**: AJAX-based nearby posts loading
- **Form Validation**: Client-side and server-side validation

### 3.3 User Experience
- Intuitive navigation between sections
- Clear visual hierarchy
- Image-based content cards
- Smooth page transitions
- Error handling and user feedback

## 4. Server Side Design

### 4.1 Backend Framework
- **Laravel 12**: Modern PHP framework
- **PHP 8.2+**: Latest PHP features

### 4.2 Architecture Components

#### Controllers
- `ExploreController`: Handles post browsing and discovery
- `PostController`: Manages post CRUD operations
- `JourneyController`: Manages journey CRUD operations
- `NearbyController`: Handles map-based post queries
- `PeopleController`: User profile management
- `AuthController`: Authentication logic

#### Models & Relationships
```
User
├── hasMany Post
├── hasMany Journey
└── hasMany Vote

Post
├── belongsTo User
├── belongsToMany Tag
├── hasMany PostPhoto
└── hasMany Vote

Journey
├── belongsTo User
└── hasMany JourneyNode

JourneyNode
├── belongsTo Journey
└── belongsTo Post (optional)
```

#### Database Design
- **Normalized Schema**: Reduces data redundancy
- **Indexes**: Optimized for location-based queries
- **Foreign Keys**: Ensures referential integrity
- **Soft Deletes**: Data retention strategy

### 4.3 Security Implementation
- **Authentication**: Laravel's built-in auth system
- **Authorization**: Ownership checks for edit/delete operations
- **CSRF Protection**: Token-based form protection
- **Input Validation**: Server-side validation on all inputs
- **Password Hashing**: Bcrypt encryption
- **SQL Injection Prevention**: Eloquent ORM parameter binding

### 4.4 File Storage
- Local filesystem storage (configurable)
- Organized storage structure:
  - `storage/app/public/posts/` - Post images
  - `storage/app/public/journeys/` - Journey cover images
  - `storage/app/public/avatars/` - User avatars
- Symlink to public directory for web access

## 5. Others

### 5.1 Mobility Support
- **Responsive Design**: Works on desktop, tablet, and mobile devices
- **Touch-Friendly**: Large buttons and touch targets
- **Mobile-Optimized Maps**: Leaflet.js responsive map controls
- **Future-Ready**: Architecture prepared for native mobile app development

### 5.2 Location-Based Features
- **Geolocation API**: Browser-based current location detection
- **Map Integration**: OpenStreetMap tiles via Leaflet
- **Coordinate Storage**: Decimal degrees (lat/lng) with 7 decimal precision
- **Spatial Queries**: Bounds-based filtering for nearby posts
- **Route Visualization**: Polyline connections for journey routes

### 5.3 Security Measures
- **Authentication**: Secure login/register system
- **Authorization**: Resource ownership verification
- **CSRF Tokens**: Protection against cross-site request forgery
- **Input Sanitization**: Validation and sanitization of all user inputs
- **Password Security**: Bcrypt hashing with configurable rounds
- **Session Management**: Secure session handling
- **Soft Deletes**: Prevents accidental data loss

### 5.4 Internationalization
- **Multi-language Support**: English, Simplified Chinese, Traditional Chinese
- **Locale Middleware**: Automatic language detection and switching
- **Translation Files**: Organized PHP arrays for translations
- **URL-based Switching**: Language change via query parameter

### 5.5 Performance Considerations
- **Eager Loading**: Prevents N+1 query problems
- **Pagination**: Efficient data loading
- **Image Optimization**: Proper image storage and serving
- **Caching Ready**: Laravel's caching system available
- **Database Indexing**: Optimized for common queries

## 6. Description of Application Logic and Functionalities

### 6.1 User Registration and Authentication
1. User registers with name, email, and password
2. Password is hashed using bcrypt
3. User account is created in database
4. User can log in with email/password
5. Session is created and maintained
6. Protected routes require authentication

### 6.2 Post Creation Flow
1. User navigates to "Create Post"
2. Fills in post details (title, description, category)
3. Selects location via map click or current location button
4. Uploads images (optional)
5. Adds tags (comma-separated)
6. Submits form
7. Server validates input
8. Images are stored in `storage/app/public/posts/`
9. Post is saved with geocoordinates
10. Tags are created/linked
11. User is redirected to explore page

### 6.3 Journey Creation Flow
1. User navigates to "Create Journey"
2. Fills journey information (title, summary, location, days, visibility)
3. Uploads cover image (optional)
4. Adds journey nodes (minimum 1 required)
5. For each node:
   - Enters name, type, location
   - Optionally links to existing post
   - Adds transportation, accommodation, remarks
6. Submits form
7. Journey and all nodes are saved
8. User is redirected to journey detail page

### 6.4 Nearby Posts Discovery
1. User navigates to "Nearby" page
2. Map initializes (either user location or default view)
3. On map movement, AJAX request sent with bounds
4. Server queries posts within map bounds
5. Posts are returned as JSON
6. Markers are added to map
7. Post cards are displayed in sidebar
8. Clicking card highlights marker and centers map
9. Second click navigates to post detail

### 6.5 Voting System
1. User views post detail page
2. Sees "Known" and "Didn't Know" buttons with counts
3. User clicks button
4. Server checks for existing vote (by user ID or IP)
5. If exists, vote is updated; if not, new vote is created
6. Post's `likes_count` is recalculated
7. Page refreshes with updated counts

### 6.6 Profile Management
1. User clicks "My Profile" or navigates to profile
2. Views own profile with posts and journeys
3. Clicks "Edit Profile"
4. Updates information (name, email, bio, city, avatar, password)
5. Changes are validated and saved
6. Profile page updates with new information

## 7. Project Details

### 7.1 Technology Stack
- **Backend**: PHP 8.2, Laravel 12
- **Frontend**: HTML5, CSS3, JavaScript (ES6+), Bootstrap 5
- **Database**: SQLite (default), MySQL/PostgreSQL compatible
- **Maps**: Leaflet.js 1.9.4, OpenStreetMap
- **Package Management**: Composer (PHP), NPM (JavaScript)

### 7.2 Development Environment
- **Local Development**: PHP built-in server or Laravel Sail
- **Database**: SQLite for easy setup and testing
- **Storage**: Local filesystem
- **Testing**: PHPUnit with Laravel testing tools

### 7.3 File Structure
```
locality-php/
├── app/
│   ├── Http/
│   │   ├── Controllers/        # 7 controllers
│   │   └── Middleware/         # 3 middleware classes
│   └── Models/                 # 9 Eloquent models
├── database/
│   ├── migrations/             # 13 migration files
│   └── seeders/               # 2 seeder classes
├── resources/
│   ├── lang/                   # 3 language files
│   └── views/                  # 20+ Blade templates
└── routes/
    └── web.php                 # 30+ routes
```

### 7.4 Database Schema
- **8 Main Tables**: users, posts, journeys, journey_nodes, tags, votes, bookmarks, follows
- **3 Junction Tables**: post_tag, bookmarks, follows
- **Total Migrations**: 13 migration files
- **Relationships**: Multiple one-to-many and many-to-many relationships

### 7.5 Routes Summary
- **Public Routes**: Explore, Search, Discover, Nearby, Journey/Post viewing
- **Authenticated Routes**: Create/Edit/Delete posts and journeys, Profile management
- **Auth Routes**: Login, Register, Logout

### 7.6 Future Enhancements (Prepared)
- Bookmark system (table exists)
- Follow system (table exists)
- Real-time notifications
- Advanced search filters
- Social sharing
- Native mobile applications
- API endpoints for mobile apps

### 7.7 Testing Strategy
- **Current**: SQLite database for easy testing
- **Test Data**: Seeder with sample posts and journeys
- **Test Environment**: Can use separate `.env.testing` file
- **Future**: Unit tests and feature tests with PHPUnit


## Conclusion

Locality is a well-structured, scalable web application that successfully implements location-based content sharing with a focus on user experience, security, and internationalization. The architecture supports future expansion into mobile applications and additional social features.