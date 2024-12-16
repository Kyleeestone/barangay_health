<?php
include '../common/auth.php';
include '../common/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Prepare the SQL statement to insert a new family member
    $stmt = $conn->prepare("INSERT INTO family_members (household_no, role, full_name, age, gender, birthday) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiss", $_POST['household_no'], $_POST['role'], $_POST['full_name'], $_POST['age'], $_POST['gender'], $_POST['birthday']);

    // Execute the query and check if insertion was successful
    if ($stmt->execute()) {
        // Redirect to the dashboard after successful insertion
        header("Location: dashboard.php");
        exit();
    } else {
        // Handle error if the query fails
        $error = "Error adding family member. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Family Members</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <style>
        /* Sidebar Styles */
        body {
            display: flex;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: #fff;
            position: fixed;
            height: 100%;
            padding-top: 30px;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar h3 {
            text-align: center;
            font-size: 1.8rem;
            color: #ecf0f1;
            margin-bottom: 40px;
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
            padding: 15px;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 15px;
            border-left: 3px solid transparent;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background: #16a085;
            border-left: 3px solid #1abc9c;
        }

        .sidebar .active {
            background: #1abc9c;
            border-left: 3px solid #16a085;
        }

        .content {
            margin-left: 250px;
            padding: 30px;
            flex: 1;
        }

        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        button {
            background-color: #16a085;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 1rem;
        }

        button:hover {
            background-color: #1abc9c;
        }

        .back-link {
            margin-top: 20px;
            display: inline-block;
            font-size: 1rem;
            color: #16a085;
        }

        .back-link:hover {
            color: #1abc9c;
        }

        /* Form Input Styles */
        input,
        select {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h3>BHW Dashboard</h3>
        <a href="dashboard.php"><i class="fa fa-home"></i> Home</a>
        <a href="household_form.php"><i class="fa fa-users"></i> Add Household</a>
        <a href="view_households.php"><i class="fa fa-table"></i> View Households</a>
        <a href="family_form.php" class="active"><i class="fa fa-user-plus"></i> Add Family Members</a>
        <a href="view_family.php"><i class="fa fa-list"></i> View Family Members</a>
        <div class="separator"></div>
        <a href="../common/logout.php" class="text-danger"><i class="fa fa-sign-out"></i> Logout</a>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="form-container">
            <h1>Add Family Members</h1>
            <!-- Display Error message if any -->
            <?php if (isset($error)) {
                echo "<p style='color: red;'>$error</p>";
            } ?>

            <form method="POST">
                <div class="mb-3">
                    <input type="text" class="form-control" name="household_no" placeholder="Household No." required>
                </div>
                <div class="mb-3">
                    <select name="role" class="form-control">
                        <option>Head of the Family</option>
                        <option>Housewife</option>
                        <option>Member</option>
                    </select>
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" name="full_name" placeholder="Full Name" required>
                </div>
                <div class="mb-3">
                    <input type="number" class="form-control" name="age" placeholder="Age" required>
                </div>
                <div class="mb-3">
                    <select name="gender" class="form-control">
                        <option>Male</option>
                        <option>Female</option>
                    </select>
                </div>
                <div class="mb-3">
                    <input type="date" class="form-control" name="birthday" required>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
            <!-- Back link -->
            <a href="dashboard.php" class="back-link">Back to Dashboard</a>
        </div>
    </div>

    <!-- Bootstrap JS and FontAwesome for icons -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>