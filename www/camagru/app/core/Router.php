<?php
 class Router
 {
   public static function route($url)
   {
     $controller = (isset($url[0]) && $url[0] != '') ? ucwords($url[0]) : DEFAULT_CONTROLLER;
     array_shift($url); //to unset the first element from $url
     $action = (isset($url[0]) && $url[0] != '') ? $url[0] . 'Action': 'galleryAction';
     array_shift($url);
     $controller = new $controller();
     if(method_exists($controller, $action)) //to check if the method $action exist in the $controller class
        call_user_func_array([$controller,$action], $url); //to pass params in $url to actions method in controller class
     else
        redirect('images/error404');
   }
 }
