<?php 
  include("authorization.php");
  include("includeDB.php"); 
  if (!isset($_GET['product_id'])) {
    header("Location: dashboard.php");
    exit();
  }
    $product_id = $_GET['product_id'];
    $sql_check = "SELECT * FROM product WHERE product_id = '$product_id'";
    $result = $conn->query($sql_check);
    if(mysqli_num_rows($result) == 0){
      header("Location: dashboard.php");
      exit();
    }
    $sql = "UPDATE product SET disabled = 1 WHERE product_id = '$product_id'";
    $result = $conn->query($sql);
    if($result)
    {
      echo "<script>alert('Product Disabled Successfully!'); close();</script>";
    }
    else
    {
      echo "<script>alert('Product was not disabled...'); close();</script>";
    }
?>
