<?php
class MusicController extends BaseController
{

/**
 * The function is responsible for handling a GET request to retrieve a list of music, with an optional
 * limit parameter, and returning the list as a JSON response.
 */
    public function listAction(){
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();
        if (strtoupper($requestMethod) == 'GET') {
            try {
                $musicModel = new MusicModel();
                $intLimit = 10;
                if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit']) {
                    $intLimit = $arrQueryStringParams['limit'];
                }
                $username = null;
                if (isset($arrQueryStringParams['username']) && $arrQueryStringParams['username']) {
                    $username = $arrQueryStringParams['username'];
                }
                // $arrUsers = $musicModel->getMusic($intLimit);
                $arrUsers = $musicModel->getMusicsbyUsername($username);
                $responseData = json_encode($arrUsers);
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
        // send output 
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    public function deleteAction(){
        session_start();

        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $jsonData = file_get_contents("php://input");

        // Decode the JSON data into a PHP array
        $data = json_decode($jsonData, true); // Set the second argument to true for an associative array

        if (strtoupper($requestMethod) == 'POST' && isset($data['id'])) {
            
            // Access the values
            $id = $data["id"];

            try {
                $musicModel = new MusicModel();

                $username = $_SESSION["username"];
                $song_username = $musicModel->getMusicbyId($id);
                if ($song_username[0]["username"] === $username) {

                    $boolDelete = $musicModel->deleteMusic($id);
                    if ($boolDelete) {
                        $responseData = array("success" => true);
                    }
                    else {
                        $responseData = array("success" => false);
                    }

                    $responseData = json_encode($responseData);
                }
                else {
                    $strErrorDesc = 'Something went wrong! Please contact support. Username doesn\'t match';
                    $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                }
                
            }
            catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        }
        else {
            $strErrorDesc = 'Method not supported/Wrong Params';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output 
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    public function updateAction(){

        session_start();

        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $jsonData = file_get_contents("php://input");

        // Decode the JSON data into a PHP array
        $data = json_decode($jsonData, true); // Set the second argument to true for an associative array

        if (strtoupper($requestMethod) == 'POST' && isset($data['id']) && isset($data['artist'])
            && isset($data['song']) && isset($data['rating'])) {
            
            // Access the values
            $id = $data["id"];
            $artist = $data["artist"];
            $song = $data["song"];
            $rating = $data["rating"];

            try {
                $musicModel = new MusicModel();

                $username = $_SESSION["username"];
                $song_username = $musicModel->getMusicbyId($id);
                //if ($song_username[0]["username"] === $username) {
                    if (true) {
                    $boolUpdate = $musicModel->updateMusic($id, $artist, $song, $rating);
                    if ($boolUpdate) {
                        $responseData = array("success" => true);
                    }
                    else {
                        $responseData = array("success" => false);
                    }

                    $responseData = json_encode($responseData);
                }
                else {
                    $strErrorDesc = 'Something went wrong! Please contact support. Username doesn\'t match';
                    $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                }
                
            }catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        }
        else {
            $strErrorDesc = 'Method not supported/Wrong Params';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output 
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    //this function needs to be tested based on the username session in or out
    public function createAction(){
        session_start();
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if(strtoupper($requestMethod) == 'POST'&& isset($data['id']) && isset($data['artist'])
                && isset($data['song']) && isset($data['rating']) ){
            $musicModel = new MusicModel();

            $postData = json_decode(file_get_contents('php://input'), true);
            //do I need id to be created?
            $id = $postData['id'];
            $song = $postData['song'];
            $artist = $postData['artist'];
            $rating = $postData['rating'];
            try{
                $username = $_SESSION["username"];
                $song_username = $musicModel->getMusicbyId($id);
                if ($song_username[0]["username"] === $username) {
                    if(empty($artist) || empty($song) || empty($rating)){
                        throw new Exception('You need to enter an artist, song and rating to use this feature.');
                    }elseif(is_numeric($rating) == false || (int)$rating != $rating){
                        throw new Exception('You must enter an integers for your rating');
                    }elseif ($rating < 1 OR $rating > 5){
                        throw new Exception ('You must enter an integers between 1 and 5 for your rating.');
                    }else{
                        $musicModel->createMusic($artist, $song, $rating);
                    }
                }
                else{
                    $strErrorDesc = 'Something went wrong! Please contact support. Username doesn\'t match';
                    $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                }
            }catch(Exception $e){
                $strErrorDesc = $e->getMessage();
            }
        }
    }

    public function readAction(){
        session_start();
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'GET') {
            try{
                $musicModel = new MusicModel();

                $postData = json_decode(file_get_contents('php://input'), true);

                $id = $postData['id'];

                $rating = $musicModel->viewRating($id);

                $responseData = json_encode($rating);
            }catch (Error $e){
                $strErrorDesc = $e->getMessage(). 'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Interal Server Error';
            }
        }else{
            $strErrorDesc = "Method not supported";
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
    }

}
