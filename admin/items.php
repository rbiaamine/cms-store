<?php
 session_start();
 $pageTitle = '';
 if(isset($_SESSION['Username'])){
     include 'init.php';
     $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
 ?>
 <?php if ($do == 'Manage') : ?>
    <?php
        echo 'Manage';
        ?>
 <?php elseif ($do == 'Add') : ?>
    <h1 class="text-center text-primary">Add New Product</h1>
        <div class="container p-5">
            <form class="cat-form" method="POST" action="?do=Insert">
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Product Name(<span style="color: red;">*</span>)</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control"  placeholder="Name of Product"  />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Product Description(<span style="color: red;">*</span>)</label>
                    <div class="col-sm-10">
                        <input type="text" name="description" class="form-control"  placeholder="Describe The Product"  />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Price(<span style="color: red;">*</span>) <span class="badge badge-primary">€</span></label>
                    <div class="col-sm-10">
                        <input type="number" name="price" class="form-control"  placeholder="price of Product"  />
                        
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Country(<span style="color: red;">*</span>)</label>
                    <div class="col-sm-10">
                        <input type="text" name="country" class="form-control"  placeholder="The Country In Which The Product is Made"  />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Statu(<span style="color: red;">*</span>)</label>
                    <div class="col-sm-10">
                        <select name="status" class="form-control" >
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
                        <select name="rate" class="form-control" >
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
                        <select name="seller" class="form-control" placeholder="Seller name" >
                        <option value="0">...</i></option>
                            <?php
                            $cat = $dbconnection->prepare("SELECT * FROM users");
                            $cat->execute();
                            $options = $cat->fetchAll();
                            foreach($options as $option){
                                echo ' <option value="'.$option['userid'].'">'.$option['username'].'</i></option>';
                            }

                            ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Category</label>
                    <div class="col-sm-10">
                        <select name="category" class="form-control" >
                        <option value="0">...</i></option>
                        <?php
                            $cat = $dbconnection->prepare("SELECT * FROM categories");
                            $cat->execute();
                            $options = $cat->fetchAll();
                            foreach($options as $option){
                                echo ' <option value="'.$option['catid'].'">'.$option['name'].'</i></option>';
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
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            echo 'insert';
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $country = $_POST['country'];
            $status = $_POST['status'];
            $rate = $_POST['rate'];
            $category = $_POST['category'];
            $seller = $_POST['seller'];
            
            if(empty($name)){
                $errForm[] = '<div class="alert alert-danger"> Name fild is empty.</div>';
            }
            if(empty($description)){
                $errForm[] = '<div class="alert alert-danger"> Description fild is empty.</div>';
            }
            if(empty($price)){
                $errForm[] = '<div class="alert alert-danger"> Price fild is empty.</div>';
            }
            if(empty($country)){
                $errForm[] = '<div class="alert alert-danger"> Country fild is empty.</div>';
            }
            if($status == 0){
                $errForm[] = '<div class="alert alert-danger"> Status fild is empty.</div>';
            }
            if($category == 0){
                $errForm[] = '<div class="alert alert-danger"> Category fild is empty.</div>';
            }
            if($seller == 0){
                $errForm[] = '<div class="alert alert-danger"> Seller fild is empty.</div>';
            }
            if(empty($errForm)){
                $add = $dbconnection->prepare("INSERT INTO 
                items (name,description,price,country_made,status,rating,cat_id,user_id) 
                VALUE (:name,:description,:price,:country,:status,:rate,:category,:seller)");
                $add->execute(array(
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'country' => $country,
                    'status' =>$status,
                    'rate' => $rate,
                    'category' => $category,
                    'seller' => $seller

                ));
                $msg = '<div class="alert alert-success"> Product Has Seccessfuly Added.</div>';
                redirectHome($msg);
            }else{
                foreach($errForm as $err){
                    echo $err;
                }
                redirectHome('','back',3,'items.php?do=Add');
             
            }
        }else{
            $msg = '<div class="alert alert-danger"> Authentication is Required.</div>';
            redirectHome($msg);
        }
        ?>
 <?php elseif ($do == 'Edit') : ?>
 <?php elseif ($do == 'Update') : ?>
 <?php elseif ($do == 'Delete') : ?>
 <?php endif ?>
 <?php

     include $tpl . 'footer.php';
} else {
       
        header('Location:index.php');
        exit();
}