<?php
/**
 *
 */
class Users extends Controller
{
  public function __construct() {
    $this->user=$this->model('User');
    $this->image=$this->model('Image');
  }
  
  public function indexAction()
  {
    if(isLoggedIn())
    {
      $users = $this->user->getUser($_SESSION['user_id']);
      $data=[
        'users' => $users,
        'page'=>'Profile'
      ];
      $this->view('users/index', $data);
    }
  }

  public function loginAction()
  {
    if(isLoggedIn())
      redirect('home/index');
    else
    {
      if(empty($_SESSION['csrf']))
        $_SESSION['csrf'] = hash_hmac('whirlpool', 'login', randomizer());
      if($_SERVER['REQUEST_METHOD'] == 'POST' && hash_equals($_SESSION['csrf'],$_POST['csrf']))
      {
        $_POST=filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      
        $data=[
          'login' => trim($_POST['login']),
          'password' => trim($_POST['password']),
          'login_error' => '',
          'pass_error'=> '',
          'csrf' => $_POST['csrf'],
          'page'=> 'Log In'
          ];

        if(empty($data['login']))
          $data['login_error'] = 'Please enter your login';
        if(empty($data['password']))
          $data['pass_error'] = 'Please enter your passord';
          
        if(empty($data['login_error']) && empty($data['pass_error']))
        {
          $res = $this->user->findByLogin($_POST['login']);
          if($this->user->findByLogin($_POST['login']))
          {
            $pass = hash('whirlpool', $data['password']);
            if(!(strcmp($pass,$res->password)) && $res->status == 1)
            {
              $_SESSION['user_id'] = $res->id_user;
              $_SESSION['user_name'] = $res->name;
              $_SESSION['notif'] = $res->notif;
              $_SESSION['user_email'] = $res->email;
              $_SESSION['user_login'] = $res->login;
              $_SESSION['user_profile'] = $res->profile;
              unset($_SESSION['csrf']);
              redirect('home/index');
            }
          }
          flashMessage('login_error','error', 'Login or Password invalid');
        }
      
        $this->view('users/login', $data);
      }
      else
      {
        $data=[
          'login' => '',
          'password' => '',
          'login_error' => '',
          'pass_error'=> '',
          'csrf' => $_SESSION['csrf'],
          'page'=> 'Log In'
        ];
        $this->view('users/login',$data);
      }
    }
  }
  
  public function registerAction()
  {
    
    if(isLoggedIn())
      redirect('home/index');
    else
    { 
      if(empty($_SESSION['csrf'])) 
        $_SESSION['csrf'] = hash_hmac('whirlpool', 'register', randomizer());
      if($_SERVER['REQUEST_METHOD'] == 'POST' && hash_equals($_SESSION['csrf'],$_POST['csrf']))
      {
        $_POST=filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $data=[
          'name' => trim($_POST['name']),
          'login' => trim($_POST['login']),
          'email' => trim($_POST['email']),
          'cemail' => trim($_POST['cemail']),
          'password' => trim($_POST['password']),
          'cpassword' => trim($_POST['cpassword']),
          'token'=>randomizer(),
          'name_error' => '',
          'email_error' => '',
          'cemail_error' => '',
          'login_error' => '',
          'pass_error'=> '',
          'cpass_error' => '',
          'csrf' => $_POST['csrf'],
          'page'=> 'Register'
        ];
        if(empty($data['name']))
          $data['name_error'] = 'Please enter your name';
        if(empty($data['email']))
          $data['email_error'] = 'Please enter your email';
        else
        {
          if($this->user->findByEmail($data['email']))
            $data['email_error'] = 'This email is already used!';
          if(!(filter_var($data['email'],FILTER_VALIDATE_EMAIL)))
            $data['email_error'] = 'Please enter a valid email'; 
        }
        if(empty($data['cemail']))
          $data['cemail_error'] = 'Please confirm your email';
        else
        {
          if(strcmp($data['email'],$data['cemail']))
            $data['cemail_error'] = 'The emails do not match';
        }
        if(empty($data['login']))
          $data['login_error'] = 'Please enter your login';
        else
        {
          if(strlen($data['login']) > 8)
            $data['login_error'] = 'Login must have maximum 8 characteres';
          if($this->user->findByLogin($data['login']))
            $data['login_error'] = 'This login is already used!';
        }
        if(empty($data['password']))
          $data['pass_error'] = 'Please enter your password';
        else
        {
          $reg = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[\d])(?=.*[^A-Za-z0-9\s]).{8,}/";
          if(!(preg_match($reg,$data['password'])))
          $data['pass_error'] = 'Enter a combination of at least 8 numbers, uppercase, lowercase and special charracters (such as @,&...).';
        }
        if(empty($data['cpassword']))
          $data['cpass_error'] = 'Please confirm your password';
        else
        {
          if(strcmp($data['password'],$data['cpassword']))
          $data['cpass_error'] = 'the passwords do not match';
        }

        if(empty($data['name_error']) && empty($data['email_error']) && empty($data['cemail_error']) && empty($data['login_error']) 
                                      && empty($data['pass_error']) && empty($data['cpass_error']))
        {
          $data['password'] = hash('whirlpool', $data['password']);
          if($this->user->insert($data))
          {
            $to = $data['email'];
            $subject = "Camagru Account Verification";
            $message = "Welcome to CAMAGRU APPLICATION, Please <a href='".PROOT."users/verify/".$data['token']."'>click here</a> to verify your account.";
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            mail($to,$subject,$message,$headers);
            flashMessage('register_success', 'success', 'Account created, Please check your email to verify your Account.');
            unset($_SESSION['csrf']);
            redirect('users/login');
          }
          else
            dnd($this->user->getError()); //to fix;
        }
        else
          $this->view('users/register', $data);
      }
      else
      {
        $data=[
          'name' => '',
          'email' => '',
          'login' => '',
          'password' => '',
          'cpassword' => '',
          'name_error' => '',
          'email_error' => '',
          'login_error' => '',
          'pass_error'=> '',
          'cpass_error' => '',
          'csrf' => $_SESSION['csrf'],
          'page'=> 'Register'
        ];
        $this->view('users/register', $data);
      }
    }
  }
  
  public function verifyAction($token)
  {
    if($this->user->verify($token))
        flashMessage('verif_success', 'success', 'Your account has been verified you can login now!');
    else
        flashMessage('verif_success', 'success', 'This account is already verified!');
    redirect('users/login');
  }
  public function logoutAction()
  {
    unset($_SESSION['user_id']);
    unset($_SESSION['user_name']);
    unset($_SESSION['user_login']);
    session_destroy();
    redirect('users/login');
  }

  public function resetAction($token = '')
  {
    if(empty($_SESSION['csrf'])) 
        $_SESSION['csrf'] = hash_hmac('whirlpool', 'register', randomizer());
    if(isLoggedIn())
      redirect('home/index');
    elseif($token != '')
    {
      $res = $this->user->findByToken($token);
      $time_duration = 300;
      $expire = ($_SERVER['REQUEST_TIME'] - $_SESSION['time']);
      if(!($res) || !(isset($_SESSION['email'])) || $expire > $time_duration)
      {
        flashMessage('reset_error', 'error', 'Link expired or User not found!');
        unset($_SESSION['time']);
        unset($_SESSION['email']);
        redirect('users/reset');
      }
      else
      {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && hash_equals($_SESSION['csrf'],$_POST['csrf']))
        {
          $_POST=filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
          $data=[
          'token' => $token,
          'password' => trim($_POST['password']),
          'cpassword' => trim($_POST['cpassword']),
          'csrf' => $_POST['csrf'],
          'pass_error' => '',
          'cpass_error' => '',
          ];
          if(empty($data['password']))
            $data['pass_error'] = 'Please enter your password';
          else
          {
            $reg = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[\d])(?=.*[^A-Za-z0-9\s]).{8,}/";
            if(!(preg_match($reg,$data['password'])))
            $data['pass_error'] = 'Enter a combination of at least 8 numbers, uppercase, lowercase and special charracters (such as @,&...).';
          }
          if(empty($data['cpassword']))
            $data['cpass_error'] = 'Please confirm your password';
          else
          {
            if(strcmp($data['password'],$data['cpassword']))
              $data['cpass_error'] = 'the passwords do not match';
          }
          if(empty($data['pass_error']) && empty($data['cpass_error']))
          {
            
            if($expire <= $time_duration)
            {
              $data['password'] = hash('whirlpool', $data['password']);
              $this->user->resetPassword($_SESSION['email'], $data['password']);
              flashMessage('reset_success', 'succes', 'Password Successfully changed');
              redirect('users/login');
            }
            else
            {
              flashMessage('reset_error', 'error', 'Link was expired');
              redirect('users/reset');
            }
            unset($_SESSION['time']);
            unset($_SESSION['csrf']);
            unset($_SESSION['email']);
          }
          else
            $this->view('users/reset_password', $data);  
        }
        else
        {
          $data=[
            'token' => $token,
            'csrf' => $_SESSION['csrf'],
            'email' => '',
            'email_error' => ''
          ];
          $this->view('users/reset_password', $data);
        }
      }
    }
    else
    {  
      if($_SERVER['REQUEST_METHOD'] == 'POST'  && hash_equals($_SESSION['csrf'],$_POST['csrf']))
      {
        $_POST=filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $data=[
          'email' => trim($_POST['email']),
          'csrf' => $_POST['csrf'],
          'email_error' => ''
        ];
        if(empty($data['email']))
          $data['email_error'] = 'Please enter your email';
        else
        {
          if(!(filter_var($data['email'],FILTER_VALIDATE_EMAIL)))
            $data['email_error'] = 'Please enter a valid email'; 
        }
        if(empty($data['email_error']))
        {
          if($this->user->findByEmail($data['email']))
          {
            $res = $this->user->findByEmail($data['email']);
            $to = $data['email'];
            $subject = "Camagru Reset Your Password";
            $message = "Please <a href='".PROOT."users/reset/".$res->token."'>click here</a> to reset your password";
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            mail($to,$subject,$message,$headers);
            $_SESSION['time'] = $_SERVER['REQUEST_TIME'];
            $_SESSION['email'] = $data['email'];
            unset($_SESSION['csrf']);
            flashMessage('verif_success', 'success', 'Please Check your email to reset your password');
            redirect('users/reset');
          }
          else
          {
            flashMessage('verif_error', 'error', 'Account not found!');
            redirect('users/reset');
          }

        }
        else
          $this->view('users/reset_password', $data);
      }
      else
      {
        $data=[
          'email' => '',
          'csrf' => $_SESSION['csrf'],
          'email_error' => ''
        ];
        $this->view('users/reset_password', $data);
      }
    }
  }

  public function settingsAction()
  {
    if(!isLoggedIn())
      redirect('home/index');
    else
    {
      if(empty($_SESSION['csrf'])) 
        $_SESSION['csrf'] = hash_hmac('whirlpool', 'register', randomizer());
      $data=[
        'tab' => 'edit-profile',
        'csrf' => $_SESSION['csrf'],
        'page'=> 'Settings'
      ];
      $this->view("users/settings",$data);
    }
  }

  public function editAction()
  {
    if(!isLoggedIn())
      redirect('home/index');
    else
    {
     
      if(empty($_SESSION['csrf'])) 
        $_SESSION['csrf'] = hash_hmac('whirlpool', 'register', randomizer());
      if($_SERVER['REQUEST_METHOD'] == 'POST' && hash_equals($_SESSION['csrf'], $_POST['csrf']))
      {
        $_POST=filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if($_POST['notif'])
          $notif = 1;
        else
          $notif = 0;
        $data=[
          'tab' =>'edit-profile',
          'name' => trim($_POST['name']),
          'login' => trim($_POST['login']),
          'email' => trim($_POST['email']),
          'password' => trim($_POST['password']),
          'profile' => trim($_POST['profile']),
          'notif' => $notif,
          'csrf' => $_POST['csrf'],
          'name_error' => '',
          'login_error' => '',
          'email_error' => '',
          'pass_error' =>''
        ];
        if($this->user->findByEmail($data['email']) && ($data['email'] != $_SESSION['user_email']))
            $data['email_error'] = 'This email is already used!';
        if(!(filter_var($data['email'],FILTER_VALIDATE_EMAIL)))
            $data['email_error'] = 'Please enter a valid email';
        if(strlen($data['login']) > 8)
            $data['login_error'] = 'Login must have maximum 8 characteres';
        if($this->user->findByLogin($data['login']) && ($data['login'] != $_SESSION['user_login']))
            $data['login_error'] = 'This login is already used!';
        if(empty($data['password']))
            $data['pass_error'] = 'Please enter your password';
        else
        {
          $u = $this->user->getLoggedUser($_SESSION['user_id']);
          if($u)
          {
            $pass = hash('whirlpool', $data['password']);
            if((strcmp($pass,$u->password)))
              $data['pass_error'] = 'Incorrect Password';
          }
        }
        if(empty($data['name_error']) && empty($data['login_error']) && empty($data['login_error']) && empty($data['pass_error']))
        {
          if($this->user->edit($data, $_SESSION['user_id']))
          {
            $_SESSION['user_name'] =$data['name'];
            $_SESSION['user_email'] = $data['email'];
            $_SESSION['user_login'] =$data['login'];
            $_SESSION['notif'] = $data['notif'];
            $_SESSION['user_profile'] = $data['profile'];
            flashMessage('edit_success', 'succes', 'Profile Updated Successfully');
            unset($_SESSION['csrf']);
            redirect('users/settings');
          }
          else
          {
            flashMessage('edit_error', 'error', 'Something went Wrong');
            unset($_SESSION['csrf']);
            redirect('users/settings');
          }
        }
        else
          $this->view('users/settings', $data);

      }
    }
  }
  public function changePasswordAction()
  {
    if(!isLoggedIn())
      redirect('home/index');
    else
    {
      if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
          $_POST=filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
          $data=[
          'tab' =>'change-pass',
          'crtpassword' => trim($_POST['crtpassword']),
          'npassword' => trim($_POST['npassword']),
          'cnfpassword' => trim($_POST['cnfpassword']),
          'csrf' => $_POST['csrf'],
          'crtpass_error' => '',
          'npass_error' => '',
          'cnfpass_error' => ''
          ];
          if(empty($data['crtpassword']))
            $data['crtpass_error'] = 'Please enter your current password';
          else
          {
            $u = $this->user->getLoggedUser($_SESSION['user_id']);
            if($u)
            {
              $pass = hash('whirlpool', $data['crtpassword']);
              if((strcmp($pass,$u->password)))
                $data['crtpass_error'] = 'Incorrect Password';
            }
          }
          if(empty($data['npassword']))
            $data['npass_error'] = 'Please enter your new password';
          else
          {
            $reg = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[\d])(?=.*[^A-Za-z0-9\s]).{8,}/";
            if(!(preg_match($reg,$data['npassword'])))
              $data['npass_error'] = 'Enter a combination of at least 8 numbers, uppercase, lowercase and special charracters (such as @,&...).';
            
          }
          if(empty($data['cnfpassword']))
            $data['cnfpass_error'] = 'Please confirm your new password';
          else
          {
            if(strcmp($data['npassword'],$data['cnfpassword']))
              $data['cnfpass_error'] = 'the passwords do not match';
          }
          if(empty($data['crtpass_error']) && empty($data['npass_error']) && empty($data['cnfpass_error']))
          {
            $npass = hash('whirlpool', $data['npassword']);
            if($this->user->resetPassword($u->email, $npass))
            {
              flashMessage('reset_success', 'succes', 'Password Successfully changed');
              unset($_SESSION['csrf']);
              redirect('users/settings');
            }
            else
            {
              dnd($this->user->getError());
            }
          }
          else
            $this->view('users/settings', $data);  
        }
    }
  }
  
  public function changeProfileAction()
  {
    if(isset($_POST['data']))
    {
      $data = [];
      $img = $imgString = preg_replace('/\s/', '+', $_POST['data']);
      $uniquesavename=time().uniqid(rand());
      $img = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $img));
      $data['path'] = 'uploads/'.$uniquesavename.'.png';
      
      $data['publisher_id'] = $_SESSION['user_id'];
      if($this->image->insertProfile($data))
      {
        file_put_contents($data['path'],$img);
        $_SESSION['user_profile'] = $data['path'];
        $this->user->setProfile($_SESSION['user_id'], $_SESSION['user_profile']);
        echo PROOT.$data['path'];
      }
      else
        echo "problem in insert";
    }
  }
}