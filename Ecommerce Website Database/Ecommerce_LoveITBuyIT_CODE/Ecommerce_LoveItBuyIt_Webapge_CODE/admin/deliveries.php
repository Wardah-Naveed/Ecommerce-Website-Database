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
    <title>Pending Deliveries | Admin</title>
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
            <a href="deliveries.php"  class="active"><i class="fa fa-truck"></i>Pending Deliveries</a></li>
            <a href="users.php"><i class="fa fa-user"></i>Users List</a></li>                
            <a href="logout.php"><i class="fa fa-sign-out"></i>Logout</a></li>
        </div>
    </div>
    <div class="content">
        <div class="card outercard">
            <h2 style="color: white;">Pending Deliveries</h3>
            <table>
                <thead>
                  <tr>
                    <th>Delivery Id</th>
                    <th>Date</th>
                    <th>Customer Name</th>
                    <th>Address</th>
                    <th>Number</th>
                    <th>Bill</th>
                    <th>Details</th>
                    <th>Deliver</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                      $sql_check = "SELECT * FROM order_cust where completed = 0 ORDER BY order_id";
                      $result = $conn->query($sql_check);
                      while ($row = mysqli_fetch_array($result)) { 
                        $customer_id = $row['cust_id'];
                        $sql_check = "SELECT CONCAT(first_name, ' ', last_name) AS full_name, CONCAT(address, ' ', province, ' ', country) AS full_address, NUMBER FROM customer WHERE cust_id = $customer_id"; 
                        $check = $conn->query($sql_check); 
                        $n = mysqli_fetch_array($check)?>
                      <tr>
                        <td><?php echo $row['order_id'] ?></td>
                        <td><?php echo $row['date_time'] ?></td>
                        <td><?php echo $n['full_name'] ?></td>
                        <td><?php echo $n['full_address'] ?></td>
                        <td><?php echo $n['NUMBER'] ?></td>
                        <td><?php echo $row['price_total'] ?></td>
                        <td><a href="order.php?order=<?php echo $row['order_id']; ?>"><i class="fa fa-eye" style="color:white;"></i></a></td>
                        <td><a href="deliver.php?order=<?php echo $row['order_id']; ?>" target="_blank"><i class="fa fa-truck" style="color:white;"></i></a></td>
                      </tr>
                      <?php } ?>
                </tbody>
              </table>
        </div>
    </div>
</body>
</html>