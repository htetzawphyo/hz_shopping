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
                <h3 class="card-title">Order Listings</h3>
              </div>
              <!-- /.card-header -->
              <?php
                if(isset($_GET['pageno'])){
                  $pageno = $_GET['pageno'];
                }else {
                  $pageno = 1;
                }

                $numOfRec = 5;
                $offset = ($pageno - 1) * $numOfRec;

                $pdoStmt = $pdo->prepare("SELECT * FROM sale_orders ORDER BY id Desc");
                $pdoStmt->execute();
                $rawResult = $pdoStmt->fetchAll();

                $totalPage = ceil(count($rawResult) / $numOfRec);

                $stmt = $pdo->prepare("SELECT * FROM sale_orders ORDER BY id Desc LIMIT $offset,$numOfRec");
                $stmt->execute();
                $result = $stmt->fetchAll();
              ?>

              <div class="card-body">
                <table class="table table-bordered mb-4">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>User</th>
                      <th>Total Price</th>
                      <th>Order Date</th>
                      <th style="width: 40px">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $i = 1;
                      foreach($result as $value){ 
                        $userStmt = $pdo->prepare("SELECT * FROM users WHERE id=".$value['user_id']);
                        $userStmt->execute();
                        $userResult = $userStmt->fetchAll();  
                      ?>
                        <tr>
                          <td><?php echo $i; ?></td>
                          <td><?php echo escape($userResult[0]['name']); ?></td>
                          <td><?php echo escape($value['total_price']); ?></td> 
                          <td><?php echo escape(date('Y-m-d',strtotime($value['order_date']))); ?></td>
                          <td>
                            <div class="btn-group">
                              <div class="container">
                                <a href="order_detail.php?viewId=<?php echo $value['id'] ?>"
                                  type="button"
                                  class="btn btn-info btn-sm">View</a>
                              </div>
                            </div>
                          </td>
                        </tr>
                    <?php  
                    $i++;
                    }
                    ?>
                  </tbody>
                </table>

                 <!-- pagination -->
                 <nav aria-label="Page navigation example" class="float-right">
                   <ul class="pagination">
                      <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                      <li class="page-item <?php if($pageno <= 1){echo 'disabled';} ?>">
                        <a class="page-link" href="<?php if($pageno <= 1){echo '#';}
                                    else{echo '?pageno='.($pageno - 1);} ?>">Prev</a>
                      </li>
                      <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
                      <li class="page-item <?php if($pageno >= $totalPage){echo 'disabled';} ?>">
                        <a class="page-link" href="<?php if($pageno >= $totalPage){echo '#';}
                                    else{echo '?pageno='.($pageno + 1);} ?>">Next</a>
                      </li>
                      <li class="page-item"><a class="page-link" href="?pageno=<?php echo $totalPage; ?>">Last</a></li>
                   </ul>
                 </nav>

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
