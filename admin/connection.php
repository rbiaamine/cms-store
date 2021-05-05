<?php
$dsn = 'mysql:host=localhost;dbname=store';
$user = 'amine';
$pass = 'amine';
$options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
);
try{
    $dbconnection = new PDO($dsn, $user, $pass, $options);
    $dbconnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
}catch(PDOException $e){
    echo 'failed to connect '. $e->getMessage();
}