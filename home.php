<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['LAU_EMAIL'])) {
    header("Location: signup.php");
    exit;
}
?>

<DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
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
        <p>Welcome to the LEMS. Your one stop to all university events</p>
        <?php
            $servername = "localhost";  
            $username = "root";
            $password = "";
            $dbname = "lems";
            $conn = new mysqli($servername, $username, $password, $dbname);
            if($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $stmt = $conn->prepare("SELECT E.EVENT_NAME, E.EVENT_DESCRIPTION, E.START_TIME, E.LOCATIONID, L.CAMPUS, EC.CLUBID, C.CLUB_NAME
                                    FROM EVENT E
                                    JOIN LOCATION L ON E.LOCATIONID = L.LOCATIONID
                                    JOIN EVENT_CLUB EC ON E.EVENTID = EC.EVENTID
                                    JOIN CLUB C ON EC.CLUBID = C.ID
                                    WHERE E.START_TIME > NOW()
                                    ORDER BY E.START_TIME
                                    LIMIT 5");
            $stmt->execute();
            $result = $stmt->get_result();
            for ($i = 0; $i < 5; $i++) {
                if ($row = $result->fetch_assoc()) {
                    echo '<div class="event-card">';
                    echo '<h3>' . $row['EVENT_NAME'] . '</h3>';
                    echo '<p><strong>By: ' . $row['CLUB_NAME'] . '</strong></p>';
                    echo '<p>' . $row['EVENT_DESCRIPTION'] . '</p>';
                    echo '<p><strong>Date: ' . $row['START_TIME'] . '</strong></p>';
                    echo '<p><strong>Location: ' . $row['CAMPUS']. '</strong></p>';
                    echo '<button>Register</button>';
                    echo '</div>';
                } else {
                    if($i === 0){
                        echo "<h4>No future events yet</h4>";
                    }
                    break;
                }
            }
            $stmt->close();
            $conn->close();
        ?>
        <div class = "event-card">
            <h3>EVENT 1</h3>
            <p><strong>By: Club A</strong></p>
            <p>event description</p>
            <button>register</button>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 404 GROUP NOT FOUND. All rights reserved.</p>
    </footer>