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

    /*
     * Display the users a form that they can use to delete the ratings of their 
     * music.
     */

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Delete"])) {
        $music_id = intval($_POST['id']);
        $sql = "SELECT username, artist, song, rating FROM ratings WHERE id=$music_id";
        $result = mysqli_query($conn, $sql);

        $row = mysqli_fetch_assoc($result);
        $music_username = $row['username'];
        $music_artist = $row['artist'];
        $music_song = $row['song'];
        $music_rating = $row['rating'];

        ?>

<?php

        }

        else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Delete_Confirm"])) {
            
            $sql = "DELETE FROM ratings WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $_POST["id"]);
            try {
                if (mysqli_stmt_execute($stmt) === TRUE) {
                    header("Location: main.php");
                    exit();
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
            }
            catch (Exception $e) {
                echo $e;
            }
        } 

        else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Delete_Cancel"])) {
            header("Location: main.php");
            exit();
        }
?>

<!DOCTYPE html>
<html>
<body>
    <!-- shows on top of the page that the user is logged in as -->
    <h1>You are logged in <?php echo $_SESSION['username']; ?></h1>
    <?php echo "Are you sure you want to delete this?" ?>
    <form action="" method="post">
    <input type='hidden' name='id' value=<?php echo $music_id ?>>
    <input type="submit" value="No" name="Delete_Cancel">
    <input type="submit" value="Yes" name="Delete_Confirm">
</form>
</body>
</html>

