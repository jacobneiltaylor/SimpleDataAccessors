<?php
    /*
        SimpleDataAccessors - MySQL Implementation
        Copyright Taylor Networks 2014
    */
    
    require_once 'dirname(__FILE__) . '/../sda.inc.php';
    
    trait sda_mysql
    {
        use sda;
        
        protected $defaultPort = 3306;
        
        protected function connect()
        {
            $this->db = new mysqli($this->host, $this->user, $this->cred, $this->datasource, $this->port);
            
            if (!$this->db->connect_errno)
            {
                $this->setSuccess();
            }
            else
            {
                $this->setFailure(!$this->db->connect_error);
            }
            
            return $this->errorState();
        }
        
        protected function query($sql)
        {
            $this->queryResult = $this->db->query($sql);
            
            $this->errorPassthrough();
            
            return $this->errorState();
        }
        
        protected function prepare($sql)
        {
            if(strpos($sql, '?') === false)
            {
                $this->setFailure("SDA Error - prepare(): No argument insertion points in query.");
            }
            else
            {
                $this->statement = $this->db->prepare($sql);
                $this->errorPassthrough();
            }
            
            return $this->errorState();
        }
        
        protected function execute()
        {
            $argCount = func_num_args();
            
            if($argCount > 0)
            {
                if($this->statement->param_count = $argCount)
                {
                    $argArray = func_get_args();
                    $error = false;
                    $bindArgs[0] = "";
                    
                    for($i = 1; i <= $argCount && !error; $i++)
                    {
                        $class = classifyArg($argArray[$i-1]);
                        
                        if($class === false)
                        {
                            $error = true;
                            $this->setFailure("SDA Error - execute(): Invalid argument at position ".$i.".");
                        }
                        else
                        {
                            $bindArgs[0] .= $class;
                            $bindArgs[$i] = $argArray[$i-1];
                        }
                    }
                    
                    if(!error)
                    {
                        call_user_func_array(array($this->statement, 'execute'),$bindArgs);
                        $this->statement->execute();
                        
                        $this->errorPassthrough();
                        
                        $this->queryResult = $this->statement->getResult();                    
                    }
                }
                else
                {
                    $this->setFailure("SDA Error - execute(): Argument/parameter count mismatch.");
                }
            }
            else
            {
                $this->setFailure("SDA Error - execute(): Not enough arguments.");
            }
            
            return $this->errorState();
        }
        
        
        
        protected function resultGenerator()
        {
            while($result = $this->queryResult->fetch_array())
            {
                yield $result;
            }
        }
        
        //Helper function for building bind strings
        private function classifyArg($arg)
        {
            $type = gettype($arg);
            $retval = true; //Prime a bad return value
            
            switch ($type)
            {
                case "boolean":
                case "integer":
                    $retval = "i";
                    break;
                
                case "double":
                    $retval = "d";
                    break;
                 
                case "string":
                    $retval = "s";
            }
            
            return $retval;
        }
        
        // Helper function for passing through driver errors 
        private function errorPassthrough()
        {
            if(!$this->db->errno)
            {
                $this->setSuccess();
            }
            else
            {
                $this->setFailure("Driver error: ".$this->db->error);
            }
        }
    }
?>
