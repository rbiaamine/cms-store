<?php
session_start();
function navItem(string $tag, string $lien): string
{
  $class = 'nav-link ';

  if ($lien === $_SERVER['SCRIPT_NAME']) {
    $class .= ' active';
  }

  return <<<HTML
     <li class = "nav-item">
          <a class=" $class " aria-current="page" href=" $lien "> $tag </a>
        </li>
HTML;
}




?>
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-5">
  <a class="navbar-brand" href="#"><?php echo lang('HOME_ADMIN'); ?></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0 mx-auto">
      <?= navItem(lang('CATEGORIES'), '/ecom/admin/dashboard.php') ?>
      <?= navItem(lang('ITEMS'), '/ecom/admin/category.php') ?>
      <?= navItem(lang('MEMBERS'), '/ecom/admin/members.php') ?>
      <?= navItem(lang('STATISTICS'), '/ecom/admin/index.php') ?>
      <?= navItem(lang('LOGS'), '/ecom/admin/index.php') ?>
     
      <li class="nav-item dropdown ">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?= $_SESSION['Username'] ?>
        </a>
        <div class="dropdown-menu p-0" aria-labelledby="navbarDropdown">
      <a class="dropdown-item m-0" href="members.php?do=Edit&userid=<?= $_SESSION['Id'] ?>">Edit Profile</a> 
      <a class="dropdown-item m-0" href="#">Another action</a> 
      <a class="dropdown-item m-0" href="logout.php">Log out</a> 
  </div>
  </li>
  </ul>
  </div>

</nav>