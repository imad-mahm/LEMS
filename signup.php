<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lems";
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Check if the form is submitted
// form takes LAU_EMAIL, and STUDENT_NAME only
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['LAU_EMAIL'];
    $name = $_POST['STUDENT_NAME'];
    $name = explode(" ", $name); // Split the name into first and last name
    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO USER (LAU_EMAIL, USER_ROLE, FIRST_NAME, LAST_NAME) VALUES (?, 'user', ?, ?)");
    $stmt->bind_param("sss", $email, $name[0], $name[1]);
    $check = $conn->prepare("SELECT * FROM USER WHERE LAU_EMAIL = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();
    if ($result->num_rows > 0) {
        echo "Email already exists";
        session_start();
        $_SESSION['LAU_EMAIL'] = $email;
        $_SESSION['FIRST_NAME'] = $name[0];
        $_SESSION['LAST_NAME'] = $name[1];
        $_SESSION['USER_ROLE'] = $result->fetch_assoc()['USER_ROLE'];
        header("Location: home.php");
        exit;
    }
    // Execute the statement
    if ($stmt->execute()) {
        echo "New record created successfully";
        session_start();
        $_SESSION['LAU_EMAIL'] = $email;
        $_SESSION['FIRST_NAME'] = $name[0];
        $_SESSION['LAST_NAME'] = $name[1];
        $_SESSION['USER_ROLE'] = "user"; // Default role for new users
        header("Location: home.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
    // Close the statement and connection
    $stmt->close();
}
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="style.css">
    <script type="module">
      import { app } from "./firebase.js";
    </script>
    </head>
    </html>