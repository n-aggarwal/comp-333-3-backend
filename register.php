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
        * Check whether the user entered a username and password, the password matches the
        * required guidelines, and whether the password and confirm password are the same.
        * If everything is good, add user info to users table thereby registering the user.
        */
        $userid = $_POST['userid'];
        $password = $_POST['password'];
        $password_2 = $_POST['password_2'];
    
        if (empty($userid)) {
            echo "Username is required!";
        }
        else if (empty($password)) {
            echo "Password is required!";
        }
        else if (empty($password_2)) {
            echo "Confirm password is required!";
        }
        else if ($password !== $password_2) {
            echo "Password and Confirm Password do not match!";
        }
        else if (strlen($password) < 10) {
            echo ("The password is not long enough. You need at least 10 characters.");
        }
        /*
         * Using Prepared SQL queries to avoid injection attacks.
         * Hashed Passwords
         */
        else {
            //First Check if the username already exists in DB
            $check_sql = "SELECT * FROM users WHERE username = ?";
            $check_stmt = mysqli_prepare($conn, $check_sql);
            mysqli_stmt_bind_param($check_stmt, "s", $userid);
            try {
                if (mysqli_stmt_execute($check_stmt) === TRUE) {
                    $result = mysqli_stmt_get_result($check_stmt);
                    $row = mysqli_fetch_array($result);

                    if(mysqli_num_rows($result) > 0) {
                        echo "Please try another username";
                    } 
                    
                    else {
                        // If not already in db, we register the user
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                        $sql = "INSERT INTO users (username, password) VALUES (?,?)";
                        $stmt = mysqli_prepare($conn, $sql);
                        mysqli_stmt_bind_param($stmt, "ss", $userid, $hashed_password);
                        try {
                            if (mysqli_stmt_execute($stmt) === TRUE) {
                                session_start();
                                $_SESSION["loggedin"] = true;
                                $_SESSION["username"] = $userid;
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
From with username, password and confirm password that allows users to register for the
website.
-->
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>
    <div id="form">
        <h1>Register</h1>
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
                <label> CONFIRM PASSWORD: </label>
                <input type="password" id="confirm_pass" name="password_2" />
            </p>
            <p>
                <input type="submit" id="button" value="Register" />
            </p>
        </form>
        <a href="login.php">Login</a>
    </div>
</body>
</html>