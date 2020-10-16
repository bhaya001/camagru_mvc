<?php require 'app/views/includes/header.php';?>
<?php (isset($_SESSION['reset_success'])) ? flashMessage('reset_success','success') : '';?>
<?php (isset($_SESSION['edit_success'])) ? flashMessage('edit_success','success') : '';?>
<?php (isset($_SESSION['edit_error'])) ? flashMessage('edit_error','error') : '';?>

<div id="vertical-tabs" >
<input type="hidden" id="tab2show" value="<?=$data['tab']?>">
  <nav class="tabs">
    <div class="tab<?=($data['tab'] == 'edit-profile') ? ' tab-active' : ''?>" onclick="changetab(event, 'edit-profile')">Edit Profile</div>
    <div class="tab<?=($data['tab'] == 'change-pass') ? ' tab-active' : ''?>" onclick="changetab(event, 'change-pass')">Change Password</div>
  </nav>
  <div class="tab-content" id="edit-profile">
    <div class="header">
      
      <div class="img-box img-setting">
        <img class="edit-profile-img" src="<?=(isset($_SESSION['user_profile'])) ? PROOT.$_SESSION['user_profile'] : PROOT.'fonts/default.png'?>" alt="">
        <div id="edit-profile-pic" class="transparent-box trans-setting">
          <div class="caption caption-setting">
            <p class="opacity-low"><i class="fas fa-edit"></i></p>
          </div>
        </div> 
      </div>
      <h2>Edit Profile</h2>
    </div>
    
    <form class="box settings edit" action="<?=PROOT?>users/edit"  method="post" autocomplete="off">
      <span class="for">Name</span><input type="text" name="name" class="input <?=(!empty($data['name_error'])) ? 'is-invalid' : ''?>" value="<?=(empty($data['name'])) ? $_SESSION['user_name'] : $data['name']?>">
      <span class="invalid-feedback"><?=$data['name_error']?></span>
      <span class="for">Login</span><input type="text" name="login" class="input <?=(!empty($data['login_error'])) ? 'is-invalid' : ''?>" value="<?=(empty($data['login'])) ? $_SESSION['user_login'] : $data['login']?>">
      <span class="invalid-feedback"><?=$data['login_error']?></span>
      <span class="for">Email</span><input type="email" name="email" class="input <?=(!empty($data['email_error'])) ? 'is-invalid' : ''?>" value="<?=(empty($data['email'])) ? $_SESSION['user_email'] : $data['email']?>">
      <span class="invalid-feedback"><?=$data['email_error']?></span>
      <div style="margin: 20px;"><span class="for">Notifications</span><label class="container-check"><?=($_SESSION['notif']=='1') ? 'Allowed' : 'Denied'?><input id="chk_bx" name="notif" class="input" type="checkbox" <?=($_SESSION['notif']=='1') ? 'checked' : ''?> value="true"><span class="checkmark"></span></label></div>
      <span class="invalid-feedback"></span>
      <span class="for">Password</span><input type="password" name="password" class="input <?=(!empty($data['pass_error'])) ? 'is-invalid' : ''?>" placeholder="Password">
      <span class="invalid-feedback"><?=$data['pass_error']?></span>
      <input type="hidden" name="csrf" value="<?=$data['csrf']?>">
      <input type="hidden" name="profile" value="<?=$_SESSION['user_profile']?>">
      <input type="button"  onclick="window.location.href='<?=PROOT?>users/settings'" value="Cancel">
      <input type="submit" name="submit" value="Edit">
    </form>
</div>
  <div class="tab-content" id="change-pass">
  
    <div class="header">
      <span class="icon-box">
        <i class="fas fa-user-lock"></i>
      </span>
      <h2>Change Password</h2>
    </div>
    <form class="box settings" action="<?=PROOT?>users/changePassword"  method="post" autocomplete="off">
      <input type="password" name="crtpassword" class="input <?=(!empty($data['crtpass_error'])) ? 'is-invalid' : ''?>" placeholder="Current Password">
      <span class="invalid-feedback"><?=$data['crtpass_error']?></span>
      <input type="password" name="npassword" class="input <?=(!empty($data['npass_error'])) ? 'is-invalid' : ''?>" placeholder="New Password">
      <span class="invalid-feedback"><?=$data['npass_error']?></span>
      <input type="password" name="cnfpassword" class="input <?=(!empty($data['cnfpass_error'])) ? 'is-invalid' : ''?>" placeholder="Confirm Password">
      <span class="invalid-feedback"><?=$data['cnfpass_error']?></span>
      <input type="hidden" name="csrf" value="<?=$data['csrf']?>">
      <input type="submit" name="submit" value="Change">
    </form>
  </div>
</div>
<div id="snapshot" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span id="close">&times;</span>
            <h2>Snapshot</h2>
        </div>
        <div class="modal-body">
        <img src="<?=(isset($_SESSION['user_profile'])) ? PROOT.$_SESSION['user_profile'] : PROOT.'fonts/default.png'?>" style="display:block;" alt="">
        </div>
        <div class="modal-footer">
            <h3>Actions</h3>
            <img id="save" class="btn btn-right" src="<?=PROOT?>fonts/send.png" title="save picture">
            <a id ="download" download="" href=""><img  class="btn btn-right" src="<?=PROOT?>fonts/download.png" title="Download Picture"></a>
            <img id="set-profile" class="btn btn-right" src="<?=PROOT?>fonts/profile.png" title="Make as Profile">
        </div>
    </div>
</div>
<script src="<?=PROOT?>js/settings.js"></script>
<?php require 'app/views/includes/footer.php';?>