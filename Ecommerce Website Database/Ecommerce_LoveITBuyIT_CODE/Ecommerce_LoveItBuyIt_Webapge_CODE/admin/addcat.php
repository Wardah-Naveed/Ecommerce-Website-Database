<?php 
  include("authorization.php");
  include("includeDB.php"); 

  if(isset($_POST['category'])) 
  {
    $category = $_POST['category'];
    $sql = "INSERT INTO category (category_name) VALUES ('$category')";
    $result = $conn->query($sql);
    if($result)
    {
      echo "<script>alert('Category Added Successfully!')</script>";
    }
    else
    {
      echo "<script>alert('Category Addition Failed!')</script>";
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="forms.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>New Category | Admin</title>
</head>
<body>
    <div class="sidebar">
        <div class="logo"><h2><i class="fa fa-user-circle"></i>Admin Panel</h2></div>
        <div class="nav">
            <a href="dashboard.php"><i class="fa fa-home back"></i>Dashboard</a></li>
            <a href="sales.php"><i class="fa fa-money back"></i>Previous Sales</a></li>
            <a href="addproduct.php"><i class="fa fa-plus-square"></i>Add Product</a></li>
            <a href="allproducts.php"><i class="fa fa-list-alt"></i>All Product</a></li>
            <a href="addcat.php" class="active"><i class="fa fa-plus"></i>Add Category</a></li>
            <a href="allcats.php"><i class="fa fa-list"></i>All Categories</a></li>
            <a href="deliveries.php"><i class="fa fa-truck"></i>Pending Deliveries</a></li>
            <a href="users.php"><i class="fa fa-user"></i>Users List</a></li>                
            <a href="logout.php"><i class="fa fa-sign-out"></i>Logout</a></li>
        </div>
    </div>
    <div class="content">
        <div class="card outercard">
            <h1>Add New Category</h1>
      <form action="" method="POST" enctype="multipart/form-data">
        <div class="row">
          <div class="col-25">
            <label for="title">Category Name</label>
          </div>
          <div class="col-75">
            <input type="text" id="title" name="category" placeholder="Enter Category name.." required>
          </div>
        </div>
        <div class="row">
          <input type="submit" value="Post">
        </div>
      </form>
        </div>
    </div>
</body>
</html>