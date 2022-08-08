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

?>

<?php include 'header.php'; ?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Royal Customers</h3>
              </div>
              <!-- /.card-header -->
              <?php
                $pdoStmt = $pdo->prepare("SELECT * FROM sale_orders WHERE total_price > 200000 
                                          ORDER BY id Desc");
                $pdoStmt->execute();
                $result = $pdoStmt->fetchAll();
              ?>

              <div class="card-body">
                <table class="table table-bordered mb-4" id="d_table">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>User Id</th>
                      <th>Total Amount</th>
                      <th>Email</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                      $i = 1;
                      foreach($result as $value){ 
                        $userStmt = $pdo->prepare("SELECT * FROM users WHERE id=".$value['user_id']);
                        $userStmt->execute();
                        $userResult = $userStmt->fetch();  
                      ?>
                        <tr>
                          <td><?php echo $i; ?></td>
                          <td><?php echo escape($userResult['name']); ?></td>
                          <td><?php echo escape($value['total_price']); ?></td>
                          <td><?php echo escape($userResult['email']); ?></td>
                        </tr>
                    <?php  
                    $i++;
                    }
                    ?>
                  </tbody>
                </table>

                
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
<script>
    $(document).ready(function () {
        $('#d_table').DataTable();
    });
</script>