<?php

  define('ROOT', dirname(__FILE__));
  require_once(ROOT .'/config/config.php');
  require_once(ROOT .'/app/lib/helpers/functions.php');
  require_once(ROOT .'/config/database.php');

  function autoload($className)
  {
    if(file_exists(ROOT .'/core/'.$className.'.php'))
      require_once(ROOT .'/core/'.$className.'.php');
    elseif(file_exists(ROOT .'/app/controllers/'.$className.'.php'))
      require_once(ROOT .'/app/controllers/'.$className.'.php');
    elseif(file_exists(ROOT .'/app/models/'.$className.'.php'))
      require_once(ROOT .'/app/models/'.$className.'.php');
  }
  spl_autoload_register('autoload');
  session_start();
  $url = isset($_SERVER['PATH_INFO']) ? explode('/', ltrim($_SERVER['PATH_INFO'],'/')) : [];
  Router::route($url);
