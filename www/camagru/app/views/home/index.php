<?php require 'app/views/includes/header.php';?>
<?php (isset($_SESSION['reset_success'])) ? flashMessage('reset_success','success') : '';?>
<?php require 'app/views/includes/footer.php';?>