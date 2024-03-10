<?php 
  include("authorization.php");
  include("includeDB.php"); 

  if(isset($_POST['title']) && isset($_POST['category']) && isset($_POST['price']) && isset($_POST['inventory']) && isset($_POST['content'])) 
  {
    $image1Data = addslashes(file_get_contents($_FILES['image1']['tmp_name']));
    $image2Data = addslashes(file_get_contents($_FILES['image2']['tmp_name']));
    $image3Data = addslashes(file_get_contents($_FILES['image3']['tmp_name']));
    
    $title = $_POST['title'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    if($price < 0)
    {
      echo "<script>alert('Price cannot be negative!')</script>";
      exit();
    }
    $inventory = $_POST['inventory'];
    if($inventory < 0)
    {
      echo "<script>alert('Inventory cannot be negative!')</script>";
      exit();
    }
    $content = $_POST['content'];
    $catId = "SELECT category_id FROM category WHERE category_name = '$category'";
    $catId = $conn->query($catId);
    $catId = mysqli_fetch_array($catId);
    $catId = $catId['category_id'];
    $sql = "INSERT INTO product (product_name, category_id, price, inventory, image1, image2, image3, product_description) VALUES ('$title', '$catId', '$price', '$inventory', '$image1Data', '$image2Data', '$image3Data', '$content')";
    $result = $conn->query($sql);
    if($result)
    {
      echo "<script>alert('Product Added Successfully!')</script>";
    }
    else
    {
      echo "<script>alert('Product Addition Failed!')</script>";
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
    <title>New Product | Admin</title>
</head>
<body>
    <div class="sidebar">
        <div class="logo"><h2><i class="fa fa-user-circle"></i>Admin Panel</h2></div>
        <div class="nav">
            <a href="dashboard.php"><i class="fa fa-home back"></i>Dashboard</a></li>
            <a href="sales.php"><i class="fa fa-money back"></i>Previous Sales</a></li>
            <a href="addproduct.php" class="active"><i class="fa fa-plus-square"></i>Add Product</a></li>
            <a href="allproducts.php"><i class="fa fa-list-alt"></i>All Product</a></li>
            <a href="addcat.php"><i class="fa fa-plus"></i>Add Category</a></li>
            <a href="allcats.php"><i class="fa fa-list"></i>All Categories</a></li>
            <a href="deliveries.php"><i class="fa fa-truck"></i>Pending Deliveries</a></li>
            <a href="users.php"><i class="fa fa-user"></i>Users List</a></li>                
            <a href="logout.php"><i class="fa fa-sign-out"></i>Logout</a></li>
        </div>
    </div>
    <div class="content">
        <div class="card outercard">
            <h1>Add New Product</h1>
      <form action="" method="POST" enctype="multipart/form-data">
        <div class="row">
          <div class="col-25">
            <label for="title">Product Name</label>
          </div>
          <div class="col-75">
            <input type="text" id="title" name="title" placeholder="Enter Product name.." required>
          </div>
        </div>
        <div class="row">
          <div class="col-25">
            <label for="category">Select Category</label>
          </div>
          <div class="col-75">
            <select id="category" name="category">
              <?php
                $sql_check = "SELECT * FROM category ORDER BY category_id";
                $result = $conn->query($sql_check);
                while ($row = mysqli_fetch_array($result)) { ?>
                  <option value="<?php echo $row['category_name'] ?>"><?php echo $row['category_name'] ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="row">
            <div class="col-25">
              <label for="Price">Price</label>
            </div>
            <div class="col-75">
              <input type="number" id="price" name="price" min="1" required>
            </div>
          </div>
        <div class="row">
          <div class="col-25">
            <label for="Inventory">Inventory</label>
          </div>
          <div class="col-75">
            <input type="number" id="inventory" name="inventory" min="1" required>
          </div>
        </div>
        <div class="row">
          <div class="col-25">
            <label for="poster">Product Image 1</label>
          </div>
          <div class="col-75">
            <section>
              <input id="upload" type="file" name="image1" required/>
            </section>
          </div>
        </div>
        <div class="row">
          <div class="col-25">
            <label for="poster">Product Image 2</label>
          </div>
          <div class="col-75">
            <section>
              <input id="upload" type="file" name="image2" required/>
            </section>
          </div>
        </div>
        <div class="row">
          <div class="col-25">
            <label for="poster">Product Image 3</label>
          </div>
          <div class="col-75">
            <section>
              <input id="upload" type="file" name="image3" required/>
            </section>
          </div>
        </div>
        <div class="row">
          <div class="col-25">
            <label for="content">Description</label>
          </div>
          <div class="col-75">
            <textarea id="content" name="content" placeholder="Write something.." style="height:150px"
              required></textarea>
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