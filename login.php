<!-- Connect to Database -->
<?php 
    require_once "config.php";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if  ($conn->connect_error) {
        die("Connection Failed: " . $conn->connect_error);
    }
    
    session_start(); 
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        header("Location: main.php");
        exit();
    }

?>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
       /*
        * Check whether the user entered a username and password, and checks
        * the information provided against the users table in DB. If username, password
        * is found then the user is successfully logged in, else an appropriate error
        * message is shown.
        */
        $userid = $_POST['userid'];
        $pass = $_POST['password'];
    
        if (empty($userid)) {
            echo "Username is required!";
        }
        else if (empty($pass)) {
            echo "Password is required!";
        }
        else {
            $sql = "SELECT password FROM users WHERE username = ? ";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $userid);
            try {
                if (mysqli_stmt_execute($stmt) === TRUE) {
                    $result = mysqli_stmt_get_result($stmt);
                    $row = mysqli_fetch_array($result);
                    if ($row) {
                        $db_password = $row['password'];
                        if (password_verify($pass, $db_password)){
                            session_regenerate_id();
                            session_start();
                            $_SESSION["loggedin"] = true;
                            $_SESSION["username"] = $userid;
                            header("Location: main.php"); 
                            exit(); 
                        }
                        else{
                            echo "Invalid Credentials";
                        }
                    } else {
                        echo "Invalid Credentials";
                    }
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
            }
            catch (Exception $e) {
                echo $e;
            }
        }

        
    }
?>

<!--
From with username, and password that allows users login into the
website.
-->
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>
    <div id="form">
        <h1>Login</h1>
        <form name="form" action="" method="POST">
            <p>
                <label> USER NAME: </label>
                <input type="text" id="user" name="userid" />
            </p>

            <p>
                <label> PASSWORD: </label>
                <input type="password" id="pass" name="password" />
            </p>
            <p>
                <input type="submit" id="button" value="Login" />
            </p>
        </form>
        <a href="register.php">Register</a>

    </div>
</body>
</html>