-- Mondy Cinema Database Schema
-- Create database
CREATE DATABASE IF NOT EXISTS mondy_cinema;
USE mondy_cinema;

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('admin', 'customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Cinemas table
CREATE TABLE cinemas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(50) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(100),
    description TEXT,
    image VARCHAR(255),
    total_screens INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Movies table
CREATE TABLE movies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    genre VARCHAR(100),
    duration INT NOT NULL, -- in minutes
    rating VARCHAR(10), -- PG, PG-13, R, etc.
    director VARCHAR(100),
    cast TEXT,
    release_date DATE,
    poster_image VARCHAR(255),
    trailer_url VARCHAR(500),
    status ENUM('active', 'inactive', 'coming_soon') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Screens table
CREATE TABLE screens (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cinema_id INT NOT NULL,
    screen_number INT NOT NULL,
    screen_name VARCHAR(50),
    total_seats INT DEFAULT 100,
    seat_layout JSON, -- Store seat configuration
    FOREIGN KEY (cinema_id) REFERENCES cinemas(id) ON DELETE CASCADE
);

-- Showtimes table
CREATE TABLE showtimes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    movie_id INT NOT NULL,
    screen_id INT NOT NULL,
    show_date DATE NOT NULL,
    show_time TIME NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    available_seats INT DEFAULT 100,
    status ENUM('active', 'cancelled', 'full') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
    FOREIGN KEY (screen_id) REFERENCES screens(id) ON DELETE CASCADE
);

-- Bookings table
CREATE TABLE bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    showtime_id INT NOT NULL,
    booking_reference VARCHAR(50) UNIQUE NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    seats_booked JSON NOT NULL, -- Store array of seat numbers
    total_seats INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    booking_status ENUM('confirmed', 'cancelled', 'pending') DEFAULT 'confirmed',
    payment_status ENUM('paid', 'pending', 'failed') DEFAULT 'pending',
    payment_method VARCHAR(50),
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (showtime_id) REFERENCES showtimes(id) ON DELETE CASCADE
);

-- Seat bookings table (for individual seat tracking)
CREATE TABLE seat_bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id INT NOT NULL,
    showtime_id INT NOT NULL,
    seat_number VARCHAR(10) NOT NULL,
    seat_row VARCHAR(5) NOT NULL,
    seat_column INT NOT NULL,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (showtime_id) REFERENCES showtimes(id) ON DELETE CASCADE,
    UNIQUE KEY unique_seat_showtime (showtime_id, seat_number)
);

-- Insert sample data
INSERT INTO users (username, email, password, role) VALUES 
('Admin User', 'admin@mondycinema.lk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer');

INSERT INTO cinemas (name, address, city, phone, email, description, image, total_screens) VALUES
('Mondy Cinema Colombo', '123 Galle Road, Colombo 03', 'Colombo', '+94 11 234 5678', 'colombo@mondycinema.lk', 'Premium cinema experience in the heart of Colombo with state-of-the-art technology and comfortable seating.', 'colombo-cinema.jpg', 4),
('Mondy Cinema Kandy', '456 Peradeniya Road, Kandy', 'Kandy', '+94 81 234 5678', 'kandy@mondycinema.lk', 'Modern cinema complex in Kandy featuring the latest movies with superior sound and visual quality.', 'kandy-cinema.jpg', 3),
('Mondy Cinema Galle', '789 Matara Road, Galle', 'Galle', '+94 91 234 5678', 'galle@mondycinema.lk', 'Coastal cinema experience with premium amenities and comfortable seating for the whole family.', 'galle-cinema.jpg', 2);

INSERT INTO movies (title, description, genre, duration, rating, director, cast, release_date, poster_image, trailer_url, status) VALUES
('Spider-Man: No Way Home', 'Peter Parker seeks help from Doctor Strange when his identity as Spider-Man is revealed, but things go wrong when villains from other dimensions arrive.', 'Action/Adventure', 148, 'PG-13', 'Jon Watts', 'Tom Holland, Zendaya, Benedict Cumberbatch', '2024-01-15', 'spiderman.jpg', 'https://www.youtube.com/watch?v=JfVOs4VSpmA', 'active'),
('Avengers: Endgame', 'The Avengers assemble once more to reverse the damage caused by Thanos in Infinity War.', 'Action/Sci-Fi', 181, 'PG-13', 'Russo Brothers', 'Robert Downey Jr., Chris Evans, Mark Ruffalo', '2024-02-01', 'avengers.jpg', 'https://www.youtube.com/watch?v=TcMBFSGVi1c', 'active'),
('The Batman', 'Batman ventures into Gotham City\'s underworld when a sadistic killer leaves behind a trail of cryptic clues.', 'Action/Crime', 176, 'PG-13', 'Matt Reeves', 'Robert Pattinson, Zoë Kravitz, Jeffrey Wright', '2024-02-15', 'batman.jpg', 'https://www.youtube.com/watch?v=mqqft2x_Aa4', 'active'),
('Top Gun: Maverick', 'After thirty years, Maverick is still pushing the envelope as a top naval aviator, training a new generation of pilots.', 'Action/Drama', 130, 'PG-13', 'Joseph Kosinski', 'Tom Cruise, Miles Teller, Jennifer Connelly', '2024-03-01', 'topgun.jpg', 'https://www.youtube.com/watch?v=qSqVVswa420', 'coming_soon'),
('Black Panther: Wakanda Forever', 'The people of Wakanda fight to protect their home from intervening world powers after the death of King T\'Challa.', 'Action/Drama', 161, 'PG-13', 'Ryan Coogler', 'Letitia Wright, Angela Bassett, Tenoch Huerta', '2024-03-15', 'blackpanther.jpg', 'https://www.youtube.com/watch?v=_Z3QKkl1WyM', 'coming_soon'),
('Dune: Part Two', 'Paul Atreides unites with Chani and the Fremen while seeking revenge against the conspirators who destroyed his family.', 'Sci-Fi/Adventure', 166, 'PG-13', 'Denis Villeneuve', 'Timothée Chalamet, Zendaya, Rebecca Ferguson', '2024-04-01', 'dune.jpg', 'https://www.youtube.com/watch?v=Way9Dexny3w', 'coming_soon');

-- Insert screens for each cinema
INSERT INTO screens (cinema_id, screen_number, screen_name, total_seats) VALUES
-- Colombo Cinema (4 screens)
(1, 1, 'Screen 1 - Premium', 120),
(1, 2, 'Screen 2 - Standard', 100),
(1, 3, 'Screen 3 - Standard', 100),
(1, 4, 'Screen 4 - IMAX', 150),
-- Kandy Cinema (3 screens)
(2, 1, 'Screen 1 - Premium', 100),
(2, 2, 'Screen 2 - Standard', 80),
(2, 3, 'Screen 3 - Standard', 80),
-- Galle Cinema (2 screens)
(3, 1, 'Screen 1 - Premium', 90),
(3, 2, 'Screen 2 - Standard', 70);

-- Insert sample showtimes for next 7 days
INSERT INTO showtimes (movie_id, screen_id, show_date, show_time, price, available_seats) VALUES
-- Today's shows
(1, 1, CURDATE(), '10:00:00', 1500.00, 120),
(1, 1, CURDATE(), '13:30:00', 1500.00, 120),
(1, 1, CURDATE(), '17:00:00', 1800.00, 120),
(1, 1, CURDATE(), '20:30:00', 1800.00, 120),
(2, 2, CURDATE(), '11:00:00', 1400.00, 100),
(2, 2, CURDATE(), '15:00:00', 1400.00, 100),
(2, 2, CURDATE(), '19:00:00', 1600.00, 100),
(3, 3, CURDATE(), '12:00:00', 1400.00, 100),
(3, 3, CURDATE(), '16:00:00', 1400.00, 100),
(3, 3, CURDATE(), '20:00:00', 1600.00, 100),

-- Tomorrow's shows
(1, 1, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '10:00:00', 1500.00, 120),
(1, 1, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '13:30:00', 1500.00, 120),
(1, 1, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '17:00:00', 1800.00, 120),
(1, 1, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '20:30:00', 1800.00, 120),
(2, 2, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '11:00:00', 1400.00, 100),
(2, 2, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '15:00:00', 1400.00, 100),
(2, 2, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '19:00:00', 1600.00, 100),
(3, 3, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '12:00:00', 1400.00, 100),
(3, 3, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '16:00:00', 1400.00, 100),
(3, 3, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '20:00:00', 1600.00, 100);

-- Create indexes for better performance
CREATE INDEX idx_showtimes_date ON showtimes(show_date);
CREATE INDEX idx_showtimes_movie ON showtimes(movie_id);
CREATE INDEX idx_bookings_user ON bookings(user_id);
CREATE INDEX idx_bookings_showtime ON bookings(showtime_id);
CREATE INDEX idx_seat_bookings_showtime ON seat_bookings(showtime_id);