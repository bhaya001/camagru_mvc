<?php
  /**
   *
   */
  class Model
  {

    protected $_db, $_table, $_modelName, $_softDelete = false, $_columnNames = [];
    public $id;

    public function __construct($table)
    {
      $this->_db = DB::getInstance();
      $this->_table = $table;
      $this->_setTableColumns();
      $this->_modelName
    }
  }
