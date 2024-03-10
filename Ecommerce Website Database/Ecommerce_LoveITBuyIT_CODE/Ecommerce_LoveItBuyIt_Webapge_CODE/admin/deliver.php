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
    $sql = "UPDATE order_cust SET completed = 1 WHERE order_id = '$order_id'";
    $result = $conn->query($sql);
    if($result)
    {
      echo "<script>alert('Product Delivered Successfully!'); close();</script>";
    }
    else
    {
      echo "<script>alert('Product was not Delivered...'); close();</script>";
    }
?>