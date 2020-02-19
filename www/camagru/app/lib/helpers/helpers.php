<?php
function dnd($data)
{
  echo '<pre>';
  var_dump($data);
  echo '</pre>';
  die();
}
function redirect($page)
{
  header('location:'.PROOT.$page);
}
function randomizer()
{
  $token = openssl_random_pseudo_bytes(64);
  $token = bin2hex($token);
  return $token;
}

function flashMessage($name='', $class = 'success', $message = '')
{
    if(!empty($name))
     {
         if(!empty($message) && empty($_SESSION[$name]))
           $_SESSION[$name] = $message;
         elseif(empty($message) && !empty($_SESSION[$name]))
         {
           echo '<div id="flash"  class="flash '.$class.'">'.$_SESSION[$name].'</div>';
           unset($_SESSION[$name]);
         }
     }
}
function isLoggedIn()
{
  if(isset($_SESSION['user_id']))
    return true;
  return false;
}

function mergePics($sourcefile,$insertfile, $pos = 0)
{
  $dst = imagecreatefrompng(ROOT."/filters/".$insertfile.".png");
  $src = imagecreatefromstring($sourcefile);

  $srcWidth=imagesx($src);
  $srcHeight=imagesy($src);
  $dstWidth=imagesx($dst);
  $dstHeight=imagesy($dst);
  if( $pos == 0 )
  {
    $dst_x = ( $srcWidth / 2 ) - ( $dstWidth / 2 );
    $dst_y = ( $srcHeight / 2 ) - ( $dstHeight / 2 );
  }
  if( $pos == 1 )
  {
    $dst_x = 0;
    $dst_y = 0;
  }

  imagecopy($src, $dst, $dst_x, $dst_y, 0, 0, $dstWidth, $dstHeight);
  ob_start();
  imagejpeg($src);
  $imagedata = ob_get_clean();
  return $imagedata;
}