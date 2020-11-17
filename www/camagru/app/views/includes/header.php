<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <link rel="shortcut icon" href="#">
    <meta charset="utf-8">
    <title><?=SITE_TITLE?></title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css">
    <link rel="stylesheet" href="<?=PROOT?>css/style.css">
    <?php
      if($data['page'] == "Image Details"){
    ?>
        <link rel="stylesheet" href="<?=PROOT?>css/comments.css">
      <?php
    }
    ?>

  </head>
  
  <body>
    <div class="wrapper">
    <?php
        $src = (isset($_SESSION['user_profile'])) ? PROOT.$_SESSION['user_profile'] : '';
      ?>
      <div class="topnav" id="myTopnav">
        <div class="navbar-left">
          <a href="<?=PROOT?>"><img src="<?=PROOT?>fonts/logo.png" alt="logo"></a>
        </div>  
        <div id="menu" class="navbar-right">
          <a class="<?=(($data['page'] == "Gallery") ?'active' :'')?>" href="<?=PROOT?>">Gallery</a>
          <?php 
           if(isLoggedIn()){
          ?>
          <a class="<?=(($data['page'] == "Camera") ?'active' :'')?>" href="<?=PROOT?>images/camera">Camera</a>
          <div class="dropdown">
            <button class="dropbtn"> 
              <div id="profile-content" class="content">
              <?php if ($src) :?>
                <img class="<?=(($data['page'] == "Settings") ?'btn-active' :'')?>" id = "menu-profile" src="<?=$src?>" alt="logged-in profile">
              <?php else :?>
                <i class="<?=(($data['page'] == "Settings") ?'i-active' :'')?> fas fa-user-circle" id = "menu-profile"></i>
              <?php endif;?>
                <span class="<?=(($data['page'] == "Settings") ?'btn-span-active' :'')?>"><?=$_SESSION['user_name']?><i class="fas fa-caret-down"></i></span>
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
        <a class="icon" onclick="responsiveMenu()">&#9776;</a>
      </div>
      <div id="main" class="main">
        <?php (isset($_SESSION['db_success'])) ? flashMessage('db_success','success') : '';?>
        <?php (isset($_SESSION['db_error'])) ? flashMessage('db_error','error') : '';?>
     

      
  

      
  
 