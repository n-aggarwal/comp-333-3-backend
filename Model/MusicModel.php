<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
require "/Applications/XAMPP/xamppfiles/htdocs/inc/bootstrap.php";

class MusicModel extends Database
{

    public function getMusic($limit)
    {
        return $this->select("SELECT * FROM ratings LIMIT ?", ["i", $limit]);
    }

    public function getMusicbyId($id) {
        return $this->select("SELECT username FROM ratings WHERE id = ?", ["i", $id]);
    }

    public function updateMusic ($id, $artist, $song, $rating) {

        $sql = "UPDATE ratings SET artist = ?, song = ?, rating = ? WHERE id = ?";
            $stmt = mysqli_prepare($this->connection, $sql);
            mysqli_stmt_bind_param($stmt, "sssi", $artist, $song, $rating, $id);
            try {
                return mysqli_stmt_execute($stmt) === TRUE;
            }
            catch (Exception $e) {
                return FALSE;
            }
    }

    public function deleteMusic ($id) {
        $sql = "DELETE FROM ratings WHERE id = ?";
        $stmt = mysqli_prepare($this->connection, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        try {
            return (mysqli_stmt_execute($stmt) === TRUE);
        }
        catch (Exception $e) {
            return FALSE;
        }
    }

    public function getMusicId($username, $song, $artist) {
        $checkQuery = "SELECT id FROM ratings WHERE username = ? AND artist = ? AND song = ?";
        $stmt = $this->connection->prepare($checkQuery);

        if ($stmt) {
            $stmt->bind_param("sss", $username, $artist, $song);

            try {
                $stmt->execute();
                $result = $stmt->get_result();
            }
            catch (Exception $e) {
                return FALSE;
             } 
        }

    return $result;

    }

    public function createMusic ($username, $artist, $song, $rating) {
        $insertQuery = "INSERT INTO ratings (username, artist, song, rating) VALUES (?, ?, ?, ?)";
        $stmt = $this->connection->prepare($insertQuery);

        if ($stmt) {
            $stmt->bind_param("sssi", $username, $artist, $song, $rating);

            try {
                $stmt->execute();
                return TRUE;
            }
            catch (Exception $e) {
                return FALSE;
             } 
        }

        else {
            return FALSE;
        }
    }
}
