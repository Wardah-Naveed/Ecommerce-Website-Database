<?php
include("header.php");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shoppping_site";

$conn = new mysqli($servername, $username, $password, $dbname);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



$initialItemCount = 1;

if (!isset($_GET['product_id'])) {
    header("Location: index.php");
    exit();
}

$product_id = $_GET['product_id'];

$sql = "SELECT p.product_name, p.product_description, p.inventory, p.price, p.discount, p.image1, p.image2, p.image3, p.category_id, c.category_name
        FROM product p
        LEFT JOIN category c ON p.category_id = c.category_id
        WHERE p.product_id = ?";
$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
    $product['image1'] = 'data:image/png;base64,' . base64_encode($product['image1']);
    $product['image2'] = 'data:image/png;base64,' . base64_encode($product['image2']);
    $product['image3'] = 'data:image/png;base64,' . base64_encode($product['image3']);
} else {
    die("Product not found");
}

$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_wishlist'])) {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        header('Location: login.php');
        exit();
    }
    $check = "SELECT product_id FROM wishlist WHERE cust_id = '$user_id'";
    $result = $conn->query($check);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row['product_id'] == $product_id) {
                //remove from wishlist
                $sqlWishlist = "DELETE FROM wishlist WHERE cust_id = '$user_id' AND product_id = '$product_id'";
                $stmtWishlist = $conn->prepare($sqlWishlist);
                $stmtWishlist->execute();
                break;
            }
        }
    }else{
    $sqlWishlist = "INSERT INTO wishlist (cust_id, product_id) VALUES (?, ?)";
    $stmtWishlist = $conn->prepare($sqlWishlist);
    $stmtWishlist->bind_param("ii", $user_id, $product_id);

    if ($stmtWishlist->execute()) {
        $wishlistMessage = "Product added to wishlist successfully!";
    } else {
        $wishlistMessage = "Error adding product to wishlist: " . $stmtWishlist->error;
    }

    $stmtWishlist->close();
    }
}

if (isset($_POST['insert_data'])) {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        header('Location: login.php');
        exit();
    }

    $quantity = $_POST['quantity'];

    $sqlCart = "INSERT INTO cart (product_id, quantity, cust_id)
                VALUES ('$product_id', '$quantity', '$user_id')
                ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)";

    $stmtCart = $conn->query($sqlCart);

    if ($stmtCart) {
        header("Location: cartpage.php");
        exit();
    } else {
        echo "<script>alert('Category Addition Failed!')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="single-prod.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div class="product-container">
        <div class="product-image-container">
            <img class="product-image" src="<?php echo $product['image1']; ?>" alt="Product Image">
            <div class="image-navigation">
                <button onclick="prevImage()"> <</button>
                <button onclick="nextImage()">></button>
            </div>
        </div>
        <div class="product-info">
            <h1 class="product-title"><?php echo htmlspecialchars($product['product_name']); ?></h1>
            <p class="product-description"><?php echo htmlspecialchars($product['product_description']); ?></p>
            <p class="product-quantity">Available quantity: <span id="available-quantity"><?php echo $product['inventory']; ?></span></p>
            <form method="post" enctype="multipart/form-data">
        Quantity: <input type="number" id="item-quantity" name="quantity" value="<?php echo $initialItemCount; ?>" min="1" max="<?php echo $product['inventory']; ?>">
        <button type="button" onclick="calculateTotal()">Calculate</button>

        <?php
        if (isset($product['discount'])) {
            echo '<p>Price: $' . number_format($product['price'], 2) . '</p>';
            $discountedPrice = $product['price'] * (1 - $product['discount'] / 100);
            echo '<p class="discounted-price" style="color: red;">Discounted Price: $' . number_format($discountedPrice, 2) . '</p>';
        } else {
            echo '<p>Price: $' . number_format($product['price'], 2) . '</p>';
        }
        ?>
        <p>Total Price: $<span id="total-price">0.00</span></p>
        <p class="discounted-total-price" style="color: red;">Discounted Total Price: $<span id="discounted-total-price">0.00</span></p>

        <!-- Hidden input fields for submission -->
        <input type="hidden" id="submit-quantity" name="submit_quantity" value="">
        <input type="hidden" id="total-cost" name="total_cost" value="">
        <input type="hidden" id="discounted-total-cost" name="discounted_total_cost" value="">

        <button type="submit" name="insert_data" onclick="this.form.submit() " class="buy-button">Add to Cart</button>
    </form>

<script>
    function calculateTotal() {
        // Get the current quantity value
        const quantity = document.getElementById('item-quantity').value;

        // Assuming you have a variable named 'price' for the product price
        const price = <?php echo $product['price']; ?>;

        // Assuming you have a variable named 'discount' for the product discount
        const discount = <?php echo $product['discount']; ?>;

        // Calculate the total and discounted total
        const total = price * quantity;
        const discountedTotal = total * (1 - discount / 100);

        // Display the regular total and discounted total in the corresponding spans
        document.getElementById('total-price').textContent = total.toFixed(2);
        document.getElementById('discounted-total-price').textContent = discountedTotal.toFixed(2);

        // Update hidden input fields for submission
        document.getElementById('submit-quantity').value = quantity;
        document.getElementById('total-cost').value = total.toFixed(2);
        document.getElementById('discounted-total-cost').value = discountedTotal.toFixed(2);
    }
</script>
  <br><br>
            <form id="add_to_wishlist" method="POST">
                <input type="hidden" name="add_to_wishlist" value="1">
                
            </form>
            Add to WHISHLIST: 
            <a href="#" onclick="document.getElementById('add_to_wishlist').submit();">
                <i class="fa fa-heart" style="color: red;"></i>
            </a>
        </div>
    </div>
    </div>

    <div class="main">
        <h3>&nbsp;&nbsp;&nbsp;Related-Items:</h3>
        <!-- Related Item Scrollable Container -->
        <div class="related-item-container">
            <div class="related-item">
                <?php
                $category_id = $product['category_id'];
                $sqlRelated = "SELECT product_id, product_name, product_description, image1 FROM product WHERE category_id = ? AND product_id <> ?";
                $stmtRelated = $conn->prepare($sqlRelated);

                // Bind the parameters
                $stmtRelated->bind_param("ii", $category_id, $product_id);

                // Execute the query
                $stmtRelated->execute();

                // Get the result set
                $resultRelated = $stmtRelated->get_result();
                // Related Item Scrollable Container

                while ($relatedProduct = $resultRelated->fetch_assoc()) {
                    echo '<div class="related-item">';
                    echo '<img src="data:image/png;base64,' . base64_encode($relatedProduct['image1']) . '" alt="' . htmlspecialchars($relatedProduct['product_name']) . '">';
                    echo '<h2 class="related-item-title">' . htmlspecialchars($relatedProduct['product_name']) . '</h2>';
                    echo '<p class="related-item-description">' . htmlspecialchars($relatedProduct['product_description']) . '</p>';
                    echo '<a href="product.php?product_id=' . $relatedProduct['product_id'] . '"><button class="view-button">View</button></a>';
                    echo '</div>';
                }
                ?>
            </div>
            <!-- Add more related items as needed -->
        </div>
    </div>
    <br><br>
    <div class="footer">
        <h4>This Site Is Created As A DBMS Project</h4>
    </div>
    <script>
        var itemCount = <?php echo $initialItemCount; ?>;
        const price = <?php echo $product['price']; ?>;
        const discount = <?php echo $product['discount']; ?>;
        let availableQuantity = <?php echo $product['inventory']; ?>;
        let currentImageIndex = 0;

        const images = [
            '<?php echo $product['image1']; ?>',
            '<?php echo $product['image2']; ?>',
            '<?php echo $product['image3']; ?>'
        ];

        const productImage = document.querySelector('.product-image');

        function prevImage() {
            currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
            updateProductImage();
        }

        function nextImage() {
            currentImageIndex = (currentImageIndex + 1) % images.length;
            updateProductImage();
        }

        function updateProductImage() {
            productImage.src = images[currentImageIndex];
        }

        function viewRelatedItem(itemIndex) {
            // Implement code to view the selected related item
            alert(`Viewing Related Item ${itemIndex}`);
        }

        // Call updateTotalPrice on page load
        updateTotalPrice();
    </script>
</body>

</html>
