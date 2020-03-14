<?php require 'app/views/includes/header.php';?>
<?php (isset($_SESSION['verif_success'])) ? flashMessage('verif_success','success') : '';?>
<?php (isset($_SESSION['login_error'])) ? flashMessage('login_error','error') : '';?>
<?php (isset($_SESSION['reset_success'])) ? flashMessage('reset_success','success') : '';?>
<?php (isset($_SESSION['register_success'])) ? flashMessage('register_success','success') : '';?>
<div id="vertical-tabs">
  <nav class="tabs">
    <div class="tab<?=($data['tab'] == 'edit-profile') ? ' tab-active' : ''?>" onclick="changetab(event, 'edit-profile')">Edit Profile</div>
    <div class="tab<?=($data['tab'] == 'change-pass') ? ' tab-active' : ''?>" onclick="changetab(event, 'change-pass')">Change Password</div>
    <div class="tab<?=($data['tab'] == 'preferences') ? ' tab-active' : ''?>" onclick="changetab(event, 'preferences')">Preferences</div>
  </nav>
  <div class="tab-content" id="edit-profile">
    <h2>Edit Profile</h2>
</div>
  <div class="tab-content" id="change-pass">
    <h2>Change Password</h2>
    <form class="box settings" action="<?=PROOT?>users/changePassword"  method="post" autocomplete="off">
      <input type="password" name="crtpassword" class="input <?=(!empty($data['crtpass_error'])) ? 'is-invalid' : ''?>" placeholder="Current Password">
      <span class="invalid-feedback"><?=$data['crtpass_error']?></span>
      <input type="password" name="npassword" class="input <?=(!empty($data['npass_error'])) ? 'is-invalid' : ''?>" placeholder="New Password">
      <span class="invalid-feedback"><?=$data['npass_error']?></span>
      <input type="password" name="cnfpassword" class="input <?=(!empty($data['cnfpass_error'])) ? 'is-invalid' : ''?>" placeholder="Confirm Password">
      <span class="invalid-feedback"><?=$data['cnfpass_error']?></span>
      <input type="hidden" name="csrf" value="<?=$data['csrf']?>">
      <input type="submit" name="submit" value="Change Password">
    </form>
  </div>
  <div class="tab-content" id="preferences">
    <h2>Preferences</h2>
    <p>Tab 3 Content</p>
  </div>
</div>
<?php require 'app/views/includes/footer.php';?>