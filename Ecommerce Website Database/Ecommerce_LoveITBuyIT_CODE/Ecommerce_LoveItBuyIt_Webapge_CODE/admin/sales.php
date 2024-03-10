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
    <title>Previous Sales | Admin</title>
</head>
<body>
    <div class="sidebar">
        <div class="logo"><h2><i class="fa fa-user-circle"></i>Admin Panel</h2></div>
        <div class="nav">
            <a href="dashboard.php"><i class="fa fa-home back"></i>Dashboard</a></li>
            <a href="sales.php" class="active"><i class="fa fa-money back"></i>Previous Sales</a></li>
            <a href="addproduct.php"><i class="fa fa-plus-square"></i>Add Product</a></li>
            <a href="allproducts.php"><i class="fa fa-list-alt"></i>All Product</a></li>
            <a href="addcat.php"><i class="fa fa-plus"></i>Add Category</a></li>
            <a href="allcats.php"><i class="fa fa-list"></i>All Categories</a></li>
            <a href="deliveries.php" ><i class="fa fa-truck"></i>Pending Deliveries</a></li>
            <a href="users.php"><i class="fa fa-user"></i>Users List</a></li>                
            <a href="logout.php"><i class="fa fa-sign-out"></i>Logout</a></li>
        </div>
    </div>
    <div class="content">
        <div class="card outercard">
            <h2 style="color: white;">Past Sales</h3>
            <table>
                <thead>
                  <tr>
                    <th>Invoice Id</th>
                    <th>Customer Name</th>
                    <th>Customer Email</th>
                    <th>Date</th>
                    <th>Invoice Details</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                      $sql_check = "SELECT * FROM order_cust where completed = 1 ORDER BY order_id DESC LIMIT 5";
                      $result = $conn->query($sql_check);
                      while ($row = mysqli_fetch_array($result)) { 
                        $customer_id = $row['cust_id'];
                        $sql_check = "SELECT CONCAT(first_name, ' ', last_name) AS 
                        full_name, email FROM customer 
                        WHERE cust_id = $customer_id"; 
                        $check = $conn->query($sql_check); 
                        $n = mysqli_fetch_array($check)?>
                      <tr>
                        <td><?php echo $row['order_id'] ?></td>
                        <td><?php echo $n['full_name'] ?></td>
                        <td><?php echo $n['email'] ?></td>
                        <td><?php echo $row['date_time'] ?></td>
                        <td><a href="order.php?order=<?php echo $row['order_id']; ?>"><i class="fa fa-eye" style="color:white;"></i></a></td>
                      </tr>
                      <?php } ?>
                </tbody>
              </table>
        </div>
    </div>
</body>
</html>