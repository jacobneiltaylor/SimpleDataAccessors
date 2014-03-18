<?php
    /*
        SimpleDataAccessors
        Copyright Taylor Networks 2014
    */
        trait sda
        {
            private $host; // DBMS host
            private $user; // Username
            private $cred; // User credential - password or file
            private $port; // Connection port
            private $datasource; // Data Source Name - Database name for example
            
            protected $defaultPort; //Providers must set this to the default port of their datasource
            
            protected $db; // Handle or object variable
            private $statement; // Prepared statement variable
            protected $queryResult; // Result of last query
            
            // All implementations of trait methods are expected to update these values when appropriate. 
            private $errorState; // Boolean value of the success of the last action
            private $errorText; // Error description, if the last action wass unsuccessful
            
            // Generic construction function 
            protected function sdaInit($user, $cred, $name, $port = -1, $host = "localhost")
            {
                $this->host = $host;
                $this->user = $user;
                $this->cred = $cred;
                
                if($port <= 0)
                {
                    $this->port = $port;
                }
                else
                {
                    $this->port = $this->defaultPort;
                }
                
                $this->datasource = $name;
            }
            
            // Use parameters provided in sdaInit to connect
            protected abstract function connect();
            
            // Generic query function
            protected abstract function query(); 
            
            // Prepare a statement for execution
            protected abstract function prepare(); 
            
            // Execute a prepared statement with parameters
            protected abstract function execute(); 
            
            // Close a prepared statement
            protected abstract function close();
            
            // A generator for results
            protected abstract function resultGenerator();
            
            // Did the last action result in an error?
            protected function errorState() 
            {
                return $this->errorState;
            }
            
            // Description of last error, if any.
            protected function errorText()
            {
                return $this->errorText;
            }
            
            // Helper function for successful actions
            protected function setSuccess()
            {
                $this->errorState = false;
                $this->errorText = "";
            }
            
            // Helper function for failed actions
            protected function setFailure($errorText)
            {
                $this->errorState = true;
                $this->errorText = $errorText;
            }
        }
        
        
?>