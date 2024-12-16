<?php
include '../common/auth.php';
include '../common/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("INSERT INTO family_members (household_no, role, full_name, age, gender, birthday) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiss", $_POST['household_no'], $_POST['role'], $_POST['full_name'], $_POST['age'], $_POST['gender'], $_POST['birthday']);
    $stmt->execute();
    header("Location: dashboard.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Family Members</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            display: flex;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
        }

        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: #fff;
            display: flex;
            flex-direction: column;
            padding-top: 30px;
            position: fixed;
            height: 100vh;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar h3 {
            text-align: center;
            font-size: 1.8rem;
            color: #ecf0f1;
            margin-bottom: 30px;
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
            background: #2980b9;
            border-left: 3px solid #1abc9c;
        }

        .sidebar .active {
            background: #2980b9;
            border-left: 3px solid #16a085;
        }

        .content {
            flex: 1;
            margin-left: 250px;
            padding: 30px;
        }

        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #1abc9c;
            border: none;
        }

        .btn-primary:hover {
            background-color: #16a085;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h3>BHW Dashboard</h3>
        <a href="dashboard.php"><i class="fas fa-home"></i> Home</a>
        <a href="household_form.php"><i class="fas fa-users"></i> Add Household</a>
        <a href="view_households.php"><i class="fas fa-table"></i> View Households</a>
        <a href="family_form.php" class="active"><i class="fas fa-user-plus"></i> Add Family Members</a>
        <a href="view_family.php"><i class="fas fa-list"></i> View Family Members</a>
        <div class="separator"></div>
        <a href="../common/logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="content">
        <h1>Add Family Members</h1>
        <div class="form-container">
            <form method="POST" class="row g-3">
                <div class="col-md-6">
                    <label for="household_no" class="form-label">Household No.</label>
                    <input type="text" id="household_no" name="household_no" class="form-control" placeholder="Enter Household No." required>
                </div>
                <div class="col-md-6">
                    <label for="role" class="form-label">Role</label>
                    <select id="role" name="role" class="form-select">
                        <option value="Head of the Family">Head of the Family</option>
                        <option value="Housewife">Housewife</option>
                        <option value="Member">Member</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" id="full_name" name="full_name" class="form-control" placeholder="Enter Full Name" required>
                </div>
                <div class="col-md-6">
                    <label for="age" class="form-label">Age</label>
                    <input type="number" id="age" name="age" class="form-control" placeholder="Enter Age" required>
                </div>
                <div class="col-md-6">
                    <label for="gender" class="form-label">Gender</label>
                    <select id="gender" name="gender" class="form-select">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="birthday" class="form-label">Birthday</label>
                    <input type="date" id="birthday" name="birthday" class="form-control" required>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>