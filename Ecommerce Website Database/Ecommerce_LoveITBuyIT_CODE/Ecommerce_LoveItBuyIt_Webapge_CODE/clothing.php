<?php
include("header.php");

// Database connection code here
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shoppping_site";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check for a successful connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Modify the SQL query to filter based on the electronics category
$sql = "SELECT p.*, c.category_name, COUNT(eo.order_id) AS order_count
        FROM product p
        LEFT JOIN each_order eo ON p.product_id = eo.product_id
        LEFT JOIN category c ON p.category_id = c.category_id
        WHERE c.category_name = 'Electronics'
        GROUP BY p.product_id";

$result = $conn->query($sql);

// Check for a successful query execution
if (!$result) {
    die("Error executing query: " . $conn->error);
}

// Store the search results in an array
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

// Close the database connection
$conn->close();
?>

<!-- Rest of your HTML code remains unchanged -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="product.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Searchpage</title>
</head>
<body>
    <div class="card">
        <h2>Electronics Products</h2><br>
        <section>
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <?php if ($product['discount'] > 0): ?>
                        <div class="badge">Sale</div>
                    <?php elseif (date('Y-m-d') == date('Y-m-d', strtotime($product['time_arrival']))): ?>
                        <div class="badge">Hot Arrival</div>
                    <?php elseif ($product['order_count'] >= 5 && $product['order_count'] <= 10): ?>
                        <div class="badge">Hot</div>
                    <?php elseif ($product['order_count'] > 10): ?>
                        <div class="badge">Best Selling</div>
                    <?php endif; ?>
                    <div class="product-tumb">
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image1']); ?>" alt="">
                    </div>
                    <div class="product-details">
                        <span class="product-catagory"><?php echo $product['product_name']; ?></span>
                        <h4><a href="product.php?product_id=<?php echo $product['product_id']; ?>"><?php echo $product['product_name']; ?></a></h4>
                        <p><?php echo $product['product_description']; ?></p>
                        <div class="product-bottom-details">
                            <div class="product-price">
                                <?php if ($product['discount'] > 0): ?>
                                    <small>$<?php echo number_format($product['price'], 2); ?></small>
                                    $<?php echo number_format($product['price'] - ($product['price'] * $product['discount'] / 100), 2); ?>
                                <?php else: ?>
                                    $<?php echo number_format($product['price'], 2); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
        <br>
    </div>
    <div class="footer">
        <h4>This Site Is Created As A DBMS Project</h4>
    </div> 
</body>
</html>
