<?php
    /*
        SimpleDataAccessors - MySQL Implementation
        Copyright Taylor Networks 2014
    */
    
    require_once '../sda.inc.php';
    
    trait sda_mysql
    {
        use sda;
        
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
            
            if(!$this->db->errno)
            {
                $this->setSuccess();
            }
            else
            {
                $this->setFailure($this->db->error);
            }
            
            return $this->errorState();
        }
        
        protected function preparedStatement()
        {
            
        }
        
        protected function resultGenerator()
        {
            while($result = $this->queryResult->fetch_array())
            {
                yield $result;
            }
        }
    }
?>