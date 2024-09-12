<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
require "/xampp/htdocs/inc/bootstrap.php";

class MusicModel extends Database
{

    public function getMusic($limit)
    {
        return $this->select("SELECT * FROM ratings LIMIT ?", ["i", 4]);
    }

    public function getMusicsbyUsername($name) {
        return $this->select("SELECT * FROM ratings WHERE username = ?",["s",$name]);
    }
    public function getMusicbyId($id) {
        return $this->select("SELECT * FROM ratings WHERE id = ?", ["i", $id]);
    }

    public function createMusic($artist, $song, $rating){
        $check_query = "SELECT id FROM ratings WHERE username = ? AND artist = ? AND song = ?";
        $stmt = $this->connection->prepare($check_query);

        if($stmt){
            mysqli_stmt_bind_param($stmt, "sss", $artist, $song);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if(mysqli_stmt_num_rows($stmt) == 0){
                $sql = "INSERT INTO ratings(artist, song, rating) VALUES (?, ?, ?, ?)";
                $stmt = $this->connection->prepare($sql);
                mysqli_stmt_bind_param($stmt, "sssi",  $artist, $song, $rating);
                mysqli_stmt_execute($stmt);
                echo "Registered Successfully!";
            }
        }
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

    public function viewRating($id){
        $check_query="SELECT username, artist, song, rating FROM ratings WHERE id = ?";
        $stmt = $this->connection->prepare($check_query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $stmt->bind_result($username, $artist, $song, $rating);
            mysqli_stmt_fetch($stmt);

            $data = array(
                "username" => $username,
                "artist" => $artist,
                "song" => $song,
                "rating" => $rating

            );

            $json = json_encode($data);

            header('Content-Type: application/json');

            echo $json;
    }
}
