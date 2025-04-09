<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['LAU_EMAIL'])) {
    header("Location: signup.php");
    exit;
}
if ($_SESSION['USER_ROLE'] != 'admin') { //only admins can edit location
    header("Location: home.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Locations</title>
    <link rel="stylesheet" href="styles.css">
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
        <h2>Locations</h2>
        <form action="locations.php" method="POST">
            <label for="campus">Location Campus:</label> <!-- dropdown menu -->
            <select id="campus" name="campus" required>
                <option value="" disabled selected>Select Campus</option>
                <option value="Byblos">Byblos</option>
                <option value="Beirut">Beirut</option>
            </select>
            <label for="building">Location Building:</label>
            <input type="text" id="building" name="building" required>
            <label for="room">Location Room:</label>
            <input type="text" id="room" name="room" required>
            <button type="submit" name="add_location">Add Location</button>
        </form>

        <?php
        // Database connection
        $conn = new mysqli('localhost', 'root', '', 'lems');

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        // Handle form submission
        if (isset($_POST['add_location'])) {
            $campus = $_POST['campus'];
            $building = $_POST['building'];
            $room = $_POST['room'];
            // Check if the location already exists
            $check = $conn->prepare("SELECT * FROM location WHERE CAMPUS = ? AND BUILDING = ? AND ROOM = ?");
            $check->bind_param("sss", $campus, $building, $room);
            $check->execute();
            $result = $check->get_result();
            if ($result->num_rows > 0) {
                echo "<p>Location already exists!</p>";
            } else {
                // Insert location into the database
                $sql = "INSERT INTO location (ROOM, BUILDING, CAMPUS) VALUES (?,?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $room, $building, $campus);
                if($stmt->execute()) {
                    echo "<p>Location added successfully!</p>";
                } else {
                    echo "<p>Error adding location: " . $stmt->error . "</p>";
                }
            }
        }?>
        <?php
        // Fetch and display locations
        $sql = "SELECT * FROM location";
        $result = $conn->prepare($sql);
        $result->execute();
        $result = $result->get_result();
        if ($result->num_rows > 0) {
            echo "<h3>Existing Locations:</h3>";
            echo "<table><tr><th>Campus</th><th>Building</th><th>Room</th></tr>";
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["CAMPUS"] . "</td><td>" . $row["BUILDING"] . "</td><td>" . $row["ROOM"] . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No locations found.</p>";
        }
        // Close connection
        $result->close();
        $conn->close();

        ?>
    </main>
    <footer>
        <p>&copy; 2025 404 GROUP NOT FOUND. All rights reserved.</p>
    </footer>
</body>
</html>