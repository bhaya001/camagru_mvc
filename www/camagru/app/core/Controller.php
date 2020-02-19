<?php
class Controller
{
  public function model($model)
  {
    require_once ROOT.'/app/models/'.$model.'.php';
    return new $model();
  }

  public function view($view, $data = [])
  {
    if(file_exists(ROOT .'/app/views/' .$view. '.php'))
      require_once(ROOT .'/app/views/' .$view. '.php');
    else
      require_once(ROOT .'/app/views/404Error.php');
  }
}
