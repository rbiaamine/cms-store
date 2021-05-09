<?php
session_start();
$pageTitle = '';
if (isset($_SESSION['Username'])) {
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
?>
    <?php if ($do == 'Manage') : ?>
        <?php
        $query = $dbconnection->prepare("SELECT items.*,users.username AS seller,categories.name AS category FROM items INNER JOIN users ON users.userid = items.user_id INNER JOIN categories ON categories.catid = items.cat_id");
        $query->execute();
        $rows = $query->fetchAll();

        ?>
        <!-- Manage Page -->
        <h2 class="text-center">Manage Product</h2>

        <div class="table-responsive ">
            <table class="main-table table text-center table-bordered">
                <tr>
                    <td>#ID</td>
                    <td>name</td>
                    <td>Description</td>
                    <td>Price</td>
                    <td>Add Date</td>
                    <td>Image</td>
                    <td>Status</td>
                    <td>Rating</td>
                    <td>Category</td>
                    <td>Seller</td>
                    <td>Control</td>
                </tr>
                <?php foreach ($rows as $row) : ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['name'] ?></td>
                        <td><?= $row['description'] ?></td>
                        <td><?= $row['price'] ?></td>
                        <td><?= $row['add_date'] ?></td>
                        <td><?= $row['country_made'] ?></td>
                        <td><?= $row['status'] ?></td>
                        <td><?= $row['rating'] ?></td>
                        <td><?= $row['category'] ?></td>
                        <td><?= $row['seller'] ?></td>
                        <td>
                            <a href="?do=Edit&id=<?= $row['id']; ?>" class="btn  rounded-circle btn-outline-primary" style="padding:.37em .475rem;"><i class="fas fa-user-edit "></i></a>
                            <a href="?do=Delete&id=<?= $row['id']; ?>" class="btn  rounded-circle btn-outline-danger confirm" style="padding:.37em .475rem;"><i class="fas fa-user-times "></i></a>

                        </td>
                    </tr>
                <?php endforeach ?>
            </table>
        </div>
        <a href="items.php?do=Add" class="btn btn-outline-primary rounded-pill "><i class="fa fa-plus"></i> Add New Product</a>

    <?php elseif ($do == 'Add') : ?>
        <h1 class="text-center text-primary">Add New Product</h1>
        <div class="container p-5">
            <form class="cat-form" method="POST" action="?do=Insert">
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Product Name(<span style="color: red;">*</span>)</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" placeholder="Name of Product" />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Product Description(<span style="color: red;">*</span>)</label>
                    <div class="col-sm-10">
                        <input type="text" name="description" class="form-control" placeholder="Describe The Product" />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Price(<span style="color: red;">*</span>) <span class="badge badge-primary">€</span></label>
                    <div class="col-sm-10">
                        <input type="number" name="price" class="form-control" placeholder="price of Product" />

                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Country(<span style="color: red;">*</span>)</label>
                    <div class="col-sm-10">
                        <input type="text" name="country" class="form-control" placeholder="The Country In Which The Product is Made" />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Statu(<span style="color: red;">*</span>)</label>
                    <div class="col-sm-10">
                        <select name="status" class="form-control">
                            <option value="0">...</option>
                            <option value="1">New</option>
                            <option value="2">Like New</option>
                            <option value="3">Rebufished</option>
                            <option value="4">Used & still active</option>
                            <option value="5">Damaged</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Rating</label>
                    <div class="col-sm-10">
                        <select name="rate" class="form-control">
                            <option value="0">...</i></option>
                            <option value="1">*</option>
                            <option value="2">**</option>
                            <option value="3">***</option>
                            <option value="4">****</option>
                            <option value="5">*****</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Seller</label>
                    <div class="col-sm-10">
                        <select name="seller" class="form-control" placeholder="Seller name">
                            <option value="0">...</i></option>
                            <?php
                            $cat = $dbconnection->prepare("SELECT * FROM users");
                            $cat->execute();
                            $options = $cat->fetchAll();
                            foreach ($options as $option) {
                                echo ' <option value="' . $option['userid'] . '">' . $option['username'] . '</i></option>';
                            }

                            ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Category</label>
                    <div class="col-sm-10">
                        <select name="category" class="form-control">
                            <option value="0">...</i></option>
                            <?php
                            $cat = $dbconnection->prepare("SELECT * FROM categories");
                            $cat->execute();
                            $options = $cat->fetchAll();
                            foreach ($options as $option) {
                                echo ' <option value="' . $option['catid'] . '">' . $option['name'] . '</i></option>';
                            }

                            ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-offset-10 col-sm-10">
                        <input type="submit" class="btn btn-outline-primary rounded-pill" value="Add New Product">
                    </div>
                </div>
            </form>
        </div>
    <?php elseif ($do == 'Insert') : ?>
        <?php
        $errForm = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo 'insert';
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $country = $_POST['country'];
            $status = $_POST['status'];
            $rate = $_POST['rate'];
            $category = $_POST['category'];
            $seller = $_POST['seller'];

            if (empty($name)) {
                $errForm[] = '<div class="alert alert-danger"> Name fild is empty.</div>';
            }
            if (empty($description)) {
                $errForm[] = '<div class="alert alert-danger"> Description fild is empty.</div>';
            }
            if (empty($price)) {
                $errForm[] = '<div class="alert alert-danger"> Price fild is empty.</div>';
            }
            if (empty($country)) {
                $errForm[] = '<div class="alert alert-danger"> Country fild is empty.</div>';
            }
            if ($status == 0) {
                $errForm[] = '<div class="alert alert-danger"> Status fild is empty.</div>';
            }
            if ($category == 0) {
                $errForm[] = '<div class="alert alert-danger"> Category fild is empty.</div>';
            }
            if ($seller == 0) {
                $errForm[] = '<div class="alert alert-danger"> Seller fild is empty.</div>';
            }
            if (empty($errForm)) {
                $add = $dbconnection->prepare("INSERT INTO 
                items (name,description,price,country_made,status,rating,cat_id,user_id) 
                VALUE (:name,:description,:price,:country,:status,:rate,:category,:seller)");
                $add->execute(array(
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'country' => $country,
                    'status' => $status,
                    'rate' => $rate,
                    'category' => $category,
                    'seller' => $seller

                ));
                $msg = '<div class="alert alert-success"> Product Has Seccessfuly Added.</div>';
                redirectHome($msg, 'items.php', 3, 'items.php');
            } else {
                foreach ($errForm as $err) {
                    echo $err;
                }
                redirectHome('', 'back', 3, 'items.php?do=Add');
            }
        } else {
            $msg = '<div class="alert alert-danger"> Authentication is Required.</div>';
            redirectHome($msg);
        }
        ?>
    <?php elseif ($do == 'Edit') : ?>
        <?php
        //check if user id is numeric and get it as a int
        $id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? intval($_GET['id']) : 0;
        //select all data on this id    
        $stmt = $dbconnection->prepare('SELECT items.*,categories.name AS category,users.username AS seller FROM items INNER JOIN categories ON categories.catid = items.cat_id INNER JOIN users ON users.userid = items.user_id WHERE id = ?');
        // execute query    
        $stmt->execute(array($id));
        //fetch th data    
        $row = $stmt->fetch();
        //check if isset id data row count > 0     
        $count = $stmt->rowCount();
        //if isset usetid & userid > 0 show the form

        if ($count > 0) {

        ?>
            <!-- Edit Page -->
            <h1 class="text-center text-primary">Edit Product</h1>
            <div class="container p-5">
                <form class="cat-form" method="POST" action="?do=Update">
                <input type="hidden" name="id" value="<?= $id; ?>">
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Product Name(<span style="color: red;">*</span>)</label>
                        <div class="col-sm-10">
                            <input type="text" name="name" value="<?= $row['name']; ?>" class="form-control" placeholder="Name of Product" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Product Description(<span style="color: red;">*</span>)</label>
                        <div class="col-sm-10">
                            <input type="text" name="description" class="form-control" value="<?= $row['description']; ?>" placeholder="Describe The Product" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Price(<span style="color: red;">*</span>) <span class="badge badge-primary">€</span></label>
                        <div class="col-sm-10">
                            <input type="number" name="price" class="form-control" value="<?= $row['price']; ?>" placeholder="price of Product" />

                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Country(<span style="color: red;">*</span>)</label>
                        <div class="col-sm-10">
                            <input type="text" name="country" class="form-control" value="<?= $row['country_made']; ?>" placeholder="The Country In Which The Product is Made" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Statu(<span style="color: red;">*</span>)</label>
                        <div class="col-sm-10">
                            <select name="status" class="form-control">
                                <option value="0">...</option>
                                <option value="1" <?php if ($row['status'] == 1) {
                                                        echo 'selected';
                                                    } ?>>New</option>
                                <option value="2" <?php if ($row['status'] == 2) {
                                                        echo 'selected';
                                                    } ?>>Like New</option>
                                <option value="3" <?php if ($row['status'] == 3) {
                                                        echo 'selected';
                                                    } ?>>Rebufished</option>
                                <option value="4" <?php if ($row['status'] == 4) {
                                                        echo 'selected';
                                                    } ?>>Used & still active</option>
                                <option value="5" <?php if ($row['status'] == 5) {
                                                        echo 'selected';
                                                    } ?>>Damaged</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Rating</label>
                        <div class="col-sm-10">
                            <select name="rating" value="<?= $row['rating']; ?>" class="form-control">
                                <option value="0" <?php if ($row['rating'] == 0) {
                                                        echo 'selected';
                                                    } ?>>...</i></option>
                                <option value="1" <?php if ($row['rating'] == 1) {
                                                        echo 'selected';
                                                    } ?>>*</option>
                                <option value="2" <?php if ($row['rating'] == 2) {
                                                        echo 'selected';
                                                    } ?>>**</option>
                                <option value="3" <?php if ($row['rating'] == 3) {
                                                        echo 'selected';
                                                    } ?>>***</option>
                                <option value="4" <?php if ($row['rating'] == 4) {
                                                        echo 'selected';
                                                    } ?>>****</option>
                                <option value="5" <?php if ($row['rating'] == 5) {
                                                        echo 'selected';
                                                    } ?>>*****</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Seller</label>
                        <div class="col-sm-10">
                            <select name="seller" class="form-control" placeholder="Seller name">
                                <option value="0">...</i></option>
                                <?php
                                $cat = $dbconnection->prepare("SELECT * FROM users");
                                $cat->execute();
                                $options = $cat->fetchAll();
                                foreach ($options as $option) {
                                    echo ' <option value="' . $option['userid'] . '"';
                                    if ($row['user_id'] == $option['userid']) {
                                        echo 'selected';
                                    }
                                    echo '>' . $option['username'] . '</i></option>';
                                }

                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Category</label>
                        <div class="col-sm-10">
                            <select name="category" class="form-control">
                                <option value="0">...</i></option>
                                <?php
                                $cat = $dbconnection->prepare("SELECT * FROM categories");
                                $cat->execute();
                                $options = $cat->fetchAll();
                                foreach ($options as $option) {
                                    echo ' <option value="' . $option['catid'] . '"';
                                    if ($row['cat_id'] == $option['catid']) {
                                        echo 'selected';
                                    }
                                    echo '>' . $option['name'] . '</i></option>';
                                }

                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-offset-10 col-sm-10">
                            <input type="submit" class="btn btn-outline-primary rounded-pill" value="Update Product">
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
    <?php elseif ($do == 'Update') : ?>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $country = $_POST['country'];
            $status = $_POST['status'];
            $riting = $_POST['riting'];
            $seller = $_POST['seller'];
            $category = $_POST['category'];
            $check = checkItem('id', 'items', $id);
            if ($check > 0) {
                $update = $dbconnection->prepare('UPDATE items SET name = :name, description = :description, price = :price, country_made = :country, status = :status, rating = :riting , cat_id = :category, user_id = :seller WHERE id = :id');
                $update->execute(array(
                    'id'         => $id,
                    'name'       => $name,
                    'description' => $description,
                    'price'      => $price,
                    'country'    => $country,
                    'status'     => $status,
                    'riting'     => $riting,
                    'category'   => $category,
                    'seller'     => $seller
                ));
                $row = $update->rowCount();
                if ($row > 0) {
                    $msg = '<div class="alert alert-success"> Product Has Seccessfuly Updated.</div>';
                    redirectHome($msg, 'items.php', 3, 'items.php');
                } else {
                    $msg = '<div class="alert alert-danger"> Product Has not Updated.</div>';
                    redirectHome($msg, 'items.php', 3, 'items.phpßdo=Edit');
                }
            } else {
                $msg = '<div class="alert alert-danger"> Product Has not Found.</div>';
                redirectHome($msg, 'items.php', 3, 'items.php');
            }
        } else {
            $msg = '<div class="alert alert-danger"> Authentification Is Required.</div>';
            redirectHome($msg);
        }
        ?>
    <?php elseif ($do == 'Delete') : ?>
    <?php endif ?>
<?php

    include $tpl . 'footer.php';
} else {

    header('Location:index.php');
    exit();
}
