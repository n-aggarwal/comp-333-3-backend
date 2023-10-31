<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
require "/Applications/XAMPP/xamppfiles/htdocs/inc/bootstrap.php";

class MusicModel extends Database
{

    public function getMusic($limit)
    {
        return $this->select("SELECT * FROM ratings LIMIT ?", ["i", $limit]);
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
    public function getMusicbyId($id) {
        return $this->select("SELECT * FROM ratings WHERE id = ?", ["i", $id]);
    }
}
