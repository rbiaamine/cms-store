<?php

/*managee member add|edit|delete| options */

session_start();
$pageTitle = 'Members';
if (isset($_SESSION['Username'])) {
  include 'init.php';
  $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
?>
  <!-- Start Manage page -->
  <?php if ($do == 'Manage') : ?>
    <?php //select users from database
    $stmt = $dbconnection->prepare('SELECT * FROM users');
    $stmt->execute();
    $rows = $stmt->fetchAll();
    ?>
    <!-- Manage Page -->
    <h2 class="text-center">Manage Member</h2>
    <div class="container">
      <div class="table-responsive ">
        <table class="main-table table text-center table-bordered">
          <tr>
            <td>#ID</td>
            <td>Username</td>
            <td>Email</td>
            <td>Full Name</td>
            <td>Date Registration</td>
            <td>Control</td>
          </tr>
          <?php foreach ($rows as $row) : ?>
            <tr>
              <td><?= $row['userid'] ?></td>
              <td><?= $row['username'] ?></td>
              <td><?= $row['email'] ?></td>
              <td><?= $row['fullname'] ?></td>
              <td><?= $row['singup_date'] ?></td>
              <td>
                <a href="?do=Edit&userid=<?= $row['userid']; ?>" class="btn  rounded-circle btn-outline-primary" style="padding:.37em .475rem;"><i class="fas fa-user-edit "></i></a>
                <a href="?do=Delete&userid=<?= $row['userid']; ?>" class="btn  rounded-circle btn-outline-danger confirm" style="padding:.37em .475rem;"><i class="fas fa-user-times "></i></a>
              </td>
            </tr>
          <?php endforeach ?>
        </table>
      </div>
      <a href="members.php?do=Add" class="btn btn-outline-primary rounded-pill "><i class="fa fa-plus"></i> Add New Member</a>
    </div>
    <!-- Add Page -->
  <?php elseif ($do == 'Add') :  ?>

    <h1 class="text-center text-primary">Add Member</h1>
    <div class="container p-5">
      <form method="POST" action="?do=insert">
        <div class="row mb-3">
          <label class="col-sm-2 col-form-label">Username</label>
          <div class="col-sm-10">
            <input type="text" name="username" class="form-control" autocomplete="off">
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-sm-2 col-form-label">Password</label>
          <div class="col-sm-10">
            <input type="password" name="password" class="form-control">
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-sm-2 col-form-label">Email</label>
          <div class="col-sm-10">
            <input type="email" class="form-control" name="email" autocomplete="off">
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-sm-2 col-form-label">Fullname</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" name="fullname" autocomplete="off">
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-sm-offset-10 col-sm-10">
            <input type="submit" class="btn btn-outline-primary rounded-pill" value="insert">
          </div>
        </div>
      </form>
    </div>
    <!-- check if user press submit in form -->
  <?php elseif ($do == 'insert') : ?>
    <?php

    $formErrors = array();
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $username = $_POST['username'];
      $password = $_POST['password'];
      $email = $_POST['email'];
      $fullname = $_POST['fullname'];
      $hashedpass = sha1($password);
      if (empty($_POST['username'])) {
        $formErrors[] = '<div class="alert alert-danger"> username must be entre 3 and 20 caracter</div>';
      }
      if (empty($_POST['password'])) {
        $formErrors[] = '<div class="alert alert-danger"> enter your password </div>';
      }
      if (empty($_POST['email'])) {
        $formErrors[] = '<div class="alert alert-danger"> enter your email</div>';
      }
      if (empty($_POST['fullname'])) {
        $formErrors[] = '<div class="alert alert-danger"> enter your fullname please</div>';
      }
      foreach ($formErrors as $error) {
        echo '<div class="container" >' . $error . '</div>';
      }
      if (empty($formErrors)) {
        $check = checkItem('username', 'users', $username);

        if ($check == 0) {


          $stmt = $dbconnection->prepare("INSERT INTO users(username, password, email, fullname,singup_date) VALUES (:username, :password, :email, :fullname, now())");
          //$stmt->bindParam(':username', $username);
          //$stmt->bindParam(':password', $password);
          //$stmt->bindParam(':email', $email);
          //$stmt->bindParam(':fullname', $fullname);
          $stmt->execute(array(
            'username' => $username,
            'password' => $hashedpass,
            'email'    => $email,
            'fullname' => $fullname
          ));
          $count = $stmt->rowCount();
          echo $count;
          if ($count > 0) {
            $msg = '<div class="container"><div class="alert alert-success"> User Seccessefully registred </div></div>';
            redirectHome($msg, 'members.php',3,'members.php');
          } else {
            $msg = 'not registred';
            redirectHome($msg, 'back'); 
          }
        } else {
          $msg = '<div class="alert alert-danger">' . $username . ' is used choose an other username please :) v2 </div>';
          redirectHome($msg,'back',3,'?do=Manage');
        }
      } else {
        foreach ($formErrors as $error) {
          echo '<div class="container" >' . $error . '</div>';
        }
      }
    } else {
      $msg = 'Sorry you cant browse this page directly';
      redirectHome($msg);
    }

    ?>


  <?php elseif ($do == 'Edit') : ?>
    <?php
    //check if user id is numeric and get it as a int
    $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;
    //select all data on this id    
    $stmt = $dbconnection->prepare('SELECT * FROM users WHERE userid = ?');
    // execute query    
    $stmt->execute(array($userid));
    //fetch th data    
    $row = $stmt->fetch();
    //check if isset id data row count > 0     
    $count = $stmt->rowCount();
    //if isset usetid & userid > 0 show the form

    if ($count > 0) {

    ?>
      <!-- Edit Page -->
      <h1 class="text-center">Edit Member</h1>
      <div class="container p-5">
        <form method="POST" action="?do=update">
          <input type="hidden" name="userid" value="<?= $userid; ?>">
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Username</label>
            <div class="col-sm-10">
              <input type="text" name="username" value="<?= $row['username']; ?>" class="form-control" autocomplete="off" required="required">
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Password</label>
            <div class="col-sm-10">
              <input type="hidden" name="oldpassword" value="<?= $row['password']; ?>" class="form-control">
              <input type="password" name="newpassword" class="form-control">
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
              <input type="email" value="<?= $row['email']; ?>" class="form-control" name="email" autocomplete="off" required="required">
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Fullname</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="fullname" value="<?= $row['fullname']; ?>" autocomplete="off" required="required">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-sm-offset-10 col-sm-10">
              <input type="submit" class="btn btn-outline-primary rounded-pill" value="Add Member">
            </div>
          </div>
        </form>
      </div>
    <?php
      //if there is no such userid show error message 
    } else {
      $msg = 'no usrid';
      redirectHome($msg, 'back');
    } ?>
  <?php elseif ($do == 'update') : ?>
    <?php
    //check if user comming from the form by clicking th submit button
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      //get the form data and make it in variables
      $id = $_POST['userid'];
      $username = $_POST['username'];
      $email = $_POST['email'];
      $fullname = $_POST['fullname'];
      //check if password changed
      $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);
      $formErrors = array();
      if (strlen($username) < 3 || strlen($username) > 20) {
        $formErrors[] = 'username lenght between 3 and 15 caracter';
      }
      if (empty($username)) {
        $formErrors[] = 'enter you username';
      }
      if (empty($email)) {
        $formErrors[] = 'enter you email';
      }
      if (empty($fullname)) {
        $formErrors[] = 'enter you full name';
      }


      foreach ($formErrors as $error) {
        echo ' <div class="container">
        <div class="alert alert-danger">' . $error . '</div>
        </div>';
      }
      $msg = 'redirection . . .';
      redirectHome($msg);

      //update the data in the database
      $stmt = $dbconnection->prepare("UPDATE users SET username = ?, password = ? ,email = ? ,fullname = ? WHERE userid = ? ");
      $stmt->execute(array($username, $pass, $email, $fullname, $id));
      echo '<div class="container"> <div class="alert alert-success" >' . $stmt->rowCount() . ' record updated </div></div>';
      //header('Location:dashboard.php');
    } else {
      $msg = 'permission denieded';
      redirectHome($msg);
    }
    ?>
  <?php elseif ($do == 'Delete') : ?>
    <?php
    $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;
    //select all data on this id    
    //$stmt = $dbconnection->prepare('SELECT * FROM users WHERE userid = ?');
    //// execute query    
    //$stmt->execute(array($userid));
//
    ////check if isset id data row count > 0     
    //$count = $stmt->rowCount();
    ////if isset usetid & userid > 0 show the form
    $check = checkItem($userid,'users',$userid);
    if ($check > 0) {
      $stmt = $dbconnection->prepare("DELETE FROM users WHERE userid = :userid");
      $stmt->bindParam(':userid', $userid);
      $stmt->execute();
      $msg = '<div class="alert alert-danger">' . $count . ' User Have Been Deleted.</div>';
      redirectHome($msg,'back',3 ,'?do=Manage');
    } else {
      $msg =  'this id is not exist?';
      redirectHome($msg,'back');
    }
    ?>
  <?php endif ?>
<?php include $tpl . 'footer.php';
} else {
  header('Location: index.php');
  exit();
}
?>