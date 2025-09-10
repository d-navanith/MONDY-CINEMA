# MONDY-CINEMA
Mondy Cinema is a database-driven web app built with HTML, CSS, JavaScript, PHP &amp; MySQL. Users can browse movies, register/login, and book tickets, while admins manage movies &amp; bookings via a secure dashboard. Responsive design ensures a seamless and user-friendly cinema booking experience.
# Demo Video
https://youtu.be/AJgwwlxJbuw?si=zJQNuzfqMvkQTSTO

## Features

### Frontend Features
- **Modern Responsive Design** - Works on all devices
- **Movie Listings** - Now showing and upcoming movies
- **Trailer Integration** - YouTube trailer playback
- **Cinema Locations** - Multiple cinema locations
- **Seat Selection** - Interactive seat booking system
- **User Authentication** - Login/Register system
- **Special Offers** - Promotional deals and discounts

### Backend Features
- **Admin Dashboard** - Complete management system
- **Movie Management** - Add, edit, delete movies
- **Showtime Management** - Schedule movie showtimes
- **Booking Management** - View and manage bookings
- **User Management** - Customer account management
- **Reports** - Revenue and booking analytics

### Technical Features
- **PHP Backend** - Server-side processing
- **MySQL Database** - Reliable data storage
- **AJAX Integration** - Dynamic content loading
- **Responsive Design** - Mobile-first approach
- **Security** - SQL injection prevention, password hashing

## Installation

1. **Database Setup**
   ```sql
   database/schema.sql
   mysql -u root -p < database/schema.sql
   ```
2. **Configuration**
   - Update database credentials in `config/database.php`
   - Ensure PHP and MySQL are running

3. **File Permissions**
   ```bash
   chmod 755 assets/images/movies
   chmod 755 assets/images/cinemas
   chmod 755 assets/images/trailers
   ```

4. **Admin Access**
   - Default admin login: admin@mondycinema.lk
   - Default password: password

## Directory Structure

```
├── admin/                  # Admin panel
│   ├── index.php          # Dashboard
│   ├── movies.php         # Movie management
│   └── includes/          # Admin includes
├── api/                   # API endpoints
│   ├── get-showtimes.php  # Showtime data
│   ├── get-seats.php      # Seat availability
│   └── book-tickets.php   # Booking processing
├── assets/                # Static assets
│   ├── css/              # Stylesheets
│   ├── js/               # JavaScript files
│   └── images/           # Image assets
├── config/               # Configuration files
│   └── database.php      # Database connection
├── database/             # Database files
│   └── schema.sql        # Database schema
├── includes/             # Common includes
│   ├── header.php        # Site header
│   └── footer.php        # Site footer
├── index.php             # Homepage
├── buy-tickets.php       # Booking system
├── login.php             # User login
├── register.php          # User registration
├── cinemas.php           # Cinema locations
└── contact.php           # Contact page
```

## Database Schema

### Core Tables
- **users** - User accounts and authentication
- **movies** - Movie information and metadata
- **cinemas** - Cinema locations and details
- **screens** - Individual cinema screens
- **showtimes** - Movie scheduling
- **bookings** - Ticket reservations
- **seat_bookings** - Individual seat assignments

## Key Features Explained

### Booking System
1. **Movie Selection** - Choose from available movies
2. **Date & Time** - Select showdate and time
3. **Seat Selection** - Interactive seat map
4. **Payment** - Secure payment processing
5. **Confirmation** - Booking reference generation

### Admin Panel
- **Dashboard** - Overview statistics
- **Movie Management** - CRUD operations for movies
- **Showtime Scheduling** - Manage movie showtimes
- **Booking Reports** - Revenue and customer analytics

### Security Features
- **Password Hashing** - Secure password storage
- **SQL Injection Prevention** - Prepared statements
- **Session Management** - Secure user sessions
- **Input Validation** - Server and client-side validation

## API Endpoints

### GET /api/get-showtimes.php
Get available showtimes for a movie and date
```
Parameters: movie_id, date
Response: Cinema locations with available showtimes
```

### GET /api/get-seats.php
Get seat availability for a showtime
```
Parameters: showtime_id
Response: Seat layout with availability status
```

### POST /api/book-tickets.php
Process ticket booking
```
Body: Customer details, selected seats, payment info
Response: Booking confirmation and reference
```

## Customization

### Adding New Movies
1. Login to admin panel
2. Navigate to Movies section
3. Click "Add New Movie"
4. Fill in movie details including poster image and trailer URL
5. Set release date and status

### Managing Cinemas
1. Access admin panel
2. Go to Cinemas section
3. Add new locations with address and contact details
4. Configure screens and seating capacity

### Styling Customization
- Main styles: `assets/css/style.css`
- Booking styles: `assets/css/booking.css`
- Admin styles: `assets/css/admin.css`
- Authentication: `assets/css/auth.css`

## Browser Support
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers

## Performance Features
- **Optimized Images** - Compressed movie posters
- **Lazy Loading** - Progressive content loading
- **Caching** - Browser and server-side caching
- **CDN Integration** - External library loading

## Troubleshooting

### Common Issues
1. **Database Connection Error**
   - Check credentials in `config/database.php`
   - Ensure MySQL service is running

2. **Image Upload Issues**
   - Check file permissions on image directories
   - Verify upload_max_filesize in php.ini

3. **Session Issues**
   - Clear browser cookies
   - Check session configuration in PHP

### Development Mode
Enable error reporting for debugging:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## License
This project is created for educational purposes.

## Support
For technical support or feature requests, please contact the development team.
