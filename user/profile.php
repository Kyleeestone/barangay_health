<?php
include '../common/auth.php'; // Ensures user is logged in
include '../common/db.php';

$username = $_SESSION['username'];

// Fetch user details
$stmt = $conn->prepare("SELECT username, role FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Profile</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <h2>Profile</h2>
    <p><strong>Username:</strong> <?php echo $user['username']; ?></p>
    <p><strong>Role:</strong> <?php echo ucfirst($user['role']); ?></p>
    <a href="../logout.php">Logout</a>
</body>

</html>