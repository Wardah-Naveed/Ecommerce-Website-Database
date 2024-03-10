<?php
// fetch_products.php

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

// Initialize $search to an empty string
$search = '';

// Handle search query only if Enter key is pressed and search bar is not empty
if (isset($_POST['search']) && isset($_POST['enter_pressed'])) {
    $search = $_POST['search'];

    // Check if the search bar is not empty
    if (!empty($search)) {
        // Modify the SQL query to filter based on the search query
        $sql = "SELECT p.*, c.category_name, COUNT(eo.order_id) AS order_count
            FROM product p
            LEFT JOIN each_order eo ON p.product_id = eo.product_id
            LEFT JOIN category c ON p.category_id = c.category_id
            WHERE p.product_name LIKE '%$search%' OR
                  c.category_name LIKE '%$search%'
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
    } else {
        // If search bar is empty, set $products to an empty array
        $products = [];
    }
} else {
    // No search or Enter key not pressed, don't fetch products
    $products = [];
}

// Close the database connection
$conn->close();
?>


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
<div class="head">
        <div class="header">
            <div class="navbar grid">
                <div class="logo"><img src="imgs/logo.png" alt="" class="logoimg"></div>
                <div class="search">
                    <form class="searchbar" action="" method="post">
                        <input type="text" placeholder="Search It Buy It..." name="search">
                        <button type="submit" class="background"><i class="fa fa-search"></i></button>
                    </form>
                </div>
                <div class="loginbtn">
                    <?php
                    session_start();
                    if (isset($_SESSION['user_id'])) {
                        echo '<a href="profile.php" class="button right"><i class="fa fa-user-circle"></i></a>';
                    } else {
                        echo '<a href="login.php" class="button right"><i class="fa fa-sign-in"></i></a>';
                    }
                    ?>
                </div>
                <div class="cartbtn"><a href="cartpage.php" class="button left"><i class="fa fa-shopping-cart"></i></a></div>
            </div>
        </div>
    </div>

    <div id="nav" class="background">
        <ul>
        <li><div><a href="index.php" class="active">Home</a></div></li>
            <li><div><a href="clothing.php" class="">Clothing</a></div></li>
            <li><div><a href="tech.php" class="">Tech</a></div></li>
            <li><div><a href="acessories.php" class="">Accessories</a></div></li>
            <li><div><a href="contact.php" class="">Contact Us</a></div></li>
        </ul>
    </div>

    <br><br>

    <div class="card">
        <?php if (isset($_POST['search']) && isset($_POST['enter_pressed'])): ?>
            <h2>Searched Results for "<?php echo htmlspecialchars($search); ?>"</h2><br>
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
        <?php endif; ?>
    </div>

    <br><br>
    <script>
    // Add JavaScript to detect Enter key press and submit the form
    document.addEventListener('DOMContentLoaded', function () {
        // Function to handle the "Enter" key press
        document.querySelector('.searchbar input').addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                document.querySelector('.searchbar').insertAdjacentHTML('beforeend', '<input type="hidden" name="enter_pressed" value="true">');
                document.querySelector('.searchbar').submit();
            }
        });

        // Function to handle the search button click
        document.querySelector('.searchbar button').addEventListener('click', function (event) {
            // Prevent the default form submission to handle it using AJAX
            event.preventDefault();

            // Simulate "Enter" key press behavior
            document.querySelector('.searchbar').insertAdjacentHTML('beforeend', '<input type="hidden" name="enter_pressed" value="true">');
            document.querySelector('.searchbar').submit();
        });
    });
</script>


</body>
</html>

