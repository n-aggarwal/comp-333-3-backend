<?php
    header("Access-Control-Allow-Origin: http://localhost:3000");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
    header("Access-Control-Allow-Headers: Content-Type");
// php config and login checking starts here
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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["Logout"])) {
            handleLogout();
        } elseif (isset($_POST["Update"])) {
            handleUpdate();
        }
    }

    //Get a new cookie value because there is a change in permissions and logout.
    function handleLogout() {
        $_SESSION = array();
        session_regenerate_id();
        session_destroy();
        header("Location: login.php");
        exit();
    }
    
    function handleUpdate() {
        echo "Form 2 submitted!";
    }
// php config and login checking ends here

$username = $_SESSION['username'];

// Query the database to retrieve all songs
$sql = "SELECT * FROM ratings";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Main Page</title>
</head>
<body>
    <h1>You are logged in <?php echo $username; ?></h1>
    <h2>List of Songs</h2>

    <table border="6">
        <tr>
            <th>ID</th>
            <th>Artist</th>
            <th>Username</th>
            <th>Song</th>
            <th>Rating</th>
            <th>Actions</th>
        </tr>
        <!-- CHECKING FOR EACH ROW THE DATA COLUMNS -->
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['username'] ?></td>
            <td><?= $row['artist'] ?></td>
            <td><?= $row['song'] ?></td>
            <td><?= $row['rating'] ?></td>
            <!-- ENSURES THE DELETE AND UPDATE BUTTON IS ONLY ACCESSIBLE TO THE LOGGED IN USER -->
            <td>
                <a href="view_music.php?song_id=<?= $row['id'] ?>">View</a>

                <?php if ($username === $row["username"]): ?>
                <!-- updates a song -->
                <form action="update_music.php" method="post">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <input type="submit" value="Update" name="Update">
                </form>
                <!-- deletes a song -->
                <form action="delete_music.php" method="post">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <input type="submit" value="Delete" name="Delete">
                </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- LOGOUT BUTTON WHICH DIRRECTS TO THE LOGIN -->
    <form method="post" action="">
        <button type="submit" name="Logout">Logout</button>
    </form>

    <!-- create new song rating -->
    <a href="create_music.php">Add new song rating</a>

</body>
</html>