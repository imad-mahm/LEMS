<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['LAU_EMAIL'])) {
    header("Location: signup.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Clubs</title>
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
        <h2>Join Clubs</h2>
        <form action="joinClubs.php" method="POST">
            <label for="club_id">Select Club:</label>
            <select id="club_id" name="club_id" required>
                <?php
                $servername = "localhost";  
                $username = "root";
                $password = "";
                $dbname = "lems";
                $conn = new mysqli($servername, $username, $password, $dbname);
                if($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                // Fetch clubs from the database
                $stmt = $conn->prepare("SELECT ID, CLUB_NAME FROM CLUB");
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['ID'] . "'>" . $row['CLUB_NAME'] . "</option>";
                }
                ?>
            </select>
            <input type="submit" value="Join Club"> 
        </form>
        <?php
        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $club_id = $_POST['club_id'];
            // Check if the user is already a member of the club
            $check = $conn->prepare("SELECT * FROM CLUB_USER WHERE LAU_EMAIL = ? AND CLUBID = ?");
            $check->bind_param("si", $_SESSION['LAU_EMAIL'], $club_id);
            $check->execute();
            $result = $check->get_result();
            if ($result->num_rows > 0) {
                echo "You are already a member of this club.";
            } else {
                // Add the user to the club
                $stmt = $conn->prepare("INSERT INTO CLUB_USER (LAU_EMAIL, CLUBID) VALUES (?, ?)");
                $stmt->bind_param("si", $_SESSION['LAU_EMAIL'], $club_id);
                $check = $conn->prepare("SELECT * FROM CLUB_USER WHERE LAU_EMAIL = ?");
                $check->bind_param("s", $_SESSION['LAU_EMAIL']);
                $check->execute();
                $result = $check->get_result();
                if ($result->num_rows >1) {
                    echo "You can only be in 2 clubs at a time.";
                }
                else if ($stmt->execute()) {
                    echo "You have successfully joined the club.";
                } else {
                    echo "Error: " . $stmt->error;
                }
            }
        }
        // Close the statement and connection
        $stmt->close();
        $conn->close();
        ?>
        <h2>My Clubs</h2>
        <ul>
            <?php
            // Fetch clubs the user is a member of
            $conn = new mysqli($servername, $username, $password, $dbname);
            if($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $stmt = $conn->prepare("SELECT C.ID, C.CLUB_NAME FROM CLUB_USER CU JOIN CLUB C ON CU.CLUBID = C.ID WHERE CU.LAU_EMAIL = ?");
            $stmt->bind_param("s", $_SESSION['LAU_EMAIL']);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $clubName = $row['CLUB_NAME'];
                    $clubId = $row['ID'];
                
                    echo "
                    <li>
                        $clubName
                        <form method='POST' action='leave_club.php' style='display:inline;'>
                            <input type='hidden' name='club_id' value='$clubId'>
                            <button type='submit'>Leave Club</button>
                        </form>
                    </li>";
                }
                
            } else {
                echo "<li>No clubs joined yet.</li>";
            }
            // Close the statement and connection
            $stmt->close();
            $conn->close();
            ?>
        </ul>
    </main>
    <footer>
        <p>&copy; 2025 GROUP NOT FOUND. All rights reserved.</p>
    </footer>
</body>
</html>
