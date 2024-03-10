<?php 
  include("authorization.php");
  include("includeDB.php"); 
  if (!isset($_GET['order'])) {
    header("Location: dashboard.php");
    exit();
  }
    $order_id = $_GET['order'];
    $sql_check = "SELECT * FROM order_cust WHERE order_id = '$order_id'";
    $result = $conn->query($sql_check);
    if(mysqli_num_rows($result) == 0){
      header("Location: dashboard.php");
      exit();
    }
    $row = mysqli_fetch_array($result);
    $delivered = $row['completed'];
    $order_time = $row['date_time'];
    $customer = $row['cust_id'];
    $total_bill = $row['price_total'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        text-align: center;
        background-color: #f0f0f0;
    }

    header {
        background-color: #333535;
        color: #fff;
        padding: 20px;
        width: 50%;
    }

    .order-details {
        margin: 20px;
        text-align: left;
        border: 1px solid #ddd;
        padding: 20px;
        box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.2);
        background-color: #d3d2ce; /* Change to orange background color */
        width: 40%;
    }

    .order-details h2 {
        margin-top: 10px;
        color: orange /* Change the heading color to green */
    }

    .order-details p {
        color: #333;
    }

    #order-time {
        color: #777;
    }
    .main {
        display: flex;
        justify-content: center;
    }
    button{
    width: 30%;
    position: relative;
    border: none;
    border-radius: 5px;
    background-color: #fca311;
    padding: 7px 25px;
    cursor: pointer;
    color: white;
}
th, td {
  padding: 15px;
}
</style>
<body>
   <center> <header>
        <h1>Order Details</h1>
    </header> </center>
    <div>
        <a href="dashboard.php"><button> Dashboard </button></a>
    </div>
    <div class="main">
    <div class="order-details">
        <h2>Order ID: <span id="order-id"><?php echo $order_id ?></span></h2>
        <h2>User Information:</h2>
        <?php 
            $sql_check = "SELECT * FROM customer WHERE cust_id = '$customer'";
            $result = $conn->query($sql_check);
            $row = mysqli_fetch_array($result);
            $user_id = $row['cust_id'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $address = $row['address'];
            $province = $row['province'];
            $country = $row['country'];
            $mobile_number = $row['NUMBER'];
        ?>
        <p>User ID: <span id="user-id"><?php echo $user_id ?></span></p>
        <p>First Name: <span id="first-name"><?php echo $first_name ?></span></p>
        <p>Last Name: <span id="last-name"><?php echo $last_name ?></span></p>
        <p>Address: <span id="address"><?php echo $address .' '. $province .' '. $country ?></span></p>
        <p>Mobile Number: <span id="mobile-number"><?php echo $mobile_number ?></span></p>
        
        <h2>Order Information:</h2>
        <p>Order Time: <span id="order-time"><?php echo $order_time ?></span></p>
        <p>Status: <span id="order-deliver"><?php if ($delivered == 1) {
            echo "Delivered";
        }else   echo "Not Delivered" ?></span></p>
        <table>
            <thead>
              <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Items</th>
                <th>Cost Per Item</th>
                <th>Total Cost</th>
              </tr>
            </thead>
            <tbody>
            <?php
                  $sql_check = "SELECT * FROM each_order WHERE order_id = '$order_id'";
                  $result = $conn->query($sql_check);
                  while ($row = mysqli_fetch_array($result)) { 
                    $product_id = $row['product_id'];
                    $sql_check = "SELECT * FROM product WHERE product_id = '$product_id'";
                    $check = $conn->query($sql_check);
                    $n = mysqli_fetch_array($check)?>
                    <tr>
                      <td><?php echo $row['product_id'] ?></td>
                      <td><?php echo $n['product_name'] ?></td>
                      <td><?php echo $row['quantity'] ?></td>
                      <td><?php echo $n['price'] ?></td>
                      <td><?php echo $row['quantity']*$n['price'] ?></td>
                    </tr>
                    <?php } ?>
            </tbody>
          </table>
          <p>After Applying Discounts</p>
        <p>Total Bill: $<span id="cost-per-item"><?php echo $total_bill ?></span></p>
        
    </div>
</div>
</body>
</html>
