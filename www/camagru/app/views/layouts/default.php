<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title><?= $this->siteTitle();?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=PROOT?>css/style.css">
    <?= $this->content('head'); ?>
  </head>
  <body>
    <div class="container">
        <?= $this->content('body'); ?>
    </div>
  </body>
</html>
