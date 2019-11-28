<?php
  class View
  {
      protected $_head, $_body, $_siteTitle = SITE_TITLE, $_outputBuffer, $_layout = DEFAULT_LAYOUT;

      public function __construct()
      {

      }

      public function render($viewName)
      {
        if(file_exists(ROOT .'/app/views/' . $viewName .'.php'))
        {
          include(ROOT .'/app/views/' . $viewName .'.php');
          include(ROOT .'/app/views/layouts/' . $this->_layout .'.php');
        }
        else
        {
          die('The view \"'.$viewName.'\" doesnt exist');
        }
      }

      public function content($type)
      {
        if($type == 'head')
            return $this->_head;
        elseif($type == 'body')
            return $this->_body;
        return false;
      }

      public function start($type)
      {
          $this->_outputBuffer = $type;
          ob_start();
      }

      public function end()
      {
          if($this->_outputBuffer == 'head')
              $this->_head = ob_get_clean();
          elseif($this->_outputBuffer == 'body')
              $this->_body = ob_get_clean();
          else
              die('must start the start method');
      }

      public function siteTitle()
      {
        return $this->_siteTitle;
      }

      public function setSiteTitle($title)
      {
          $this->_siteTitle = $title;
      }

      public function setLayout($path)
      {
          $this->_layout = $path;
      }

  }
