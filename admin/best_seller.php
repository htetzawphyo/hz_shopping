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
                <h3 class="card-title">Best Seller Items</h3>
              </div>
              <!-- /.card-header -->
              <?php
                $pdoStmt = $pdo->prepare("SELECT * FROM sale_order_detail GROUP BY product_id 
                                          HAVING SUM(quantity) > 5 ORDER BY id Desc");
                $pdoStmt->execute();
                $result = $pdoStmt->fetchAll();
              ?>

              <div class="card-body">
                <table class="table table-bordered mb-4" id="d_table">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Product Name</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                      $i = 1;
                      foreach($result as $value){ 
                        $userStmt = $pdo->prepare("SELECT * FROM products WHERE id=".$value['product_id']);
                        $userStmt->execute();
                        $userResult = $userStmt->fetch();  
                      ?>
                        <tr>
                          <td><?php echo $i; ?></td>
                          <td><?php echo escape($userResult['name']); ?></td>
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