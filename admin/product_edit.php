<?php
    session_start();

    require "../config/config.php";
    require "../config/common.php";

    if(empty($_SESSION['id']) && empty($_SESSION['name'])){
      header('location: login.php');
    }

    if($_SESSION['role'] == 0){
      header('location: ../login.php');
    }

    // FOR SHOW
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=".$_GET['editId']);
    $stmt->execute();
    $result = $stmt->fetch();


    // FOR UPDATE
    $nameError = "";
    $descError = "";
    $catError = "";
    $quantError = "";
    $priceError = "";

    if(isset($_POST['updateButton'])){
        $name = $_POST['name'];
        $desc = $_POST['description'];
        $cat = $_POST['category'];
        $quant = $_POST['quantity'];
        $price = $_POST['price'];
        $id = $_POST['id'];
        if(empty($name) || empty($desc) || empty($cat) || empty($quant) || empty($price)){
            if(empty($name)){
                $nameError = "The name box field is required!";
            }
            if(empty($desc)){
                $descError = "The description field is required!";
            }
            if(empty($cat)){
                $catError = "The category field is required!";
            }
            if(empty($quant)){
                $quantError = "The quantity field is required!";
            }elseif(!is_numeric($quant)){
                $quantError = "Quantity should be number!";
            }
            if(empty($price)){
                $priceError = "The price field is required!";
            }elseif(!is_numeric($price)){
                $priceError = "Price should be number!";
            }
        }else {   // fields are includes
            if(!is_numeric($quant)){
                $quantError = "Quantity should be number!";
            }
            if(!is_numeric($price)){
                $priceError = "Price should be number!";
            }
            if($quantError == '' and $priceError == ''){
                $img = $_FILES['image']['name'];
                if($_FILES['image']['name'] != null){

                $file = 'images/'.($_FILES['image']['name']);
                $imageType = pathinfo($file, PATHINFO_EXTENSION);

                if($imageType == "jpg" || $imageType == "png" || $imageType == "jpeg"){
                    move_uploaded_file($_FILES['image']['tmp_name'],$file);

                    $pdostmt = $pdo->prepare("UPDATE products SET name=:name, description=:desc, category_id=:cat,
                                            quantity=:quant, price=:price, image=:image WHERE id=$id");

                    $result = $pdostmt->execute([
                        ':name' => $name,
                        ':desc' => $desc,
                        ':cat' => $cat,
                        ':quant' => $quant,
                        ':price' => $price,
                        ':image' => $img
                    ]);
                    if($result){
                        header('location: index.php');
                    }
                    }
                } else {
                    $pdostmt = $pdo->prepare("UPDATE products SET name=:name, description=:desc, category_id=:cat,
                                            quantity=:quant, price=:price WHERE id=$id");

                    $result = $pdostmt->execute([
                        ':name' => $name,
                        ':desc' => $desc,
                        ':cat' => $cat,
                        ':quant' => $quant,
                        ':price' => $price
                    ]);
                    if($result){
                        #echo "<script>swal('Good job!', 'Update Successfully!', 'success');window.location.href='index.php';</script>";
                        header('location: index.php');
                    }
                }
            }
            }
            
                      
    }

?>


<?php include 'header.php'; ?>

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="">Product Update</h3>
                </div>
                <div class="col-md-6">
                    <a href="index.php" class="btn btn-primary btn-sm float-right"><< Back</a>
                </div>
            </div>
            
          </div>
          <!-- /.card-header -->
          

          <div class="card-body">
            <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
            <input type="hidden" name="id" value="<?php echo $result['id']; ?>">
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control"
                           value="<?php echo $result['name'] ?>">
                    <i class="text-danger"><?php echo $nameError; ?></i>
                </div>
                <div class="mb-3">
                    <label>Description</label>
                    <input type="text" name="description" class="form-control"
                    value="<?php echo $result['description'] ?>">
                    <i class="text-danger"><?php echo $descError; ?></i>
                </div>
                <div class="mb-3">
                    <?php
                        $catStmt = $pdo->prepare("SELECT * FROM categories");
                        $catStmt->execute();
                        $catResult = $catStmt->fetchAll();
                    ?>
                    <label for="">Category</label>
                    <select name="category" id="" class="form-control">
                        <option value="">Choose Category</option>
                        <?php 
                            foreach($catResult as $value) { ?>
                            <option value="<?php echo $value['id']; ?>" 
                                <?php if($value['id'] == $result['category_id']){echo 'selected';}  ?>
                            >
                                <?php echo $value['name']; ?>
                            </option>                     
                        <?php } ?>
                    </select>
                    <i class="text-danger"><?php echo $catError; ?></i>
                </div>
                <div class="mb-3">
                    <label>Quantity</label>
                    <input type="number" name="quantity" class="form-control"
                           value="<?php echo $result['quantity']; ?>">
                    <i class="text-danger"><?php echo $quantError; ?></i>
                </div>
                <div class="mb-3">
                    <label>Price</label>
                    <input type="number" name="price" class="form-control"
                           value="<?php echo $result['price']; ?>">
                    <i class="text-danger"><?php echo $priceError; ?></i>
                </div>
                <div class="mb-3">
                    <label>Image</label>
                    <input type="file" name="image" class="form-control-file mb-2">
                    <img src="images/<?php echo escape($result['image'])?>" alt="" height="200" class="img-thumbnail">
                    
                </div>
                
                <button type="submit" name="updateButton" class="btn btn-success">Update Product</button>
            </form>
          </div>
          <!-- /.card-body -->

        </div>
        <!-- /.card -->
      </div>
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
</div>

<?php include 'footer.html' ?>
