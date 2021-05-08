<?php

/*category => [ Manage|Edit|...|...|] */
$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

//if the page is the main page

if ($do == 'Manage') {
    echo 'Welcome You are in manage category';
    echo '<a href="?do=Insert">Add New Category + </a>';
} elseif($do == 'Add'){
    echo 'Welcome You are in Add category';    

} elseif($do == 'Insert'){
    echo 'Welcome You are in insert category';    

}else{
    echo 'Welcome There are no page with this name';    
}
