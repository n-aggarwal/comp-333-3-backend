<?php
class UserController extends BaseController
{

/**
 * The function is responsible for handling a GET request to retrieve a list of users, with an optional
 * limit parameter, and returning the list as a JSON response.
 */
    public function listAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();
        if (strtoupper($requestMethod) == 'GET') {
            try {
                $userModel = new UserModel();
                $intLimit = 10;
                if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit']) {
                    $intLimit = $arrQueryStringParams['limit'];
                }
                $arrUsers = $userModel->getUsers($intLimit);
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

/**
 * The loginAction function handles the authentication process for a user logging in, including
 * verifying the username and password, starting a session, and returning a JSON response.
 */
    public function loginAction() {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $jsonData = file_get_contents("php://input");

        // Decode the JSON data into a PHP array
        $data = json_decode($jsonData, true); // Set the second argument to true for an associative array

        // Access the values
        $username = $data["username"];
        $password = $data["password"];

        if (strtoupper($requestMethod) == 'POST' && isset($data['username']) && isset($data['password'])) {
            try {
                $userModel = new UserModel();
                $arrUser = $userModel->getUserByUsername($username);

                if (password_verify($password, $arrUser[0]["password"])) {
                
                    ini_set('session.cookie_lifetime', 20 * 60);
                    session_start();

                    $_SESSION["loggedin"] = true;
                    $_SESSION["username"] = $username;

                    $responseData = array(
                        "success" => true,
                        "message" => "Authentication successful",
                        "user_id" => $username,
                    );
                    
                    $responseData = json_encode($responseData);

                }
                else {
                    $responseData = array(
                        "success" => false,
                        "message" => "Authentication failed",
                    );
                    
                    $responseData = json_encode($responseData);
                }
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

}
