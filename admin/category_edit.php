<?php
    session_start();
    require "../config/config.php";
    require "../config/common.php";

    if(empty($_SESSION['id']) && empty($_SESSION['name'])){
      header('location: login.php');
    }

    if($_SESSION['role'] == 0){
      header('location: login.php');
    }
    
    // FOR SHOW
    $id = $_GET['editId'];
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id=$id");
    $stmt->execute();
    $result = $stmt->fetch();

    // FOR UPDATE
    $nameError = "";
    $descError = "";
    if(isset($_POST['updateButton'])){
        $id = $_POST['id'];
        $name = $_POST['name'];
        $desc = $_POST['description'];

        if(empty($name)){
            $nameError = "The name field is required!";
        }
        if(empty($desc)){
            $descError = "The description field is required!";
        }
        if(!empty($name) and !empty($desc)){
            $stmt = $pdo->prepare("UPDATE categories SET name=:name, description=:desc WHERE id=$id");
            $result = $stmt->execute([
                ':name' => $name,
                ':desc' => $desc
            ]);

            if($result){
                header('location: category.php');
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
                    <h3 class="">Edit Category</h3>
                </div>
                <div class="col-md-6">
                    <a href="category.php" class="btn btn-primary btn-sm float-right"><< Back</a>
                </div>
            </div>
          </div>
          <!-- /.card-header -->         

          <div class="card-body">
            <form action="" method="post">
            <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
            <input type="hidden" name="id" value="<?php echo $result['id']; ?>">
                <div class="mb-3">
                    <label>Name</label>
                    <input name="name" type="text" 
                           class="form-control <?php if($nameError){ echo 'is-invalid'; } ?>"
                           value="<?php if($nameError == "" and empty($name)){
                            echo $result['name'];
                           } 
                           if(isset($name)){
                            echo $name;
                           }?>">
                    <i class="text-danger"><?php echo $nameError; ?></i>
                </div>
                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="description" id="" cols="30" rows="8" 
                    class="form-control <?php if($descError != ''){ echo 'is-invalid'; } ?>"><?php if($descError == "" and empty($desc)){
                        echo $result['description'];
                    }
                    if(isset($desc)){
                        echo $desc;
                    }?></textarea>
                    <i class="text-danger"><?php echo $descError; ?></i>
                </div>
                <button type="submit" name="updateButton" class="btn btn-success">Update</button>
                
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
