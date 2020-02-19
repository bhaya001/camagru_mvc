<?php require ROOT.'/app/views/includes/header.php';?>

<form class="box" action="" method = "post" autocomplete="off">
<?php (isset($_SESSION['verif_success'])) ? flashMessage('verif_success','success') : '';?>
<?php (isset($_SESSION['login_error'])) ? flashMessage('login_error','error') : '';?>
<?php (isset($_SESSION['reset_success'])) ? flashMessage('reset_success','success') : '';?>
<?php (isset($_SESSION['register_success'])) ? flashMessage('register_success','success') : '';?>
<input type="password" name="crtpassword" class="input <?=(!empty($data['crtpass_error'])) ? 'is-invalid' : ''?>" placeholder="Current Password">
<span class="invalid-feedback"><?=$data['crtpass_error']?></span>
<input type="password" name="npassword" class="input <?=(!empty($data['npass_error'])) ? 'is-invalid' : ''?>" placeholder="New Password">
<span class="invalid-feedback"><?=$data['npass_error']?></span>
<input type="password" name="cnfpassword" class="input <?=(!empty($data['cnfpass_error'])) ? 'is-invalid' : ''?>" placeholder="Confirm Password">
<span class="invalid-feedback"><?=$data['cnfpass_error']?></span>
<input type="hidden" name="csrf" value="<?=$data['csrf']?>">
<input type="submit" name="submit" value="Change Password">
</form>
<?php require ROOT.'/app/views/includes/footer.php';?>