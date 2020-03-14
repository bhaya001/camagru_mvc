<?php
  class DB
  {
      private static $_instance = null;
      private $_pdo, $_error, $_stmt;

      public function __construct()
      {
        try
        {
          $this->_pdo = new PDO(DSN,DB_USER,DB_PASS);
        } catch (PDOException $e)
        {
          include('config/setup.php');
          $this->_pdo = $pdo;
        }
      }
      
      public function query($sql)
      {
          $this->_stmt = $this->_pdo->prepare($sql);
      }
      public function bind($param, $value, $type=null)
      {
        if(is_null($type))
          {
            switch(true)
            {
              case is_int($value):
                $type = PDO::PARAM_INT;
                break;
              case is_null($value):
                $type = PDO::PARAM_NULL;
                break;
              case is_bool($value):
                $type = PDO::PARAM_BOOL;
                break;
              default :
                $type = PDO::PARAM_STR;
            }
          }
          $this->_stmt->bindValue($param, $value, $type);
      }

      public function execute()
      {
        return $this->_stmt->execute();
      }

      public function rows()
      {
        return $this->_stmt->rowCount();
      }

      public function getResults()
      {
        $this->execute();
        return $this->_stmt->fetchAll(PDO::FETCH_OBJ);
      }

      public function getFirst()
      {
        $this->execute();
        return $this->_stmt->fetch(PDO::FETCH_OBJ);
      }
      public function error()
      {
        return $this->_stmt->errorInfo();
      }


  }
