<?php
include '../common/auth.php';
include '../common/db.php';

if (isset($_GET['household_no'])) {
    $stmt = $conn->prepare("SELECT * FROM households WHERE household_no = ?");
    $stmt->bind_param("s", $_GET['household_no']);
    $stmt->execute();
    $result = $stmt->get_result();
    $household = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("
        UPDATE households SET 
        name = ?, dob = ?, sex = ?, civil_status = ?, education = ?, religion = ?, ethnicity = ?, is_4ps_member = ?, philhealth_id = ?, medical_history = ?, water_source = ?, toilet_facility = ?, address = ?
        WHERE household_no = ?
    ");
    $stmt->bind_param(
        "ssssssssssssss",
        $_POST['name'],
        $_POST['dob'],
        $_POST['sex'],
        $_POST['civil_status'],
        $_POST['education'],
        $_POST['religion'],
        $_POST['ethnicity'],
        $_POST['is_4ps_member'],
        $_POST['philhealth_id'],
        $_POST['medical_history'],
        $_POST['water_source'],
        $_POST['toilet_facility'],
        $_POST['address'],
        $_POST['household_no']
    );
    $stmt->execute();
    header("Location: view_households.php");
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Household</title>
</head>

<body>
    <h1>Edit Household</h1>
    <form method="POST">
        <input type="hidden" name="household_no" value="<?php echo $household['household_no']; ?>">

        <!-- Name -->
        <input type="text" name="name" value="<?php echo $household['name']; ?>" required><br>

        <!-- Date of Birth -->
        <input type="date" name="dob" value="<?php echo $household['dob']; ?>" required><br>

        <!-- Gender -->
        <select name="sex">
            <option <?php if ($household['sex'] === 'Male') echo 'selected'; ?>>Male</option>
            <option <?php if ($household['sex'] === 'Female') echo 'selected'; ?>>Female</option>
        </select><br>

        <!-- Civil Status -->
        <input type="text" name="civil_status" value="<?php echo $household['civil_status']; ?>"><br>

        <!-- Education -->
        <input type="text" name="education" value="<?php echo $household['education']; ?>"><br>

        <!-- Religion -->
        <input type="text" name="religion" value="<?php echo $household['religion']; ?>"><br>

        <!-- Ethnicity -->
        <input type="text" name="ethnicity" value="<?php echo $household['ethnicity']; ?>"><br>

        <!-- Is 4Ps Member -->
        <select name="is_4ps_member">
            <option value="1" <?php if ($household['is_4ps_member']) echo 'selected'; ?>>Yes</option>
            <option value="0" <?php if (!$household['is_4ps_member']) echo 'selected'; ?>>No</option>
        </select><br>

        <!-- PhilHealth ID -->
        <input type="text" name="philhealth_id" value="<?php echo $household['philhealth_id']; ?>"><br>

        <!-- Medical History -->
        <textarea name="medical_history"><?php echo $household['medical_history']; ?></textarea><br>

        <!-- Water Source -->
        <input type="text" name="water_source" value="<?php echo $household['water_source']; ?>"><br>

        <!-- Toilet Facility -->
        <input type="text" name="toilet_facility" value="<?php echo $household['toilet_facility']; ?>"><br>

        <!-- Address -->
        <input type="text" name="address" value="<?php echo $household['address']; ?>" required><br>

        <button type="submit">Update</button>
    </form>
    <a href="view_households.php">Back</a>
</body>

</html>