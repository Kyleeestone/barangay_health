<?php
include '../common/auth.php';
include '../common/db.php';

// Handle form submission for logbook entry
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("INSERT INTO logbook (entry_date, full_name, address, age, sex, date_monitored, date_completed, remarks) 
                            VALUES (CURDATE(), ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "ssissss",
        $_POST['full_name'],
        $_POST['address'],
        $_POST['age'],
        $_POST['sex'],
        $_POST['date_monitored'],
        $_POST['date_completed'],
        $_POST['remarks']
    );
    $stmt->execute();
}

// Fetch all logbook entries ordered by date
$entries = $conn->query("SELECT * FROM logbook ORDER BY entry_date DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logbook</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #eee;
        }

        .container {
            max-width: 900px;
            margin-top: 80px;
        }

        .logbook-form {
            background: #eee;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 50px;
        }

        .logbook-entry {
            margin-bottom: 20px;
        }

        .card-header {
            background-color: #eee;
            color: white;
            font-weight: bold;
        }

        .card-body {
            background-color: #eee;
        }

        .card-footer {
            background-color: #eee;
        }

        .card-body p {
            margin-bottom: 8px;
        }

        .form-label {
            font-weight: bold;
        }

        .back-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 9999;
            background-color: #34495e;
            color: white;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .back-btn i {
            margin-right: 8px;
        }

        .icon {
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }

        .text-primary {
            color: #eee !important;
        }

        .btn-primary {
            background-color: rgb(31, 32, 32);
            border: none;
        }

        .btn-primary:hover {
            background-color: rgb(38, 38, 39);
        }

        .card {
            margin-bottom: 15px;
        }

        .entry-title {
            font-size: 1.2rem;
        }

        .container h1,
        .container h3 {
            color: rgb(0, 0, 0);
        }

        /* Media Queries for better mobile responsiveness */
        @media (max-width: 768px) {
            .container {
                margin-top: 30px;
            }

            .logbook-form {
                padding: 15px;
            }

            .back-btn {
                font-size: 14px;
                padding: 8px 12px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center mb-4">Barangay Health Logbook</h1>

        <!-- Back Button -->
        <a href="dashboard.php" class="btn back-btn"><i class="fa fa-arrow-left"></i> Back to Dashboard</a>

        <!-- Logbook Entry Form -->
        <div class="logbook-form">
            <h3>Add Logbook Entry</h3>
            <form method="POST">
                <div class="mb-3">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="full_name" placeholder="Enter full name" required>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" name="address" placeholder="Enter address" required>
                </div>

                <div class="mb-3">
                    <label for="age" class="form-label">Age</label>
                    <input type="number" class="form-control" name="age" placeholder="Enter age" required>
                </div>

                <div class="mb-3">
                    <label for="sex" class="form-label">Sex</label>
                    <input type="text" class="form-control" name="sex" placeholder="Enter sex" required>
                </div>

                <div class="mb-3">
                    <label for="date_monitored" class="form-label">Date Monitored</label>
                    <input type="date" class="form-control" name="date_monitored" required>
                </div>

                <div class="mb-3">
                    <label for="date_completed" class="form-label">Date Completed</label>
                    <input type="date" class="form-control" name="date_completed" required>
                </div>

                <div class="mb-3">
                    <label for="remarks" class="form-label">Remarks</label>
                    <textarea class="form-control" name="remarks" placeholder="Enter remarks..." required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Add Entry</button>
            </form>
        </div>

        <!-- Logbook Entries List -->
        <h3>Recent Log Entries</h3>
        <div id="accordion">
            <?php while ($row = $entries->fetch_assoc()): ?>
                <div class="logbook-entry">
                    <div class="card">
                        <div class="card-header" id="heading<?php echo $row['id']; ?>">
                            <h5 class="mb-0">
                                <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $row['id']; ?>" aria-expanded="true" aria-controls="collapse<?php echo $row['id']; ?>">
                                    <i class="fa fa-calendar icon"></i><?php echo $row['entry_date']; ?>
                                </button>
                            </h5>
                        </div>

                        <div id="collapse<?php echo $row['id']; ?>" class="collapse" aria-labelledby="heading<?php echo $row['id']; ?>" data-bs-parent="#accordion">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><i class="fa fa-user icon"></i><strong>Full Name:</strong> <?php echo htmlspecialchars($row['full_name']); ?></p>
                                        <p><i class="fa fa-map-marker icon"></i><strong>Address:</strong> <?php echo htmlspecialchars($row['address']); ?></p>
                                        <p><i class="fa fa-birthday-cake icon"></i><strong>Age:</strong> <?php echo $row['age']; ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><i class="fa fa-venus-mars icon"></i><strong>Sex:</strong> <?php echo htmlspecialchars($row['sex']); ?></p>
                                        <p><i class="fa fa-calendar-check icon"></i><strong>Date Monitored:</strong> <?php echo $row['date_monitored']; ?></p>
                                        <p><i class="fa fa-check-circle icon"></i><strong>Date Completed:</strong> <?php echo $row['date_completed']; ?></p>
                                    </div>
                                </div>
                                <p><i class="fa fa-comment icon"></i><strong>Remarks:</strong> <?php echo htmlspecialchars($row['remarks']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>