<?php
 class Router
 {
   public static function route($url)
   {
     $controller = (isset($url[0]) && $url[0] != '') ? ucwords($url[0]) : DEFAULT_CONTROLLER;
     $controller_name = $controller;
     array_shift($url);

     $action = (isset($url[0]) && $url[0] != '') ? $url[0] . 'Action': 'indexAction';
     array_shift($url);
     $dispatch = new $controller($controller_name,$action);
     if(method_exists($controller, $action))
        call_user_func_array([$dispatch,$action], $url); //call a callback with array of params 
     else
        redirect('home/error404');
   }
 }
