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
?>

<?php include 'header.php'; ?>

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">User Listing</h3>
          </div>
          <!-- /.card-header -->
          

          <div class="card-body">

            <a href="category_add.php" type="button" class="btn btn-success mb-3">User Add</a>

            <table class="table table-bordered mb-4">
              <thead>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Name</th>
                  <th>Description</th>
                  <th style="width: 40px">Actions</th>
                </tr>
              </thead>

              <?php
                if(!empty($_GET['pageno'])){
                  $pageno = $_GET['pageno'];
                }else {
                  $pageno = 1;
                }

                $numOfRec = 5;
                $offset = ($pageno - 1) * $numOfRec;

                $pdostmt = $pdo->prepare("SELECT * FROM categories ORDER BY id DESC");
                $pdostmt->execute();
                $rawResult = $pdostmt->fetchAll();

                $total_page = ceil(count($rawResult) / $pageno);

                $stmt = $pdo->prepare("SELECT * FROM categories ORDER BY id DESC LIMIT $offset, $numOfRec");
                $stmt->execute();
                $result = $stmt->fetchAll();
              ?>

              <tbody>
                
              </tbody>
            </table>

             <!-- pagination -->
             <nav aria-label="Page navigation example" class="float-right">
               <ul class="pagination">
                  <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                  <li class="page-item <?php if($pageno <= 1){ echo 'disabled'; } ?>">
                    <a class="page-link" href="<?php if($pageno <= 1){ echo '#';}else{ echo '?pageno='.($pageno - 1); } ?>">Prev</a>
                  </li>
                  <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
                  <li class="page-item <?php if($pageno >= $total_page){ echo 'disabled'; } ?>">
                    <a class="page-link" href="<?php if($pageno >= $total_page){ echo '#'; }else { echo '?pageno='.($pageno + 1); } ?>">Next</a>
                  </li>
                  <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_page; ?>">Last</a></li>
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
