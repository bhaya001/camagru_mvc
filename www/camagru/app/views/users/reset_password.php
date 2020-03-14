<?php require 'app/views/includes/header.php';?>
<form class="box" action="" method = "post" autocomplete="off">
<?php (isset($_SESSION['verif_error'])) ? flashMessage('verif_error','error') : '';?>
<?php (isset($_SESSION['reset_error'])) ? flashMessage('reset_error','error') : '';?>


<?php
    if(empty($data['token']))
    {?>
<input type="email" name="email" class="input <?=(!empty($data['email_error'])) ? 'is-invalid' : ''?>" placeholder="Email" value="<?=$data['email']?>">
<span class="invalid-feedback"><?=$data['email_error']?></span>
<input type="submit" name="submit" value="Send Confirmation">
<div class="links"><a href="<?=PROOT?>users/login">Cancel</a></div>
<?php
    }
    else
    {?>
<input type="password" name="password" class="input <?=(!empty($data['pass_error'])) ? 'is-invalid' : ''?>" placeholder="Password"?>
<span class="invalid-feedback"><?=$data['pass_error']?></span>
<input type="password" name="cpassword" class="input <?=(!empty($data['cpass_error'])) ? 'is-invalid' : ''?>" placeholder="Confirm Password"?>
<span class="invalid-feedback"><?=$data['cpass_error']?></span>
<input type="submit" name="submit" value="Reset Password">
<div class="links"><a href="<?=PROOT?>users/login">Cancel</a></div>
<?php
    }
?>
</form>
<?php require 'app/views/includes/footer.php';?>