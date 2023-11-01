<?php

require "/Applications/XAMPP/xamppfiles/htdocs/inc/bootstrap.php";

class Database
{
    protected $connection = null;
/**
 * The function establishes a connection to a MySQL database using the provided server name, username,
 * password, and database name.
 */
    public function __construct()
    {

        require "/Applications/XAMPP/xamppfiles/htdocs/inc/config.php";

        try {
            $this->connection = new mysqli($servername, $username, $password, $dbname);
    	
            if ( mysqli_connect_errno()) {
                throw new Exception("Could not connect to database.");   
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());   
        }			
    }
    
    /**
     * The select function executes a SQL query and returns the result as an associative array.
     * 
     * @param query The "query" parameter is a string that represents the SQL query you want to
     * execute. 
     * @param params The `` parameter allows you to pass parameters that are needed for the query. 
     * These parameters can be used to bind values to placeholders in the query string, which 
     * helps prevent SQL injection attacks.
     * 
     * @return result Result of the query as an associative array.
     */
    
    public function select($query = "" , $params = [])
    {
        try {
            $stmt = $this->executeStatement( $query , $params );
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);				
            $stmt->close();
            return $result;
        } catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }
    }

    /**
     * The function "executeStatement" prepares and executes a SQL query with and
     * returns the statement object.
     * 
     * @param query The "query" parameter is a string that represents the SQL query that you want to
     * execute. 
     * @param params The  parameter is an array that contains the values to be bound to the
     * prepared statement. It should have two elements.
     * 
     * @return stmt result of the executed statement object.
     */
    private function executeStatement($query = "" , $params = [])
    {
        try {
            $stmt = $this->connection->prepare( $query );
            if($stmt === false) {
                throw New Exception("Unable to do prepared statement: " . $query);
            }
            if( $params ) {
                $stmt->bind_param($params[0], $params[1]);
            }
            $stmt->execute();
            return $stmt;
        } catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }	
    }
}