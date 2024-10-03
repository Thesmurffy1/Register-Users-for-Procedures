<?php
// Database connection parameters
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "zona"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $procedure = $conn->real_escape_string($_POST['procedure']);
    $total_amount = floatval($_POST['amount']);
    $salary = $total_amount * 0.60;

    // Prepare the statement
    $stmt = $conn->prepare("INSERT INTO users (name, `procedure`, total_amount, salary) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("ssdd", $name, $procedure, $total_amount, $salary);

    // Execute the statement
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $stmt->close();
}

// Fetch existing entries to display
$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procedure Registration</title>
    <style>
        .save-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .save-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h2>Register Users for Procedures</h2>

<form method="POST" action="">
    <table id="procedureTable">
        <thead>
            <tr>
                <th>Name (სახელი)</th>
                <th>Procedure (პროცედურა)</th>
                <th>Total Amount (სრული თანხა)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type="text" name="name" placeholder="Enter name" required></td>
                <td><input type="text" name="procedure" placeholder="Enter procedure" required></td>
                <td><input type="number" name="amount" placeholder="Enter amount" required></td>
                <td>
                    <button class="save-button" type="submit">Save</button>
                </td>
            </tr>
        </tbody>
    </table>
</form>

<h2>Existing Registrations</h2>
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Procedure</th>
            <th>Total Amount</th>
            <th>Salary</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['name']) . "</td>
                        <td>" . htmlspecialchars($row['procedure']) . "</td>
                        <td>" . htmlspecialchars($row['total_amount']) . "</td>
                        <td>" . htmlspecialchars($row['salary']) . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No entries found.</td></tr>";
        }
        $conn->close();
        ?>
    </tbody>
</table>

</body>
</html>
