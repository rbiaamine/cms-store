<?php

/*title function that echo pagetitle variable if isset else echo default value*/

function getTitle(){
    global $pageTitle;
    if(isset($pageTitle)){
        echo $pageTitle;
    }else{
        echo 'web page';
    }
}

/**
 * Redirect functions v2.0 [This function accept parameter]
 * $errorMsg = echo the error MSG
 * $second = Number of seconds before redirecting
 */

 function redirectHome($msg, $url = null, $second = 3,$url2= 'index.php'){
     
     if($url == null){
         $url = 'index.php';
     } elseif($url2 !== 'index.php'){
        $url = $url2;
     } else {
         $url = isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '' ? $_SERVER['HTTP_REVERER'] : $url2;
        
     }
     echo $msg ;
     header("refresh:$second;url= $url");
     exit();
 }
 /**
  * check items function that verify if exist in database  
  */
  function checkItem($inpToVerified , $table ,$itemIndb){
      global $dbconnection ;
      $statement = $dbconnection->prepare("SELECT $inpToVerified FROM $table WHERE $inpToVerified = ?");
      $statement->execute(array($itemIndb));
      $count = $statement->rowCount();
      return $count;
    }