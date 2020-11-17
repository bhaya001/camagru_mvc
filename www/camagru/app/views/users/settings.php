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
    <div id ="head" class="header">
    <?php if(isset($_SESSION['user_profile'])):?>
      <div id="img-box" class="img-box img-setting">
        <a onclick="openpopup(event)"><img class="edit-profile-img" src="<?=PROOT.$_SESSION['user_profile']?>" alt="">
          <span id="action-profile" class="popup-action">
            <i id="del-profile" class="fas fa-trash" onclick="deleteProfile(event)"></i>
            <i id="edit-profile" class="fas fa-edit" onclick="editProfile(event)"></i>
          </span>
        </a>
      </div>
    <?php else:?>
        <span class="profile-setting">
          <i onclick="openpopup(event)" class="icon fas fa-user-circle">
            <span id="action-profile" class="popup-action">
              <i id="edit-profile" class="fas fa-edit" onclick="editProfile(event)"></i>
            </span>
          </i>
        </span>
    <?php endif;?>
      
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
      <input id='csrf' type="hidden" name="csrf" value="<?=$data['csrf']?>">
      <input type="button"  onclick="window.location.href='<?=PROOT?>users/settings'" value="Cancel">
      <input type="submit" name="submit" value="Edit">
    </form>
</div>
  <div class="tab-content" id="change-pass">
  
    <div class="header">
      <span class="icon-box">
        <i class="fas fa-cogs"></i>
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
<script src="<?=PROOT?>js/settings.js"></script>
<?php require 'app/views/includes/footer.php';?>