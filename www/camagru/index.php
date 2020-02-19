<?php
  define('ROOT', dirname(__FILE__));
  require_once(ROOT .'/config/config.php');
  require_once(ROOT .'/app/lib/helpers/helpers.php');
  require_once(ROOT .'/config/database.php');
  function autoload($className)
  {
    if(file_exists(ROOT .'/app/core/'.$className.'.php'))
      require_once(ROOT .'/app/core/'.$className.'.php');
    elseif(file_exists(ROOT .'/app/controllers/'.$className.'.php'))
      require_once(ROOT .'/app/controllers/'.$className.'.php');
    elseif(file_exists(ROOT .'/app/models/'.$className.'.php'))
      require_once(ROOT .'/app/models/'.$className.'.php');
    else
      redirect('home/error404');
  }
  spl_autoload_register('autoload');
  session_start();
  date_default_timezone_set('UTC');
  $db = new DB();
  $url = isset($_SERVER['PATH_INFO']) ? explode('/', ltrim($_SERVER['PATH_INFO'],'/')) : [];
  Router::route($url);