<?php 
// FOR SEARCH
if(!empty($_POST['search'])){
	setcookie('search', $_POST['search'], time() + (86400 * 30), "/");
}

if(empty($_POST['search']) and empty($_GET['pageno'])){
	unset($_COOKIE['search']);
	setcookie('search', null, -1, '/');
}

?>

<?php include('header.php') ?>

<?php 

    if(session_status() == PHP_SESSION_NONE){
		session_start();
	}

	require "config/config.php";
	#require "config/common.php"; // already used header.php

	if(empty($_SESSION['id']) && empty($_SESSION['name'])){
		header('location: login.php');
	}
	if($_SESSION['role'] == 1){
		header('location: admin/login.php');
	}

	if(isset($_GET['pageno'])){
		$pageno = $_GET['pageno'];
	}else {
		$pageno = 1;
	}

	$numOfRec = 6;
	$offset = ($pageno - 1) * $numOfRec;

	if(empty($_POST['search']) && empty($_COOKIE['search'])){

		if(isset($_GET['category_id'])){
			$id = $_GET['category_id'];
			$stmt = $pdo->prepare("SELECT * FROM products WHERE category_id=$id ORDER BY id DESC");
			$stmt->execute();
			$rawResult = $stmt->fetchAll();
			
			$total_page = ceil(count($rawResult) / $numOfRec);

			$stmt = $pdo->prepare("SELECT * FROM products WHERE category_id=$id ORDER BY id DESC LIMIT $offset, $numOfRec");
			$stmt->execute();
			$result = $stmt->fetchAll();
		}else{
			$stmt = $pdo->prepare("SELECT * FROM products ORDER BY id DESC");
			$stmt->execute();
			$rawResult = $stmt->fetchAll();

			$total_page = ceil(count($rawResult) / $numOfRec);

			$stmt = $pdo->prepare("SELECT * FROM products ORDER BY id DESC LIMIT $offset, $numOfRec");
			$stmt->execute();
			$result = $stmt->fetchAll();
		}		
	}else {
		$search_key = "";
		if(isset($_POST['search'])){
		$search_key = $_POST['search'];
		} else{
		$search_key = $_COOKIE['search'];
		}
		$stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE '%$search_key%' ORDER BY id DESC");
		$stmt->execute();
		$rawResult = $stmt->fetchAll();

		$total_page = ceil(count($rawResult) / $numOfRec);

		$stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE '%$search_key%' ORDER BY id DESC LIMIT $offset, $numOfRec");
		$stmt->execute();
		$result = $stmt->fetchAll();
	}
?>
    <!-- Categories -->
	<div class="container">
	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-5">
		<div class="sidebar-categories">
			<div class="head">Browse Categories</div>
			<ul class="main-categories">
				<li class="main-nav-list">
					<?php 
						$catStmt = $pdo->prepare("SELECT * FROM categories ORDER BY id DESC");
						$catStmt->execute();
						$catResult = $catStmt->fetchAll();
					?>
					<?php foreach($catResult as $value) { ?>
						<a href="index.php?category_id=<?php echo $value['id'];?>">
							<span class="lnr lnr-arrow-right"></span><?php echo escape($value['name']); ?>
						</a>					
					<?php } ?>
				</li>
			</ul>
		</div>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-7">

	<!-- Pagination -->
	<div class="filter-bar d-flex flex-wrap align-items-center">
		<div class="pagination">
			<a href="?pageno=1" class="active">First</a>
			<a <?php if($pageno <= 1){ echo 'disabled'; } ?> 
				class="prev-arrow"
				href="<?php if($pageno <= 1){ echo '#';}else{ echo '?pageno='.($pageno - 1); } ?>">
				<i class="fa fa-long-arrow-left" aria-hidden="true"></i>
			</a>
			<a href="#" class="active"><?php echo $pageno; ?></a>
			<a <?php if($pageno >= $total_page){ echo 'disabled'; } ?> class="next-arrow"
				href="<?php if($pageno >= $total_page){ echo '#'; }else { echo '?pageno='.($pageno + 1); } ?>">
				<i class="fa fa-long-arrow-right" aria-hidden="true"></i>
			</a>
			<a href="?pageno=<?php echo $total_page; ?>" class="active">Last</a>
		</div>
	</div>	

	<!-- Start Best Seller -->
	<section class="lattest-product-area pb-40 category-list">
		<div class="row">
			<?php 
			    if($result){
					foreach($result as $key => $value) { ?>
					<!-- single product -->
					<div class="col-lg-4 col-md-6">
						<div class="single-product">
							<a href="product_detail.php?product_detail_id=<?php echo $value['id']; ?>">
								<img class="img-fluid" src="admin/images/<?php echo escape($value['image']); ?>" alt="" style="height: 250px">
							</a>
							<div class="product-details">
								<h6><?php echo escape($value['name']); ?></h6>
								<div class="price">
									<h6><?php echo escape($value['price']); ?></h6>
								</div>
								<div class="prd-bottom">

									<a href="" class="social-info">
										<span class="ti-bag"></span>
										<p class="hover-text">add to bag</p>
									</a>
									<a href="product_detail.php?product_detail_id=<?php echo $value['id']; ?>" class="social-info">
										<span class="lnr lnr-move"></span>
										<p class="hover-text">view more</p>
									</a>
								</div>
							</div>
						</div>
					</div>
				<?php }
				}
			?>
		</div>
	</section>
	<!-- End Best Seller -->
<?php include('footer.php');?>
