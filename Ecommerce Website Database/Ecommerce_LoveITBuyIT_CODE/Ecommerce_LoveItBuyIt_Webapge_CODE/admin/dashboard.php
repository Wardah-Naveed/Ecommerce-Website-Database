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
    <title>Dashboard | Admin</title>
</head>
<body>
    <div class="sidebar">
        <div class="logo"><h2><i class="fa fa-user-circle"></i>Admin Panel</h2></div>
        <div class="nav">
            <a href="dashboard.php" class="active"><i class="fa fa-home back"></i>Dashboard</a></li>
            <a href="sales.php"><i class="fa fa-money back"></i>Previous Sales</a></li>
            <a href="addproduct.php"><i class="fa fa-plus-square"></i>Add Product</a></li>
            <a href="allproducts.php"><i class="fa fa-list-alt"></i>All Product</a></li>
            <a href="addcat.php"><i class="fa fa-plus"></i>Add Category</a></li>
            <a href="allcats.php"><i class="fa fa-list"></i>All Categories</a></li>
            <a href="deliveries.php"><i class="fa fa-truck"></i>Pending Deliveries</a></li>
            <a href="users.php"><i class="fa fa-user"></i>Users List</a></li>                
            <a href="logout.php"><i class="fa fa-sign-out"></i>Logout</a></li>
        </div>
    </div>
    <div class="content">
        <div class="grid">
            <div class="card">
              <div class="card-icon">
                <i class="fa fa-line-chart"></i>
              </div>
              <div class="card-title">
                <p>Total Sales <br><span style="color: white;">
                <?php
                  $sql_check = "SELECT SUM(sold) as sales FROM product";
                  $result = $conn->query($sql_check);
                  $n = mysqli_fetch_array($result);
                  echo $n['sales'];
                ?></span></p>
              </div>
            </div>
            <div class="card">
              <div class="card-icon">
                <i class="fa fa-area-chart"></i>
              </div>
              <div class="card-title">
                <p>Total Revenue <br>
                <span style="color: white;"><i class="fa fa-dollar"></i>
                <?php
                  $sql_check = "SELECT SUM(price_total) as revenue FROM order_cust where completed = 1";
                  $result = $conn->query($sql_check);
                  $n = mysqli_fetch_array($result);
                  echo $n['revenue'];
                ?>
                </span>
                </p>
              </div>
            </div>
            <div class="card">
              <div class="card-icon">
                <i class="fa fa-send"></i>
              </div>
              <div class="card-title">
                <p>Ready To Send<br>
                <span style="color: white;">
                <?php
                  $sql_check = "SELECT count(order_id) as pending FROM order_cust where completed = 0";
                  $result = $conn->query($sql_check);
                  $n = mysqli_fetch_array($result);
                  echo $n['pending'];
                ?>
                </span>
                </p>
              </div>
            </div>
            <div class="card">
              <div class="card-icon">
                <i class="fa fa-user"></i>
              </div>
              <div class="card-title">
                <p>Current Users<br>
                <span style="color: white;">
                <?php
                  $sql_check = "SELECT count(cust_id) as customers FROM customer";
                  $result = $conn->query($sql_check);
                  $n = mysqli_fetch_array($result);
                  echo $n['customers'];
                ?>
                </span>
                </p>
              </div>
            </div>
        </div>
        <div class="bigrid">
            <div class="card">
                <h3 style="color: white;">Top Selling</h3>
                <table>
                    <thead>
                      <tr>
                        <th>Item Id</th>
                        <th>Item Name</th>
                        <th>Sold</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $sql_check = "SELECT * FROM product ORDER BY sold DESC LIMIT 5";
                        $result = $conn->query($sql_check);
                        while ($row = mysqli_fetch_array($result)) { ?>
                        <tr>
                          <td><?php echo $row['product_id'] ?></td>
                          <td><?php echo $row['product_name'] ?></td>
                          <td><?php echo $row['sold'] ?></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
            </div>
            <div class="card">
                <h3 style="color: white;">Low Inventory</h3>
                <table>
                    <thead>
                      <tr>
                        <th>Item Id</th>
                        <th>Item Name</th>
                        <th>Remaining</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $sql_check = "SELECT * FROM product ORDER BY inventory ASC LIMIT 5";
                        $result = $conn->query($sql_check);
                        while ($row = mysqli_fetch_array($result)) { ?>
                        <tr>
                          <td><?php echo $row['product_id'] ?></td>
                          <td><?php echo $row['product_name'] ?></td>
                          <td><?php echo $row['inventory'] ?></td>
                        </tr>
                      <?php } ?> 
                    </tbody>
                  </table>
            </div>
        </div>
        <div class="card outercard">
            <h3 style="color: white;">Recent Sales</h3>
            <table>
                <thead>
                  <tr>
                    <th>Invoice Id</th>
                    <th>Date</th>
                    <th>Customer Name</th>
                    <th>Items Bought</th>
                    <th>Amount Paid</th>
                    <th>Details</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                      $sql_check = "SELECT * FROM order_cust where completed = 1 ORDER BY order_id DESC LIMIT 10";
                      $result = $conn->query($sql_check);
                      while ($row = mysqli_fetch_array($result)) { 
                        $customer_id = $row['cust_id'];
                        $sql_check = "SELECT CONCAT(first_name, ' ', last_name) AS 
                        full_name FROM customer 
                        WHERE cust_id = $customer_id"; 
                        $check = $conn->query($sql_check); 
                        $n = mysqli_fetch_array($check)?>
                      <tr>
                        <td><?php echo $row['order_id'] ?></td>
                        <td><?php echo $row['date_time'] ?></td>
                        <td><?php echo $n['full_name'] ?></td>
                        <td><?php echo $row['no_of_products'] ?></td>
                        <td><?php echo $row['price_total'] ?></td>
                        <td><a href="order.php?order=<?php echo $row['order_id']; ?>"><i class="fa fa-eye" style="color:white;"></i></a></td>
                      </tr>
                      <?php } ?>
                </tbody>
              </table>
        </div>
    </div>
</body>
</html>