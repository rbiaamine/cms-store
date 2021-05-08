<?php

/*title function that echo pagetitle variable if isset else echo default value*/

function getTitle()
{
    global $pageTitle;
    if (isset($pageTitle)) {
        echo $pageTitle;
    } else {
        echo 'web page';
    }
}

/**
 * Redirect functions v2.0 [This function accept parameter]
 * $errorMsg = echo the error MSG
 * $second = Number of seconds before redirecting
 */

function redirectHome($msg, $url = null, $second = 3, $url2 = 'index.php')
{

    if ($url == null) {
        $url = 'index.php';
    } elseif ($url == 'back' && $url2 =='index.php') {
        $url = isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '' ? $_SERVER['HTTP_REVERER'] : $url2;
    } else {
        $url = $url2;
    }
    echo $msg;
    header("refresh:$second;url= $url");
    exit();
}
/**
 * check items function that verify if exist in database  
 */
function checkItem($tag, $table, $cond = null)
{
    global $dbconnection;
    $statement = $dbconnection->prepare("SELECT $tag FROM $table WHERE $tag = ?");
    $cond = ($cond == null) ? $tag : $cond;
    $statement->execute(array($cond));
    $count = $statement->rowCount();
    return $count;
}

/**
 * Functions that count number of row of items in databases
 * v1.0 
 */
//function Count($tag, $table)
//{
//    global $dbconnection;
//    $countRow = $dbconnection->prepare("SELECT COUNT($tag) FROM $table");
//    $countRow->execute();
//    return $countRow->fetchColumn();
//}
//
/**
 * Functions that count number of row of items in databases
 * v2.0 
 */
//function rowCount2($tag, $table,$val)
//{
//    global $dbconnection;
//    $countRow = $dbconnection->prepare("SELECT COUNT($tag) FROM $table WHERE $tag = $val");
//    $countRow->execute();
//    return $countRow->fetchColumn();
//}
/**
 * get latest 5 items in databases
 * $table = [table to select from it]
 * $col = [column to select from table]
 * $lim = [number of lines to get]
 * $order = [parameter to order by it  example (userid)]
 */
function getLatest($table,$order, $col = '*',$lim = 5)
{
    global $dbconnection;
    $getStmt = $dbconnection->prepare("SELECT $col FROM $table ORDER BY $order DESC LIMIT $lim");
    $getStmt->execute();
    $rows = $getStmt->fetchAll();
    return $rows;
}
