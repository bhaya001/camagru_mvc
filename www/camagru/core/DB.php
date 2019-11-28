<?php
  class DB
  {
      private static $_instance = null;
      private $_pdo, $_error = false, $_result, $_json, $_count = 0;

      private function __construct()
      {
        try
        {
          $this->_pdo = new PDO(DSN,DB_USER,DB_PASS);
        } catch (PDOException $e)
        {
          include(ROOT .'/config/setup.php');
          $this->_pdo = $pdo;
        }
      }

      public static function getInstance()
      {
          if(!isset(self::$_instance))
              self::$_instance = new DB();
          return self::$_instance;
      }

      public function query($sql, $params = [])
      {
          $this->_error = false;
          if($this->_query = $this->_pdo->prepare($sql))
          {
            $x = 1;
            if (count($params))
            {
              foreach ($params as $param)
              {
                $this->_query->bindValue($x, $param);
                $x++;
              }
            }
            if ($this->_query->execute())
            {
              $this->_result = $this->_query->fetchAll(PDO::FETCH_OBJ);
              $this->_json = json_encode($this->_result);
              $this->_count = $this->_query->rowCount();
            }
            else
              $this->_error = true;
          }
          return $this;
      }

      public function insert($table, $fields = [])
      {
        $fieldString = '';
        $fieldValue = "";
        $values = [];
        foreach ($fields as $field => $value)
        {
          $fieldString .= '`' . $field .'`,';
          $fieldValue .= "?,";
          $values[] = $value;
        }
        $fieldString = rtrim($fieldString, ',');
        $fieldValue = rtrim($fieldValue, ',');
        $sql = "INSERT INTO {$table} ({$fieldString}) VALUES ({$fieldValue});";
        if(!$this->query($sql, $values)->error())
            return true;
        return false;
      }

      public function update($table, $id, $fields = [])
      {
          $fieldString = "";
          $id_table = rtrim($table,'s');
          $values = [];
          foreach ($fields as $field => $value)
          {
            $fieldString .= ' '. $field . '= ?,';
            $values[] = $value;
          }
          $fieldString = rtrim($fieldString, ',');
          $sql = "UPDATE {$table} SET {$fieldString} WHERE id_{$id_table} = {$id};";
          if(!$this->query($sql, $values)->error())
              return true;
          return false;
      }

      protected function _select($table, $params=[])
      {
          $conditionString = '';
          $bind = [];
          $order = '';
          $limit = '';

          if(isset($params['conditions']))
          {
              if(is_array($params['conditions']))
              {
                  foreach ($params['conditions'] as $condition)
                      $conditionString .= ' ' .$condition .' AND';
                  $conditionString = trim($conditionString);
                  $conditionString = rtrim($conditionString, 'AND');
              }
              else
                  $conditionString = $params['conditions'];
              if($conditionString)
                  $conditionString = ' WHERE '. $conditionString;
          }

          if(array_key_exists('bind',$params))
              $bind = $params['bind'];
          if(array_key_exists('order',$params))
              $order = ' ORDER BY ' . $params['order'];
          if(array_key_exists('limit',$params))
              $limit = ' LIMIT ' . $params['limit'];
          $sql = "SELECT * FROM {$table}{$conditionString}{$order}{$limit}";
          if($this->query($sql, $bind))
          {
            if($this->_result && !count($this->_result))
                return false;
            return true;
          }
          return false;
      }

      public function find($table, $params = [])
      {
          if($this->_select($table, $params))
            return $this->result();
          return false;
      }

      public function getById($table, $id)
      {
        $id_table = rtrim($table,'s');
        $sql = "SELECT * FROM {$table} WHERE id_{$id_table} = $id";
        if(!$this->query($sql)->error())
            return $this->result();
        return false;
      }

      public function delete($table , $id)
      {
        $id_table = rtrim($table,'s');
        $sql = "DELETE FROM {$table} WHERE id_{$id_table} = {$id};";
        if(!$this->query($sql)->error())
            return true;
        return false;
      }

      public function result()
      {
          return $this->_result;
      }

      public function json_result()
      {
        return $this->_json;
      }

      public function count()
      {
        return $this->_count;
      }

      public function error()
      {
        return $this->_error;
      }


  }
