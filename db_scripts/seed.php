<?php
include '../common/db.php';

// Seed initial users
$admin_password = password_hash('admin123', PASSWORD_BCRYPT);
$bhw_password = password_hash('bhw123', PASSWORD_BCRYPT);

$conn->query("INSERT INTO users (username, password, role) VALUES 
    ('admin', '$admin_password', 'admin'),
    ('bhw', '$bhw_password', 'bhw')
");

echo "Seeded admin and BHW accounts successfully.";
