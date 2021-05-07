<?php
ob_start();
session_start();
$pageTitle = 'dashboard';

if (isset($_SESSION['Username'])) {
    include "init.php";
    $lim = 5;
    $columns = getLatest('users', 'userid', '*', $lim);

?>
    <div class="home-stat">
        <div class="container text-center">
            <h2 class="text-center">Dashboard</h2>
            <div class="row">
                <div class="col-md-3">
                    <div class="stat comments">
                        Total Comments
                        <span>150</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat items">
                        Pending Members
                        <span>
                            <a href="members.php?do=Manage&page=pending">
                                <?= checkItem('regstatus', 'users', 0); ?>
                            </a>
                        </span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat pending">
                        Total Items
                        <span><?= checkItem('regstatus', 'users', 0); ?></span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat members">
                        Total Members
                        <span>
                            <a href="members.php"> <?php echo checkItem('groupid', 'users', 0); ?></a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="latest">
            <div class="container text-center">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-header">

                                <i class="fa fa-users"></i> Latest <?= $lim ?> registred Users
                            </div>
                            <ul class="list-unstyled latest-users">
                                <?php

                                foreach ($columns as $column) {


                                    echo '<li> ' . $column['username'] . '<a href="members.php?do=Edit&userid=' . $column['userid'] . '" class="btn  rounded-circle btn-outline-primary pull-right" style="padding:.37em .475rem;"><i class="fas fa-user-edit "></i></a><a href="members.php?do=Delete&userid=' . $column['userid'] . '" class="btn  rounded-circle btn-outline-danger confirm pull-right" style="padding:.37em .475rem;"><i class="fas fa-user-times "></i></a>';
                                    if ($column['regstatus'] == 0) {
                                        echo '<a href="members.php?do=Activate&userid=' . $column['userid'] . '" class="btn rounded-circle btn-outline-success" style="padding:.37em .5rem;"><i class="fas fa-user-check " ></i></a>';
                                      }
                                    echo' </li>';
                                }
                                ?>

                            </ul>

                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <i class="fa fa-tag"></i> Latest Items
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Cras justo odio</li>
                                <li class="list-group-item">Dapibus ac facilisis in</li>
                                <li class="list-group-item">Vestibulum at eros</li>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
    require $tpl . 'footer.php';
} else {
    header('Location:index.php');
    exit();
}
ob_end_flush();
?>