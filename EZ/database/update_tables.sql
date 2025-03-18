-- Add account_id column to guest_bookings table
ALTER TABLE guest_bookings ADD COLUMN account_id INT;

-- Add foreign key constraint
ALTER TABLE guest_bookings 
ADD CONSTRAINT fk_guest_bookings_account 
FOREIGN KEY (account_id) REFERENCES accounts(account_id);

-- Update existing records to link with accounts
UPDATE guest_bookings gb
JOIN bookings b ON gb.booking_id = b.booking_id
SET gb.account_id = b.account_id;

-- Make account_id NOT NULL after updating data
ALTER TABLE guest_bookings MODIFY account_id INT NOT NULL; 