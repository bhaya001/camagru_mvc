<?php $this->start('head'); ?>
<?php $this->end(); ?>

<?php $this->start('body'); ?>

<div class="row">
  <div class="col s8 offset-s2">
    <form class="col s12">
      <div class="row">
        <div class="input-field col s6">
          <input id="first_name" type="text">

        </div>
        <div class="input-field col s6">
          <input id="last_name" type="text">

        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <input id="login" type="text">

        </div>
        <div class="input-field col s6">
          <input id="email" type="email">

        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <input id="password" type="password">

        </div>
        <div class="input-field col s6">
          <input id="cpassword" type="password">

        </div>
      </div>

    </form>
  </div>
</div>

  <?php $this->end(); ?>
