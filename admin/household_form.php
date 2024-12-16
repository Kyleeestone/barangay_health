<?php
include '../common/auth.php';
include '../common/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("INSERT INTO households (household_no, name, dob, sex, civil_status, education, religion, ethnicity, is_4ps_member, philhealth_id, medical_history, water_source, toilet_facility, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssssss", $_POST['household_no'], $_POST['name'], $_POST['dob'], $_POST['sex'], $_POST['civil_status'], $_POST['education'], $_POST['religion'], $_POST['ethnicity'], $_POST['is_4ps_member'], $_POST['philhealth_id'], $_POST['medical_history'], $_POST['water_source'], $_POST['toilet_facility'], $_POST['address']);
    $stmt->execute();
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Household</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            display: flex;
            flex-direction: column;
            height: 100vh;
            margin: 0;
            background-color: #f0f2f5;
            font-family: 'Arial', sans-serif;
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
            background: #34495e;
            border-left: 3px solid #1abc9c;
        }

        .sidebar .active {
            background: #2980b9;
            border-left: 3px solid #16a085;
        }

        .sidebar .separator {
            border-top: 1px solid #ecf0f1;
            margin: 20px 0;
        }

        .content {
            flex: 1;
            margin-left: 250px;
            padding: 30px;
            overflow-y: auto;
        }

        .header {
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 2rem;
            color: #2c3e50;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 1.1rem;
            color: #7f8c8d;
        }

        .form-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .form-container input,
        .form-container select,
        .form-container textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-container button {
            width: 100%;
            padding: 12px;
            background-color: #1abc9c;
            border: none;
            color: white;
            font-size: 1.1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .form-container button:hover {
            background-color: #16a085;
        }

        .real-time-feedback {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: -10px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h3>Admin Dashboard</h3>
        <a href="dashboard.php"><i class="fas fa-home"></i> Home</a>
        <a href="household_form.php" class="active"><i class="fas fa-users"></i> Add Household</a>
        <a href="view_households.php"><i class="fas fa-table"></i> View Households</a>
        <a href="family_form.php"><i class="fas fa-user-plus"></i> Add Family Members</a>
        <a href="view_family.php"><i class="fas fa-list"></i> View Family Members</a>
        <a href="../common/logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="content">
        <div class="header">
            <h1>Add Household</h1>
            <p>Complete the form below to register a new household.</p>
        </div>
        <div class="form-container">
            <form id="household-form" method="POST">
                <input type="text" name="household_no" id="household_no" placeholder="Household No." required>
                <div class="real-time-feedback" id="feedback-household-no"></div>

                <input type="text" name="name" id="name" placeholder="Name" required>

                <label for="dob">Birthday</label>
                <input type="date" name="dob" id="dob" required>

                <label for="sex">Sex</label>
                <select name="sex" id="sex" required>
                    <option value="" disabled selected>Select Sex</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>

                <input type="text" name="civil_status" placeholder="Civil Status">
                <input type="text" name="education" placeholder="Education">
                <input type="text" name="religion" placeholder="Religion">
                <input type="text" name="ethnicity" placeholder="Ethnicity">

                <label for="is_4ps_member">4Ps Member</label>
                <select name="is_4ps_member" id="is_4ps_member" required>
                    <option value="" disabled selected>Is 4Ps Member?</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>

                <input type="text" name="philhealth_id" placeholder="PhilHealth ID">
                <textarea name="medical_history" placeholder="Medical History"></textarea>
                <input type="text" name="water_source" placeholder="Water Source">
                <input type="text" name="toilet_facility" placeholder="Toilet Facility">
                <input type="text" name="address" placeholder="Address" required>

                <button type="submit">Save Household</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('household_no').addEventListener('input', function() {
            const value = this.value;
            const feedback = document.getElementById('feedback-household-no');
            if (value.length < 3) {
                feedback.textContent = "Household No. must be at least 3 characters long.";
            } else {
                feedback.textContent = "";
            }
        });

        document.getElementById('household-form').addEventListener('submit', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Confirm Submission',
                text: 'Are you sure you want to save this household?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Save',
                cancelButtonText: 'No, Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    </script>
</body>

</html>