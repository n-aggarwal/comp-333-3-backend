<?php
class UserController extends BaseController
{

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
                
                    session_start();

                    $_SESSION["loggedin"] = true;
                    $_SESSION["username"] = $username;

                    $responseData = array(
                        "success" => true,
                        "message" => "Authentication successful",
                        "username" => $username,
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

    public function logoutAction () {

        session_start();
        session_destroy();

        $responseData = array(
            "success" => true,
        );

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );

    }
 
    public function registerAction() {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $jsonData = file_get_contents("php://input");

        // Decode the JSON data into a PHP array
        $data = json_decode($jsonData, true); // Set the second argument to true for an associative array


        if (strtoupper($requestMethod) == 'POST' && isset($data['username']) && isset($data['password']) && isset($data['confirm_password'])) {
            try {

                // Access the values
                $username = $data["username"];
                $password = $data["password"];
                $confirm_password = $data["confirm_password"];


                if ($password !== $confirm_password) {
                    $responseData = array(
                        "success" => false,
                        "message" => "Passwords do not match",
                    );
                }
                else {
                    $userModel = new UserModel();

                    //check if username already in DB
                    $username_check = $userModel->getUserByUsername($username);

                    if (!empty($username_check)) {
                        $responseData = array(
                            "success" => false,
                            "message" => "Username already taken",
                        );
                    }
                    else {
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                        $arrUser = $userModel->createUser($username, $hashed_password);

                        session_start();
    
                        $_SESSION["loggedin"] = true;
                        $_SESSION["username"] = $username;

                        $responseData = array(
                            "success" => true,
                            "message" => "Registration successful",
                            "username" =>  $_SESSION["username"],
                        );
                        
    
                    }

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

}
