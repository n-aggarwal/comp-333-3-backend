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
                $arrUsers = $musicModel->getMusic($intLimit);
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
        //TODO
    }

    public function updateAction(){
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
                $boolUpdate = $musicModel->updateMusic($id, $artist, $song, $rating);
                if ($boolUpdate) {
                    $responseData = array("success" => true);
                }
                else {
                    $responseData = array("success" => false);
                }

                $responseData = json_encode($responseData);
                
            }
            catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
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

    public function readAction(){
        //TODO
    }

    public function createAction() {
        //TODO
    }

}
