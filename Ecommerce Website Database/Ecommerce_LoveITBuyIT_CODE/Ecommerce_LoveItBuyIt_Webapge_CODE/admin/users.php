<?php 
  include("authorization.php");
  include("includeDB.php"); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Users List | Admin</title>
</head>
<body>
    <div class="sidebar">
        <div class="logo"><h2><i class="fa fa-user-circle"></i>Admin Panel</h2></div>
        <div class="nav">
            <a href="dashboard.php"><i class="fa fa-home back"></i>Dashboard</a></li>
            <a href="sales.php"><i class="fa fa-money back"></i>Previous Sales</a></li>
            <a href="addproduct.php"><i class="fa fa-plus-square"></i>Add Product</a></li>
            <a href="allproducts.php"><i class="fa fa-list-alt"></i>All Product</a></li>
            <a href="addcat.php"><i class="fa fa-plus"></i>Add Category</a></li>
            <a href="allcats.php"><i class="fa fa-list"></i>All Categories</a></li>
            <a href="deliveries.php" ><i class="fa fa-truck"></i>Pending Deliveries</a></li>
            <a href="users.php"  class="active"><i class="fa fa-user"></i>Users List</a></li>                
            <a href="logout.php"><i class="fa fa-sign-out"></i>Logout</a></li>
        </div>
    </div>
    <div class="content">
        <div class="card outercard">
            <h2 style="color: white;">Users List</h3>
            <table>
                <thead>
                  <tr>
                    <th>User Id</th>
                    <th>User Name</th>
                    <th>User Email</th>
                    <th>User Address</th>
                    <th>User Number</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                      $sql_check = "SELECT * FROM customer ORDER BY cust_id";
                      $result = $conn->query($sql_check);
                      while ($row = mysqli_fetch_array($result)) { ?>
                      <tr>
                        <td><?php echo $row['cust_id'] ?></td>
                        <td><?php echo $row['first_name'] . " " . $row['last_name'] ?></td>
                        <td><?php echo $row['email'] ?></td>
                        <td><?php echo $row['address'] . " " . $row['province'] . " " . $row['country'] ?></td>
                        <td><?php echo $row['NUMBER'] ?></td>
                      </tr>
                <?php } ?>
                </tbody>
              </table>
        </div>
    </div>
</body>
</html>