-- Create database
CREATE DATABASE hbwebsite2;
USE hbwebsite2;

-- Create admin_cred table
CREATE TABLE admin_cred (
    sr_no INT(11) NOT NULL AUTO_INCREMENT,
    admin_name VARCHAR(150) NOT NULL,
    admin_pass VARCHAR(150) NOT NULL,
    PRIMARY KEY (sr_no)
);

-- Create booking_details table
CREATE TABLE booking_details (
    sr_no INT(11) NOT NULL AUTO_INCREMENT,
    booking_id INT(11) NOT NULL,
    room_name VARCHAR(100) NOT NULL,
    price INT(11) NOT NULL,
    total_pay INT(11) NOT NULL,
    room_no VARCHAR(100),
    user_name VARCHAR(100) NOT NULL,
    phonenum VARCHAR(100) NOT NULL,
    address VARCHAR(150) NOT NULL,
    travel_id INT(11),
    travel_agency VARCHAR(255),
    PRIMARY KEY (sr_no),
    KEY booking_id (booking_id)
);

-- Create booking_order table
CREATE TABLE booking_order (
    booking_id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    room_id INT(11) NOT NULL,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    arrival INT(11) NOT NULL DEFAULT 0,
    refund INT(11),
    booking_status VARCHAR(100) NOT NULL DEFAULT 'pending',
    order_id VARCHAR(150) NOT NULL,
    trans_id VARCHAR(200),
    trans_amt INT(11) NOT NULL,
    trans_status VARCHAR(100) NOT NULL DEFAULT 'pending',
    trans_resp_msg VARCHAR(200),
    rate_review INT(11),
    datentime DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    travel_id INT(11),
    travel_agency VARCHAR(255),
    PRIMARY KEY (booking_id),
    KEY user_id (user_id),
    KEY room_id (room_id)
);

-- Create carousel table
CREATE TABLE carousel (
    sr_no INT(11) NOT NULL AUTO_INCREMENT,
    image VARCHAR(150) NOT NULL,
    PRIMARY KEY (sr_no)
);

-- Create contact_details table
CREATE TABLE contact_details (
    sr_no INT(11) NOT NULL AUTO_INCREMENT,
    address VARCHAR(50) NOT NULL,
    gmap VARCHAR(100) NOT NULL,
    pn1 BIGINT(20) NOT NULL,
    pn2 BIGINT(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    fb VARCHAR(100) NOT NULL,
    insta VARCHAR(100) NOT NULL,
    tw VARCHAR(100) NOT NULL,
    iframe VARCHAR(300) NOT NULL,
    PRIMARY KEY (sr_no)
);

-- Create facilities table
CREATE TABLE facilities (
    id INT(11) NOT NULL AUTO_INCREMENT,
    icon VARCHAR(100) NOT NULL,
    name VARCHAR(50) NOT NULL,
    description VARCHAR(250) NOT NULL,
    PRIMARY KEY (id)
);

-- Create features table
CREATE TABLE features (
    id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    PRIMARY KEY (id)
);

-- Create flights table
CREATE TABLE flights (
    flight_id INT(11) NOT NULL AUTO_INCREMENT,
    airline VARCHAR(100) NOT NULL,
    departure_city VARCHAR(100) NOT NULL,
    arrival_city VARCHAR(100) NOT NULL,
    departure_airport VARCHAR(100),
    arrival_airport VARCHAR(100),
    departure_time DATETIME NOT NULL,
    arrival_time DATETIME NOT NULL,
    duration VARCHAR(50),
    price DECIMAL(10,2) NOT NULL,
    seats_available INT(11) NOT NULL DEFAULT 1,
    status TINYINT(4) NOT NULL DEFAULT 1,
    removed INT(11) NOT NULL DEFAULT 0,
    datentime DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    travel_id INT(11),
    travel_agency VARCHAR(100),
    PRIMARY KEY (flight_id)
);

-- Create flight_booking_details table
CREATE TABLE flight_booking_details (
    id INT(11) NOT NULL AUTO_INCREMENT,
    flight_booking_id INT(11) NOT NULL,
    airline VARCHAR(150) NOT NULL,
    departure_city VARCHAR(150) NOT NULL,
    arrival_city VARCHAR(150) NOT NULL,
    departure_airport VARCHAR(150) NOT NULL,
    arrival_airport VARCHAR(150) NOT NULL,
    departure_time DATETIME NOT NULL,
    arrival_time DATETIME NOT NULL,
    price_per_adult DECIMAL(10,2) NOT NULL,
    price_per_child DECIMAL(10,2),
    total_pay DECIMAL(10,2) NOT NULL,
    user_name VARCHAR(150) NOT NULL,
    phonenum VARCHAR(50) NOT NULL,
    address TEXT NOT NULL,
    travel_id INT(11),
    travel_agency VARCHAR(255),
    PRIMARY KEY (id),
    KEY flight_booking_id (flight_booking_id)
);

-- Create flight_order table
CREATE TABLE flight_order (
    id INT(11) NOT NULL AUTO_INCREMENT,
    order_id VARCHAR(150) NOT NULL,
    user_id INT(11) NOT NULL,
    flight_id INT(11) NOT NULL,
    tickets INT(11) NOT NULL,
    total_pay DECIMAL(10,2) NOT NULL,
    booking_status VARCHAR(50) NOT NULL,
    trans_id VARCHAR(150) NOT NULL,
    trans_amount DECIMAL(10,2) NOT NULL,
    trans_status VARCHAR(50) NOT NULL,
    trans_resp_msg VARCHAR(255) NOT NULL,
    datentime DATETIME NOT NULL,
    travel_id INT(11),
    travel_agency VARCHAR(255),
    PRIMARY KEY (id),
    UNIQUE KEY order_id (order_id)
);

-- Create flight_rating_review table
CREATE TABLE flight_rating_review (
    sr_no INT(11) NOT NULL AUTO_INCREMENT,
    flight_booking_id INT(11) NOT NULL,
    flight_id INT(11) NOT NULL,
    user_id INT(11) NOT NULL,
    rating INT(11) NOT NULL,
    review VARCHAR(200) NOT NULL,
    seen INT(11) NOT NULL DEFAULT 0,
    datentime DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    travel_id INT(11),
    travel_agency VARCHAR(255),
    PRIMARY KEY (sr_no),
    KEY flight_booking_id (flight_booking_id),
    KEY flight_id (flight_id),
    KEY user_id (user_id)
);

-- Create rating_review table
CREATE TABLE rating_review (
    sr_no INT(11) NOT NULL AUTO_INCREMENT,
    booking_id INT(11) NOT NULL,
    room_id INT(11) NOT NULL,
    user_id INT(11) NOT NULL,
    rating INT(11) NOT NULL,
    review VARCHAR(200) NOT NULL,
    seen INT(11) NOT NULL DEFAULT 0,
    datentime DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    travel_id INT(11),
    travel_agency VARCHAR(255),
    PRIMARY KEY (sr_no),
    KEY booking_id (booking_id),
    KEY room_id (room_id),
    KEY user_id (user_id)
);

-- Create rooms table
CREATE TABLE rooms (
    id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(150) NOT NULL,
    location VARCHAR(150),
    address VARCHAR(255),
    area INT(11) NOT NULL,
    price INT(11) NOT NULL,
    quantity INT(11) NOT NULL,
    adult INT(11) NOT NULL,
    children INT(11) NOT NULL,
    description VARCHAR(350) NOT NULL,
    status TINYINT(4) NOT NULL DEFAULT 1,
    removed INT(11) NOT NULL DEFAULT 0,
    travel_id INT(11),
    travel_agency VARCHAR(255),
    PRIMARY KEY (id)
);

-- Create room_facilities table
CREATE TABLE room_facilities (
    sr_no INT(11) NOT NULL AUTO_INCREMENT,
    room_id INT(11) NOT NULL,
    facilities_id INT(11) NOT NULL,
    PRIMARY KEY (sr_no),
    KEY room_id (room_id),
    KEY facilities_id (facilities_id)
);

-- Create room_features table
CREATE TABLE room_features (
    sr_no INT(11) NOT NULL AUTO_INCREMENT,
    room_id INT(11) NOT NULL,
    features_id INT(11) NOT NULL,
    PRIMARY KEY (sr_no),
    KEY room_id (room_id),
    KEY features_id (features_id)
);

-- Create room_images table
CREATE TABLE room_images (
    sr_no INT(11) NOT NULL AUTO_INCREMENT,
    room_id INT(11) NOT NULL,
    image VARCHAR(150) NOT NULL,
    thumb TINYINT(4) NOT NULL DEFAULT 0,
    PRIMARY KEY (sr_no),
    KEY room_id (room_id)
);

-- Create settings table
CREATE TABLE settings (
    sr_no INT(11) NOT NULL AUTO_INCREMENT,
    site_title VARCHAR(50) NOT NULL,
    site_about VARCHAR(250) NOT NULL,
    shutdown TINYINT(1) NOT NULL,
    PRIMARY KEY (sr_no)
);

-- Create team_details table
CREATE TABLE team_details (
    sr_no INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    picture VARCHAR(150) NOT NULL,
    PRIMARY KEY (sr_no)
);

-- Create tour_booking_details table
CREATE TABLE tour_booking_details (
    sr_no INT(11) NOT NULL AUTO_INCREMENT,
    tour_booking_id INT(11) NOT NULL,
    package_name VARCHAR(150) NOT NULL,
    destination VARCHAR(150) NOT NULL,
    duration VARCHAR(50) NOT NULL,
    price_per_adult DECIMAL(10,2) NOT NULL,
    price_per_child DECIMAL(10,2) NOT NULL,
    adults INT(11) NOT NULL,
    children INT(11) NOT NULL,
    tour_date DATE NOT NULL,
    total_pay DECIMAL(10,2) NOT NULL,
    user_name VARCHAR(100) NOT NULL,
    phonenum VARCHAR(20) NOT NULL,
    address VARCHAR(255) NOT NULL,
    travel_id INT(11),
    travel_agency VARCHAR(255),
    PRIMARY KEY (sr_no),
    KEY tour_booking_id (tour_booking_id)
);

-- Create tour_order table
CREATE TABLE tour_order (
    booking_id INT(11) NOT NULL AUTO_INCREMENT,
    order_id VARCHAR(50) NOT NULL,
    user_id INT(11) NOT NULL,
    package_id INT(11) NOT NULL,
    adults INT(11) NOT NULL,
    children INT(11) NOT NULL,
    tour_date DATE NOT NULL,
    total_pay DECIMAL(10,2) NOT NULL,
    booking_status VARCHAR(50) NOT NULL,
    trans_id VARCHAR(100) NOT NULL,
    trans_amount VARCHAR(50) NOT NULL,
    trans_status VARCHAR(50) NOT NULL,
    trans_resp_msg VARCHAR(255) NOT NULL,
    datentime DATETIME NOT NULL,
    travel_id INT(11),
    travel_agency VARCHAR(255),
    PRIMARY KEY (booking_id)
);

-- Create tour_packages table
CREATE TABLE tour_packages (
    package_id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(150) NOT NULL,
    destination VARCHAR(100) NOT NULL,
    duration VARCHAR(50) NOT NULL,
    price_per_adult DECIMAL(10,2) NOT NULL,
    price_per_child DECIMAL(10,2),
    description TEXT NOT NULL,
    inclusion TEXT,
    exclusion TEXT,
    image VARCHAR(150),
    status TINYINT(4) NOT NULL DEFAULT 1,
    removed INT(11) NOT NULL DEFAULT 0,
    datentime DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    travel_id INT(11),
    travel_agency VARCHAR(255),
    PRIMARY KEY (package_id)
);

-- Create tour_package_images table
CREATE TABLE tour_package_images (
    sr_no INT(11) NOT NULL AUTO_INCREMENT,
    package_id INT(11) NOT NULL,
    image VARCHAR(150) NOT NULL,
    thumb TINYINT(4) NOT NULL DEFAULT 0,
    PRIMARY KEY (sr_no),
    KEY package_id (package_id)
);

-- Create tour_rating_review table
CREATE TABLE tour_rating_review (
    sr_no INT(11) NOT NULL AUTO_INCREMENT,
    tour_booking_id INT(11) NOT NULL,
    package_id INT(11) NOT NULL,
    user_id INT(11) NOT NULL,
    rating INT(11) NOT NULL,
    review VARCHAR(200) NOT NULL,
    seen INT(11) NOT NULL DEFAULT 0,
    datentime DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    travel_id INT(11),
    travel_agency VARCHAR(255),
    PRIMARY KEY (sr_no),
    KEY tour_booking_id (tour_booking_id),
    KEY package_id (package_id),
    KEY user_id (user_id)
);

-- Create travel_agency_cred table
CREATE TABLE travel_agency_cred (
    id INT(11) NOT NULL AUTO_INCREMENT,
    agency_name VARCHAR(100) NOT NULL,
    agency_pass VARCHAR(255) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY agency_name (agency_name)
);

-- Create user_cred table
CREATE TABLE user_cred (
    id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    address VARCHAR(120) NOT NULL,
    phonenum VARCHAR(100) NOT NULL,
    pincode INT(11) NOT NULL,
    dob DATE NOT NULL,
    profile VARCHAR(100) NOT NULL,
    password VARCHAR(200) NOT NULL,
    is_verified INT(11) NOT NULL DEFAULT 0,
    token VARCHAR(200),
    t_expire DATE,
    status INT(11) NOT NULL DEFAULT 1,
    datentime DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (id)
);

-- Create user_queries table
CREATE TABLE user_queries (
    sr_no INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(150) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message VARCHAR(500) NOT NULL,
    datentime DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    seen TINYINT(4) NOT NULL DEFAULT 0,
    PRIMARY KEY (sr_no)
);

-- Add foreign key constraints
ALTER TABLE booking_details
    ADD CONSTRAINT fk_booking_details_booking_order FOREIGN KEY (booking_id) REFERENCES booking_order (booking_id);

ALTER TABLE booking_order
    ADD CONSTRAINT fk_booking_order_user_cred FOREIGN KEY (user_id) REFERENCES user_cred (id),
    ADD CONSTRAINT fk_booking_order_rooms FOREIGN KEY (room_id) REFERENCES rooms (id);

ALTER TABLE room_facilities
    ADD CONSTRAINT fk_room_facilities_rooms FOREIGN KEY (room_id) REFERENCES rooms (id),
    ADD CONSTRAINT fk_room_facilities_facilities FOREIGN KEY (facilities_id) REFERENCES facilities (id);

ALTER TABLE room_features
    ADD CONSTRAINT fk_room_features_rooms FOREIGN KEY (room_id) REFERENCES rooms (id),
    ADD CONSTRAINT fk_room_features_features FOREIGN KEY (features_id) REFERENCES features (id);

ALTER TABLE room_images
    ADD CONSTRAINT fk_room_images_rooms FOREIGN KEY (room_id) REFERENCES rooms (id);

ALTER TABLE rating_review
    ADD CONSTRAINT fk_rating_review_booking_order FOREIGN KEY (booking_id) REFERENCES booking_order (booking_id),
    ADD CONSTRAINT fk_rating_review_rooms FOREIGN KEY (room_id) REFERENCES rooms (id),
    ADD CONSTRAINT fk_rating_review_user_cred FOREIGN KEY (user_id) REFERENCES user_cred (id);

ALTER TABLE flight_booking_details
    ADD CONSTRAINT fk_flight_booking_details_flight_order FOREIGN KEY (flight_booking_id) REFERENCES flight_order (id);

ALTER TABLE flight_rating_review
    ADD CONSTRAINT fk_flight_rating_review_flight_order FOREIGN KEY (flight_booking_id) REFERENCES flight_order (id),
    ADD CONSTRAINT fk_flight_rating_review_flights FOREIGN KEY (flight_id) REFERENCES flights (flight_id),
    ADD CONSTRAINT fk_flight_rating_review_user_cred FOREIGN KEY (user_id) REFERENCES user_cred (id);

ALTER TABLE tour_booking_details
    ADD CONSTRAINT fk_tour_booking_details_tour_order FOREIGN KEY (tour_booking_id) REFERENCES tour_order (booking_id);

ALTER TABLE tour_package_images
    ADD CONSTRAINT fk_tour_package_images_tour_packages FOREIGN KEY (package_id) REFERENCES tour_packages (package_id);

ALTER TABLE tour_rating_review
    ADD CONSTRAINT fk_tour_rating_review_tour_order FOREIGN KEY (tour_booking_id) REFERENCES tour_order (booking_id),
    ADD CONSTRAINT fk_tour_rating_review_tour_packages FOREIGN KEY (package_id) REFERENCES tour_packages (package_id),
    ADD CONSTRAINT fk_tour_rating_review_user_cred FOREIGN KEY (user_id) REFERENCES user_cred (id);

-- Use the database

-- Insert data into admin_cred
INSERT INTO admin_cred (admin_name, admin_pass) VALUES
('admin', 'admin123'),
('superadmin', 'super123'),
('manager', 'manager123');

-- Insert data into facilities
INSERT INTO facilities (icon, name, description) VALUES
('wifi', 'Wi-Fi', 'High speed internet access'),
('air-conditioner', 'AC', 'Temperature control'),
('tv', 'TV', '32-inch flat screen TV'),
('room-heater', 'Room Heater', 'For cold weather'),
('geyser', 'Geyser', 'Hot water 24/7'),
('spa', 'Spa', 'Relaxation and massage'),
('swimming-pool', 'Swimming Pool', 'Outdoor swimming facility');

-- Insert data into features
INSERT INTO features (name) VALUES
('King Size Bed'),
('Ocean View'),
('Balcony'),
('Mountain View'),
('Mini Bar'),
('Bathtub'),
('Private Pool');

-- Insert data into settings
INSERT INTO settings (site_title, site_about, shutdown) VALUES
('Travel & Stay', 'Your one-stop destination for all travel needs. Book hotels, flights, and tour packages with ease.', 0);

-- Insert data into contact_details
INSERT INTO contact_details (address, gmap, pn1, pn2, email, fb, insta, tw, iframe) VALUES
('123 Travel Street, Tourism City', 'https://maps.example.com/location', 9876543210, 8765432109, 'contact@travel.com', 'facebook.com/travelstay', 'instagram.com/travelstay', 'twitter.com/travelstay', '<iframe src="https://maps.example.com/embed" width="600" height="450" frameborder="0"></iframe>');

-- Insert data into carousel
INSERT INTO carousel (image) VALUES
('carousel1.jpg'),
('carousel2.jpg'),
('carousel3.jpg'),
('carousel4.jpg');

-- Insert data into team_details
INSERT INTO team_details (name, picture) VALUES
('John Smith', 'john.jpg'),
('Emily Johnson', 'emily.jpg'),
('Michael Brown', 'michael.jpg'),
('Sarah Wilson', 'sarah.jpg');

-- Insert data into travel_agency_cred
INSERT INTO travel_agency_cred (agency_name, agency_pass) VALUES
('Explore Adventures', 'explore123'),
('Dream Vacations', 'dream123'),
('Global Trips', 'global123');

-- Insert data into user_cred
INSERT INTO user_cred (name, email, address, phonenum, pincode, dob, profile, password, is_verified) VALUES
('Alice Cooper', 'alice@example.com', '45 Maple Street, New York', '1234567890', 10001, '1985-05-15', 'alice_profile.jpg', 'password123', 1),
('Bob Smith', 'bob@example.com', '78 Pine Avenue, Los Angeles', '2345678901', 90001, '1990-10-20', 'bob_profile.jpg', 'password456', 1),
('Carol Johnson', 'carol@example.com', '123 Oak Lane, Chicago', '3456789012', 60007, '1988-03-25', 'carol_profile.jpg', 'password789', 1),
('David Brown', 'david@example.com', '56 Cedar Road, Miami', '4567890123', 33101, '1992-12-10', 'david_profile.jpg', 'passwordabc', 1),
('Emma Wilson', 'emma@example.com', '89 Birch Street, Seattle', '5678901234', 98101, '1995-07-30', 'emma_profile.jpg', 'passworddef', 1);

-- Insert data into rooms
INSERT INTO rooms (name, location, address, area, price, quantity, adult, children, description, travel_id, travel_agency) VALUES
('Deluxe Room', 'New York', '123 Hotel Plaza, New York', 300, 200, 10, 2, 1, 'Spacious deluxe room with city view', 1, 'Explore Adventures'),
('Premium Suite', 'Los Angeles', '456 Resort Drive, LA', 500, 350, 5, 2, 2, 'Luxury suite with all amenities', 1, 'Explore Adventures'),
('Family Room', 'Chicago', '789 Stay Street, Chicago', 450, 300, 8, 4, 2, 'Perfect room for family stays', 2, 'Dream Vacations'),
('Executive Suite', 'Miami', '101 Beach Road, Miami', 600, 400, 3, 2, 0, 'Executive suite with ocean view', 2, 'Dream Vacations'),
('Standard Room', 'Seattle', '202 Mountain View, Seattle', 250, 150, 15, 2, 1, 'Comfortable standard room', 3, 'Global Trips'),
('Honeymoon Suite', 'Hawaii', '303 Paradise Lane, Hawaii', 550, 450, 2, 2, 0, 'Romantic suite for couples', 3, 'Global Trips');

-- Insert data into room_facilities
INSERT INTO room_facilities (room_id, facilities_id) VALUES
(1, 1), (1, 2), (1, 3),
(2, 1), (2, 2), (2, 3), (2, 5), (2, 6),
(3, 1), (3, 2), (3, 3), (3, 4),
(4, 1), (4, 2), (4, 3), (4, 5), (4, 6), (4, 7),
(5, 1), (5, 2), (5, 3),
(6, 1), (6, 2), (6, 3), (6, 5), (6, 6), (6, 7);

-- Insert data into room_features
INSERT INTO room_features (room_id, features_id) VALUES
(1, 1), (1, 5),
(2, 1), (2, 2), (2, 3), (2, 5),
(3, 1), (3, 4),
(4, 1), (4, 2), (4, 3), (4, 5), (4, 6),
(5, 1),
(6, 1), (6, 2), (6, 3), (6, 5), (6, 6), (6, 7);

-- Insert data into room_images
INSERT INTO room_images (room_id, image, thumb) VALUES
(1, 'deluxe1.jpg', 1),
(1, 'deluxe2.jpg', 0),
(1, 'deluxe3.jpg', 0),
(2, 'premium1.jpg', 1),
(2, 'premium2.jpg', 0),
(3, 'family1.jpg', 1),
(3, 'family2.jpg', 0),
(3, 'family3.jpg', 0),
(4, 'executive1.jpg', 1),
(4, 'executive2.jpg', 0),
(5, 'standard1.jpg', 1),
(6, 'honeymoon1.jpg', 1),
(6, 'honeymoon2.jpg', 0);

-- Insert data into booking_order
INSERT INTO booking_order (user_id, room_id, check_in, check_out, arrival, booking_status, order_id, trans_id, trans_amt, trans_status, travel_id, travel_agency) VALUES
(1, 2, '2025-05-01', '2025-05-05', 0, 'confirmed', 'ORD-100001', 'TXN-100001', 1400, 'success', 1, 'Explore Adventures'),
(2, 4, '2025-05-10', '2025-05-15', 0, 'confirmed', 'ORD-100002', 'TXN-100002', 2000, 'success', 2, 'Dream Vacations'),
(3, 1, '2025-06-01', '2025-06-03', 0, 'confirmed', 'ORD-100003', 'TXN-100003', 400, 'success', 1, 'Explore Adventures'),
(4, 6, '2025-06-15', '2025-06-20', 0, 'confirmed', 'ORD-100004', 'TXN-100004', 2250, 'success', 3, 'Global Trips'),
(5, 3, '2025-07-01', '2025-07-07', 0, 'confirmed', 'ORD-100005', 'TXN-100005', 1800, 'success', 2, 'Dream Vacations');

-- Insert data into booking_details
INSERT INTO booking_details (booking_id, room_name, price, total_pay, room_no, user_name, phonenum, address, travel_id, travel_agency) VALUES
(1, 'Premium Suite', 350, 1400, '201', 'Alice Cooper', '1234567890', '45 Maple Street, New York', 1, 'Explore Adventures'),
(2, 'Executive Suite', 400, 2000, '401', 'Bob Smith', '2345678901', '78 Pine Avenue, Los Angeles', 2, 'Dream Vacations'),
(3, 'Deluxe Room', 200, 400, '101', 'Carol Johnson', '3456789012', '123 Oak Lane, Chicago', 1, 'Explore Adventures'),
(4, 'Honeymoon Suite', 450, 2250, '601', 'David Brown', '4567890123', '56 Cedar Road, Miami', 3, 'Global Trips'),
(5, 'Family Room', 300, 1800, '301', 'Emma Wilson', '5678901234', '89 Birch Street, Seattle', 2, 'Dream Vacations');

-- Insert data into rating_review
INSERT INTO rating_review (booking_id, room_id, user_id, rating, review, travel_id, travel_agency) VALUES
(1, 2, 1, 5, 'Amazing stay! The suite was beautiful and staff were very friendly.', 1, 'Explore Adventures'),
(2, 4, 2, 4, 'Great view from the room. Service was excellent.', 2, 'Dream Vacations'),
(3, 1, 3, 3, 'Room was clean but a bit small. Overall good experience.', 1, 'Explore Adventures'),
(4, 6, 4, 5, 'Perfect honeymoon experience! Highly recommended.', 3, 'Global Trips'),
(5, 3, 5, 4, 'Kids loved the room. Will come back again.', 2, 'Dream Vacations');

-- Insert data into flights
INSERT INTO flights (airline, departure_city, arrival_city, departure_airport, arrival_airport, departure_time, arrival_time, duration, price, seats_available, travel_id, travel_agency) VALUES
('Delta Airlines', 'New York', 'Los Angeles', 'JFK International', 'LAX', '2025-05-10 08:00:00', '2025-05-10 11:30:00', '3h 30m', 350.00, 50, 1, 'Explore Adventures'),
('American Airlines', 'Chicago', 'Miami', 'O\'Hare International', 'Miami International', '2025-05-15 10:30:00', '2025-05-15 14:45:00', '4h 15m', 400.00, 35, 2, 'Dream Vacations'),
('United Airlines', 'Seattle', 'New York', 'Seattle-Tacoma', 'JFK International', '2025-05-20 06:15:00', '2025-05-20 14:45:00', '8h 30m', 500.00, 25, 3, 'Global Trips'),
('Southwest', 'Los Angeles', 'Chicago', 'LAX', 'O\'Hare International', '2025-06-01 07:00:00', '2025-06-01 13:00:00', '6h 00m', 380.00, 40, 1, 'Explore Adventures'),
('JetBlue', 'Miami', 'Seattle', 'Miami International', 'Seattle-Tacoma', '2025-06-10 09:30:00', '2025-06-10 18:00:00', '8h 30m', 520.00, 30, 2, 'Dream Vacations');

-- Insert data into flight_order
INSERT INTO flight_order (order_id, user_id, flight_id, tickets, total_pay, booking_status, trans_id, trans_amount, trans_status, trans_resp_msg, datentime, travel_id, travel_agency) VALUES
('FLT-100001', 1, 2, 2, 800.00, 'confirmed', 'TXN-F10001', 800.00, 'success', 'Payment successful', '2025-04-20 15:30:00', 2, 'Dream Vacations'),
('FLT-100002', 3, 1, 1, 350.00, 'confirmed', 'TXN-F10002', 350.00, 'success', 'Payment successful', '2025-04-21 10:15:00', 1, 'Explore Adventures'),
('FLT-100003', 5, 3, 3, 1500.00, 'confirmed', 'TXN-F10003', 1500.00, 'success', 'Payment successful', '2025-04-22 09:45:00', 3, 'Global Trips'),
('FLT-100004', 2, 5, 2, 1040.00, 'confirmed', 'TXN-F10004', 1040.00, 'success', 'Payment successful', '2025-04-23 14:20:00', 2, 'Dream Vacations'),
('FLT-100005', 4, 4, 1, 380.00, 'confirmed', 'TXN-F10005', 380.00, 'success', 'Payment successful', '2025-04-24 11:10:00', 1, 'Explore Adventures');

-- Insert data into flight_booking_details
INSERT INTO flight_booking_details (flight_booking_id, airline, departure_city, arrival_city, departure_airport, arrival_airport, departure_time, arrival_time, price_per_adult, price_per_child, total_pay, user_name, phonenum, address, travel_id, travel_agency) VALUES
(1, 'American Airlines', 'Chicago', 'Miami', 'O\'Hare International', 'Miami International', '2025-05-15 10:30:00', '2025-05-15 14:45:00', 400.00, NULL, 800.00, 'Alice Cooper', '1234567890', '45 Maple Street, New York', 2, 'Dream Vacations'),
(2, 'Delta Airlines', 'New York', 'Los Angeles', 'JFK International', 'LAX', '2025-05-10 08:00:00', '2025-05-10 11:30:00', 350.00, NULL, 350.00, 'Carol Johnson', '3456789012', '123 Oak Lane, Chicago', 1, 'Explore Adventures'),
(3, 'United Airlines', 'Seattle', 'New York', 'Seattle-Tacoma', 'JFK International', '2025-05-20 06:15:00', '2025-05-20 14:45:00', 500.00, NULL, 1500.00, 'Emma Wilson', '5678901234', '89 Birch Street, Seattle', 3, 'Global Trips'),
(4, 'JetBlue', 'Miami', 'Seattle', 'Miami International', 'Seattle-Tacoma', '2025-06-10 09:30:00', '2025-06-10 18:00:00', 520.00, NULL, 1040.00, 'Bob Smith', '2345678901', '78 Pine Avenue, Los Angeles', 2, 'Dream Vacations'),
(5, 'Southwest', 'Los Angeles', 'Chicago', 'LAX', 'O\'Hare International', '2025-06-01 07:00:00', '2025-06-01 13:00:00', 380.00, NULL, 380.00, 'David Brown', '4567890123', '56 Cedar Road, Miami', 1, 'Explore Adventures');

-- Insert data into flight_rating_review
INSERT INTO flight_rating_review (flight_booking_id, flight_id, user_id, rating, review, travel_id, travel_agency) VALUES
(1, 2, 1, 4, 'Flight was on time and service was good.', 2, 'Dream Vacations'),
(2, 1, 3, 5, 'Excellent service and comfortable seats!', 1, 'Explore Adventures'),
(3, 3, 5, 3, 'Flight was delayed but crew was friendly.', 3, 'Global Trips'),
(4, 5, 2, 4, 'Good experience overall. Would fly again.', 2, 'Dream Vacations'),
(5, 4, 4, 5, 'On time departure and arrival. Very satisfied.', 1, 'Explore Adventures');

-- Insert data into tour_packages
INSERT INTO tour_packages (name, destination, duration, price_per_adult, price_per_child, description, inclusion, exclusion, image, travel_id, travel_agency) VALUES
('Paris Explorer', 'Paris, France', '5 days', 1200.00, 800.00, 'Explore the beautiful city of Paris including the Eiffel Tower, Louvre Museum, and more.', 'Hotel stay, Breakfast, City tour, Museum entries', 'Flights, Lunch, Dinner, Personal expenses', 'paris.jpg', 1, 'Explore Adventures'),
('Swiss Alps Adventure', 'Switzerland', '7 days', 1800.00, 1200.00, 'Experience the breathtaking Swiss Alps with skiing, hiking, and scenic train rides.', 'Hotel stay, Breakfast, Ski equipment, Train passes', 'Flights, Lunch, Dinner, Personal expenses', 'swiss.jpg', 1, 'Explore Adventures'),
('Tokyo Discovery', 'Tokyo, Japan', '6 days', 1500.00, 1000.00, 'Discover the vibrant city of Tokyo with its blend of traditional and modern attractions.', 'Hotel stay, Breakfast, City tour, Temple entries', 'Flights, Lunch, Dinner, Personal expenses', 'tokyo.jpg', 2, 'Dream Vacations'),
('Bali Relaxation', 'Bali, Indonesia', '8 days', 1300.00, 900.00, 'Relax in the beautiful beaches and cultural spots of Bali.', 'Resort stay, Breakfast, Beach access, Cultural show', 'Flights, Lunch, Dinner, Personal expenses', 'bali.jpg', 2, 'Dream Vacations'),
('Egypt Historical Tour', 'Cairo, Egypt', '7 days', 1600.00, 1100.00, 'Explore the ancient wonders of Egypt including the Pyramids and the Nile.', 'Hotel stay, Breakfast, Guided tours, Entry fees', 'Flights, Lunch, Dinner, Personal expenses', 'egypt.jpg', 3, 'Global Trips');

-- Insert data into tour_package_images
INSERT INTO tour_package_images (package_id, image, thumb) VALUES
(1, 'paris1.jpg', 1),
(1, 'paris2.jpg', 0),
(1, 'paris3.jpg', 0),
(2, 'swiss1.jpg', 1),
(2, 'swiss2.jpg', 0),
(3, 'tokyo1.jpg', 1),
(3, 'tokyo2.jpg', 0),
(3, 'tokyo3.jpg', 0),
(4, 'bali1.jpg', 1),
(4, 'bali2.jpg', 0),
(5, 'egypt1.jpg', 1),
(5, 'egypt2.jpg', 0),
(5, 'egypt3.jpg', 0);

-- Insert data into tour_order
INSERT INTO tour_order (order_id, user_id, package_id, adults, children, tour_date, total_pay, booking_status, trans_id, trans_amount, trans_status, trans_resp_msg, datentime, travel_id, travel_agency) VALUES
('TOUR-100001', 2, 1, 2, 1, '2025-06-10', 3200.00, 'confirmed', 'TXN-T10001', '3200.00', 'success', 'Payment successful', '2025-04-20 12:30:00', 1, 'Explore Adventures'),
('TOUR-100002', 4, 3, 2, 0, '2025-06-15', 3000.00, 'confirmed', 'TXN-T10002', '3000.00', 'success', 'Payment successful', '2025-04-21 14:45:00', 2, 'Dream Vacations'),
('TOUR-100003', 1, 5, 2, 2, '2025-07-01', 5400.00, 'confirmed', 'TXN-T10003', '5400.00', 'success', 'Payment successful', '2025-04-22 10:15:00', 3, 'Global Trips'),
('TOUR-100004', 3, 2, 1, 0, '2025-07-10', 1800.00, 'confirmed', 'TXN-T10004', '1800.00', 'success', 'Payment successful', '2025-04-23 09:30:00', 1, 'Explore Adventures'),
('TOUR-100005', 5, 4, 2, 1, '2025-08-01', 3500.00, 'confirmed', 'TXN-T10005', '3500.00', 'success', 'Payment successful', '2025-04-24 11:45:00', 2, 'Dream Vacations');

-- Insert data into tour_booking_details
INSERT INTO tour_booking_details (tour_booking_id, package_name, destination, duration, price_per_adult, price_per_child, adults, children, tour_date, total_pay, user_name, phonenum, address, travel_id, travel_agency) VALUES
(1, 'Paris Explorer', 'Paris, France', '5 days', 1200.00, 800.00, 2, 1, '2025-06-10', 3200.00, 'Bob Smith', '2345678901', '78 Pine Avenue, Los Angeles', 1, 'Explore Adventures'),
(2, 'Tokyo Discovery', 'Tokyo, Japan', '6 days', 1500.00, 1000.00, 2, 0, '2025-06-15', 3000.00, 'David Brown', '4567890123', '56 Cedar Road, Miami', 2, 'Dream Vacations'),
(3, 'Egypt Historical Tour', 'Cairo, Egypt', '7 days', 1600.00, 1100.00, 2, 2, '2025-07-01', 5400.00, 'Alice Cooper', '1234567890', '45 Maple Street, New York', 3, 'Global Trips'),
(4, 'Swiss Alps Adventure', 'Switzerland', '7 days', 1800.00, 1200.00, 1, 0, '2025-07-10', 1800.00, 'Carol Johnson', '3456789012', '123 Oak Lane, Chicago', 1, 'Explore Adventures'),
(5, 'Bali Relaxation', 'Bali, Indonesia', '8 days', 1300.00, 900.00, 2, 1, '2025-08-01', 3500.00, 'Emma Wilson', '5678901234', '89 Birch Street, Seattle', 2, 'Dream Vacations');

-- Insert data into tour_rating_review
INSERT INTO tour_rating_review (tour_booking_id, package_id, user_id, rating, review, travel_id, travel_agency) VALUES
(1, 1, 2, 5, 'Paris was beautiful! The tour guide was very knowledgeable.', 1, 'Explore Adventures'),
(2, 3, 4, 4, 'Great experience in Tokyo. Would recommend this package.', 2, 'Dream Vacations'),
(3, 5, 1, 5, 'Amazing historical tour! The pyramids were breathtaking.', 3, 'Global Trips'),
(4, 2, 3, 4, 'The Swiss Alps were gorgeous. Skiing was fantastic.', 1, 'Explore Adventures'),
(5, 4, 5, 5, 'Bali was so relaxing! Perfect vacation spot.', 2, 'Dream Vacations');

-- Insert data into user_queries
INSERT INTO user_queries (name, email, subject, message) VALUES
('John Doe', 'john@example.com', 'Room Availability', 'I would like to know if there are any deluxe rooms available for next weekend.'),
('Jane Smith', 'jane@example.com', 'Tour Package Inquiry', 'Can you provide more details about the Swiss Alps Adventure package?'),
('Mark Johnson', 'mark@example.com', 'Flight Cancellation', 'I need to cancel my flight booking due to an emergency.'),
('Lisa Brown', 'lisa@example.com', 'Special Requirements', 'I have dietary restrictions and would like to know if you can accommodate them.'),
('Michael Wilson', 'michael@example.com', 'Payment Issue', 'I was charged twice for my booking. Please help resolve this issue.');    