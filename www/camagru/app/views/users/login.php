<?php require 'app/views/includes/header.php';?>

<form class="box" action="" method = "post" autocomplete="off">
<?php (isset($_SESSION['verif_success'])) ? flashMessage('verif_success','success') : '';?>
<?php (isset($_SESSION['login_error'])) ? flashMessage('login_error','error') : '';?>
<?php (isset($_SESSION['reset_success'])) ? flashMessage('reset_success','success') : '';?>
<?php (isset($_SESSION['register_success'])) ? flashMessage('register_success','success') : '';?>
<input type="text" name="login" class="input <?=(!empty($data['login_error'])) ? 'is-invalid' : ''?>" placeholder="Login" value="<?=$data['login']?>">
<span class="invalid-feedback"><?=$data['login_error']?></span>
<input type="password" name="password" class="input <?=(!empty($data['pass_error'])) ? 'is-invalid' : ''?>" placeholder="Password">
<span class="invalid-feedback"><?=$data['pass_error']?></span>
<input type="hidden" name="csrf" value="<?=$data['csrf']?>">
<input type="submit" name="submit" value="SIGN IN">
<div class="links"><a href="<?=PROOT?>users/register">Create new Account</a>|<a href="<?=PROOT?>users/reset">Forgotten Password?</a></div>
</form>
<?php require 'app/views/includes/footer.php';?>