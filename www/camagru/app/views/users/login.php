<?php $this->start('head'); ?>
<?php $this->end(); ?>

<?php $this->start('body'); ?>

  <div class="col-md-6 offset-md-3 card card-body bg-light">
    <form class="form" action="<?=PROOT?>users/login" method="post">
      <h2 class="text-center">Sign-In</h2>
      <div class="form-group">
        <label for="login">Login</label>
        <input class="form-control" id="login" name="login" type="text">
      </div>
      <div class="form-group">
        <label for="login">Password</label>
        <input class="form-control" id="password" name="password" type="password">
      </div>
      <div class="form-group">
        <label for="remember"><input id="remember" name="remember" type="checkbox" value="on"> Remember Me</label>
      </div>
      <div class="form-group">
        <input class="btn btn-large btn-success" type="submit" value="Login"></label>
      </div>
      <div class="text-right">
        <a href="<?=PROOT?>users/register">Sign-Up</a>
      </div>

    </form>
  </div>

<?php $this->end(); ?>
