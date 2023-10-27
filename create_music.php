<?php
    require_once "config.php";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if  ($conn->connect_error) {
        die("Connection Failed: " . $conn->connect_error);
    }

    session_start(); 
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === false) {
        header("Location: login.php");
        exit();
    }


if (!isset($_SESSION['username'])) {
    // Redirect to the login page or display a login link
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form submission
    $username = $_SESSION['username'];
    $artist = $_POST['artist'];
    $songTitle = $_POST['song_title'];
    $rating = $_POST['rating'];

    // Check if the song already exists for the user
    $checkQuery = "SELECT id FROM ratings WHERE username = ? AND artist = ? AND song = ?";
    $stmt = $conn->prepare($checkQuery);

    if ($stmt) {
        $stmt->bind_param("sss", $username, $artist, $songTitle);
        $stmt->execute();
        $result = $stmt->get_result();
        //  Prevents users from rating the same songs twice
        if ($result->num_rows > 0) {
            echo "You have already rated this song. Please rate a different song.";
        } else {
            // Insert the new song rating into the database
            $insertQuery = "INSERT INTO ratings (username, artist, song, rating) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);

            if ($stmt) {
                $stmt->bind_param("sssi", $username, $artist, $songTitle, $rating);
                $stmt->execute();

                // Redirect to the song list or another appropriate page
                header("Location: main.php");
                exit();
            } else {
                echo "Error preparing the insert query.";
            }
        }

        $stmt->close();
    } else {
        echo "Error preparing the check query.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Song Rating</title>
</head>
<body>
    <!-- shows on top of the page that the user is logged in as -->
    <h1>You are logged in <?php echo $_SESSION['username']; ?></h1>
    <!-- adds a new a song -->
    <h2>Add New Song Rating</h2>
    <form method="post">
        <label for="artist">Artist:</label>
        <input type="text" name="artist" id="artist" required><br>

        <label for="song_title">Song Title:</label>
        <input type="text" name="song_title" id="song_title" required><br>

        <label for="rating">Rating (1-5):</label>
        <input type="number" name="rating" id="rating" required min="1" max="5"><br>

        <input type="submit" value="Submit">
    </form>
    <a href="main.php">Back to Song List</a>
</body>
</html>
