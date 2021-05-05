<?php
session_start();
$pageTitle = 'dashboard';

if (isset($_SESSION['Username'])) {
    include "init.php";


?>
    <div class="home-stat">
        <div class="container text-center">
            <h2 class="text-center">Dashboard</h2>
            <div class="row">
                <div class="col-md-3">
                    <div class="stat">
                        Total Members
                        <span>150</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat">
                        Pending Members
                        <span>15</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat">
                        Total Items
                        <span>5800</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat">
                        Total Members
                        <span>150</span>
                    </div>
                </div>
            </div>
        </div>
            <div class="latest">
                <div class="container text-center">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <i class="fa fa-users"></i>Latest registred Users
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <i class="fa fa-tag"></i>Latest Items
                                </div>
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
}
?>