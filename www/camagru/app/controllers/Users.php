<?php
/**
 *
 */
class Users extends Controller
{

  function __construct($controller, $action)
  {
      parent::__construct($controller, $action);
      $this->view->setLayout('default');
  }

  public function loginAction()
  {
    $this->view->render('users/login');
  }
  
  public function registerAction()
  {
    $this->view->render('users/register');
  }
}
