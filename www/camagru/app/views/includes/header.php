<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <link rel="shortcut icon" href="#">
    <meta charset="utf-8">
    <title><?=SITE_TITLE?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?=PROOT?>css/style.css">
  </head>
  <body>
    <div class="content">
    <?php
        
        $src = (isset($_SESSION['profile'])) ? PROOT.$_SESSION['profile'] : PROOT.'fonts/default.png';
      ?>
      <div class="topnav" id="myTopnav">
        <div class="navbar-left">
          <a href="<?=PROOT?>home"><img src="<?=PROOT?>fonts/logo.png" alt="logo"></a>
        </div>  
        <div id="menu" class="navbar-right">
          <a class="<?=(($data['page'] == "Gallery") ?'active' :'')?>" href="<?=PROOT?>home">Gallery</a>
          <?php 
           if(isLoggedIn()){
          ?>
          <a class="<?=(($data['page'] == "Camera") ?'active' :'')?>" href="<?=PROOT?>home/edit">Camera</a>
          <div class="dropdown">
            <button class="dropbtn"> 
              <div class="content">
                <img class="<?=(($data['page'] == "Settings") ?'btn-active' :'')?>" id = "menu-profile" src="<?=$src?>" alt="logged-in profile">
                <span><?=$_SESSION['user_name']?><i class="fa fa-caret-down"></i></span>
              </div>
            </button>
            <div class="dropdown-content animate-top">
              <a href="<?=PROOT?>users/settings">Settings</a>
              <a href="<?=PROOT?>users/logout">Logout</a>
            </div>
          </div> 
          <?php
          }
          else{
          ?>
          <a class="<?=(($data['page'] == "Log In") ?'active' :'')?>" href="<?=PROOT?>users/login">Log In</a>
          <a class="<?=(($data['page'] == "Register") ?'active' :'')?>" href="<?=PROOT?>users/register">Register</a>
          <?php
          }
          ?>
          
        </div>
        <a class="icon" onclick="myFunction()">&#9776;</a>
      </div>
      <?php (isset($_SESSION['db_success'])) ? flashMessage('db_success','success') : '';?>
      <?php (isset($_SESSION['db_error'])) ? flashMessage('db_error','error') : '';?>
      
  

      
  
 