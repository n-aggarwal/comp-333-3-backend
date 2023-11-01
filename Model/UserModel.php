<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
require "/Applications/XAMPP/xamppfiles/htdocs/inc/bootstrap.php";

class UserModel extends Database
{
/**
 * The getUsers function retrieves a specified number of users from the database.
 * 
 * @param limit The limit parameter is used to specify the maximum number of rows to be returned from
 * the database query.
 * 
 * @return The getUsers function is returning the result of a database query. 
 */
    public function getUsers($limit)
    {
        return $this->select("SELECT * FROM users LIMIT ?", ["i", $limit]);
    }

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
}
