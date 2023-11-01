<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
require "/Applications/XAMPP/xamppfiles/htdocs/inc/bootstrap.php";

class UserModel extends Database
{

/**
 * The function retrieves the username and password of a user from the database based on their
 * username.
 * 
 * @param username The username parameter is the username of the user you want to retrieve from the
 * database.
 * 
 * @return result The result of the database query. 
 */
    public function getUserByUsername($username) {
        return $this->select("SELECT username, password FROM users WHERE username = ?", ["s", $username]);
    }

    public function createUser($userid , $hashed_password) {
        $sql = "INSERT INTO users (username, password) VALUES (?,?)";
        $stmt = mysqli_prepare($this->connection, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $userid, $hashed_password);
        try {
            return (mysqli_stmt_execute($stmt) === TRUE) ;
        }
        catch (Exception $e) {
            return FALSE;
        }
    }
}
