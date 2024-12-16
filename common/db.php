<?php
$conn = new mysqli("localhost", "root", "", "barangay_health");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
