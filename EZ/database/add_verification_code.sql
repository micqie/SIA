ALTER TABLE bookings ADD COLUMN verification_code VARCHAR(12) NOT NULL AFTER booking_reference;
