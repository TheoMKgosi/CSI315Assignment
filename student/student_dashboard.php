<?php
include_once "../util.php"; // Include the utility functions for database connection and sanitization
session_start();

$conn = connectToDatabase();

// Get application ID from the session if it exists
if (isset($_SESSION["student_id"])) {
    $student_id = $_SESSION["student_id"];
    $query = "SELECT application_id FROM applications WHERE student_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION["application_id"] = $row["application_id"];
    } else {
        $_SESSION["application_id"] = null;
    }
} else {
    header("Location: student_login.php"); // Redirect to login page if not logged in
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            color: #333;
        }

        .top-nav {
            background-color: #00264d;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .top-nav h1 {
            font-size: 22px;
        }

        .logout-btn {
            background-color: #e60000;
            border: none;
            color: white;
            padding: 10px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .logout-btn:hover {
            background-color: #cc0000;
        }

        .dashboard {
            max-width: 1100px;
            margin: 40px auto;
            padding: 20px;
        }

        .welcome {
            margin-bottom: 30px;
            text-align: center;
        }

        .welcome h2 {
            color: #003366;
            margin-bottom: 10px;
        }

        .button-bar {
            text-align: center;
            margin-bottom: 30px;
        }

        .button-bar button {
            background-color: #00509e;
            border: none;
            color: white;
            padding: 12px 20px;
            margin: 5px;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        .button-bar button:hover {
            background-color: #003f7d;
        }

        .nav-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 20px;
        }

        .card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card a {
            text-decoration: none;
            color: #00509e;
            font-weight: bold;
        }

        .card a:hover {
            color: #003f7d;
        }

        footer {
            margin-top: 40px;
            text-align: center;
            padding: 20px;
            background-color: #00264d;
            color: white;
        }

        footer a {
            color: #00aced;
        }
    </style>
</head>
<body>

<div class="top-nav">
    <h1>Student Dashboard</h1>
    <form action="logout.php" method="post">
        <button type="submit" class="logout-btn">Logout</button>
    </form>
</div>

<div class="dashboard">
    <div class="welcome">
        <h2>Welcome, <?php echo $_SESSION["name"]; ?></h2>
        <p>Your one-stop portal for admission to the University of Botswana.</p>
    </div>

    <!-- Key Action Buttons -->
    <div class="button-bar">
        <?php
        // Check if the user filled out the application form
        if (isset($_SESSION["application_id"])) {
            echo "<button onclick=\"window.location.href='edit_personal.php'\">Edit Personal Info</button>";
        } else {
            echo "<button onclick=\"window.location.href='form.php'\">New Application</button>";
        }
        ?>
    </div>

    <!-- Navigation Cards -->
    <div class="nav-grid">
        <div class="card"><a href="notifications.php">Notifications</a></div>
        <div class="card"><a href="support.php">Contact Support</a></div>
        <div class="card"><a href="faq.html">Frequently Asked Questions</a></div>
        <div class="card"><a href="admission_requirements.html">Admission Requirements</a></div>
        <div class="card"><a href="important_dates.php">Important Dates & Deadlines</a></div>
        <div class="card"><a href="orientation_guide.php">New Student Orientation</a></div>
    </div>
</div>

<footer>
    <p>&copy; <?php echo date("Y"); ?> University of Botswana. All rights reserved.</p>
    <p>Need help? <a href="support.php">Contact Support</a></p>
</footer>

</body>
</html>
