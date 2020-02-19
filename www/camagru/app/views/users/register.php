<?php require ROOT.'/app/views/includes/header.php';?>
<form class="box" action="" method = "post" autocomplete="off">
<div class="row">
    <div class="group-input">
        <input type="text" name="name" class="input <?=(!empty($data['name_error'])) ? 'is-invalid' : ''?>" placeholder="Name" value="<?=$data['name']?>">
        <span class="invalid-feedback"><?=$data['name_error']?></span>
    </div>
    <div class="group-input">
        <input type="text" name="login" class="input <?=(!empty($data['login_error'])) ? 'is-invalid' : ''?>" placeholder="Login" value="<?=$data['login']?>">
        <span class="invalid-feedback"><?=$data['login_error']?></span>
    </div>
</div>
<div class="row">
    <div class="group-input">
        <input type="email" name="email" class="input <?=(!empty($data['email_error'])) ? 'is-invalid' : ''?>" placeholder="Email" value="<?=$data['email']?>">
        <span class="invalid-feedback"><?=$data['email_error']?></span>
    </div>
    <div class="group-input">
        <input type="email" name="cemail" class="input <?=(!empty($data['cemail_error'])) ? 'is-invalid' : ''?>" placeholder="Confirm Email">
        <span class="invalid-feedback"><?=$data['cemail_error']?></span>
    </div>
</div>
<div class="row">
    <div class="group-input">
        <input type="password" name="password" class="input <?=(!empty($data['pass_error'])) ? 'is-invalid' : ''?>" placeholder="Password">
        <span class="invalid-feedback"><?=$data['pass_error']?></span>
    </div>
    <div class="group-input">
        <input type="password" name="cpassword" class="input <?=(!empty($data['cpass_error'])) ? 'is-invalid' : ''?>"placeholder="Confirm Password">
        <span class="invalid-feedback"><?=$data['cpass_error']?></span>
    </div>
</div>
<input type="hidden" name="csrf" value = <?=$data['csrf']?>>
<input type="submit" name="submit" value="SIGN UP">
<div class="links"> <a href="<?=PROOT?>users/login">Already Member? Login</a></div>
</form>
<?php require ROOT.'/app/views/includes/footer.php';?>