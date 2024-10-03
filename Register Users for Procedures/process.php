<?php
// process.php

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "zona"; 

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Loop through each entry in the submitted form data
    for ($i = 0; $i < count($_POST['name']); $i++) {
        $name = $conn->real_escape_string($_POST['name'][$i]);
        $procedure = $conn->real_escape_string($_POST['procedure'][$i]);
        $total_amount = floatval($_POST['amount'][$i]);
        $salary = $total_amount * 0.60;

        // Insert into the users table
        $sql = "INSERT INTO users (name, `procedure`, total_amount, salary) VALUES ('$name', '$procedure', '$total_amount', '$salary')";

        if (!$conn->query($sql)) {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Redirect back to the form
    header("Location: register.php");
    exit();
}

$conn->close();
?>
