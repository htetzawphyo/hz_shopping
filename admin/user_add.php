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
    // FOR ADD USER

    $nameError = "";
    $emailError = "";
    $passwordError = "";
    $passwordShortError = "";
    if(isset($_POST['createButton'])){
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        if(empty($name)){
            $nameError = "The name field is required!";
        }
        if(empty($email)){
            $emailError = "The email field is required!";
        }
        if(empty($password)){
            $passwordError = "The password field is required!";
        }
        if(strlen($password) < 4){
            $passwordShortError = "At least 4 character password!";
        }

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user){
          echo '<script>alert("Already have Email!");</script>';
        }else {
          if(!empty($name) && !empty($email) && !empty($password) && $passwordShortError == ""){
            $hashPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name,email,password) 
                                   VALUES (:name,:email,:password)");
            $result = $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => $hashPassword
            ]);
            if($result){
                header('location: user_list.php');
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
                <div class="col-md-6 col-sm-6">
                    <h3 class="">User Add</h3>
                </div>
                <div class="col-md-6 col-sm-6">
                    <a href="user_list.php" class="btn btn-primary btn-sm float-right"><< Back</a>
                </div>
            </div>
          </div>
          <!-- /.card-header -->
          

          <div class="card-body">
            <form action="" method="post">
            <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" placeholder="Input your name..." 
                           name="name" 
                           class="form-control <?php if($nameError != ""){ echo 'is-invalid'; } ?>"
                           value="<?php if(isset($name)){ echo $name; } ?>">
                    <i class="text-danger"><?php if($nameError != ""){ echo $nameError; } ?></i>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" placeholder="Input your email..." 
                           name="email" 
                           class="form-control <?php if($emailError != ""){ echo 'is-invalid'; } ?>"
                           value="<?php if(isset($email)){ echo $email; } ?>">
                    <i class="text-danger"><?php if($emailError != ""){ echo $emailError; } ?></i>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" placeholder="Input your password..." 
                           name="password" 
                           class="form-control <?php if($passwordError != "" || $passwordShortError != ""){ echo 'is-invalid'; } ?>"
                           value="<?php if(isset($password)){ echo "$password";} ?>">
                    <i class="text-danger">
                        <?php if($passwordError != ""){
                            echo $passwordError;
                            } elseif($passwordShortError != ""){
                                echo $passwordShortError;
                            } ?>
                    </i>
                </div>

                <button type="submit" class="btn btn-success" name="createButton">Create User</button>

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
