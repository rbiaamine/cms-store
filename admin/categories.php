<?php
session_start();
$pageTitle = 'Categories';

if (isset($_SESSION['Username'])) {
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
?>

    <?php if ($do == 'Manage') : ?>
        <?php
        $ord = isset($_GET['sort']) ? $_GET['sort'] : 'ASC';
        $stmt = $dbconnection->prepare("SELECT * FROM categories ORDER BY catid $ord");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        ?>
        <h2 class="text-center">Manage Categories</h2>
        <div class="container">
            <div class="table-responsive ">
                <div class="btn-group  float-right mb-2" role="group" aria-label="Basic example">
                    <span class="text-right  mr-1 mt-2 text-muted">Sort By <i class="fa fa-sort"></i></span>
                    <a type="button" class="btn btn-outline-secondary <?php if ($ord == 'DESC') {
                                                                            echo 'active';
                                                                        } ?>" href="?sort=DESC">DESC</a>
                    <a type="button" class="btn btn-outline-secondary <?php if ($ord == 'ASC') {
                                                                            echo 'active';
                                                                        } ?>" href="?sort=ASC">ASC</a>
                </div>
                <table class="main-table table text-center table-bordered ">
                    <tr class="border-top-0">
                        <td>#ID</td>
                        <td>name</td>
                        <td>Description</td>
                        <td>Ordering</td>
                        <td>Visibility</td>
                        <td>Allow Comment</td>
                        <td>Allow Ads</td>
                        <td>Control</td>
                    </tr>
                    <?php foreach ($rows as $row) : ?>
                        <tr class="rowcat">
                            <td><?= $row['catid'] ?></td>
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['description'] ?></td>
                            <td><?= $row['ordering'] ?></td>
                            <td><?php if ($row['visibility'] == 0) {
                                    echo '<span class="badge rounded-pill bg-primary text-light">Visible</span>';
                                } else {
                                    echo '<span class="badge rounded-pill bg-secondary text-light">Hidden</span>';
                                }  ?></td>
                            <td><?php if ($row['allow_comment'] == 0) {
                                    echo '<span class="badge rounded-pill bg-primary text-light">Active</span>';
                                } else {
                                    echo '<span class="badge rounded-pill bg-secondary text-light">Disabled</span>';
                                }  ?></td>
                            <td><?php if ($row['allow_ads'] == 0) {
                                    echo '<span class="badge rounded-pill bg-primary text-light">Allowed Ads</span>';
                                } else {
                                    echo '<span class="badge rounded-pill bg-secondary text-light">No Ads</span>';
                                }  ?></td>

                            <td>
                                <a href="?do=Edit&catid=<?= $row['catid']; ?>" class="edit-btn"><i class="fas fa-pen-square"></i></a>
                                <a href="?do=Delete&catid=<?= $row['catid']; ?>" class="delete-btn confirm"><i class="fas fa-trash-alt"></i></a>


                            </td>
                        </tr>
                    <?php endforeach ?>
                </table>
            </div>
            <a href="categories.php?do=Add" class="btn btn-outline-primary rounded-pill "><i class="fa fa-plus"></i> Add New Category</a>
        </div>
    <?php elseif ($do == 'Add') : ?>
        <!-- Add Ctegories pages -->
        <h1 class="text-center text-primary">Add New Category</h1>
        <div class="container p-5">
            <form class="cat-form" method="POST" action="?do=insert">
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Name (<span style="color: red;">*</span>)</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" autocomplete="off" placeholder="Name of Category" required />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Description</label>
                    <div class="col-sm-10">
                        <input type="text" name="description" class="form-control" />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Ordering</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" name="ordering" autocomplete="off" required />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Visibility</label>
                    <div class="col-sm-10">
                        <input id="visible" type="radio" name="vesibility" value="0" checked />
                        <label for="visible">Yes</label>
                        <input id="hidden" type="radio" name="vesibility" value="1" />
                        <label for="hidden">No</label>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Allow Comment</label>
                    <div class="col-sm-10">
                        <input id="com-yes" type="radio" name="comment" value="0" checked />
                        <label for="com-yes">Yes</label>
                        <input id="com-no" type="radio" name="comment" value="1" />
                        <label for="com-no">No</label>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Allow Ads</label>
                    <div class="col-sm-10">
                        <input id="ads-yes" type="radio" name="ads" value="0" checked />
                        <label for="ads-yes">Yes</label>
                        <input id="ads-no" type="radio" name="ads" value="1" />
                        <label for="ads-no">No</label>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-offset-10 col-sm-10">
                        <input type="submit" class="btn btn-outline-primary rounded-pill" value="Add Category">
                    </div>
                </div>
            </form>
        </div>

    <?php elseif ($do == 'insert') : ?>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name           = $_POST['name'];
            $description    = $_POST['description'];
            $ordering       = $_POST['ordering'];
            $visibility     = $_POST['vesibility'];
            $allow_comment  = $_POST['comment'];
            $allow_ads      = $_POST['ads'];
            if (isset($name) && $name !== '') {

                $check = checkItem('name', 'categories', $name);
                echo $check;
                if ($check == 0) {
                    echo 'passer de check';
                    $createCat = $dbconnection->prepare("INSERT INTO categories ( name, description, ordering, visibility, allow_comment, allow_ads) VALUES(:name,:desc,:ordr,:visib,:acom,:aads)
                                                         ");
                    $createCat->bindParam(':name', $name);
                    $createCat->bindParam(':desc', $description);
                    $createCat->bindParam(':ordr', $ordering);
                    $createCat->bindParam(':visib', $visibility);
                    $createCat->bindParam(':acom', $allow_comment);
                    $createCat->bindParam(':aads', $allow_ads);
                    $createCat->execute();
                    //$createCat->execute(array(
                    //    'name' => $name,
                    //    'desc' => $description,
                    //    'ordr' => $ordering,
                    //    'visib'=> $visibility,
                    //    'acom' => $allow_comment,
                    //    'aads' => $allow_ads
                    //));
                    $msg = '<div class=" alert alert-success"> A New Category Has Been Created.</div>';
                    redirectHome($msg, 'back', 3, 'categories.php');
                } else {
                    $msg = '<div class=" alert alert-danger"> Choose An Other Name This Name Is Used</div>';
                    redirectHome($msg, 'back', 3, 'categories.php');
                }
            } else {
                $msg = '<div class=" alert alert-danger"> Name Field Is Required To Create New Category</div>';
                redirectHome($msg, 'back', 3, 'categories.php');
            }
        } else {
            $msg = '<div class=" alert alert-danger"><h2 class="text-center">Redirecting to Homepage . . .</h2></div>';
            redirectHome($msg, 'back');
        }
        ?>

    <?php elseif ($do == 'Edit') : ?>
        <?php
        $catid = (isset($_GET['catid']) && is_numeric($_GET['catid'])) ? intval($_GET['catid']) : 0;
        //select all data on this id    
        $stmt = $dbconnection->prepare('SELECT * FROM categories WHERE catid = ?');
        // execute query    
        $stmt->execute(array($catid));
        //fetch th data    
        $row = $stmt->fetch();
        //check if isset id data row count > 0     
        $count = $stmt->rowCount();
        //if isset usetid & userid > 0 show the form

        ?>

        <h1 class="text-center text-primary">Edit Category</h1>
        <div class="container p-5">
            <form class="cat-form" method="POST" action="?do=update&catid=<?= $_GET['catid'] ?>">
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Name (<span style="color: red;">*</span>)</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" autocomplete="off" placeholder="Name of Category" value="<?= $row['name']; ?>" required />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Description</label>
                    <div class="col-sm-10">
                        <input type="text" name="description" class="form-control" value="<?= $row['description'] ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Ordering</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" name="ordering" autocomplete="off" value="<?= $row['ordering'] ?> " required />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Visibility</label>
                    <div class="col-sm-10">
                        <input id="visible" type="radio" name="vesibility" value="0" <?php if ($row['vesibility'] == 0) {
                                                                                            echo 'checked';
                                                                                        } ?> />
                        <label for="visible">Yes</label>
                        <input id="hidden" type="radio" name="vesibility" value="1" <?php if ($row['vesibility'] == 1) {
                                                                                        echo 'checked';
                                                                                    } ?> />
                        <label for="hidden">No</label>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Allow Comment</label>
                    <div class="col-sm-10">
                        <input id="com-yes" type="radio" name="comment" value="0" <?php if ($row['allow_comment'] == 0) {
                                                                                        echo 'checked';
                                                                                    } ?> />
                        <label for="com-yes">Yes</label>
                        <input id="com-no" type="radio" name="comment" value="1" <?php if ($row['allow_comment'] == 1) {
                                                                                        echo 'checked';
                                                                                    } ?> />
                        <label for="com-no">No</label>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Allow Ads</label>
                    <div class="col-sm-10">
                        <input id="ads-yes" type="radio" name="ads" value="0" <?php if ($row['allow_ads'] == 0) {
                                                                                    echo 'checked';
                                                                                } ?> />
                        <label for="ads-yes">Yes</label>
                        <input id="ads-no" type="radio" name="ads" value="1" <?php if ($row['allow_ads'] == 1) {
                                                                                    echo 'checked';
                                                                                } ?> />
                        <label for="ads-no">No</label>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-offset-10 col-sm-10">
                        <input type="submit" class="btn btn-outline-primary rounded-pill" value="Update Category">
                    </div>
                </div>
            </form>
        </div>


    <?php elseif ($do == 'update') : ?>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $check = checkItem('name', 'categories', $name);
            if ($check == 0) {
                $id = $_GET['catid'];
                $name = $_POST['name'];
                $description = $_POST['description'];
                $ordering = $_POST['ordering'];
                $visibility = $_POST['vesibility'];
                $allow_comment = $_POST['comment'];
                $allow_ads = $_POST['ads'];
                $createCat = $dbconnection->prepare("UPDATE categories SET name = ? ,description = ? ,ordering = ?,visibility = ?,allow_comment = ?,allow_ads = ? WHERE catid = ?");
                $createCat->execute(array($name, $description, $ordering, $visibility, $allow_comment, $allow_ads, $id));

                $mesg = '<div class=" alert alert-success"> Category Has Been Updated</div>';
                redirectHome($mesg, 'back', 3, 'categories.php');
            } else {
                $msg = '<div class=" alert alert-danger"> Category Has Not Updated.</div>';
                redirectHome($msg, 'back', 3, 'categories.php');
            }
        }
        ?>
    <?php elseif ($do == 'Delete') : ?>
        <?php
        $id = isset($_GET['catid']) && is_numeric($_GET['catid']) ? $_GET['catid'] : 0;

        $check = checkItem('catid', 'categories',$id);
        echo $check;
        if ($check > 0) {

            $delete = $dbconnection->prepare("DELETE  FROM categories WHERE catid = :id ");
            $delete->execute(array(
                'id' => $id
            ));
            $msg = '<div class=" alert alert-danger"> Category Has Been Deleted.</div>';
            redirectHome($msg, 'back', 3,'categories.php');
        } else {
            $msg = '<div class=" alert alert-danger">Category Not Found.</div>';
            redirectHome($msg, 'back', 3,'categories.php');
        }

        ?>

    <?php endif ?>
<?php
    include $tpl . 'footer.php';
} else {
    header('Location: index.php');
    exit();
}

?>