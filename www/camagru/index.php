<?php
define('ROOT', dirname(__FILE__));
  require_once('config/config.php');
  require_once('app/lib/helpers/helpers.php');
  require_once('config/database.php');
  function autoload($className)
  {
    if(file_exists('app/core/'.$className.'.php'))
      require_once('app/core/'.$className.'.php');
    elseif(file_exists('app/controllers/'.$className.'.php'))
      require_once('app/controllers/'.$className.'.php');
    elseif(file_exists('app/models/'.$className.'.php'))
      require_once('app/models/'.$className.'.php');
    else
      redirect('home/error404');
  }
  spl_autoload_register('autoload');
  session_start();
  date_default_timezone_set('UTC');
  $db = new DB();
  $url = isset($_SERVER['PATH_INFO']) ? explode('/', ltrim($_SERVER['PATH_INFO'],'/')) : [];
  Router::route($url);