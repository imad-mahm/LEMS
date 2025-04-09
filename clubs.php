<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['LAU_EMAIL'])) {
    header("Location: signup.php");
    exit;
}
if ($_SESSION['USER_ROLE'] != 'admin') { //only admins can add clubs
    header("Location: home.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clubs</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Welcome <?php echo $_SESSION['FIRST_NAME'];
                        if($_SESSION['USER_ROLE'] != 'user'){
                            echo " [" . $_SESSION['USER_ROLE'] . "]";
                        }  ?> to LEMS</h1>
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
                <li><a href="events.php">Events</a></li>
                <li><a href="joinClubs.php">Join A Club</a></li>
                <?php
                // Check if the user is an admin
                if($_SESSION['USER_ROLE'] === "admin" || $_SESSION['USER_ROLE'] === "organizer"){
                    echo '<li><a href="create_event.php">Create Event</a></li>';
                }
                // Check if the user is an admin
                if($_SESSION['USER_ROLE'] === "admin"){
                    echo '<li><a href="clubs.php">Clubs</a></li>';
                    echo '<li><a href="locations.php">Locations</a></li>';
                    echo '<li><a href="users.php">Users</a></li>';
                }
                ?>

            </ul>
        </nav>
    </header>

    <main>
        <h2>Clubs</h2>
        <form action="clubs.php" method="POST">
            <label for="club_name">Club Name:</label>
            <input type="text" id="club_name" name="club_name" required>
            <label for="club_description">Club Description:</label>
            <input type="text" id="club_description" name="club_description" required>
            <label for="club_email">Club Email:</label>
            <input type="email" id="club_email" name="club_email" required>
            <input type="submit" value="Create">
        </form>
        <?php
            $servername = "localhost";  
            $username = "root";
            $password = "";
            $dbname = "lems";
            $conn = new mysqli($servername, $username, $password, $dbname);
            if($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Add a new club
                $club_name = $_POST['club_name'];
                $club_description = $_POST['club_description'];
                $club_email = $_POST['club_email'];
                // Check if the club already exists
                $check = $conn->prepare("SELECT * FROM CLUB WHERE CLUB_NAME = ? OR CLUB_EMAIL = ?");
                $check->bind_param("ss", $club_name, $club_email);
                $check->execute();
                $result = $check->get_result();
                if ($result->num_rows > 0) {
                    echo "<p>Club already exists</p>";
                } else {
                    // Prepare and bind
                    $stmt = $conn->prepare("INSERT INTO CLUB (CLUB_NAME, CLUB_DESCRIPTION, CLUB_EMAIL) VALUES (?,?,?)");
                    $stmt->bind_param("sss", $club_name, $club_description, $club_email);
                    if ($stmt->execute()) {
                        echo "<p>New club created successfully</p>";
                    } else {
                        echo "<p>Error: " . $stmt->error . "</p>";
                    }
                    $stmt->close();
                }
                $check->close();
            }
            ?>
            <?php
            // Display all clubs
            $stmt = $conn->prepare("SELECT * FROM CLUB");
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                echo "<h3>All Clubs</h3>";
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='club-card'>";
                    echo "<h4>" . $row['CLUB_NAME'] . "</h4>";
                    echo "<p>" . $row['CLUB_DESCRIPTION'] . "</p>";
                    echo "<p><strong>Email: " . $row['CLUB_EMAIL'] . "</strong></p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No clubs found</p>";
            }
            $stmt->close();
            $conn->close();
        ?>
        </main>
        <footer>
            <p>&copy; 2025 GROUP NOT FOUND. All rights reserved.</p>
        </footer>
        </body>
        </html>