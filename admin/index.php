


<?php include 'header.php'; ?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Products Listings</h3>
              </div>
              <!-- /.card-header -->
              

              <div class="card-body">

                <a href="add.php" type="button" class="btn btn-success mb-3">New Category</a>

                <table class="table table-bordered mb-4">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Description</th>
                      <th style="width: 40px">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    

                  </tbody>
                </table>

                 <!-- pagination -->
                 <nav aria-label="Page navigation example" class="float-right">
                   <ul class="pagination">
                      <li class="page-item"><a class="page-link" href="">First</a></li>
                      <li class="page-item ">
                        <a class="page-link" href="">Prev</a>
                      </li>
                      <li class="page-item"><a class="page-link" href="#"></a></li>
                      <li class="page-item ">
                        <a class="page-link" href="">Next</a>
                      </li>
                      <li class="page-item"><a class="page-link" href="?pageno=">Last</a></li>
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
