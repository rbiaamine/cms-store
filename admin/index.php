<?php 
session_start();
if(isset($_SESSION['Username'])){
    //header('Location:dashboard.php');
    header('Location:dashboard.php');  
}
$noNavbar = '';
$pageTitle = 'login';

include "init.php";
//check if user comming from HTTP POST  Request
$message ='<div class="alert alert-success">Enter Your Informations</div>';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = $_POST['user'];
    $password = $_POST['pass'];
    $hashedpass = sha1($password);
    
    
    #check if user exist in databases ;
    $stmt = $dbconnection->prepare('SELECT userid,username,password  FROM users WHERE username = ? AND password = ? AND groupid = 1 LIMIT 1');
    $stmt->execute(array($username,$hashedpass));
    $row = $stmt->fetch();
    $login = $stmt->rowCount();
    if($login > 0){
        $_SESSION['Username'] = $username;
        $_SESSION['Id'] = $row['userid'];
        header('Location:dashboard.php');
        exit();
    }else{
        $message = ' <div class="alert alert-danger"> new member? register.</div> ';
    }
}
?>

<form  class="login" action="<?php $_SERVER['PHP_SELF']?>" method="POST">
    <?php  echo $message; ?>
 
    <input type="text" class="form-control" name ="user" placeholder="Username" autocomplete="">
    <input type="password" class="form-control" name ="pass" placeholder="Password" autocomplete="new-password">
    <input type="submit" class="btn btn-primary btn-block" value="login">
</form>

<?php include $tpl . "footer.php"; ?>