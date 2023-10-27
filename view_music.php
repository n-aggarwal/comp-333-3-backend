<?php
    require_once "config.php";

    //  conn is the database connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    if  ($conn->connect_error) {
        die("Connection Failed: " . $conn->connect_error);
    }

    session_start(); 
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === false) {
        header("Location: login.php");
        exit();
    }
    // php config and login checking ends here


    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['song_id'])) {
        $songId = $_GET['song_id'];
    
        // Retrieve song details from the database
        $query = "SELECT username, artist, song, rating FROM ratings WHERE id = ?";
        $stmt = $conn->prepare($query);
    
        if ($stmt) {
            $stmt->bind_param("i", $songId); // 'i' is for an integer
            $stmt->execute();
            $result = $stmt->get_result();
            
            // If song exists, then retreive the info from that row
            if ($result->num_rows > 0) {
                $songData = $result->fetch_assoc();
                $username = $songData['username'];
                $artist = $songData['artist'];
                $songTitle = $songData['song'];
                $rating = $songData['rating'];
            } else {
                // Song does not exist, display an error message or redirect
                echo "Song not found.";
                exit();
            }
    
            $stmt->close();
        } else {
            // Error handling for query preparation
            echo "Error preparing the query.";
            exit();
        }
    } else {
        // Invalid request, redirect to an appropriate page
        header("Location: main.php");
        exit();
    }
?>


<!DOCTYPE html>
<html>
<head>
    <title>Song Details</title>
</head>
<body>
    <!-- shows on top of the page that the user is logged in as -->
    <h1>You are logged in <?php echo $_SESSION['username']; ?></h1>
    <!-- Display the song details -->
    <h2>Song Details</h2>
    <p><strong>Username:</strong> <?php echo $username; ?></p>
    <p><strong>Artist:</strong> <?php echo $artist; ?></p>
    <p><strong>Song Title:</strong> <?php echo $songTitle; ?></p>
    <p><strong>Rating:</strong> <?php echo $rating; ?></p>
    <a href="main.php">Back to Song List</a>
</body>
</html>