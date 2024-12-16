<?php
include '../common/auth.php';
include '../common/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the family member data
    $stmt = $conn->prepare("SELECT * FROM family_members WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $member = $result->fetch_assoc();

    if (!$member) {
        die("Family member not found.");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $full_name = $_POST['full_name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $birthday = $_POST['birthday'];
    $role = $_POST['role'];

    // Update the family member data
    $stmt = $conn->prepare("
        UPDATE family_members 
        SET full_name = ?, age = ?, gender = ?, birthday = ?, role = ?
        WHERE id = ?
    ");
    $stmt->bind_param("sssssi", $full_name, $age, $gender, $birthday, $role, $id);
    $stmt->execute();

    header("Location: view_family.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Family Member</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>Edit Family Member</h1>

        <form method="POST" action="edit_family_member.php">
            <input type="hidden" name="id" value="<?php echo $member['id']; ?>" />
            <div class="mb-3">
                <label for="full_name" class="form-label">Full Name</label>
                <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($member['full_name']); ?>" required />
            </div>
            <div class="mb-3">
                <label for="age" class="form-label">Age</label>
                <input type="number" name="age" class="form-control" value="<?php echo htmlspecialchars($member['age']); ?>" required />
            </div>
            <div class="mb-3">
                <label for="gender" class="form-label">Gender</label>
                <input type="text" name="gender" class="form-control" value="<?php echo htmlspecialchars($member['gender']); ?>" required />
            </div>
            <div class="mb-3">
                <label for="birthday" class="form-label">Birthday</label>
                <input type="date" name="birthday" class="form-control" value="<?php echo htmlspecialchars($member['birthday']); ?>" required />
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <input type="text" name="role" class="form-control" value="<?php echo htmlspecialchars($member['role']); ?>" required />
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="view_family.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>