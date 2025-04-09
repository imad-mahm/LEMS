<?php
session_start();
$clubId = $_POST['club_id'];
$lauEmail = $_SESSION['LAU_EMAIL'];

// DB connection


$conn = new mysqli("localhost", "root", "", "lems");
if($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute DELETE
$stmt = $conn->prepare("DELETE FROM CLUB_USER WHERE LAU_EMAIL = ? AND CLUBID = ?");
$stmt->bind_param("si", $lauEmail, $clubId);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    // Optional: success message
    header("Location: joinClubs.php"); // Redirect back
} else {
    // Optional: failure message
    header("Location: joinClubs.php"); // Redirect back
}

$stmt->close();
$conn->close();
?>
